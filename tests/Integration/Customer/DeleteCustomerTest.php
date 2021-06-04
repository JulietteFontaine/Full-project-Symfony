<?php

namespace App\Tests\Integration\Customer;

use App\Entity\User;
use App\Repository\CustomerRepository;
use App\Tests\Factory\UserFactory;
use App\Tests\Factory\CustomerFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteCustomerTest extends WebTestCase
{
    public function testAnAuthenticatedUserCanDeleteCustomer()
    {
        $client = static::createClient();

        $user = UserFactory::createOne();

        $client->loginUser($user->object());

        $customer = CustomerFactory::createOne([
            'user' => $user
        ])->object();

        $client->request('GET', '/customers/' . $customer->id . '/delete');

        $this->assertResponseRedirects('/customers');

        $deletedCustomer = CustomerFactory::findBy([
            'email' => $customer->email,
            'firstName' => $customer->firstName,
            'lastName' => $customer->lastName
        ])[0] ?? null;

        $this->assertNull($deletedCustomer);
    }


    /**
     * @dataProvider provideRoles
     */
    public function testAModeratorOrAdminCanDeleteAnyCustomer(string $role)
    {
        $client = static::createClient();

        $user = UserFactory::createOne([
            'roles' => [$role]
        ]);

        $client->loginUser($user->object());

        $customer = CustomerFactory::createOne()->object();

        $client->request('GET', '/customers/' . $customer->id . '/delete');

        $this->assertResponseRedirects('/customers');

        $deletedCustomer = CustomerFactory::findBy([
            'email' => $customer->email,
            'firstName' => $customer->firstName,
            'lastName' => $customer->lastName
        ])[0] ?? null;

        $this->assertNull($deletedCustomer);
    }

    public function provideRoles() {
        return [
                ['ROLE_ADMIN'],
                ['ROLE_MODERATOR'],
        ];
    }
}
