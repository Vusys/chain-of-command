<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Vusys\Bitemporal\Concerns\HasBitemporalRelations;
use Vusys\Bitemporal\Relations\BitemporalMany;
use Vusys\Bitemporal\Relations\BitemporalOne;
use Vusys\QuantumSlipstreamDrive\HasIdentityMap;

class Person extends Model
{
    use HasBitemporalRelations;

    // A chart names the same managers over and over; each is fetched once.
    use HasIdentityMap;

    protected $fillable = ['name', 'email'];

    /** The whole timeline — the relation every write goes through. */
    public function assignments(): BitemporalMany
    {
        return $this->bitemporalMany(Assignment::class);
    }

    /** The one assignment in force — pin it with validAt() or a lens. */
    public function assignment(): BitemporalOne
    {
        return $this->bitemporalOne(Assignment::class);
    }
}
