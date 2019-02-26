<?php

namespace Paysera\Services\Commissions;

use Paysera\Entities\Operation;
use Paysera\Repositories\OperationRepository;

interface CommissionCalculatorStrategy
{
    public function __construct(Operation $operation, OperationRepository $repository);

    public function calculate(): float;
}
