<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StaffImport implements ToCollection, WithHeadingRow
{
    protected array $results = [
        'imported' => 0,
        'skipped' => 0,
        'errors' => [],
        'imported_ids' => [],
    ];

    /**
     * Process the imported collection.
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because of header row and 0-indexed

            try {
                $this->processRow($row, $rowNumber);
            } catch (\Exception $e) {
                $this->results['errors'][] = "Row {$rowNumber}: " . $e->getMessage();
                $this->results['skipped']++;
            }
        }
    }

    /**
     * Process a single row.
     */
    protected function processRow(Collection $row, int $rowNumber): void
    {
        $name = trim($row['name'] ?? '');
        $email = strtolower(trim($row['email'] ?? ''));
        $roleInput = strtolower(trim($row['role'] ?? 'teacher'));

        // Validate required fields
        if (empty($name)) {
            throw new \Exception('Name is required.');
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Valid email is required.');
        }

        // Check if email already exists
        if (User::where('email', $email)->exists()) {
            throw new \Exception("Email '{$email}' already exists.");
        }

        // Map role input to role constant
        $role = $this->mapRole($roleInput);

        // Create the user WITHOUT a password
        // Password will be generated when welcome email is sent
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => null,
            'role' => $role,
            'is_approved' => true, // Staff are auto-approved
        ]);

        // Track imported user IDs
        $this->results['imported_ids'][] = $user->id;
        $this->results['imported']++;
    }

    /**
     * Map role input string to role constant.
     */
    protected function mapRole(string $input): string
    {
        return match ($input) {
            'admin', 'administrator' => User::ROLE_ADMIN,
            'super_admin', 'superadmin' => User::ROLE_SUPER_ADMIN,
            default => User::ROLE_TEACHER,
        };
    }

    /**
     * Get the import results.
     */
    public function getResults(): array
    {
        return $this->results;
    }
}
