<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vusys\Bitemporal\Bitemporal;

/**
 * Where a person sat, who they reported to, their title and salary, over a
 * valid spell — and when we believed it, over a recorded spell. Written only
 * through Person::assignments(); never updated in place.
 *
 * Larastan reads columns from migrations but not from the bitemporal
 * Blueprint macros, so the columns those add are declared here.
 *
 * @property int $person_id
 * @property CarbonImmutable $valid_from
 * @property CarbonImmutable|null $valid_to
 * @property CarbonImmutable $recorded_from
 * @property CarbonImmutable|null $recorded_to
 * @property bool $is_retraction
 */
class Assignment extends Model
{
    use Bitemporal;

    protected string $temporalEntity = Person::class;

    protected $guarded = [];

    protected $casts = [
        'salary' => 'integer',
    ];

    /** The value tuple every write must pass in full. */
    public const array VALUES = ['department_id', 'manager_id', 'title', 'salary'];

    /** @return BelongsTo<Person, $this> */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /** @return BelongsTo<Department, $this> */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /** @return BelongsTo<Person, $this> */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'manager_id');
    }
}
