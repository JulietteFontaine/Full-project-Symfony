<?php


namespace App\Controller;


use App\Repository\CustomerRepository;
use App\Repository\InvoiceRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{

    /**
     * @Route("/", name="dashboard")
     * @IsGranted("ROLE_USER")
     */
    public function dashboard(InvoiceRepository $invoiceRepository, CustomerRepository $customerRepository){

        $lastInvoices = $invoiceRepository->findInvoicesForUser($this->getUser(), 5, "createdAt", "DESC");

        $bestCustomers = $customerRepository->findBestCustomers($this->getUser());

        $lastSales = $invoiceRepository->findLastMonthSales($this->getUser());

        return $this->render('dashboard/index.html.twig', [
            'lastInvoices' => $lastInvoices,
            'bestCustomers' => $bestCustomers,
            'lastSales' => $lastSales 
        ]);
    }

}