<?php

namespace App\Controller\Invoice;


use App\Form\InvoiceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InvoiceCreationController extends AbstractController
{

    /**
     * @Route("/invoices/create", name="invoices_create")
     */
    public function create(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(InvoiceType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $invoice = $form->getData();

            $em->persist($invoice);
            $em->flush();
        }

        return $this->render('invoices/create.html.twig', [
            'customers' => $this->getUser()->customers,
            'form' => $form->createView()
        ]);
    }
}