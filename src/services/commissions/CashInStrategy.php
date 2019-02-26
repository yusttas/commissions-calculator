<?php

namespace Paysera\Services\Commissions;

use Paysera\Repositories\OperationRepository;
use Paysera\Entities\Operation;

class CashInStrategy implements CommissionCalculatorStrategy
{
    protected $operation;

    public $commission_percent = 0.03;
    public $commission_max = 5;

    public function __construct(Operation $operation, OperationRepository $repository)
    {
        $this->operation = $operation;
    }

    public function calculate():float
    {
        $amount = $this->operation->getAmount() * $this->commission_percent / 100;

        if ($amount > $this->commission_max) {
            return $this->commission_max;
        }

        return $amount;
    }
}
