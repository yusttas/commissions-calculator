<?php

abstract class CommissionStrategy
{
    public function __construct(Operation $operation)
    {
        $this->operation = $operation;
    }
}
