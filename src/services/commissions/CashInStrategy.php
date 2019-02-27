<?php

namespace Paysera\Services\Commissions;

use Paysera\Repositories\OperationRepository;
use Paysera\Entities\Operation;

class CashInStrategy implements CommissionCalculatorStrategy
{
    protected $operation;

    const COMMISSION_PERCENT = 0.03;
    const COMMISSION_MAX = 5;

    public function __construct(Operation $operation, OperationRepository $repository)
    {
        $this->operation = $operation;
    }

    public function calculate():float
    {
        $commission = $this->operation->getAmount() * self::COMMISSION_PERCENT / 100;

        if ($commission > self::COMMISSION_MAX) {
            return self::COMMISSION_MAX;
        }

        return $commission;
    }
}
