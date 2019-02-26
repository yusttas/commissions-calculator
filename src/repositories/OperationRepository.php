<?php

namespace Paysera\Repositories;

interface OperationRepository
{
    public function getAll(): array;

    public function getPersonCashOutOperationsFromSameWeek(): array;
}
