<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Department;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DirectoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_it_lists_everyone_in_post_by_name_five_to_a_page(): void
    {
        $this->get('/people?on=2025-08-01')
            ->assertOk()
            ->assertSee('10 in post on 1 August 2025')
            ->assertSeeInOrder(['Ada Okafor', 'Bram Visser', 'Chen Wei', 'Dana Ruiz', 'Eli Novak'])
            ->assertDontSee('Farah Haddad');

        $this->get('/people?on=2025-08-01&page=2')
            ->assertSeeInOrder(['Farah Haddad', 'Gus Lindqvist', 'Hana Sato', 'Ivo Petrov', 'Jun Park']);
    }

    public function test_the_date_is_a_point_in_valid_time(): void
    {
        // Hana left in April 2026; Jun joined in June 2025.
        $this->get('/people?on=2026-05-01&q=Hana')->assertSee('Nobody matches');
        $this->get('/people?on=2025-05-01&q=Jun')->assertSee('Nobody matches');
        $this->get('/people?on=2025-07-01&q=Jun')->assertSee('Jun Park');
    }

    public function test_search_matches_names_and_titles_and_marks_the_match(): void
    {
        $this->get('/people?on=2026-02-01&q=support')
            ->assertSee('2 in post')
            ->assertSee('<mark>Support</mark> Lead', false)
            ->assertSee('<mark>Support</mark> Agent', false)
            ->assertDontSee('Chen Wei');

        $this->get('/people?on=2026-02-01&q=ana')
            ->assertSee('D<mark>ana</mark> Ruiz', false)
            ->assertSee('H<mark>ana</mark> Sato', false);
    }

    public function test_a_department_includes_every_team_under_it(): void
    {
        $engineering = Department::query()->where('name', 'Engineering')->sole();

        $this->get("/people?on=2025-08-01&department={$engineering->id}")
            ->assertSee('5 in post')
            ->assertSee('Kestrel Labs › Engineering')
            ->assertSee('Farah Haddad')
            ->assertDontSee('Chen Wei');
    }

    public function test_the_search_term_is_escaped(): void
    {
        $this->get('/people?q='.urlencode('<script>x</script>'))
            ->assertOk()
            ->assertDontSee('<script>x</script>', false)
            ->assertSee('&lt;script&gt;', false);
    }

    public function test_paging_keeps_the_filters(): void
    {
        // A real link, not the escaped markup of one (laravel-smarty#68).
        $this->get('/people?on=2025-08-01&q=e')
            ->assertSee('<nav role="navigation"', false)
            ->assertSee('rel="next"', false)
            ->assertSee('q=e&amp;page=2', false)
            ->assertDontSee('&lt;nav', false);
    }
}
