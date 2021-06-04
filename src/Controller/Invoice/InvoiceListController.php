<?php

namespace App\Controller\Invoice;

use App\Repository\InvoiceRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InvoiceListController extends AbstractController
{


    /**
     * @Route("/invoices", name="invoice_list")
     * @return Response
     */
    public function invoicesList(InvoiceRepository $invoiceRepository): Response
    {

        // $filterDate = new DateTime();
        // if($filterDate) {
        //     $qb->andWhere('i.createdAt = :date')
        //     ->setParameter(':date', $filterDate);
        // }
        // if ($filterDate) {
        //     $where = ' AND i.createdAt = :date ';
        // } else {
        //     $where = '';
        // }
        // // requete 'template' pour aller chercher dans la BDD, puis set les parametres voulu
        // $query = $em->createQuery('
        // SELECT i FROM App\Entity\Invoice AS i
        // JOIN i.customer AS c
        // WHERE c.user = :user
        // ' . $where)
        //     ->setParameter(':user', $this->getUser());

        // if ($filterDate) {
        //     $query->setParameter(':date', $filterDate);
        // }

        $invoices = $invoiceRepository->findInvoicesForUser($this->getUser());

        return $this->render("invoices/list.html.twig", [
            'invoices' => $invoices
        ]);
    }
}
