<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SendGrid\Mail\Mail;
use SendGrid;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_name' => 'required|string|max:255',
            'student_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'grade_interest' => 'required|string|max:255',
            'school_year' => 'required|string|max:255',
            'how_heard' => 'required|string|max:255',
            'schedule_tour' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'subscribe' => 'boolean',
        ]);

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
