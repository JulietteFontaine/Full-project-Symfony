<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CustomerEditionController extends AbstractController
{
    private $repository;
    private $em;

    public function __construct(
        CustomerRepository $customerRepository,
        EntityManagerInterface $em)
    {
        $this->repository = $customerRepository;
        $this->em = $em;
    }

    /**
     * @Route("customers/{id}/edit", name="customers_edit")
     * @IsGranted("CAN_EDIT", subject="customer")
     */
    public function edit(Request $request, Customer $customer)
    {
        // Plus besoin grace au Listener intégré --->

            // // Récuperer l'id (Request)
            // $id = $request->attributes->get('id');

            // // Chercher le customer dans la base (CustomerRepository)
            // $customer = $this->repository->find($id);

            // // S'il n'existe pas, Not Found
            // if (!$customer) {
            //     throw new NotFoundHttpException("Le client n°$id n'existe pas");
            // }

        // Créer un formulaire (FormFactory ou $this->createForm)
        $form = $this->createForm(CustomerType::class, $customer);

        //Gérer la soumission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        // Envoyer la requete (EntityManagerInterface)
            $this->em->flush();

        // Rediriger vers la liste des customers (RouterInterface, UrlGeneratorInterface)
            return $this->redirectToRoute('customers_list');
        }

        // Afficher un fichier twig (Environment ou $this->render)
        return $this->render("customers/edit.html.twig", [
            'form' => $form->createView()
        ]);
    }
}
