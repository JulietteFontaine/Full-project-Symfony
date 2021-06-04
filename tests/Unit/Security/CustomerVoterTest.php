<?php

namespace APP\Tests\Unit\Security;

use App\Entity\User;
use Monolog\Test\TestCase;
use App\Security\Voter\CustomerVoter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CustomerVoterTest extends TestCase
{
    public function testItWorks()
    {
        $customerVoter = new CustomerVoter();

        $attributs = [
            'CAN_REMOVE',
            'CAN_EDIT',
            'CAN_LIST_CUSTOMERS',
            'CAN_CREATE_CUSTOMER',
            'CAN_LIST_ALL_CUSTOMERS'
        ];

        $mockTokenInterface = $this->createMock(TokenInterface::class);

        foreach ($attributs as $attribute) {
            $vote = $customerVoter->vote($mockTokenInterface, null, [$attribute]);
            $this->assertNotEquals(VoterInterface::ACCESS_ABSTAIN, $vote);
        }
    }

    /**
     * @dataProvider provideRolesAndResult
     */
    public function testPolicyWithNoSubject(string $attribute, array $roles, int $resultatAttendu)
    {
        $customerVoter = new CustomerVoter();

        $user = new User;
        $user->roles = $roles;

        $mockTokenInterface = $this->createMock(TokenInterface::class);
        // s'attend a ce que mon mock utilise une fois la methode apellée qui retourne le resultat donné
        $mockTokenInterface
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $result = $customerVoter->vote($mockTokenInterface, null, [$attribute]);

        $this->assertEquals($resultatAttendu, $result);
    }

    public function provideRolesAndResult()
    {
        return [
            //CAN_CREATE_CUSTOMER
            [
                'CAN_CREATE_CUSTOMER',
                ['ROLE_MODERATOR'],
                VoterInterface::ACCESS_DENIED
            ],
            [
                'CAN_CREATE_CUSTOMER',
                ['ROLE_ADMIN'],
                VoterInterface::ACCESS_GRANTED
            ],
            [
                'CAN_CREATE_CUSTOMER',
                [],
                VoterInterface::ACCESS_GRANTED
            ],

            //CAN_LIST_CUSTOMERS
            [
                'CAN_LIST_CUSTOMERS',
                ['ROLE_MODERATOR'],
                VoterInterface::ACCESS_GRANTED
            ],
            [
                'CAN_LIST_CUSTOMERS',
                ['ROLE_ADMIN'],
                VoterInterface::ACCESS_GRANTED
            ],
            [
                'CAN_LIST_CUSTOMERS',
                [],
                VoterInterface::ACCESS_GRANTED
            ],

            //CAN_LIST_ALL_CUSTOMERS
            [
                'CAN_LIST_ALL_CUSTOMERS',
                ['ROLE_MODERATOR'],
                VoterInterface::ACCESS_GRANTED
            ],
            [
                'CAN_LIST_ALL_CUSTOMERS',
                ['ROLE_ADMIN'],
                VoterInterface::ACCESS_GRANTED
            ],
            [
                'CAN_LIST_ALL_CUSTOMERS',
                [],
                VoterInterface::ACCESS_DENIED
            ],

        ];
    }
}
