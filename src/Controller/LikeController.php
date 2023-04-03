<?php

	namespace App\Controller;

	use App\Entity\Event;
	use App\Entity\MicroPost;
	use App\Repository\EventRepository;
	use App\Repository\MicroPostRepository;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;

	class LikeController extends AbstractController
	{
		#[Route('/like/{id}', name: 'app_like')]
		public function like(MicroPost $post, MicroPostRepository $posts, Request $request): Response
		{
			$currentUser = $this -> getUser();
			$post -> addLikedBy($currentUser);
			$posts -> save($post, true);
			return $this -> redirect($request -> headers -> get('referer'));
		}

		#[Route('/unlike/{id}', name: 'app_unlike')]
		public function unlike(MicroPost $post, MicroPostRepository $posts, Request $request): Response
		{
			$currentUser = $this -> getUser();
			$post -> removeLikedBy($currentUser);
			$posts -> save($post, true);
			return $this -> redirect($request -> headers -> get('referer'));

		}
		#[Route('/likeEvent/{id}', name: 'app_like_eventLike')]
		public function eventLike(Event $event, EventRepository $events, Request $request): Response
		{
			$currentUser = $this -> getUser();
			$event -> addLikedByEvent($currentUser);
			$events -> save($event, true);
			return $this -> redirect($request -> headers -> get('referer'));
		}

		#[Route('/unlikeEvent/{id}', name: 'app_unlike_eventLike')]
		public function eventUnlike(Event $event, EventRepository $events, Request $request): Response
		{
			$currentUser = $this -> getUser();
			$event-> removeLikedByEvent($currentUser);
			$events -> save($event, true);
			return $this -> redirect($request -> headers -> get('referer'));

		}

	}
