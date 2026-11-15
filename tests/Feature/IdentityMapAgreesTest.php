<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\Department;
use App\Models\Person;
use App\Org\Staffing;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Vusys\Bitemporal\Facades\TemporalLens;
use Vusys\QuantumSlipstreamDrive\IdentityMap;
use Vusys\QuantumSlipstreamDrive\Testing\AssertsIdentityMapAgrees;

/**
 * Slipstream composed onto the nested set's builder and onto bitemporal's,
 * checked against the database for the reads the app makes.
 */
class IdentityMapAgreesTest extends TestCase
{
    use AssertsIdentityMapAgrees;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        IdentityMap::flush();
    }

    public function test_department_tree_reads_agree(): void
    {
        $engineering = Department::query()->where('name', 'Engineering')->firstOrFail();

        $this->assertIdentityMapAgrees(fn () => Department::query()->defaultOrder()->get());
        $this->assertIdentityMapAgrees(fn () => Department::query()->find($engineering->id));
        $this->assertIdentityMapAgrees(fn () => $engineering->descendants()->get());
    }

    public function test_point_in_time_assignment_reads_agree(): void
    {
        $dana = Person::query()->where('email', 'dana@kestrel.test')->firstOrFail();

        $this->assertIdentityMapAgrees(fn () => Assignment::query()->validAt('2025-08-01')->currentKnowledge()->orderBy('id')->get());
        $this->assertIdentityMapAgrees(fn () => Assignment::query()->validAt('2025-03-01')->knownAt('2025-03-01')->orderBy('id')->get());
        $this->assertIdentityMapAgrees(fn () => $dana->assignments()->currentKnowledge()->orderBy('id')->get());
        $this->assertIdentityMapAgrees(fn () => Assignment::query()->find(1));
    }

    public function test_reads_inside_an_as_of_lens_agree(): void
    {
        $this->assertIdentityMapAgrees(fn () => TemporalLens::asOf('2025-03-01', '2025-03-01', fn () => Assignment::query()->orderBy('id')->get()));
        $this->assertIdentityMapAgrees(fn () => TemporalLens::asOf('2025-03-01', '2025-03-01', fn () => Assignment::query()->find(1)));
    }

    public function test_finding_a_row_the_lens_excludes_agrees(): void
    {
        // Hana left on 1 April 2026: in a lens on 1 June her rows are not valid.
        $hana = Person::query()->where('email', 'hana@kestrel.test')->firstOrFail();
        $row = Assignment::query()->where('person_id', $hana->id)->whereNotNull('valid_to')->currentKnowledge()->firstOrFail();
        Assignment::query()->orderBy('id')->get();

        $this->assertNull(IdentityMap::disabled(fn () => TemporalLens::asOf('2026-06-01', null, fn () => Assignment::query()->find($row->id))), 'The lens excludes the row in the database.');
        $this->assertIdentityMapAgrees(fn () => TemporalLens::asOf('2026-06-01', null, fn () => Assignment::query()->find($row->id)));
        $this->assertIdentityMapAgrees(fn () => TemporalLens::asOf('2026-06-01', null, fn () => Assignment::query()->where('person_id', $hana->id)->get()));
    }

    public function test_a_read_after_a_lens_read_agrees(): void
    {
        // The lens filters at execution time; if the identity map recorded
        // that filtered answer as the unfiltered query's, it would serve it
        // again once the lens is gone.
        TemporalLens::asOf('2025-03-01', '2025-03-01', fn () => Assignment::query()->orderBy('id')->get());

        $this->assertIdentityMapAgrees(fn () => Assignment::query()->orderBy('id')->get());
    }

    public function test_reads_after_a_correction_agree(): void
    {
        $dana = Person::query()->where('email', 'dana@kestrel.test')->firstOrFail();
        Assignment::query()->validAt('2025-03-01')->currentKnowledge()->get();

        (new Staffing)->correct($dana, ['salary' => 120000], '2025-02-01');

        $this->assertIdentityMapAgrees(fn () => Assignment::query()->validAt('2025-03-01')->currentKnowledge()->orderBy('id')->get());
        $this->assertIdentityMapAgrees(fn () => $dana->assignments()->orderBy('id')->get());
    }
}
