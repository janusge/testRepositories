<?php

namespace App\Entity\Factory;

use App\Entity\Factory\Base\BaseFactory;
use App\Entity\Payment;
use App\Entity\Contract\Entity;

class PaymentFactory extends BaseFactory
{
    protected function getDefaultAttributes()
    {
        return array(
            'reference' => 12345678,
            'amount' => 100000,
            'description' => 'description',
            'customerId' => rand(1,10000)
        );
    }

    protected function mapAttributesToEntity(
        array $attributes,
        Entity $entity
    ) {
        $entity->setReference($attributes['reference']);
        $entity->setAmount($attributes['amount']);
        $entity->setDescription($attributes['description']);
        $entity->setCustomerId($attributes['customerId']);
    }
    protected function getEntityClass()
    {
        return Payment::class;
    }
}
