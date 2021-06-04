<?php

namespace App\Controller\Invoice;

use App\Entity\Invoice;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InvoiceDeleteController extends AbstractController
{

    /**
     * @Route("invoice/{id}/delete", name="invoice_delete")
     */
    public function invoiceDelete(Request $request, Invoice $invoice, EntityManagerInterface $em)
    {
        $em->remove($invoice);
        $em->flush();

        return $this->redirectToRoute('invoice_list');
    }
}
