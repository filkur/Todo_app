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
        if ($form->isSubmitted() && $form->isValid()) {
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
}
