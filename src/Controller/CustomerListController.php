<?php


namespace App\Controller;


use App\Repository\CustomerRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerListController extends AbstractController {

    private $repository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->repository = $customerRepository;

    }

    /**
     * @Route("/customers", name="customers_list")
     * @IsGranted("CAN_LIST_CUSTOMERS")
     * @return Response
     */
    public function list(): Response
    {

        if($this->isGranted('CAN_LIST_ALL_CUSTOMERS'))
        {
            $customers = $this->repository->findAll();
        }
        else
        {
            $customers = $this->repository->findBy(['user' => $this->getUser()]);
        }

        return $this->render("customers/list.html.twig", [
            'customers' => $customers
        ]);
    }

}