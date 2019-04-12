<?php

namespace Paysera\Services\Commissions;

use Paysera\Entities\Operation;

abstract class CommissionStrategy
{
    protected $operation;

    public function __construct(Operation $operation)
    {
        $this->operation = $operation;
    }

    abstract public function calculate();
}
