<?php

namespace Paysera\Persistance;

use Paysera\Persistance\Persistance;

class InMemoryPersistance implements Persistance
{
    private $data = array();

    public function getAll(): array
    {
        return $this->data;
    }

    public function getOne(int $id):array
    {
        foreach ($this->data as $row) {
            if ($row['id'] == $id) {
                return $row;
                break;
            }
        }
    }

    public function persist(array $row)
    {
        $this->data[] = $row;
    }
}
