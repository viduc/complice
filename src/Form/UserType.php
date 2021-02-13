<?php declare(strict_types=1);
/******************************************************************************/
/*                                   COMPLICE                                 */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'nom',
                TextType::class,
                [
                    'attr' =>
                        [
                        'class' => 'form-control',
                        'placeholder' => $options['transaltor']
                            ->trans('utilisateur.edit.nom')
                        ]
                ]
            )
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'form-control',
                    'placeholder' => $options['transaltor']->trans(
                        'utilisateur.edit.prenom'
                )]
            ])
            ->add('civilite', ChoiceType::class, [
                'choices'  => [
                    'Mr' => 'Mr',
                    'Mme' => 'Mme'
                ],
                'attr' => ['class' => 'form-control',
                    'placeholder' => $options['transaltor']->trans(
                        'utilisateur.edit.civilite'
                )]
            ])
            ->add('email', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $options['transaltor']->trans(
                        'utilisateur.edit.email'
                )]
            ])
            ->add('roles', ChoiceType::class, [
                'choices'  => $options['roles'],
                'multiple' => true,
                'attr' => ['class' => 'form-control', 'size' => 2]
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'form-control btn btn-success'],
                'label' => $options['transaltor']->trans('utilisateur.edit.save')
            ])
        ;
        if ($options['type'] === 'editer') {
            $builder
                ->add('actif', CheckboxType::class, [
                    'attr' => ['class' => 'custom-control-input'],
                    'required' => false
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'transaltor' => null,
            'roles' => [],
            'type' => 'creer'
        ]);
    }
}
