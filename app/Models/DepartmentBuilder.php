<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Vusys\NestedSet\Query\TreeQueryBuilder;
use Vusys\QuantumSlipstreamDrive\Query\InteractsWithIdentityMap;

/**
 * The nested set's tree builder with slipstream's identity map composed on,
 * so the chart's repeated department lookups are fetched once.
 *
 * @template TModel of Model
 *
 * @extends TreeQueryBuilder<TModel>
 */
class DepartmentBuilder extends TreeQueryBuilder
{
    /** @use InteractsWithIdentityMap<TModel> */
    use InteractsWithIdentityMap;
}
