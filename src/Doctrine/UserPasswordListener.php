<?php

namespace App\Doctrine;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserPasswordListener
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoderInterface) {

        $this->encoder = $userPasswordEncoderInterface;
    }

    public function prePersist(User $entity)
    {

        $hash = $this->encoder->encodePassword($entity, $entity->password);

        $entity->password = $hash;

        // dd($event);
    }
}