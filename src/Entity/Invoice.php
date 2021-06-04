<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Invoice
{

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    public $id;

    /**
     * @ORM\Column(type="integer")
     */
    public $amount;

    /**
     * @ORM\Column(type="datetime")
     */
    public $createdAt;

    /**
     * @ORM\ManytoOne(targetEntity="App\Entity\Customer", inversedBy="invoices")
     */
    public $customer;

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        if ($this->createdAt) {
            return;
        }

        $this->createdAt = new DateTime();
    }
}
