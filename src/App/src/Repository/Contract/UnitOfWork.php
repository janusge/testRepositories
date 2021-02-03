<?php

namespace App\Repository\Contract;

interface UnitOfWork
{
  public function complete();
}
