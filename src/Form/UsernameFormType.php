<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class UsernameFormType extends AbstractType
{
    private $security;

    /**
     * UsernameFormType constructor.
     *
     * @param $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username',
                TextType::class,
                [
                    'label' => 'Username',
                    'attr'  => [
                        'placeholder' => $this->security->getUser()->getUsername(),
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
