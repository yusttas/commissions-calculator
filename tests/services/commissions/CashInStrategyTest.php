<?php

namespace Paysera\Services\Commissions;

use Paysera\Entities\Operation;
use Paysera\Services\Commissions\CashInStrategy;
use PHPUnit\Framework\TestCase;

class CashInStrategyTest extends TestCase
{

    protected function setUp()
    {

    }

    public function testGetInstance()
    {
        $operation = $this->createMock(Operation::class);

        $strategy = new CashInStrategy($operation);

        $this->assertInstanceOf(CashInStrategy::class, $strategy);
    }

/*     public function testFailInstance()
{

$strategy = new CashInStrategy();

$this->assertInstanceOf(CashInStrategy::class, $strategy);
} */

    public function testCalculate()
    {
        //$operation = $this->createMock(Operation::class);
        $operation=new Operation();
        $operation->setAmount(3);
        $strategy = new CashInStrategy($operation);

        $result = $strategy->calculate();

        $this->assertEquals('0.0009', $result);
    }
}
