<?php

	namespace App\Form;

	use App\Entity\Messages;
	use App\Entity\User;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\Form\Extension\Core\Type\TextareaType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;

	class MessagesType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			$builder
				-> add('message', TextareaType::class)
				-> add('recipient', EntityType::class, [
					'class' => User::class,
					'choice_label' => 'email']);

		}

		public function configureOptions(OptionsResolver $resolver)
		{
			$resolver -> setDefaults([
				'data_class' => Messages::class,
			]);
		}
	}