<?php

namespace Paysera\Repositories;

use Paysera\Entities\Operation;
use Paysera\Persistance\Persistance;

class InMemoryOperationRepository implements Repository
{
    /**
     * @var Singleton
     */
    private static $instance;
    private $persistance;

    public function __construct(Persistance $persistance)
    {
        $this->persistance = $persistance;
    }

    public function getAll(): array
    {
        $result = $this->buildCollection($this->persistance->getAll());

        return $result;
    }

    public function getPersonOperationsSameWeek(int $person_id, $date): array
    {
        $operations = [];

        $current_date = new \DateTime($date);
        $current_week = $current_date->format('W');

        foreach ($this->persistance->getAll() as $operation) {

            $operation_date = new \DateTime($operation['date']);
            $operation_week = $operation_date->format('W');

            if ($operation['person_id'] == $person_id && $operation['name'] == 'cash_out') {

                if ($current_week == $operation_week) {
                    $operations[] = $operation;
                } else if ($current_week < $operation_week) {
                    break;
                }
            }
        }

        return $this->buildCollection($operations);
    }

    public function persist(Operation $operation)
    {
        $this->persistance->persist([
            'id' => $operation->getId(),
            'date' => $operation->getDate(),
            'person_id' => $operation->getPersonId(),
            'person_type' => $operation->getPersonType(),
            'name' => $operation->getName(),
            'amount' => $operation->getAmount(),
            'currency' => $operation->getCurrency(),
        ]);
    }

    public function buildCollection($data): array
    {
        $collection = [];
        foreach ($data as $row) {
            $operation = new Operation();
            $operation->setId($row['id']);
            $operation->setName($row['name']);
            $operation->setDate($row['date']);
            $operation->setPersonId($row['person_id']);
            $operation->setPersonType($row['person_type']);
            $operation->setCurrency($row['currency']);
            $operation->setAmount($row['amount']);

            $collection[] = $operation;
        }

        return $collection;
    }
}
