<?php

namespace App\Service;

use App\Service\Contract\PaymentServiceInterface;
use App\Repository\Contract\PaymentUnitOfWorkInterface;
use App\Entity\Contract\Entity;

class PaymentService implements PaymentServiceInterface
{
    protected $unitOfWork;

    public function __construct(PaymentUnitOfWorkInterface $unitOfWork)
    {
        $this->unitOfWork = $unitOfWork;
    }

    public function create($attributes): Entity
    {
        $payment = $this
          ->unitOfWork
          ->getPaymentRepository()
          ->addWithArray($attributes);
        $this->unitOfWork->complete();
        return $payment;
    }

    public function details($id): Entity
    {
        $payment = $this
          ->unitOfWork
          ->getPaymentRepository()
          ->get($id);

        return $payment;
    }

    public function list(): array
    {
        $payments = $this
          ->unitOfWork
          ->getPaymentRepository()
          ->getAll();

        return $payments;
    }

    public function update($id, $attributes): Entity
    {
        $payment = $this
          ->unitOfWork
          ->getPaymentRepository()
          ->get($id);

        $payment->setAmount($attributes['amount']);
        $payment->setReference($attributes['reference']);
        $payment->setDescription($attributes['description']);

        $this->unitOfWork->complete();

        return $payment;
    }

    public function destroy($id): Entity
    {
        $payment = $this
          ->unitOfWork
          ->getPaymentRepository()
          ->remove($id);
        $this->unitOfWork->complete();
        return $payment;
    }
}
