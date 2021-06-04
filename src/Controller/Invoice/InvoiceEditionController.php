<?php

namespace App\Controller\Invoice;

use App\Entity\Invoice;
use App\Form\InvoiceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InvoiceEditionController extends AbstractController
{

    /**
     * @Route("invoice/{id}/edit", name="invoice_edit")
     */
    public function invoiceEdit(Request $request, Invoice $invoice, EntityManagerInterface $em)
    {

        $form = $this->createForm(InvoiceType::class, $invoice);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            return $this->redirectToRoute('invoice_list');
        }

        return $this->render("invoices/edit.html.twig", [
            'form' => $form->createView()
        ]);
    }
}
