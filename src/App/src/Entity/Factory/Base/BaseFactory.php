<?php

namespace App\Entity\Factory\Base;

use App\Entity\Contract\Entity;

abstract class BaseFactory
{
  protected $entityManager;

  abstract protected function mapAttributesToEntity(
    array $attributes,
    Entity $entity
  );
  abstract protected function getDefaultAttributes();
  abstract protected function getEntityClass();

  public function __construct($entityManager)
  {
      $this->entityManager = $entityManager;
  }

  public function create($newAttributes = array()): Entity
  {
    $entityClass = $this->getEntityClass();
    $entity = new $entityClass();
    $attributes = $this->setAttributes($newAttributes);
    $this->storeEntity($attributes, $entity);
    return $entity;
  }

  public function createMany($numb): array
  {
      $entities = array();
      for ($i=0; $i < $numb; $i++) {
        $entity = $this->create();
        array_push($entities, $entity);
      }

      return $entities;
  }

  protected function setAttributes(array $newAttributes)
  {
      $defaultAttributes = $this->getDefaultAttributes();
      return array_merge($defaultAttributes, $newAttributes);
  }

  protected function storeEntity(array $attributes, Entity $entity)
  {
    $this->mapAttributesToEntity($attributes, $entity);
    $this->entityManager->persist($entity);
    $this->entityManager->flush();
  }
}
