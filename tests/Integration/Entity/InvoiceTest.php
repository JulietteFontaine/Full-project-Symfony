<?php

namespace App\Tests\Integration\Entity;

use DateTime;
use App\Entity\Invoice;
use App\Tests\Factory\CustomerFactory;
use App\Tests\Factory\InvoiceFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class InvoiceTest extends KernelTestCase
{
    public function testWeCanPersistAndFlushAnInvoice()
    {
        static::bootKernel();

        // GIVEN we have an EntityManager
        $em = static::$container->get(EntityManagerInterface::class);

        // AND there is a Customer
        $customer = CustomerFactory::createOne();

        // WHEN i persiste and flush the new Invoice
        $invoice = InvoiceFactory::createOne([
            'customer' => $customer
        ]);
        
        // THEN the invoice should exist in the database
        $this->assertNotNull($invoice->id);
    }

    public function testWeCanAccessACustomersInvoices()
    {
        static::bootKernel();

        // Given there is a customer
        $customer = CustomerFactory::createOne();

        // And there are 5 invoices linked to this customer
        $invoices = InvoiceFactory::createMany(5, [
            'customer' => $customer
        ]);

        // When I ask for customer's invoices

        //Then we should find 5 invoices inside the customer
        $this->assertCount(5, $customer->invoices);
    }
}
