<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {  
        $form = $this->createFormBuilder()
           ->add('username')
           ->add('password', RepeatedType::class, array(
            'type' => PasswordType::class,
            'first_options'  => array('label' => 'Password'),
            'second_options' => array('label' => 'Repeat Password')
           ))

           ->add('register', SubmitType::class)

           ->getForm();

       $form->handleRequest($request);

       if($form->isSubmitted()) {
          $data = $form->getData();
          
          
          $user = new User();
          $user->setUsername($data['username']);
          $user->setPassword(
                   $passwordEncoder->encodePassword($user, $data['password'])
          );
          
           //dd($data);

        $em = $this->getDoctrine()->getManager();

        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('app_login'));
       }
           

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
