<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface
{

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $password;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Customer", mappedBy="user")
     */
    public $customers;

    /**
     * @ORM\Column(type="array")
     */
    public $roles = [];

    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist()
     */
    public function initializeCreatedAt()
    {
        if (empty($this->createdAt)) {
            $this->createdAt = new DateTime();
        }
    }

    public function getFullName()
    {
        return "{$this->firstName} {$this->lastName}";
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        return array_merge(['ROLE_USER'], $this->roles);
    }

    public function getSalt()
    {

    }

    public function eraseCredentials()
    {

    }

}