<?php

namespace Paysera\Services;

use Paysera\Entities\Operation;
use Paysera\Repositories\Repository;
use Paysera\Services\Commissions\CashInStrategy;
use Paysera\Services\Commissions\CashOutStrategy;
use Paysera\Services\Commissions\CommissionCalculatorStrategy;

class CommissionCalculator
{
    protected $operations;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function addOperation(Operation $operation)
    {
        $this->operations[] = $operation;
    }

    public function addOperations(array $operations)
    {
        foreach ($operations as $operation) {
            $this->addOperation($operation);
        }
    }

    protected function getStrategy(Operation $operation): CommissionCalculatorStrategy
    {
        $operation_name = $operation->getName();

        switch ($operation->getName()) {
            case 'cash_in':
                $strategy = new CashInStrategy($operation, $this->repository);
                break;
            case 'cash_out':
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
        foreach ($this->operations as $operation) {

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
