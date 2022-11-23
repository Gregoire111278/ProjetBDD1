<?php

	namespace App\DataFixtures;

	use App\Entity\MicroPost;
	use App\Entity\User;
	use DateTime;
	use Doctrine\Bundle\FixturesBundle\Fixture;
	use Doctrine\Persistence\ObjectManager;
	use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

	class AppFixtures extends Fixture
	{

		public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
		{

		}

		public function load(ObjectManager $manager): void
		{



			$manager -> flush();
		}
	}
