<?php

namespace App\Tets\Integration\Customer;

use App\Tests\Factory\UserFactory;
use App\Tests\Factory\CustomerFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EditCustomerTest extends WebTestCase
{

    public function testAnUnauthenticatedUserCanNotAccessForm()
    {
        $this->client = static::createClient();

        // GIVEN there is a customer
        $customer = CustomerFactory::createOne();

        // WHEN i navigate to /customer/id/edit
        $this->client->request('GET', '/customers/' . $customer->id . '/edit');

        // THEN i should be redirected to /login
        $this->assertResponseRedirects('/login');
    }

    public function testAModeratorCanNotAccessForm()
    {
        $client = static::createClient();

        // GIVEN there is a customer and i am authanticated as moderator
        $customer = CustomerFactory::createOne();
        $user = UserFactory::createOne([
            'roles' => ['ROLE_MODERATOR']
        ]);

        $client->loginUser($user->object());

        // WHEN i navigate to /customer/id/edit
        $client->request('GET', '/customers/' . $customer->id . '/edit');

        // THEN i should recieved a 403
        $this->assertResponseStatusCodeSame(403);
    }

    public function testAUserCanNotEditACustomerFromAnOtherUser()
    {
        $client = static::createClient();

        // GIVEN there is a customer and i am authanticated with another user
        $customer = CustomerFactory::createOne();
        $user = UserFactory::createOne();

        $client->loginUser($user->object());

        // WHEN i navigate to /customer/id/edit
        $client->request('GET', '/customers/' . $customer->id . '/edit');

        // THEN i should recieved a 403
        $this->assertResponseStatusCodeSame(403);
    }

    public function testAUserCanEditACustomer()
    {
        $client = static::createClient();

        // GIVEN there is a customer and i am authanticated
        $user = UserFactory::createOne();
        $customer = CustomerFactory::createOne([
            'user' => $user
        ]);

        $client->loginUser($user->object());

        // WHEN i navigate to /customer/id/edit
        $client->request('GET', '/customers/' . $customer->id . '/edit');

        // THEN the response should be successfull
        // and the form should be a <form> element
        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('form');
    }

    public function testAnAdminCanEditCustomer()
    {
        $client = static::createClient();

        // GIVEN there is a customer 
        // and i am authanticated as moderator
        $customer = CustomerFactory::createOne();
        $user = UserFactory::createOne([
            'roles' => ['ROLE_ADMIN']
        ]);

        $client->loginUser($user->object());

        // WHEN i navigate to /customer/id/edit
        $client->request('GET', '/customers/' . $customer->id . '/edit');

        // THEN the response should be successfull
        // and the form should be a <form> element
        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('form');

        // WHEN i submit the form
        $client->submitForm('Enregistrer', [
            'customer[firstName]' => 'MOCK_FIRSTNAME'
        ]);
        // THEN the customer should be edited
        $updatedCustomer = CustomerFactory::find($customer->id);
        $this->assertEquals('MOCK_FIRSTNAME', $updatedCustomer->firstName);

        // and I am redirected to /customers
        $this->assertResponseRedirects('/customers');
         
    }
}
