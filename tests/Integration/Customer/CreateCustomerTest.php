<?php

namespace App\Tests\Integration\Customer;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use App\Repository\UserRepository;
use App\Repository\CustomerRepository;
use App\Tests\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateCustomerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = static::$container->get(UserRepository::class);
    }

    public function testAnUnauthenticatedUserCanNotCreateACustomer()
    {
        // SETUP / Given
        // Si j'appel /customers/create sans être connecté

        $this->client->request('GET', '/customers/create');

        // Alors je suis redirigé sur /login

        // $this->assertResponseStatusCodeSame(302);
        // $this->assertResponseHeaderSame('Location', '/login');
        $this->assertResponseRedirects('/login');
    }

    public function testAModeratorCanNotCreateCustomer()
    {
        // 2. Connexion en tant qu'un utilisateur qui a le rôle modérator
        // Créer un user en bdd test qui soit modérator
        $user = UserFactory::createOne([
            'roles' => ['ROLE_MODERATOR']
        ]);
        
        // $em = static::$container->get(EntityManagerInterface::class);

        // $em->persist($user);
        // $em->flush();

        $this->client->loginUser($user->object());

        // 3. Accès à la page /customers/create
        $this->client->request('GET', '/customers/create');

        // 4. Assurons nous qu'on nous a foutu à la porte
        $this->assertResponseStatusCodeSame(403);
    }

    public function testAnAuthenticatedUserCanCreateACustomer()
    {
        // Given I am an authenticated user
        $user = new User;

        $user->firstName = 'MOCK_FIRSTNAME';
        $user->lastName = 'MOCK_LASTNAME';
        $user->email = 'email@mail.com';
        $user->password = 'password';
        $user->roles = [];

        $em = static::$container->get(EntityManagerInterface::class);

        $em->persist($user);
        $em->flush();

        $this->client->loginUser($user);

        // When I call /customers/create
        $crawler = $this->client->request('GET', '/customers/create');

        // Then the response should be OK (200)
        // $this->assertResponseStatusCodeSame(200);
        $this->assertResponseIsSuccessful();

        // And we should see a form
        // $this->assertEquals(1, $crawler->filter('form')->count());
        $this->assertSelectorExists('form');

        $this->client->submitForm('Enregistrer', [
            'customer[firstName]' => 'Jérome',
            'customer[lastName]' => 'Dupont',
            'customer[email]' => 'jdupont@mail.com'
        ]);

        // Le customer devrait désormais exister dans la BDD
        /**
         * @var CustomerRepository
         */
        $repository =  static::$container->get(CustomerRepository::class);

        $customer = $repository->findOneBy([
            'firstName' => 'Jérome',
            'lastName' => 'Dupont',
            'email' => 'jdupont@mail.com'
        ]);

        $this->assertNotNull($customer);

        // Je devrais être redirigé vers /customers
        $this->assertResponseRedirects('/customers');
    }
}