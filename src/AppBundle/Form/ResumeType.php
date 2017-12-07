<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResumeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('age')
            ->add('symfony')
            ->add(
                'position',
                ChoiceType::class,
                [
                    'placeholder' => 'Choose a position',
                    'choices'     => [
                        'Backend Developer'   => 'Backend Developer',
                        'Frontend Developer'  => 'Frontend Developer',
                        'Fullstack Developer' => 'Fullstack Developer',
                        'Symfony Developer'   => 'Symfony Developer',
                        'Product Owner'       => 'Product Owner',
                        'Roxxor'              => 'Roxxor',
                    ],
                ]
            )
            ->add('comment')
            ->add('test', SubmitType::class, ['label' => 'Send']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Resume',
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_resume';
    }


}
