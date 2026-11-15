<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Vusys\Bitemporal\BitemporalBuilder;
use Vusys\Bitemporal\Facades\TemporalLens;
use Vusys\Bitemporal\Lens\LensFrame;
use Vusys\QuantumSlipstreamDrive\Query\InteractsWithIdentityMap;

/**
 * Bitemporal's builder with slipstream composed on. The as-of lens applies
 * its dates at execution time, after the identity map would have answered,
 * so the map stands aside while a lens frame is open.
 * tests/Feature/IdentityMapAgreesTest checks the reads against the database.
 *
 * @template TModel of Model
 *
 * @extends BitemporalBuilder<TModel>
 */
class AssignmentBuilder extends BitemporalBuilder
{
    /** @use InteractsWithIdentityMap<TModel> */
    use InteractsWithIdentityMap {
        bypassesIdentityMap as private bypassesByDefault;
    }

    protected function bypassesIdentityMap(): bool
    {
        return TemporalLens::current() instanceof LensFrame || $this->bypassesByDefault();
    }
}
