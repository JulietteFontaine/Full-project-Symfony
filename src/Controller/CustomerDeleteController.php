<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CustomerDeleteController extends AbstractController
{
    private $repository;
    private $em;

    public function __construct(
        CustomerRepository $customerRepository,
        EntityManagerInterface $em,
        UrlGeneratorInterface $urlGeneratorInterface
    ) {
        $this->repository = $customerRepository;
        $this->em = $em;
        $this->urlGenerator = $urlGeneratorInterface;
    }

    /**
     * @Route("/customers/{id}/delete", name="customers_delete")
     */
    public function delete(Request $request, Customer $customer)
    {

        // if(!$this->isGranted('ROLE_USER')) {
        //     throw new AccessDeniedException("T'es pas la");
        // }

        // if ($customer->user !== $this->getUser()) {
        //     throw new NotFoundHttpException();
        // }

        if (!$this->isGranted('CAN_REMOVE', $customer)) {
            throw new AccessDeniedException("C'est dead");
        }

        // Plus besoin grace au Listener intégré --->

        // // On a besoin de l'identifiant (j'ai besoin de la request)
        //     $id = $request->attributes->get('id');

        // // On vérifie le customer existant (j'ai besoin du customer CustomerRepository)
        //     $customer = $this->repository->find($id);

        // // On vérifie qu'il existe (404 - je dois créer et retourner un response)
        //     if(!$customer) {
        //         throw new NotFoundHttpException("Le client n°$id n'existe pas");
        //     }

        // On le supprime (j'ai besoin l'entity manager)
        $this->em->remove($customer);
        $this->em->flush();

        // Redirection sur la liste (je dois créer et retourner un response 302)
        // $url = $this->urlGenerator->generate('customers_list');

        // return $this->redirect($url);
        return $this->redirectToRoute('customers_list');

        // return new RedirectResponse($url);

        // return new Response('', 302, [
        //     'Location' => $url
        // ]);
    }
}
