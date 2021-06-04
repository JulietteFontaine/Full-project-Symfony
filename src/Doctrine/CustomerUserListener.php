<?php

namespace App\Doctrine;

use App\Entity\Customer;
use Symfony\Component\Security\Core\Security;

class CustomerUserListener
{
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function PrePersist(Customer $customer)
    {
        if (!empty($customer->user)) {
            return;
        }

        $customer->user = $this->security->getUser();
    }
}
