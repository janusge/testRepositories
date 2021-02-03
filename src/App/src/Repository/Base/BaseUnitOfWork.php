<?php

namespace App\Repository\Base;

use App\Repository\Contract\UnitOfWork;

abstract class BaseUnitOfWork implements UnitOfWork
{
  protected $entityManager;

  public function __construct($entityManager)
  {
      $this->entityManager = $entityManager;
  }

  public function complete()
  {
      $this->entityManager->flush();
  }
}
