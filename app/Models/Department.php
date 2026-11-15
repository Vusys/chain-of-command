<?php

declare(strict_types=1);

namespace App\Models;

use DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Vusys\NestedSet\Contracts\MaintainsTreeAggregates;
use Vusys\NestedSet\NodeTrait;
use Vusys\QuantumSlipstreamDrive\HasIdentityMap;

/**
 * A node in the company tree, identity-mapped through a builder that
 * composes slipstream onto the nested set's own (both traits claim
 * newEloquentBuilder(); {@see DepartmentBuilder} is both).
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
    use HasIdentityMap, NodeTrait {
        NodeTrait::newEloquentBuilder insteadof HasIdentityMap;
    }

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

    /**
     * @param  Builder  $query
     * @return DepartmentBuilder<self>
     */
    public function newEloquentBuilder($query): DepartmentBuilder
    {
        /** @var DepartmentBuilder<self> $builder */
        $builder = new DepartmentBuilder($query);

        return $builder;
    }

    /** @return HasMany<Assignment, $this> */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }
}
