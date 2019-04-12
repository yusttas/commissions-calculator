<?php

namespace Paysera\Services\Commissions;

abstract class CommissionStrategy
{
    public function __construct(Operation $operation)
    {
        $this->operation = $operation;
    }
}
