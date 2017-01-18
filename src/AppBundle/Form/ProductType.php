<?php
/**
 * Created by PhpStorm.
 * User: Nea
 * Date: 12/01/2017
 * Time: 16:33
 */

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('moisSemis', IntegerType::class)
            ->add('prix', IntegerType::class)
            ->add('stock', IntegerType::class)
            ->add('image', FileType::class,array(
                'label'=>'Image',
                'data_class' => null,
                'required' => false
            ))
            ->add('type', EntityType::class, array(
                'class' => 'AppBundle:Type',
                'choice_label' => 'nom',
                'expanded' => true
            ));
    }

}