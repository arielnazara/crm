<?php

namespace CRM\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntrepriseType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('raisonSocial', TextType::class)
            ->add('nomCommercial', TextType::class, ['required' => false])
            ->add('siret', TextType::class, ['required' => false])
            ->add('rue', TextType::class, ['required' => false])
            ->add('addresse', TextType::class, ['required' => false])
            ->add('codePostal', TextType::class, ['required' => false])
            ->add('ville', TextType::class, ['required' => false])
            ->add('pays', CountryType::class)
            ->add('email', EmailType::class, ['required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'CRM\Domain\Entreprise'
        ));
    }
}