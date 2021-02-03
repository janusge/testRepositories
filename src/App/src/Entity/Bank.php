<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Base\BaseEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="banks")
 */
class Bank extends BaseEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id",type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(name="name", length=100)
     */
    protected $name;

    /**
     * @ORM\Column(name="description", length=100, nullable=TRUE)
     */
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="Payment", mappedBy="bank")
     */
    protected $payments;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function addPayment(Payment $payment)
    {
        $this->payments[] = $payment;
    }

    public function getPayments()
    {
        return $this->payments;
    }
}
