<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
                'required' => false,
                'label' => 'Email',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champs email est requis',
                    ]),
                ],
            ])

            ->add('nom',TextType::class,[
                'required' => false,
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champs nom est requis',
                    ]),
                ],
            ])
            ->add('prenom',TextType::class,[
                'required' => false,
                'label' => 'prénom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champs prénom est requis',
                    ]),
                ],
            ])
            ->add('adresse',TextType::class,[
                'required' => false,
                'label' => 'Adresse',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champs adresse est requis',
                    ]),
                ],
            ])
            ->add('complement',TextType::class,[
                'required' => false,
                'label' => 'Complement'
            ])
            ->add('code_postal',IntegerType::class,[
                'required' => false,
                'label' => 'Code postal',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champs code postal est requis',
                    ]),
                ],
            ])
            ->add('ville',TextType::class,[
                'required' => false,
                'label' => 'Ville',
                 'constraints' => [
                    new NotBlank([
                        'message' => 'Le champs ville est requis',
                    ]),
                ],
            ])
            ->add('telephone',TextType::class,[
                'required' => false,
                'label' => 'Téléphone',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champs téléphone est requis',
                    ]),
                ],
            ])
            ->add('Kbis',TextType::class,[
                'required' => false,
                'label' => 'N° Kbis requis pour devenir client',
                
            ])
         
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'required' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'first_options'  => [
                    'label' => 'Mot de passe',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Le mot de passe est requis',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Le mot de passe doit faire au moins {{ limit }} caractères.',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmer votre mot de passe',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'La confirmation du mot de passe est requise',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Le mot de passe doit faire au moins {{ limit }} caractères.',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                ],
               
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Accepter les CGUV',
                'required' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les CGU.',
                    ]),
                ],
            ])
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
