<?php
namespace App\Form;

use App\Entity\CurrencyInquiry;
use App\Entity\SequenceFormData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SequenceForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numbers', TextAreaType::class, [
                'attr' => [
                    'style' =>'margin:5px 25px 10px 4px'
                ],
                'label' => 'Podaj liczby: ',
            ])
            ->add('Calculate!', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-dark',
                ],
            ])
        ;
    }
}