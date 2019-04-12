<?php

require_once 'vendor/autoload.php';

use Paysera\Entities\Operation;
use Paysera\Persistance\InMemoryPersistance;
use Paysera\Repositories\InMemoryOperationRepository as OperationRepository;
use Paysera\Services\Commissions\CommissionCalculator;
use Paysera\Services\Readers\CsvReader;

//$path=trim(fgets(STDIN)); arba $argv[1];
$path = 'input.csv';

$repository = new OperationRepository();

$reader = new CsvReader($path);
$data = $reader->getData();

$id = 1;
foreach ($data as $row) {
    $operation = new Operation();
    $operation->setId($id++);
    $operation->setDate($row[0]);
    $operation->setPersonId((int) $row[1]);
    $operation->setPersonType($row[2]);
    $operation->setName($row[3]);
    $operation->setAmount($row[4]);
    $operation->setCurrency($row[5]);

    $repository->add($operation);
}

$calculator = new CommissionCalculator($repository);
$results = $calculator->calculate();

foreach ($results as $result) {
    fwrite(STDOUT, $result);
    fwrite(STDOUT, "\n");
}
