<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Department;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentMoveTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_a_department_moves_with_everything_under_it(): void
    {
        $operations = $this->department('Operations');

        $this->postJson("/departments/{$operations->id}/move", ['parent' => $this->department('Engineering')->id])
            ->assertOk();

        $support = $this->department('Support');
        $this->assertSame('Operations', $support->parent?->name);
        $this->assertTrue($support->isDescendantOf($this->department('Engineering')));
        $this->assertFalse(Department::isBroken());
        $this->get('/chart')->assertSeeInOrder(['Engineering', 'Platform', 'Product', 'Operations', 'Support', 'Finance']);
    }

    public function test_a_department_cannot_move_into_its_own_subtree(): void
    {
        $engineering = $this->department('Engineering');

        $this->postJson("/departments/{$engineering->id}/move", ['parent' => $this->department('Platform')->id])
            ->assertStatus(422)
            ->assertJson(['message' => 'Engineering cannot move under Platform, which is inside it.']);

        $this->assertNull($this->department('Platform')->children()->first());
    }

    public function test_the_company_itself_cannot_move(): void
    {
        $company = $this->department('Kestrel Labs');

        $this->postJson("/departments/{$company->id}/move", ['parent' => $this->department('Finance')->id])
            ->assertStatus(422);
    }

    private function department(string $name): Department
    {
        return Department::query()->where('name', $name)->firstOrFail();
    }
}
