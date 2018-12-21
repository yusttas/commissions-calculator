<?php

namespace Paysera\Services\Commissions;

use Paysera\Entities\Operation;
use Paysera\Repositories\Repository;
use Paysera\Services\Commissions\CommissionCalculatorStrategy;
use Paysera\Services\CurrencyConverter;

class CashOutStrategy implements CommissionCalculatorStrategy
{
    protected $operation;
    protected $repository;

    public $commission_percent = 0.3;
    public $commission_min_legal = 0.50;
    public $times_per_week = 3;
    public $amount_per_week = 1000;

    public function __construct(Operation $operation, Repository $repository)
    {
        $this->operation = $operation;
        $this->repository = $repository;
    }

    public function calculate(): float
    {
        $person_type = $this->operation->getPersonType();

        if ($person_type == 'natural') {
            $commission = $this->calculateForNaturalPerson();
        } else if ($person_type == 'legal') {
            $commission = $this->calculateForLegalPerson();
        }

        return (float) $commission;
    }

    protected function calculateForNaturalPerson():float
    {
        $id = $this->operation->getId();
        $person_id = $this->operation->getPersonId();
        $current_date = $this->operation->getDate();

        $current_amount = CurrencyConverter::convertToEur($this->operation->getAmount(), $this->operation->getCurrency());

        $person_operations = $this->repository->getPersonOperationsSameWeek($person_id, $current_date);

        $times_per_week = 0;
        $amount_per_week = 0;
        $discount_id = null;

        foreach ($person_operations as $operation) {
            $times_per_week++;
            if ($times_per_week <= $this->times_per_week) {
                $amount_per_week += CurrencyConverter::convertToEur($operation->getAmount(), $operation->getCurrency());
            }

            if ($amount_per_week >= $this->amount_per_week) {
                $discount_id = $operation->getId();
                break;
            }
        }

        if (!empty($discount_id)) {

            if ($id == $discount_id) {
                $current_amount = $amount_per_week - $this->amount_per_week;
            } else if ($id < $discount_id) {
                $current_amount = 0;
            }

        }

        $commission = $current_amount * $this->commission_percent / 100;

        $converted = CurrencyConverter::convertEur($commission, $this->operation->getCurrency());

        return $converted;
    }

    protected function calculateForLegalPerson(): float
    {
        $amount = $this->operation->getAmount() * $this->commission_percent / 100;

        if ($amount < $this->commission_min_legal) {
            return $this->commission_min_legal;
        }

        return $amount;
    }
}
