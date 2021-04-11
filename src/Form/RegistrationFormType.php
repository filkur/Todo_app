<?php
declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => false,
                    'attr'  => [
                        'placeholder' => 'E-mail',
                    ],
                ]
            )
            ->add(
                'username',
                TextType::class,
                [
                    'label' => false,
                    'attr'  => [
                        'placeholder' => 'Username',
                    ],
                ]
            )
            ->add('image', FileType::class, [
                'mapped'=> false,
                'label' => false,
            ])
            ->add(
                'agreeTerms',
                CheckboxType::class,
                [
                    'mapped'      => false,
                    'constraints' => [
                        new IsTrue(
                            [
                                'message' => 'You should agree to our terms.',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'password',
                RepeatedType::class,
                [
                    // instead of being set onto the object directly,
                    // this is read and encoded in the controller
                    'type'            => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'options'         => ['attr' => ['class' => 'password-field']],
                    'required'        => true,
                    'first_options'   => [
                        'label' => false,
                        'attr'  => [
                            'placeholder' => 'Password',
                        ],
                    ],
                    'second_options'  => [
                        'label' => false,
                        'attr'  => [
                            'placeholder' => 'Confirm Password',
                        ],
                    ],

                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }
}
