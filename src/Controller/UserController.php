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
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
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

        $formUsername = $this->get("form.factory")
                             ->createNamedBuilder('formUsername')
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
                                 'password',
                                 PasswordType::class
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
        $formEmail = $this->get("form.factory")
                          ->createNamedBuilder('formEmail')
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
                              'password',
                              PasswordType::class
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
        $formImage = $this->get("form.factory")
                          ->createNamedBuilder('formImage')
                          ->add(
                              'image',
                              FileType::class,
                              [
                                  'mapped' => false,
                              ]
                          )
                          ->add(
                              'password',
                              PasswordType::class
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

        if ($request->request->has('formUsername')) {
            $formUsername->handleRequest($request);
            if ($formUsername->isSubmitted() && $formUsername->isValid()) {
                $fieldToUpdate = $formUsername->getData();
                if ($this->passwordEncoder->isPasswordValid($user, $fieldToUpdate['password'])) {
                    $user->setUsername($fieldToUpdate['username']);
                    $em->flush();
                    $this->addFlash('success', 'Profile updated!');

                    return $this->redirect(
                        $this->generateUrl(
                            'category_index'
                        )
                    );
                }
                else $this->addFlash('warning', 'Wrong password!');
            }
        }
        if ($request->request->has('formEmail')) {
            $formEmail->handleRequest($request);
            if ($formEmail->isSubmitted() && $formEmail->isValid()) {
                $fieldToUpdate = $formEmail->getData();
                if ($this->passwordEncoder->isPasswordValid($user, $fieldToUpdate['password'])) {
                    $user->setEmail($fieldToUpdate['email']);
                    $em->flush();
                    $this->addFlash('success', 'Profile updated!');

                    return $this->redirect(
                        $this->generateUrl(
                            'category_index'
                        )
                    );
                }
                else $this->addFlash('warning', 'Wrong password!');
            }
        }

        if ($request->request->has('formImage')) {
            $formImage->handleRequest($request);
            if ($formImage->isSubmitted() && $formImage->isValid()) {
                $fieldToUpdate = $formImage->getData();
                if ($this->passwordEncoder->isPasswordValid($user, $fieldToUpdate['password'])) {
                    $file = $formImage->get('image')
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
                }
                else $this->addFlash('warning', 'Wrong password!');
            }
        }

        return $this->render(
            'user/edit.html.twig',
            [
                'formUsername' => $formUsername->createView(),
                'formEmail'    => $formEmail->createView(),
                'formImage'    => $formImage->createView(),
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
