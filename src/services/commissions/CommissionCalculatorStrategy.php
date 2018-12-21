<?php

namespace Paysera\Services\Commissions;

use Paysera\Entities\Operation;
use Paysera\Repositories\Repository;

interface CommissionCalculatorStrategy
{
    public function __construct(Operation $operation, Repository $repository);

    public function calculate(): float;
}
