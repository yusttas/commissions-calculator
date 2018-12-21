<?php

require_once 'vendor/autoload.php';

use Paysera\Entities\Operation;
use Paysera\Persistance\InMemoryPersistance;
use Paysera\Repositories\OperationRepository;
use Paysera\Services\CommissionCalculator;
use Paysera\Services\Readers\CsvReader;

//$path=trim(fgets(STDIN)); arba $argv[1];
$path = 'input.csv';

$repository = new OperationRepository(new InMemoryPersistance());

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
    $repository->persist($operation);
}

$operations = $repository->getAll();

$calculator = new CommissionCalculator($repository);

$calculator->addOperations($operations);
$results = $calculator->calculate();

foreach ($results as $result) {
    fwrite(STDOUT, $result);
    fwrite(STDOUT, "\n");
}
