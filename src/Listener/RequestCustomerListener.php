<?php

namespace App\Listener;

use App\Repository\CustomerRepository;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestCustomerListener
{
    private $repository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->repository = $customerRepository;
    }

    public function addCustomerToRequest(RequestEvent $event)
    {
        //si je ne suis pas sur l'édition d'un customer, je dégage
        $request = $event->getRequest();

        $route = $request->attributes->get('_route');

        if($route !== "customers_edit") {
            return;
        }

        //je veux aller chercher le customer en question dans la base de données
        //j'ai besoin de son id
        $id = $request->attributes->get('id');
        $customer = $this->repository->find($id);
        dd($customer);

        //j'ajoute mon customer dans les attribus de la request pour le demnder dans la fonction de mon controller
        $request->attributes->set('myCustomer', $customer);

    }
}
