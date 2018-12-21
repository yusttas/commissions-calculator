<?php

namespace Paysera\Services\Commissions;

use Paysera\Entities\Operation;
use Paysera\Services\Commissions\CashOutStrategy;
use PHPUnit\Framework\TestCase;

class CashOutStrategyTest extends TestCase
{

    protected function setUp()
    {

    }

    public function testCalculateForNaturalPerson()
    {
        //$operation = $this->createMock(Operation::class);
        $operation=new Operation();
        $operation->setAmount(3);
        $operation->setPersonType('legal');

        $strategy = new CashOutStrategy($operation);

        $result = $strategy->calculate();

        $this->assertEquals('0.5', $result);
    }

  /*   public function testCalculateForLegalPerson()
    {
        //$operation = $this->createMock(Operation::class);
        $operation=new Operation();
        $operation->setAmount(3);
        $operation->setPersonType('legal');

        $strategy = new CashOutStrategy($operation);

        $result = $strategy->calculate();

        $this->assertEquals('0.0009', $result);
    } */
}
