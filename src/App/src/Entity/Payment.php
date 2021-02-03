<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Base\BaseEntity;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Bank;

/**
 * @ORM\Entity
 * @ORM\Table(name="payments")
 */
class Payment extends BaseEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id",type="integer")
     */
    protected $id = null;

    /**
     * @ORM\Column(name="reference",type="integer")
     */
    protected $reference;

    /**
     * @ORM\Column(name="amount",type="bigint")
     */
    protected $amount;

    /**
     * @ORM\Column(name="description", length=100, nullable=TRUE)
     */
    protected $description;

    /**
     * @ORM\Column(name="customer_id",type="integer", nullable=TRUE)
     */
    protected $customerId = null;

    /**
     * @ORM\ManyToOne(targetEntity="Bank", inversedBy="payments")
     */
    protected $bank;

    public function getId()
    {
        return $this->id;
    }

    public function getReference()
    {
        return $this->reference;
    }

    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getCustomerId()
    {
        return $this->customerId;
    }

    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
    }

    public function setBank(Bank $bank)
    {
        // $bank->addPayment($this);
        $this->bank = $bank;
    }

    public function getBank()
    {
        return $this->bank;
    }
}
