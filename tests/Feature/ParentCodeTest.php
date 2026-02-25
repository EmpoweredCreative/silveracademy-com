<?php

namespace Tests\Feature;

use App\Models\Grade;
use App\Models\Student;
use App\Models\StudentAccessCode;
use App\Models\User;
use App\Services\ParentCodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParentCodeTest extends TestCase
{
    use RefreshDatabase;

    protected Grade $grade;
    protected Student $student;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->grade = Grade::create(['name' => '1st Grade', 'sort_order' => 1]);
        $this->student = Student::create([
            'name' => 'Test Student',
            'grade_id' => $this->grade->id,
            'status' => Student::STATUS_ACTIVE,
        ]);
        $this->admin = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
            'is_approved' => true,
        ]);
    }

    public function test_validate_returns_false_for_invalid_code(): void
    {
        $response = $this->postJson('/api/parent-code/validate', ['code' => 'INVALID99']);
        $response->assertOk();
        $response->assertJson(['valid' => false]);
    }

    public function test_validate_returns_hint_for_valid_code(): void
    {
        $result = ParentCodeService::createCodeForStudent($this->student, 5, false);
        $plainCode = $result['plain_code'];

        $response = $this->postJson('/api/parent-code/validate', ['code' => $plainCode]);
        $response->assertOk();
        $response->assertJson([
            'valid' => true,
            'student_hint' => [
                'first_name' => 'Test',
                'last_initial' => 'S',
                'grade' => ['name' => '1st Grade'],
            ],
        ]);
    }

    public function test_validate_returns_false_when_at_link_cap(): void
    {
        $result = ParentCodeService::createCodeForStudent($this->student, 1, false);
        $plainCode = $result['plain_code'];
        $parent = User::factory()->create(['role' => User::ROLE_PARENT, 'is_approved' => true]);
        $this->student->parents()->attach($parent->id);

        $response = $this->postJson('/api/parent-code/validate', ['code' => $plainCode]);
        $response->assertOk();
        $response->assertJson(['valid' => false]);
    }

    public function test_signup_creates_user_and_links_student(): void
    {
        $result = ParentCodeService::createCodeForStudent($this->student, 5, false);
        $code = $result['plain_code'];

        $response = $this->postJson('/api/parent-code/signup', [
            'email' => 'newparent@example.com',
            'code' => $code,
        ]);
        $response->assertOk();
        $response->assertJson(['ok' => true]);

        $this->assertDatabaseHas('users', [
            'email' => 'newparent@example.com',
            'role' => User::ROLE_PARENT,
            'is_approved' => true,
        ]);
        $user = User::where('email', 'newparent@example.com')->first();
        $this->assertTrue($user->children()->where('students.id', $this->student->id)->exists());
    }

    public function test_signup_at_cap_returns_error(): void
    {
        $result = ParentCodeService::createCodeForStudent($this->student, 1, false);
        $code = $result['plain_code'];
        $parent = User::factory()->create(['role' => User::ROLE_PARENT, 'is_approved' => true]);
        $this->student->parents()->attach($parent->id);

        $response = $this->postJson('/api/parent-code/signup', [
            'email' => 'another@example.com',
            'code' => $code,
        ]);
        $response->assertUnprocessable();
        $response->assertJson(['ok' => false]);
    }

    public function test_add_child_requires_parent(): void
    {
        $result = ParentCodeService::createCodeForStudent($this->student, 5, false);
        $code = $result['plain_code'];

        $teacher = User::factory()->create(['role' => User::ROLE_TEACHER, 'is_approved' => true]);
        $response = $this->actingAs($teacher)->postJson('/portal/parent/add-child', ['code' => $code]);
        $response->assertForbidden();
    }

    public function test_add_child_links_student_for_parent(): void
    {
        $result = ParentCodeService::createCodeForStudent($this->student, 5, false);
        $code = $result['plain_code'];
        $parent = User::factory()->create(['role' => User::ROLE_PARENT, 'is_approved' => true]);

        $response = $this->actingAs($parent)->postJson('/portal/parent/add-child', ['code' => $code]);
        $response->assertOk();
        $response->assertJson(['message' => 'Child added successfully.']);

        $parent->refresh();
        $this->assertTrue($parent->children()->where('students.id', $this->student->id)->exists());
    }

    public function test_admin_regenerate_invalidates_old_code(): void
    {
        $result = ParentCodeService::createCodeForStudent($this->student, 5, false);
        $oldCode = $result['plain_code'];

        $response = $this->actingAs($this->admin)->postJson("/portal/admin/students/{$this->student->id}/code/regenerate");
        $response->assertOk();
        $data = $response->json();
        $this->assertNotEquals($oldCode, $data['code']);

        $validateOld = $this->postJson('/api/parent-code/validate', ['code' => $oldCode]);
        $validateOld->assertJson(['valid' => false]);

        $validateNew = $this->postJson('/api/parent-code/validate', ['code' => $data['code']]);
        $validateNew->assertJson(['valid' => true]);
    }

    public function test_admin_can_view_code_status(): void
    {
        ParentCodeService::createCodeForStudent($this->student, 5, false);

        $response = $this->actingAs($this->admin)->getJson("/portal/admin/students/{$this->student->id}/code");
        $response->assertOk();
        $response->assertJsonFragment([
            'status' => 'active',
            'max_links' => 5,
            'current_link_count' => 0,
        ]);
    }
}
