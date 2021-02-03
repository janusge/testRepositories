<?php

namespace App\Repository\Base;

use App\Repository\Contract\Repository;
use App\Entity\Contract\Entity;
use App\Exception\EntityNotFoundException;
use App\Exception\RepositoryException;

abstract class BaseRepository implements Repository
{
    protected $entityManager;
    protected $repository;

    abstract protected function getEntity();
    abstract protected function mapAttributesToEntity(
        array $attributes,
        Entity $entity
    ): Entity;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
        $entitty = $this->getEntity();
        $this->repository = $this->entityManager->getRepository($entitty);
    }

    public function add(Entity $entity): Entity
    {
        try {
            $this->entityManager->persist($entity);
            return $entity;
        } catch (\Exception $exception) {
            //logging error
            throw new RepositoryException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    public function addWithArray(array $attributes): Entity
    {
        try {
            $entityClass = $this->getEntity();
            $entity = new $entityClass();
            $entity = $this->mapAttributesToEntity($attributes, $entity);
            $this->add($entity);
            return $entity;
        } catch (\Exception $exception) {
            //logging error
            throw new RepositoryException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    public function get($id): Entity
    {
        $entityFound = $this->repository->find($id);
        if (!$entityFound) {
            throw new EntityNotFoundException('Entity not found id: ' . $id);
        }
        return $entityFound;
    }

    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    public function find(
        $criteria,
        $orderBy = ['id' => 'ASC'],
        $limit = null,
        $offset = null
    ): array {
        $entities = $this->repository->findBy(
            $criteria,
            $orderBy,
            $limit,
            $offset
        );
        return $entities;
    }

    public function remove($id): Entity
    {
        try {
            $entityFound = $this->get($id);
            $this->entityManager->remove($entityFound);
            return $entityFound;
        } catch (\Exception $exception) {
            //logging error
            throw new RepositoryException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }
}
