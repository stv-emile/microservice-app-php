<?php

namespace App\Filter\Modifier\Factory;

use App\Filter\Modifier\PriceModifierInterface;

interface PriceModifierFactoryInterface
{
    const PRICE_MODIFIER_NAMESPACE = 'App\Filter\Modifier\\';

    public function create(String $modifierType):PriceModifierInterface;
}