<?php

namespace App\Form;

use App\Entity\MicroPost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MicroPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('title')
            ->add('text', TextareaType::class, ['row_attr' => array('cols' => '3', 'rows' => '3')])
	        -> add('postImage', FileType::class,
		        ['label' => ' image (JPG orPNG file)',
			        'mapped' => false,
			        'required' => false,
			        'constraints' => [new File(
				        ['maxSize' => '1024k',
					        'mimeTypes' => ['image/jpeg',
						        'image/png']])]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MicroPost::class,
        ]);
    }
}
