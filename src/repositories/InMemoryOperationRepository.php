<?php

namespace Paysera\Repositories;

use Paysera\Entities\Operation;
use Paysera\Repositories\OperationRepository;

class InMemoryOperationRepository implements OperationRepository
{
    protected $operations;

    public function add(Operation $operation)
    {
        $this->operations[] = $operation;
    }

    public function getAll(): array
    {
        return $this->operations;
    }

    public function getPersonOperationsSameWeek(int $person_id, $date): array
    {
        $operations = [];

        $current_date = new \DateTime($date);
        $current_week = $current_date->format('W');

        foreach ($this->operations as $operation) {

            $operation_date = new \DateTime($operation->getDate());
            $operation_week = $operation_date->format('W');

            if ($operation->getPersonId() == $person_id && $operation->getName() == 'cash_out') {

                if ($current_week == $operation_week) {
                    $operations[] = $operation;
                } else if ($current_week < $operation_week) {
                    break;
                }
            }
        }

        return $operations;
    }
}
