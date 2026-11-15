<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Person;
use App\Org\Staffing;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

/**
 * Kestrel Labs, a small fictional company with two years of history: a
 * reorg, a transfer, promotions, a leaver and a backdated pay correction.
 */
class DatabaseSeeder extends Seeder
{
    public function run(Staffing $staffing): void
    {
        $company = $this->department('Kestrel Labs');
        $engineering = $this->department('Engineering', $company);
        $platform = $this->department('Platform', $engineering);
        $product = $this->department('Product', $engineering);
        $operations = $this->department('Operations', $company);
        $support = $this->department('Support', $operations);
        $finance = $this->department('Finance', $company);

        $ceo = $this->on('2025-01-01', fn (): Person => $staffing->hire('Ada Okafor', 'ada@kestrel.test', $company, null, 'Chief Executive', 180000, '2025-01-01'));
        $cto = $this->on('2025-01-01', fn (): Person => $staffing->hire('Bram Visser', 'bram@kestrel.test', $engineering, $ceo, 'Head of Engineering', 140000, '2025-01-01'));
        $ops = $this->on('2025-01-01', fn (): Person => $staffing->hire('Chen Wei', 'chen@kestrel.test', $operations, $ceo, 'Head of Operations', 120000, '2025-01-01'));
        $cfo = $this->on('2025-02-01', fn (): Person => $staffing->hire('Dana Ruiz', 'dana@kestrel.test', $finance, $ceo, 'Finance Lead', 115000, '2025-02-01'));
        $lead = $this->on('2025-01-01', fn (): Person => $staffing->hire('Eli Novak', 'eli@kestrel.test', $platform, $cto, 'Platform Lead', 105000, '2025-01-01'));
        $dev = $this->on('2025-03-01', fn (): Person => $staffing->hire('Farah Haddad', 'farah@kestrel.test', $platform, $lead, 'Engineer', 82000, '2025-03-01'));
        $pm = $this->on('2025-01-01', fn (): Person => $staffing->hire('Gus Lindqvist', 'gus@kestrel.test', $product, $cto, 'Product Manager', 90000, '2025-01-01'));
        $designer = $this->on('2025-04-01', fn (): Person => $staffing->hire('Hana Sato', 'hana@kestrel.test', $product, $pm, 'Designer', 78000, '2025-04-01'));
        $agent = $this->on('2025-01-01', fn (): Person => $staffing->hire('Ivo Petrov', 'ivo@kestrel.test', $support, $ops, 'Support Agent', 52000, '2025-01-01'));
        $this->on('2025-06-01', fn (): Person => $staffing->hire('Jun Park', 'jun@kestrel.test', $support, $ops, 'Support Agent', 52000, '2025-06-01'));

        // Dana's starting salary was keyed wrong; she was hired on 118,000.
        // Found and fixed months later; the original belief stays on record.
        $this->on('2025-06-15', fn () => $staffing->correct($cfo, ['salary' => 118000], '2025-02-01'));

        // Farah moves to Product mid-year, then is promoted.
        $this->on('2025-07-01', fn () => $staffing->transfer($dev, $product, $pm, '2025-07-01'));
        $this->on('2026-01-01', fn () => $staffing->promote($dev, 'Senior Engineer', 95000, '2026-01-01'));

        // Ivo leads Support from the new year; Hana leaves in the spring.
        $this->on('2026-01-01', fn () => $staffing->promote($agent, 'Support Lead', 64000, '2026-01-01'));
        $this->on('2026-04-01', fn () => $staffing->leave($designer, '2026-04-01'));
    }

    /**
     * Run $event as if today were $date, so each write lands on the
     * recorded axis when it happened — and changeEffectiveFrom, which only
     * accepts today or later, takes the historical dates.
     *
     * @template T
     *
     * @param  callable(): T  $event
     * @return T
     */
    private function on(string $date, callable $event): mixed
    {
        CarbonImmutable::setTestNow($date);

        try {
            return $event();
        } finally {
            CarbonImmutable::setTestNow();
        }
    }

    private function department(string $name, ?Department $parent = null): Department
    {
        $department = new Department(['name' => $name]);
        $parent instanceof Department ? $department->appendToNode($parent->refresh())->save() : $department->saveAsRoot();

        return $department;
    }
}
