<?php

namespace Paysera\Services\Commissions;

use Paysera\Entities\Operation;
use Paysera\Repositories\OperationRepository;

interface CommissionCalculatorStrategy
{
    public function calculate(): float;
}
