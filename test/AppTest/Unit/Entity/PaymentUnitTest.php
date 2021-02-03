<?php

namespace AppTest\Unit\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Payment;
use App\Entity\Bank;
use Helmich\JsonAssert\JsonAssertions;
use App\Exception\JsonEncodingException;

class PaymentUnitTest extends TestCase
{
    use JsonAssertions;

    public function testCanToJson()
    {
        $reference = 12345678;
        $amount = 10000;
        $description = "Test description";
        $customerId = 1;
        $bankId = 1;
        $payment = new Payment();
        $payment->setReference($reference);
        $payment->setAmount($amount);
        $payment->setDescription($description);
        $payment->setCustomerId($customerId);
        $paymentJson = $payment->toJson();

        $this->assertJsonValueEquals($paymentJson, '$.reference', $reference);
        $this->assertJsonValueEquals($paymentJson, '$.amount', $amount);
        $this->assertJsonValueEquals($paymentJson, '$.description', $description);
        $this->assertJsonValueEquals($paymentJson, '$.customerId', $customerId);
    }

    public function testAnJsonEncodingExceptionThrown()
    {
        $this->expectException(JsonEncodingException::class);
        try {
            $badDescription = "\xB1\x31";
            $reference = 12345678;
            $amount = 10000;
            $customerId = 1;
            $bankId = 1;
            $payment = new Payment();
            $payment->setReference($reference);
            $payment->setAmount($amount);
            $payment->setDescription($badDescription);
            $payment->setCustomerId($customerId);
            $paymentJson = $payment->toJson();
        } catch (JsonEncodingException $exception) {
            $this->assertStringContainsString(
                'Malformed UTF-8 characters, possibly incorrectly encoded',
                $exception->getMessage()
            );
            throw $exception;
        }
    }

    public function testCanToArray()
    {
        $reference = 12345678;
        $amount = 10000;
        $description = "Test description";
        $customerId = 1;
        $bankId = 1;
        $payment = new Payment();
        $payment->setReference($reference);
        $payment->setAmount($amount);
        $payment->setDescription($description);
        $payment->setCustomerId($customerId);
        $bank = new Bank();
        $bank->setName('bbva');
        $bank->setDescription('banco bb');
        $payment->setBank($bank);
        $paymentArray = $payment->toArray();

        $this->assertSame($reference, $paymentArray['reference']);
        $this->assertSame($amount, $paymentArray['amount']);
        $this->assertSame($description, $paymentArray['description']);
        $this->assertSame($customerId, $paymentArray['customerId']);
    }

    public function testCanGetReference()
    {
      $reference = 9876543;
      $payment = $this->newPayment([
          'reference' => $reference,
      ]);

        $this->assertSame($reference, $payment->getReference());
    }

    public function testCanSetReference()
    {
        $payment = $this->newPayment();
        $newReference = 9876543;
        $payment->setReference($newReference);

        $this->assertSame($newReference, $payment->getReference());
    }

    public function testCanGetAmount()
    {
      $amount = 2000;
      $payment = $this->newPayment([
          'amount' => $amount,
      ]);

        $this->assertSame($amount, $payment->getAmount());
    }

    public function testCanSetAmount()
    {
        $payment = $this->newPayment();
        $newAmount = 2000;
        $payment->setAmount($newAmount);

        $this->assertSame($newAmount, $payment->getAmount());
    }

    public function testCanGetDescription()
    {
      $description = 'New description';
      $payment = $this->newPayment([
          'description' => $description,
      ]);

        $this->assertSame($description, $payment->getDescription());
    }

    public function testCanSetDescription()
    {
        $payment = $this->newPayment();
        $newDescription = 'New description';
        $payment->setDescription($newDescription);

        $this->assertSame($newDescription, $payment->getDescription());
    }

    public function testCanGetCustomerId()
    {
      $customerId = 2;
      $payment = $this->newPayment([
          'customerId' => $customerId,
      ]);

        $this->assertSame($customerId, $payment->getCustomerId());
    }

    public function testCanSetCustomerId()
    {
        $payment = $this->newPayment();
        $newCustomerId = 2;
        $payment->setCustomerId($newCustomerId);

        $this->assertSame($newCustomerId, $payment->getCustomerId());
    }

    public function testCanGetId()
    {
        $payment = $this->newPayment();

        $this->assertSame(null, $payment->getId());
    }

    protected function newPayment($attributes = [])
    {
      $data = array_merge([
        'reference' => 12345678,
        'amount' => 10000,
        'description' => "Test description",
        'customerId' => 1,
      ], $attributes);
      $payment = new Payment();
      $payment->setReference($data['reference']);
      $payment->setAmount($data['amount']);
      $payment->setDescription($data['description']);
      $payment->setCustomerId($data['customerId']);
      return $payment;
    }
}
