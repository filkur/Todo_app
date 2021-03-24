<?php

namespace App\Controller;

use App\Entity\User;
use Cassandra\Type\UserType;
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

        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $em->flush();
            $this->addFlash('success', 'Profile updated!');

            return $this->redirect(
                $this->generateUrl(
                    'category_index'
                )
            );
        }

        return $this->render(
            'user/index.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
