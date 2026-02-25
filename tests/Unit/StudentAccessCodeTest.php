<?php

namespace Tests\Unit;

use App\Models\Grade;
use App\Models\Student;
use App\Models\StudentAccessCode;
use App\Models\User;
use App\Services\ParentCodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentAccessCodeTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_valid_returns_true_when_active_and_under_cap(): void
    {
        $grade = Grade::create(['name' => '1st Grade', 'sort_order' => 1]);
        $student = Student::create([
            'name' => 'Test',
            'grade_id' => $grade->id,
            'status' => Student::STATUS_ACTIVE,
        ]);
        ParentCodeService::createCodeForStudent($student, 5, false);
        $code = $student->activeAccessCode();
        $this->assertNotNull($code);
        $this->assertTrue($code->isValid());
    }

    public function test_is_valid_returns_false_when_at_cap(): void
    {
        $grade = Grade::create(['name' => '1st Grade', 'sort_order' => 1]);
        $student = Student::create([
            'name' => 'Test',
            'grade_id' => $grade->id,
            'status' => Student::STATUS_ACTIVE,
        ]);
        ParentCodeService::createCodeForStudent($student, 1, false);
        $parent = User::factory()->create(['role' => User::ROLE_PARENT, 'is_approved' => true]);
        $student->parents()->attach($parent->id);

        $code = $student->activeAccessCode();
        $this->assertNotNull($code);
        $this->assertFalse($code->isValid());
    }

    public function test_is_valid_returns_false_when_revoked(): void
    {
        $grade = Grade::create(['name' => '1st Grade', 'sort_order' => 1]);
        $student = Student::create([
            'name' => 'Test',
            'grade_id' => $grade->id,
            'status' => Student::STATUS_ACTIVE,
        ]);
        ParentCodeService::createCodeForStudent($student, 5, false);
        $code = $student->activeAccessCode();
        $code->update(['status' => StudentAccessCode::STATUS_REVOKED, 'revoked_at' => now()]);

        $this->assertFalse($code->fresh()->isValid());
    }
}
