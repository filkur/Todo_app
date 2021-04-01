<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserUpdateType;
use App\Services\UserUpdate\Auth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

/**
 * @Route ("/profile", name="user_")
 */
class UserController extends AbstractController
{
    private $encoderFactory;
    public function __construct( EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function update(User $user, Request $request, Security $security): Response
    {
        $em = $this->getDoctrine()
                   ->getManager()
        ;

        $form = $this->createForm(UserUpdateType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userToUpdate = $form->getData();

            $encoder = $this->encoderFactory->getEncoder($security->getUser());


           $encodedPassword = $encoder->encodePassword($userToUpdate->getPassword(), $security->getUser()->getSalt());
           $encodedPassword = implode((array)$encodedPassword);
            echo $encodedPassword;
            echo '</br>';
            echo $user->getPassword();
            //if ($this->passwordEncoder->encodePassword($user ,$password) == $user->getPassword())
            if ($encoder->isPasswordValid($security->getUser()->getPassword(), $user->getPassword(), $security->getUser()->getSalt()))
            {
                $user->setUsername($userToUpdate->getUsername());
                $user->setEmail($userToUpdate->getEmail());

                $em->flush();
                $this->addFlash('success', 'Profile updated!');

                return $this->redirect(
                    $this->generateUrl(
                        'category_index'
                    )
                );
            }

        }

        return $this->render(
            'user/edit.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route ("/delete/{id}", name="delete")
     */
    public function remove(User $user): Response
    {
        $em = $this->getDoctrine()
                   ->getManager()
        ;
        $em->remove($user);
        $em->flush();
        $this->addFlash(
            'success',
            'Profile removed. We will miss you...'
        );

        return $this->redirect(
            $this->generateUrl(
                'app_logout'
            )
        );
    }
}
