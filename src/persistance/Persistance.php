<?php

namespace Paysera\Persistance;

interface Persistance{

    public function getAll():array;

    public function getOne(int $id):array;

    public function persist(array $row);
}