<?php

namespace AppTest\Unit\Factory\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use App\Factory\Handler\PaymentHandlerFactory;
use App\Service\Contract\PaymentServiceInterface;
use App\Handler\PaymentHandler;

class PaymentHandlerFactoryUnitTest extends TestCase
{
    protected $container;

    protected function setUp() : void
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $paymentService = $this->prophesize(PaymentServiceInterface::class);
        $this->container
          ->get(PaymentServiceInterface::class)
          ->willReturn($paymentService);
    }

    public function testCanCreatePaymentHandler()
    {
        $factory = new PaymentHandlerFactory();
        $paymentHandler = $factory($this->container->reveal());
        $this->assertInstanceOf(PaymentHandler::class, $paymentHandler);
    }
}
