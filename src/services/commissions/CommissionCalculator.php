<?php

namespace Paysera\Services\Commissions;

use Paysera\Entities\Operation;
use Paysera\Repositories\OperationRepository;
use Paysera\Services\Commissions\CashInStrategy;
use Paysera\Services\Commissions\CashOutStrategy;
use Paysera\Services\Commissions\CommissionCalculatorStrategy;

class CommissionCalculator
{
    protected $operations;

    public function __construct(OperationRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function getStrategy(Operation $operation): CommissionCalculatorStrategy
    {
        $operation_name = $operation->getName();

        switch ($operation->getName()) {
            case Operation::CASH_IN:
                $strategy = new CashInStrategy($operation);
                break;
            case Operation::CASH_OUT:
                $strategy = new CashOutStrategy($operation, $this->repository);
                break;
            default:
                throw new \Exception("Unknown strategy: " . $operation_name);
                break;
        }

        return $strategy;
    }

    public function calculate(): array
    {
        $results = [];
        foreach ($this->repository->getAll() as $operation) {

            $calculator = $this->getStrategy($operation);

            $results[] = $this->format($calculator->calculate());
        }

        return $results;
    }

    protected function format($result): string
    {
        $rounded = ceil($result * 100) / 100;

        $formatted_result = number_format((float) $rounded, 2, '.', '');

        return $formatted_result;
    }
}
