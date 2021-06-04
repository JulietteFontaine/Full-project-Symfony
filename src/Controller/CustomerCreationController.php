<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CustomerCreationController extends AbstractController
{
    private $em;
    // private $validator;
    private $formFactory;
    private $security;

    public function __construct(
        EntityManagerInterface $em,
        // ValidatorInterface $validatorInterface,
        FormFactoryInterface $formFactoryInterface
    ) {
        $this->em = $em;
        // $this->validator = $validatorInterface;
        $this->formFactory = $formFactoryInterface;
    }

    /**
     * @Route("/customers/create", name="customers_create")
     * @IsGranted("CAN_CREATE_CUSTOMER")
     * @return Response 
     */
    public function displayForm(Request $request, FlashBagInterface $flashBag): Response
    {
        // $form = $this->formFacotry->createdNamed('', CustomerType::class)
        // $form = $this->formFactory->create(CustomerType::class);

        // if(!$this->security->isGranted('ROLE_USER')) {
        //     throw new AccessDeniedException("T'es pas la");
        // };

        $form = $this->createForm(CustomerType::class)->handleRequest($request);

        // $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customer = $form->getData();

            $customer->user = $this->getUser();

            $this->em->persist($customer);
            $this->em->flush();

            //flash-message : erreurs dans la session
            $flashBag->add('success', 'Le client a bien été enregistré');
            $flashBag->add('danger', 'Le client est moche');
            $flashBag->add('success', 'Bravo tout s\'est bien passé');

            //utilise l'url generator interface
            return $this->redirectToRoute("customers_list");
        }

        return $this->render('customers/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

//     /**
//      * @Route("/customers/store", name="customers_store", methods={"POST"})
//      * @return Response 
//      */
//     public function store(Request $request): Response
//     {
//         $form = $this->formFactory->createNamedform('', FormType::class, null, [
//             'data_class' => Customer::class
//         ]);

//         $form
//             ->add('firstName')
//             ->add('lastName')
//             ->add('email');

//         $form = $form->getForm();

//         $form->handleRequest($request);

//         if (!$form->isSubmitted()) {
//             throw new UnauthorizedHttpException("Vous n'avez pas rempli le formulaire");
//         }

//         if (!$form->isValid()) {
//             $errors = $form->getErrors(true);

//             $errorMessages = [];

//             foreach ($errors as $violation) {
//                 $champ = (string) $violation->getOrigin()->getPropertyPath();

//                 $errorMessages[$champ] = $violation->getMessage();
//             }

//             $request->getSession()->set('form.errors', $errorMessages);

//             return $this->redirectToRoute("customers_create");
//         }

//         $customer = $form->getData();

//         $this->em->persist($customer);
//         $this->em->flush();

//         return $this->redirectToRoute("customers_list");
//     }
// }

        // $data = [
        //     'firstName' => $firstName,
        //     'lastName' => $lastName,
        //     'email' => $email
        // ];

        // $constraint = new Collection([
        //     'firstName' => [
        //         new NotBlank([
        //             "message" => "Le prénom est obligatoire"
        //         ]),
        //         new Length([
        //             'min' => 3,
        //             'minMessage' => "Le prénom doit faire 3 carr."
        //         ]),
        //         ],
        //     'lastName' => [
        //         new NotBlank([
        //             "message" => "Le nom est obligatoire"
        //         ]),
        //         new Length([
        //             'min' => 5,
        //             'minMessage' => "Le nom doit faire 5 carr."
        //         ]),
        //         ],
        //     'email' => [
        //         new NotBlank(['message'=> "L'adresse email est obligatoire"]),
        //         new Email(['message' => "L'email resseigné n'est pas valide"]),
        //     ]
        // ]);


        // if($errors->count() > 0) {
        // };
        // $this->model->save($firstName, $lastName, $email);


