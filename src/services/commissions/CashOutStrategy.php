<?php

namespace Paysera\Services\Commissions;

use Paysera\Entities\Operation;
use Paysera\Repositories\OperationRepository;
use Paysera\Services\Commissions\CommissionStrategy;
use Paysera\Services\CurrencyConverter;

class CashOutStrategy extends CommissionStrategy
{
    const COMMISSION_PERCENT = 0.3;
    const COMMISSION_MIN_LEGAL = 0.50;
    const TIMES_PER_WEEK = 3;
    const AMOUNT_PER_WEEK = 1000;

    private $repository;

    public function __construct(Operation $operation, OperationRepository $repository)
    {
        parent::__construct($operation);
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

    protected function calculateForNaturalPerson(): float
    {
        $id = $this->operation->getId();
        $person_id = $this->operation->getPersonId();
        $current_date = $this->operation->getDate();

        $current_amount = CurrencyConverter::convertToEur($this->operation->getAmount(), $this->operation->getCurrency());

        $person_operations = $this->repository->getPersonCashOutOperationsFromSameWeek($person_id, $current_date);

        $times_per_week = 0;
        $amount_per_week = 0;
        $discount_id = null;

        foreach ($person_operations as $operation) {
            $times_per_week++;
            if ($times_per_week <= self::TIMES_PER_WEEK) {
                $amount_per_week += CurrencyConverter::convertToEur($operation->getAmount(), $operation->getCurrency());
            }

            if ($amount_per_week >= self::AMOUNT_PER_WEEK) {
                $discount_id = $operation->getId();
                break;
            }
        }

        if (!empty($discount_id)) {

            if ($id == $discount_id) {
                $current_amount = $amount_per_week - self::AMOUNT_PER_WEEK;
            } else if ($id < $discount_id) {
                $current_amount = 0;
            }

        } else {
            $current_amount = 0;
        }

        $commission = $current_amount * self::COMMISSION_PERCENT / 100;

        $converted = CurrencyConverter::convertEur($commission, $this->operation->getCurrency());

        return $converted;
    }

    protected function calculateForLegalPerson(): float
    {
        $commission = $this->operation->getAmount() * self::COMMISSION_PERCENT / 100;

        if ($commission < self::COMMISSION_MIN_LEGAL) {
            return self::COMMISSION_MIN_LEGAL;
        }

        return $commission;
    }
}
