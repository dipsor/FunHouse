<?php

namespace App\Services;

use App\Models\Rota;
use App\Services\Mannings\Allocator;
use App\Services\Mannings\DTODataBuilder;
use App\Services\Mannings\SingleManning;

class ManningResolver
{
    /**
     * @var Allocator
     */
    private $allocator;

    /**
     * ManningResolver constructor.
     *
     * @param  Allocator  $allocator
     */
    public function __construct(Allocator $allocator)
    {
        $this->allocator = $allocator;
    }

    public function resolve(Rota $rota): SingleManning
    {
        $allocatedManningsArray = $this->allocator->get($rota);

        return (new DTODataBuilder($allocatedManningsArray, $rota->week_commence_date))->getDTO();
    }
}
