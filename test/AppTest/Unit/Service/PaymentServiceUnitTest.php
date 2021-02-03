<?php

namespace AppTest\Unit\Service;

use AppTest\Unit\Repository\Base\DbTestCase;
use App\Entity\Factory\PaymentFactory;
use App\Service\PaymentService;
use App\Repository\PaymentUnitOfWork;
use App\Repository\PaymentRepository;
use App\Exception\EntityNotFoundException;

class PaymentServiceUnitTest extends DbTestCase
{
    protected $paymentFactory;
    protected $paymentUnitOfWork;
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentFactory = new PaymentFactory($this->entityManager);
        $this->paymentUnitOfWork = new PaymentUnitOfWork($this->entityManager);
        $this->repository = new PaymentRepository($this->entityManager);
    }

    public function testPaymentServiceCanCreatePayment()
    {
        $service = new PaymentService($this->paymentUnitOfWork);
        $attributes = [
          'reference' => 12345678,
          'amount' => 100000,
          'description' => 'description',
        ];
        $payment = $service->create($attributes);

        $this->assertTrue(
            $attributes['reference'] === $payment->getReference()
        );
        $this->assertTrue(
            $attributes['amount'] === $payment->getAmount()
        );
        $this->assertTrue(
            $attributes['description'] === $payment->getDescription()
        );
        $this->assertTrue(
            !!$payment->getId()
        );
    }

    public function testPaymentServiceCanList()
    {
        $this->paymentFactory->createMany(10);
        $service = new PaymentService($this->paymentUnitOfWork);
        $payments = $service->list();
        $paymentsCount = count($payments);

        $this->assertTrue($paymentsCount === 10);
    }

    public function testPaymentServiceCanGetDetails()
    {
        $entity = $this->paymentFactory->create();
        $id = $entity->getId();
        $service = new PaymentService($this->paymentUnitOfWork);
        $payment = $service->details($id);
        $this->assertTrue($payment->getId() === $id);
    }

    public function testPaymentServiceCanUpdatePayment()
    {
        $entity = $this->paymentFactory->create();
        $id = $entity->getId();
        $attributes = [
          'reference' => 987654,
          'amount' => 20000,
          'description' => 'New description',
        ];
        $service = new PaymentService($this->paymentUnitOfWork);
        $payment = $service->update($id, $attributes);

        $this->assertTrue(
            $attributes['reference'] === $payment->getReference()
        );
        $this->assertTrue(
            $attributes['amount'] === $payment->getAmount()
        );
        $this->assertTrue(
            $attributes['description'] === $payment->getDescription()
        );
        $this->assertTrue(
            $id === $payment->getId()
        );
    }

    public function testPaymentServiceCanDestroyPayment()
    {
        $this->expectException(EntityNotFoundException::class);
        try {
            $entity = $this->paymentFactory->create();
            $id = $entity->getId();
            $service = new PaymentService($this->paymentUnitOfWork);
            $service->destroy($id);
            $payment = $this->repository->get($id);
        } catch (EntityNotFoundException $exception) {
            $this->assertStringContainsString(
                'Entity not found id: ' . $id,
                $exception->getMessage()
            );
            throw $exception;
        }
    }
}
