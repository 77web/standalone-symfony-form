<?php

namespace My;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SimpleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('name', 'text')
          ->add('comment', 'textarea')
        ;
    }
    
    public function getName()
    {
        return 'simple_form';
    }
}