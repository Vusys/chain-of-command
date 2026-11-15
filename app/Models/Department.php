<?php

declare(strict_types=1);

namespace App\Models;

use DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Vusys\NestedSet\Contracts\MaintainsTreeAggregates;
use Vusys\NestedSet\NodeTrait;

/**
 * A node in the company tree. Not identity-mapped: NodeTrait and
 * slipstream's HasIdentityMap both replace newEloquentBuilder().
 *
 * Larastan can't see the columns the nestedSet() migration macro adds.
 *
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 * @property int|null $parent_id
 */
class Department extends Model implements MaintainsTreeAggregates
{
    use NodeTrait;

    protected $fillable = ['name'];

    protected $casts = [
        'lft' => 'integer',
        'rgt' => 'integer',
        'depth' => 'integer',
        'parent_id' => 'integer',
    ];

    /**
     * Move this department, with everything under it, to sit under $parent.
     * Refused for the top of the company, and for a move into its own subtree.
     */
    public function moveUnder(self $parent): void
    {
        if ($this->isRoot()) {
            throw new DomainException('The company itself cannot be moved.');
        }

        if ($parent->is($this) || $parent->isDescendantOf($this)) {
            throw new DomainException("{$this->name} cannot move under {$parent->name}, which is inside it.");
        }

        $this->appendToNode($parent->refresh())->save();
    }

    /** @return HasMany<Assignment, $this> */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }
}
