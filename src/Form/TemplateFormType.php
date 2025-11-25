<?php

namespace App\Form;

use App\Validator\ContainsPassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;

class TemplateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('libelle', TextType::class, [
            'label' => 'Libellé',
            'attr' => [
                'readonly' => true,
            ],
        ])
            ->add('libelle2', TextType::class, [
                'label' => 'Libellé *',
                'attr' => [
                    'required' => true,
                ],
            ])
            ->add('choice', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices' => [
                    'User' => 'ROLE_CORE_USER',
                    'Admin' => 'ROLE_ADMIN',
                ],
            ])
            ->add('date', DateType::class, [
                'label' => 'Date',
                'required' => true,
                'years' => range(1991, 2004),
                'constraints' => [
                    new GreaterThan(['value' => '-31 years', 'message' => 'Vous devez avoir 30 ans maximum']),
                    new LessThan(['value' => '-18 years', 'message' => 'Vous devez avoir au moins 18 ans']),
                ],
            ])
            ->add('description', null, [
                'label' => 'Description',
                'help' => 'Message d\'aide',
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => [
                    'label' => 'Nouveau mot de passe',
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Merci de saisir un mot de passe',
                        ]),
                        new Length([
                            'min' => 10,
                            'minMessage' => 'Votre mot de passe doit avoir au moins {{ limit }} caractères',
                            'max' => 4096,
                        ]),
                        new ContainsPassword(),
                    ],
                ],
                'second_options' => ['label' => 'Confirmation du mot de passe'],
            ])
            ->add('active', CheckboxType::class, [
                'label_attr' => [
                    'class' => 'checkbox-switch', ],
            ])
            ->add('btn_submit_save', SubmitType::class, [
                'label' => 'Enregistrer',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }

    public function getBlockPrefix(): string
    {
        return 'coreadminz_template';
    }
}
