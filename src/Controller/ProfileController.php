<?php

	namespace App\Controller;

	use App\Entity\User;
	use App\Repository\MicroPostRepository;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;

	class ProfileController extends AbstractController
	{
		#[Route('/profile/{id}', name: 'app_profile')]
		#[IsGranted('IS_AUTHENTICATED_FULLY')]
		public function show(User $user, MicroPostRepository $posts): Response
		{
			/** @var  $currentUser */
			$currentUser = $this -> getUser();
			return $this -> render('profile/show.html.twig', ['user' => $user,
				'currentUser' => $currentUser,
				'posts' => $posts -> findAllByAuthor($user)
			]);
		}

		#[Route('/profile/{id}/follows', name: 'app_profile_follows')]
		#[IsGranted('IS_AUTHENTICATED_FULLY')]
		public function follows(User $user): Response
		{/** @var  $currentUser */
			$currentUser = $this -> getUser();
			return $this -> render('profile/follows.html.twig', ['user' => $user,'currentUser' => $currentUser
			]);
		}

		#[Route('/profile/{id}/followers', name: 'app_profile_followers')]
		#[IsGranted('IS_AUTHENTICATED_FULLY')]
		public function followers(User $user): Response
		{
			/** @var  $currentUser */
			$currentUser = $this -> getUser();
			return $this -> render('profile/followers.html.twig', ['user' => $user,'currentUser' => $currentUser
			]);
		}
	}

