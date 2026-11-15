<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Vusys\NestedSet\Contracts\MaintainsTreeAggregates;
use Vusys\NestedSet\NodeTrait;

/**
 * A node in the company tree. Not identity-mapped: NodeTrait and
 * slipstream's HasIdentityMap both replace newEloquentBuilder().
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

    /** @return HasMany<Assignment, $this> */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }
}
