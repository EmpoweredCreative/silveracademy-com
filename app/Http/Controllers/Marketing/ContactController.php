<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use SendGrid\Mail\Mail;
use SendGrid;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // Honeypot check - if this field is filled, it's a bot
        if ($request->filled('website_url')) {
            Log::warning('Contact form honeypot triggered', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            return back()->with('error', 'Sorry, there was an error sending your inquiry. Please try again later.');
        }

        // Time-based validation - prevent instant submissions
        $formTimestamp = $request->input('form_timestamp');
        if ($formTimestamp) {
            $timeElapsed = time() - (int) $formTimestamp;
            // Require at least 5 seconds to fill the form (humans need time)
            // But allow up to 1 hour (3600 seconds) to prevent stale submissions
            if ($timeElapsed < 5 || $timeElapsed > 3600) {
                Log::warning('Contact form time validation failed', [
                    'ip' => $request->ip(),
                    'time_elapsed' => $timeElapsed,
                    'user_agent' => $request->userAgent(),
                ]);
                return back()->with('error', 'Please take your time filling out the form and try again.');
            }
        } else {
            Log::warning('Contact form missing timestamp', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            return back()->with('error', 'Please refresh the page and try again.');
        }

        // Check for suspicious patterns in content
        $message = $request->input('message', '');
        $email = $request->input('email', '');
        
        // Common spam patterns
        $spamPatterns = [
            '/\b(viagra|cialis|casino|poker|loan|debt|free money|click here|buy now|limited time)\b/i',
            '/https?:\/\/[^\s]+/i', // Multiple URLs in message
            '/\b\d{10,}\b/', // Long number sequences
        ];
        
        $urlCount = preg_match_all('/https?:\/\/[^\s]+/i', $message);
        if ($urlCount > 2) {
            Log::warning('Contact form contains too many URLs', [
                'ip' => $request->ip(),
                'url_count' => $urlCount,
            ]);
            return back()->with('error', 'Your message contains too many links. Please remove them and try again.');
        }

        // Enhanced validation with spam detection
        $validated = $request->validate([
            'parent_name' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\'\.]+$/u',
            'student_name' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\'\.]+$/u',
            'email' => 'required|email:rfc,dns|max:255',
            'phone' => 'required|string|max:50|regex:/^[\d\s\-\+\(\)]+$/',
            'grade_interest' => 'required|string|max:255|in:Pre-K,Kindergarten,1st Grade,2nd Grade,3rd Grade,4th Grade,5th Grade,6th Grade,7th Grade,8th Grade,Undecided',
            'school_year' => 'required|string|max:255|in:2025-2026,2026-2027,2027-2028,Not sure',
            'how_heard' => 'required|string|max:255',
            'schedule_tour' => 'required|string|max:255|in:Yes, please send me the link.,Not yet — I\'d like more information first.',
            'message' => 'required|string|min:10|max:5000',
            'subscribe' => 'boolean',
            'form_timestamp' => 'required|integer',
        ], [
            'parent_name.regex' => 'The parent name contains invalid characters.',
            'student_name.regex' => 'The student name contains invalid characters.',
            'email.email' => 'Please provide a valid email address.',
            'phone.regex' => 'Please provide a valid phone number.',
            'message.min' => 'Please provide more details in your message (at least 10 characters).',
        ]);

        // Additional spam check: Check for repeated submissions from same IP
        $ipKey = 'contact_form_ip:' . $request->ip();
        $submissionCount = Cache::get($ipKey, 0);
        
        if ($submissionCount >= 5) {
            Log::warning('Contact form rate limit exceeded for IP', [
                'ip' => $request->ip(),
                'count' => $submissionCount,
            ]);
            return back()->with('error', 'Too many submissions from this location. Please try again later.');
        }
        
        // Increment submission count (expires in 1 hour)
        Cache::put($ipKey, $submissionCount + 1, now()->addHour());

        try {
            $sendgrid = new SendGrid(config('services.sendgrid.api_key'));
            
            $email = new Mail();
            $email->setFrom(
                config('services.sendgrid.from_email'),
                config('services.sendgrid.from_name')
            );
            $email->setSubject('Admissions Inquiry: ' . $validated['student_name'] . ' - ' . $validated['grade_interest']);
            $email->addTo(
                config('services.sendgrid.to_email'),
                config('services.sendgrid.to_name')
            );
            
            // Set reply-to to the form submitter's email
            $email->setReplyTo($validated['email'], $validated['parent_name']);
            
            // Build email content
            $subscribeStatus = $validated['subscribe'] ? 'Yes' : 'No';
            
            $content = "New admissions inquiry from Silver Academy website:\n\n";
            $content .= "CONTACT INFORMATION\n";
            $content .= "-------------------\n";
            $content .= "Parent/Guardian Name: {$validated['parent_name']}\n";
            $content .= "Student Name: {$validated['student_name']}\n";
            $content .= "Email: {$validated['email']}\n";
            $content .= "Phone: {$validated['phone']}\n\n";
            $content .= "ENROLLMENT INTEREST\n";
            $content .= "-------------------\n";
            $content .= "Grade of Interest: {$validated['grade_interest']}\n";
            $content .= "School Year: {$validated['school_year']}\n";
            $content .= "Schedule a Tour: {$validated['schedule_tour']}\n\n";
            $content .= "ADDITIONAL INFO\n";
            $content .= "-------------------\n";
            $content .= "How They Heard About Us: {$validated['how_heard']}\n";
            $content .= "Subscribe to Email List: {$subscribeStatus}\n\n";
            $content .= "QUESTIONS/COMMENTS\n";
            $content .= "-------------------\n";
            $content .= "{$validated['message']}\n";
            
            $email->addContent("text/plain", $content);
            
            // Also add HTML version
            $htmlContent = "<h2>New Admissions Inquiry</h2>";
            $htmlContent .= "<h3 style='color: #1e3a5f; border-bottom: 2px solid #f5a623; padding-bottom: 8px;'>Contact Information</h3>";
            $htmlContent .= "<table style='width: 100%; border-collapse: collapse;'>";
            $htmlContent .= "<tr><td style='padding: 8px 0; font-weight: bold; width: 200px;'>Parent/Guardian Name:</td><td style='padding: 8px 0;'>" . htmlspecialchars($validated['parent_name']) . "</td></tr>";
            $htmlContent .= "<tr><td style='padding: 8px 0; font-weight: bold;'>Student Name:</td><td style='padding: 8px 0;'>" . htmlspecialchars($validated['student_name']) . "</td></tr>";
            $htmlContent .= "<tr><td style='padding: 8px 0; font-weight: bold;'>Email:</td><td style='padding: 8px 0;'><a href='mailto:" . htmlspecialchars($validated['email']) . "'>" . htmlspecialchars($validated['email']) . "</a></td></tr>";
            $htmlContent .= "<tr><td style='padding: 8px 0; font-weight: bold;'>Phone:</td><td style='padding: 8px 0;'><a href='tel:" . htmlspecialchars($validated['phone']) . "'>" . htmlspecialchars($validated['phone']) . "</a></td></tr>";
            $htmlContent .= "</table>";
            
            $htmlContent .= "<h3 style='color: #1e3a5f; border-bottom: 2px solid #f5a623; padding-bottom: 8px; margin-top: 24px;'>Enrollment Interest</h3>";
            $htmlContent .= "<table style='width: 100%; border-collapse: collapse;'>";
            $htmlContent .= "<tr><td style='padding: 8px 0; font-weight: bold; width: 200px;'>Grade of Interest:</td><td style='padding: 8px 0;'>" . htmlspecialchars($validated['grade_interest']) . "</td></tr>";
            $htmlContent .= "<tr><td style='padding: 8px 0; font-weight: bold;'>School Year:</td><td style='padding: 8px 0;'>" . htmlspecialchars($validated['school_year']) . "</td></tr>";
            $htmlContent .= "<tr><td style='padding: 8px 0; font-weight: bold;'>Schedule a Tour:</td><td style='padding: 8px 0;'>" . htmlspecialchars($validated['schedule_tour']) . "</td></tr>";
            $htmlContent .= "</table>";
            
            $htmlContent .= "<h3 style='color: #1e3a5f; border-bottom: 2px solid #f5a623; padding-bottom: 8px; margin-top: 24px;'>Additional Information</h3>";
            $htmlContent .= "<table style='width: 100%; border-collapse: collapse;'>";
            $htmlContent .= "<tr><td style='padding: 8px 0; font-weight: bold; width: 200px;'>How They Heard About Us:</td><td style='padding: 8px 0;'>" . htmlspecialchars($validated['how_heard']) . "</td></tr>";
            $htmlContent .= "<tr><td style='padding: 8px 0; font-weight: bold;'>Subscribe to Email List:</td><td style='padding: 8px 0;'>" . $subscribeStatus . "</td></tr>";
            $htmlContent .= "</table>";
            
            $htmlContent .= "<h3 style='color: #1e3a5f; border-bottom: 2px solid #f5a623; padding-bottom: 8px; margin-top: 24px;'>Questions/Comments</h3>";
            $htmlContent .= "<p style='background: #f8f9fa; padding: 16px; border-radius: 8px;'>" . nl2br(htmlspecialchars($validated['message'])) . "</p>";
            
            $email->addContent("text/html", $htmlContent);
            
            $response = $sendgrid->send($email);
            
            if ($response->statusCode() >= 200 && $response->statusCode() < 300) {
                return back()->with('success', 'Thank you for your interest in The Silver Academy! Our admissions team will reach out to you soon.');
            } else {
                Log::error('SendGrid error', [
                    'status_code' => $response->statusCode(),
                    'body' => $response->body(),
                    'headers' => $response->headers()
                ]);
                
                return back()->with('error', 'Sorry, there was an error sending your inquiry. Please try again later.');
            }
        } catch (\Exception $e) {
            Log::error('Contact form error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Sorry, there was an error sending your inquiry. Please try again later.');
        }
    }
}
