<?php

namespace Paysera\Services\Commissions;

use Paysera\Entities\Operation;
use Paysera\Services\Commissions\CommissionStrategy;

class CashInStrategy extends CommissionStrategy implements CommissionCalculatorStrategy
{
    protected $operation;

    const COMMISSION_PERCENT = 0.03;
    const COMMISSION_MAX = 5;

    public function calculate(): float
    {
        $commission = $this->operation->getAmount() * self::COMMISSION_PERCENT / 100;

        if ($commission > self::COMMISSION_MAX) {
            return self::COMMISSION_MAX;
        }

        return $commission;
    }
}
