<?php 

namespace App\Controller\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     */
    public function loginForm(Request $request)
    {
        $builder = $this->createFormBuilder();

        $builder->add('username', EmailType::class, [
            'label' => 'Identifiant',
            'attr' => [
                'placeholder' => 'Identifiant de co'
            ]
        ])->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => [
                    'placeholder' => "Mot de passe de co"
                ]

        ]);

        $form = $builder->getForm();

        return $this->render('security/login.html.twig', [
            'loginForm' => $form->createView(),
            'error' => $request->attributes->get('login.error')
        ]);      
        
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
    }
}