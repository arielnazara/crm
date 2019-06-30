<?php

namespace CRM\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;

class ContactType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('civilite', ChoiceType::class, ['choices' => ['Monsieur' => 'Monsieur', 'Madame' => 'Madame']])
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('fonction', TextType::class, ['required' => false])
            ->add('telephone', TextType::class)
            ->add('email', EmailType::class)
            ->add('isDefault', CheckboxType::class, ['required' => false])
            ->add('entreprise', EntrepriseType::class)
            ->add('Enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'CRM\Domain\Contact'
        ));
    }
}