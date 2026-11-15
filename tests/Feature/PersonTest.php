<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Person;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_the_page_shows_a_career_and_the_beliefs_a_correction_replaced(): void
    {
        $dana = $this->person('dana@kestrel.test');

        $this->get("/people/{$dana->id}")
            ->assertOk()
            ->assertSeeInOrder(['Career, as we know it now', 'Finance Lead', '£118,000'])
            ->assertSeeInOrder(['Beliefs we have since corrected', '£115,000']);
    }

    public function test_a_career_lists_each_period_in_order(): void
    {
        $farah = $this->person('farah@kestrel.test');

        $this->get("/people/{$farah->id}")
            ->assertSeeInOrder(['Platform', 'Engineer', 'Product', 'Engineer', 'Product', 'Senior Engineer']);
    }

    public function test_correcting_a_period_keeps_the_old_belief(): void
    {
        $farah = $this->person('farah@kestrel.test');

        $this->post("/people/{$farah->id}/corrections", [
            'from' => '2025-03-01', 'to' => '2025-07-01', 'title' => 'Engineer', 'salary' => 84000,
        ])->assertRedirect("/people/{$farah->id}")->assertSessionHasNoErrors();

        $this->get("/people/{$farah->id}")
            ->assertSee('is corrected')
            ->assertSeeInOrder(['Career, as we know it now', '£84,000'])
            ->assertSeeInOrder(['Beliefs we have since corrected', '£82,000']);
        $this->get('/chart?on=2025-04-01')->assertSee('£84,000');
        $this->get('/chart?on=2025-08-01')->assertDontSee('£84,000');
    }

    public function test_a_change_records_a_new_title_and_salary_from_a_date(): void
    {
        $ivo = $this->person('ivo@kestrel.test');
        $from = now()->addMonth()->startOfMonth()->toDateString();

        $this->post("/people/{$ivo->id}/changes", ['from' => $from, 'title' => 'Head of Support', 'salary' => 70000])
            ->assertRedirect("/people/{$ivo->id}")->assertSessionHasNoErrors();

        $this->get("/chart?on={$from}")->assertSee('Head of Support');
        $this->get('/chart')->assertDontSee('Head of Support');
    }

    public function test_a_change_cannot_be_backdated(): void
    {
        $ivo = $this->person('ivo@kestrel.test');

        $this->from("/people/{$ivo->id}")
            ->post("/people/{$ivo->id}/changes", ['from' => '2025-01-01', 'title' => 'Head of Support', 'salary' => 70000])
            ->assertSessionHasErrors('from');
    }

    public function test_the_chart_links_each_person_to_their_page(): void
    {
        $ada = $this->person('ada@kestrel.test');

        $this->get('/chart')->assertSee("/people/{$ada->id}", false);
    }

    private function person(string $email): Person
    {
        return Person::query()->where('email', $email)->firstOrFail();
    }
}
