<?php

	namespace App\Form;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Validator\Constraints\File;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Form\Extension\Core\Type\FileType;
	class ProfileImageType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options): void
		{
			$builder
				-> add('profileImage', FileType::class,
					['label' => 'Profile images (JPG orPNG file)',
						'mapped' => false,
						'required' => false,
						'constraints' => [new File(
							['maxSize' => '1024k',
								'mimeTypes' => ['image/jpeg',
									'image/png']])
						]]);
		}

		public function configureOptions(OptionsResolver $resolver): void
		{
			$resolver -> setDefaults([
				// Configure your form options here
			]);
		}
	}
