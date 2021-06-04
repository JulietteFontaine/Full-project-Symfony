<?php

namespace App\Tests\Integration\Entity;

use App\Tests\Factory\CustomerFactory;
use App\Tests\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateInvoiceTest extends WebTestCase
{
    public function testAnAuthenticatedUserCanCreateAnInvoice()
    {
        $client = static::createClient();

        // Given I am authenticated as a normal user
        $user = UserFactory::createOne();

        $client->loginUser($user->object());

        // And there are a random number of customers linked to the user
        $count = mt_rand(5, 10);
        $customers = CustomerFactory::createMany($count, [
            'user' => $user
        ]);

        CustomerFactory::createMany(10);

        // When I navigate to /invoices/create
        $crawler = $client->request('GET', '/invoices/create');

        // Then the response is successfull
        $this->assertResponseIsSuccessful();

        // And I see a from
        $this->assertSelectorExists('form');

        // And I see a have a <select with 5 options
        $this->assertSelectorExists('select');

        $this->assertEquals($count, $crawler->filter('select option')->count());

        // When I submit the form
        $client->submitForm('Enregistrer', [
            'invoice[amount]' => 299.99,
            'invoice[customer]' => $customers[3]->id
        ]);

        // Then the invoice should be found in the database
        $this->assertCount(1, $customers[3]->invoices);

        $invoice = $customers[3]->invoices[0];

        $this->assertEquals(29999, $invoice->amount);
        $this->assertNotNull($invoice->createdAt);
    }
}
