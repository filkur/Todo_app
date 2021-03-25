<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserUpdateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/profile", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function update(User $user, Request $request): Response
    {
        $em = $this->getDoctrine()
                   ->getManager()
        ;

        $form = $this->createForm(UserUpdateType::class);

        $form->handleRequest($request);

        if ($form->get('Remove')
                 ->isClicked()) {
            return $this->redirect(
                $this->generateUrl(
                    'user_delete',
                    [
                        'id' => $this->getUser()
                                     ->getId(),
                    ]
                )
            );
        }
        if ($form->get('Update')
                 ->isClicked()) {
            $userToUpdate = $form->getData();

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
