<?php

declare(strict_types=1);

namespace AppTest\Handler;

use App\Handler\PaymentHandler;
use Laminas\Diactoros\Response\JsonResponse;
use AppTest\Unit\Repository\Base\DbTestCase;
use Psr\Http\Message\ServerRequestInterface;
use App\Entity\Factory\PaymentFactory;
use App\Service\PaymentService;
use App\Repository\PaymentUnitOfWork;

use function json_decode;

class PaymentHandlerTest extends DbTestCase
{
    protected $paymentFactory;
    protected $paymentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentFactory = new PaymentFactory($this->entityManager);
        $paymentUnitOfWork = new PaymentUnitOfWork($this->entityManager);
        $this->paymentService = new PaymentService($paymentUnitOfWork);
    }

    public function testPaymentHandlerPostAction()
    {
        $reference = "12365479";
        $amount = 1000;
        $description = "pago de compra";
        $paymentHandler = new PaymentHandler($this->paymentService);
        $request = $this->prophesize(ServerRequestInterface::class);
        $request->getMethod()->willReturn("POST");
        $request->getParsedBody()->willReturn([
            "reference" => $reference,
            "amount" => $amount,
            "description" => $description,
        ]);
        $response = $paymentHandler->handle(
            $request->reveal()
        );

        $json = json_decode((string) $response->getBody());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertTrue(isset($json->payment));
        $this->assertTrue($json->payment->reference === $reference);
        $this->assertTrue($json->payment->amount === $amount);
        $this->assertTrue($json->payment->description === $description);
    }

    public function testPaymentHandlerGetListAction()
    {
        $this->paymentFactory->createMany(10);
        $paymentHandler = new PaymentHandler($this->paymentService);
        $request = $this->prophesize(ServerRequestInterface::class);
        $request->getMethod()->willReturn("GET");
        $request->getAttribute("id")->willReturn("");
        $response = $paymentHandler->handle(
            $request->reveal()
        );

        $json = json_decode((string) $response->getBody());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertTrue(isset($json->payments));
        $this->assertTrue(count($json->payments) === 10);
    }

    public function testPaymentHandlerGetAction()
    {
        $payment = $this->paymentFactory->create();
        $id = $payment->getId();
        $request = $this->prophesize(ServerRequestInterface::class);
        $request->getMethod()->willReturn("GET");
        $request->getAttribute("id")->willReturn($id);
        $paymentHandler = new PaymentHandler($this->paymentService);
        $response = $paymentHandler->handle(
            $request->reveal()
        );

        $json = json_decode((string) $response->getBody());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertTrue(isset($json->payment));
        $this->assertTrue($json->payment->id === $id);
    }

    public function testPaymentHandlerPutAction()
    {
        $payment = $this->paymentFactory->create();
        $id = $payment->getId();
        $reference = "12365479";
        $amount = 1000;
        $description = "pago de compra";
        $paymentHandler = new PaymentHandler($this->paymentService);
        $request = $this->prophesize(ServerRequestInterface::class);
        $request->getMethod()->willReturn("PUT");
        $request->getAttribute("id")->willReturn($id);
        $request->getParsedBody()->willReturn([
            "reference" => $reference,
            "amount" => $amount,
            "description" => $description,
        ]);
        $response = $paymentHandler->handle(
            $request->reveal()
        );

        $json = json_decode((string) $response->getBody());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertTrue(isset($json->payment));
        $this->assertTrue($json->payment->reference === $reference);
        $this->assertTrue($json->payment->amount === $amount);
        $this->assertTrue($json->payment->description === $description);
    }

    public function testPaymentHandlerDeleteAction()
    {
        $payment = $this->paymentFactory->create();
        $id = $payment->getId();
        $paymentHandler = new PaymentHandler($this->paymentService);
        $request = $this->prophesize(ServerRequestInterface::class);
        $request->getMethod()->willReturn("DELETE");
        $request->getAttribute("id")->willReturn($id);
        $response = $paymentHandler->handle(
            $request->reveal()
        );

        $json = json_decode((string) $response->getBody());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertTrue(isset($json->payment));
        $this->assertTrue($json->payment->id === null);
    }
}
