<?php

namespace App\Repository;

use App\Repository\Contract\PaymentRepositoryInterface;
use App\Repository\Base\BaseRepository;
use App\Entity\Payment;
use App\Entity\Contract\Entity;

class PaymentRepository extends BaseRepository implements
  PaymentRepositoryInterface
{
    protected function getEntity()
    {
        return Payment::class;
    }

    protected function mapAttributesToEntity(
        array $attributes,
        Entity $entity
    ): Entity {
        $nullables = [
            'customerId' => null
        ];
        $complete = array_merge($nullables, $attributes);
        $entity->setReference($complete['reference']);
        $entity->setAmount($complete['amount']);
        $entity->setDescription($complete['description']);
        $entity->setCustomerId($complete['customerId']);

        return $entity;
    }

    public function findByDescription(
        $description,
        $orderBy = ['id' => 'ASC'],
        $limit = null,
        $offset = null
    ): array {
        $payments = $this->repository->findBy(
            ['description' => $description],
            $orderBy,
            $limit,
            $offset
        );
        return $payments;
    }
}
