<?php

namespace App\Repository;

use App\Repository\Base\BaseUnitOfWork;
use App\Repository\Contract\PaymentUnitOfWorkInterface;
use App\Repository\Contract\PaymentRepositoryInterface;
use App\Repository\PaymentRepository;

class PaymentUnitOfWork extends BaseUnitOfWork implements
  PaymentUnitOfWorkInterface
{
    protected $paymentRepository;

    public function __construct($entityManager)
    {
        $this->paymentRepository = new PaymentRepository($entityManager);
        parent::__construct($entityManager);
    }

    public function getPaymentRepository(): PaymentRepositoryInterface
    {
        return $this->paymentRepository;
    }
}
