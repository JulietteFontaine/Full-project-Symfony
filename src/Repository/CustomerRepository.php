<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

//Customer::class === "App\Entity\Customer"

class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Customer::class);
    }

    public function findBestCustomers(User $user)
    {
        // SELECT c.first_name, c.last_name, SUM(i.amount) AS total FROM customer AS c JOIN invoice AS i  ON c.id = i.customer_id GROUP BY c.id ORDER BY total DESC LIMIT 3;
        return $this->createQueryBuilder('c')
            ->select('c.firstName, c.lastName, SUM(i.amount) AS total')
            ->join('c.invoices', 'i')
            ->where('c.user = :user')
            ->setParameter(':user', $user)
            ->groupBy('c.id')
            ->orderBy('total', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }
}