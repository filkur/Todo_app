<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserUpdateType;
use App\Services\Category\UserCategories;
use App\Services\File\FileUploader;
use App\Services\UserUpdate\Auth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->passwordEncoder = $userPasswordEncoder;
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function update(
        User $user,
        Request $request,
        UserCategories $userCategories,
        FileUploader $fileUploader
    ): Response {
        $categories = $userCategories->getCategories();

        $em = $this->getDoctrine()
                   ->getManager()
        ;

        $formUsername = $this->createFormBuilder($user)
                             ->add(
                                 'username',
                                 TextType::class,
                                 [
                                     'label' => 'Username',
                                     'attr'  => [
                                         'placeholder' => $user->getUsername(),
                                     ],
                                 ]
                             )
                             ->add(
                                 'Update',
                                 SubmitType::class,
                                 [
                                     'label' => 'Update',
                                     'attr'  => [
                                         'class' => 'btn btn-warning',
                                     ],
                                 ]
                             )
                             ->getForm()
        ;
        $formEmail = $this->createFormBuilder($user)
                          ->add(
                              'email',
                              EmailType::class,
                              [
                                  'label' => 'Email',
                                  'attr'  => [
                                      'placeholder' => $user->getEmail(),
                                  ],
                              ]
                          )
                          ->add(
                              'Update',
                              SubmitType::class,
                              [
                                  'label' => 'Update',
                                  'attr'  => [
                                      'class' => 'btn btn-warning',
                                  ],
                              ]
                          )
                          ->getForm()
        ;
        $formImage = $this->createFormBuilder($user)
                             ->add(
                                 'image',
                                 FileType::class,
                                 [
                                     'mapped' => false,
                                 ]
                             )
                             ->add(
                                 'Update',
                                 SubmitType::class,
                                 [
                                     'label' => 'Update',
                                     'attr'  => [
                                         'class' => 'btn btn-warning',
                                     ],
                                 ]
                             )
                             ->getForm()
        ;

        /*
        $form = $this->createForm(UserUpdateType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userToUpdate = $form->getData();

            if ($this->passwordEncoder->isPasswordValid($user, $userToUpdate->getPassword())) {
                $user->setUsername($userToUpdate->getUsername());
                $user->setEmail($userToUpdate->getEmail());

                $file = $form->get('image')
                             ->getData()
                ;
                $filename = $fileUploader->uploadFile($file);

                $user->setImage($filename);

                $em->flush();
                $this->addFlash('success', 'Profile updated!');

                return $this->redirect(
                    $this->generateUrl(
                        'category_index'
                    )
                );
            } else {
                $this->addFlash('error', 'Wrong Password!');
            }
        }
        */
        //byq uzyj switcha

        $formUsername->handleRequest($request);
        if ($formUsername->isSubmitted() && $formUsername ->isValid()){
            $fieldToUpdate = $formUsername->getData();
            $user->setUsername($fieldToUpdate->getUsername());
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
                //'form' => $form->createView(),
                'formUsername' => $formUsername->createView(),
                'formEmail' => $formEmail->createView(),
                'formImage' => $formImage->createView(),
                'categories'   => $categories,
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
