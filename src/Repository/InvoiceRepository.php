<?php


namespace App\Repository;


use App\Entity\Invoice;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class InvoiceRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoice::class);
    }

    public function findLastMonthSales(User $user) {
        // SELECT SUM(i.amount) AS total, YEAR(i.created_at) AS annee, MONTH(i.created_at) AS mois FROM invoice AS i GROUP BY annee, mois ORDER BY annee DESC, mois DESC LIMIT 5;

        return $this->createQueryBuilder('i')
        ->select('SUM(i.amount) AS total, YEAR(i.createdAt) AS annee, MONTH(i.createdAt) AS mois')
        ->groupBy('annee, mois')
        ->orderBy('annee', 'DESC')
        ->addOrderBy('mois', 'DESC')
        ->setMaxResults(5)
        ->getQuery()
        ->getResult();
    }

    public function findInvoicesForUser(
        User $user,
        int $maxResults = null,
        string $order = null,
        string $direction = "ASC")
    {

        $qb = $this->createQueryBuilder('i')
            ->join('i.customer', 'c')
            ->where('c.user = :user')
            ->setParameter(':user', $user);

        if ($maxResults){
            $qb->setMaxResults($maxResults);
        }

        if($order){
            $qb->orderBy("i." . $order, $direction);
        }

        return $qb->getQuery()
            ->getResult();
    }

}