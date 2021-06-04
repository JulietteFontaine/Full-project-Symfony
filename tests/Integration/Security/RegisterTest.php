<?php

namespace APP\Integration\Security;

use App\Entity\User;
use App\Tests\Factory\UserFactory;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterTest extends WebTestCase
{
    public function testAUserCanRegister()
    {
        $client = static::createClient();

        // Quand je me rend sur /register
        $client->request('GET', '/register');

        // Alors la réponse est ok (200)
        $this->assertResponseIsSuccessful();

        // Et je vois un <form>
        $this->assertSelectorExists('form');

        // Quand je soumet le formulaire
        $client->submitForm('Confirmer', [
            'register[firstName]' => 'MOCK_FIRSTNAME',
            'register[lastName]' => 'MOCK_LASTNAME',
            'register[email]' => 'MOCK_EMAIL@mail.com',
            'register[password]' => 'password'
        ]);

        // Alors je suis redirigé vers /login
        $this->assertResponseRedirects('/login');

        // Et mes infos sont dans la BDD
        $user = UserFactory::findBy(['email' => 'MOCK_EMAIL@mail.com'])[0] ?? null;

        $this->assertNotNull($user);
        $this->assertEquals('MOCK_FIRSTNAME', $user->firstName);

        // Et le mdp est hashé
        $this->assertNotEquals('password', $user->password);
    }

    public function testARegisteredUserCanLogin()
    {
        $client = static::createClient();

        $user = UserFactory::createOne([
            'email' => 'test@mail.com',
            'password' => 'password'
        ]);

        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');

        $client->submitForm('Connexion', [
            'form[username]' => 'test@mail.com',
            'form[password]' => 'password',
        ]);

        $this->assertResponseRedirects('/customers');

        $security = static::$container->get(Security::class);

        $this->assertTrue($security->isGranted('ROLE_USER'));

        $loggedUser = $security->getUser();
        $this->assertEquals($user->email, $loggedUser->email);
    }

    public function testItWillNotLoginBadCredetials()
    {
        $client = static::createClient();

        // Quand je me rend sur /login et la requete est ok
        $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();

        // Il y a un <form> qui s'affiche
        $this->assertSelectorExists('form');

        // Je soumet le formulaire avec des infos non existante en BDD
        $client->submitForm('Connexion', [
            'form[username]' => 'fake@mail.com',
            'form[password]' => 'fakepassword',
        ]);

        // Security bloque la connexion et n'accepte pas le user
        $security = static::$container->get(Security::class);
        $this->assertFalse($security->isGranted('ROLE_USER'));

        // On retourne sur la même page, pas de redirection
        $this->assertResponseStatusCodeSame(200);

    }
}
