<?php

	namespace App\Controller;

	use App\Entity\Messages;
	use App\Form\MessagesType;
	use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;

	class MessagesController extends AbstractController
	{

		#[Route('/micro-post/messages', name: 'app_micro_post_messages')]
		#[IsGranted('IS_AUTHENTICATED_FULLY')]
		public function index(Request $request, PersistenceManagerRegistry $doctrine): Response
		{
			$message = new Messages;
			$form = $this -> createForm(MessagesType::class, $message);

			$form -> handleRequest($request);

			if ($form -> isSubmitted() && $form -> isValid()) {
				$message -> setSender($this -> getUser());

				$em = $doctrine -> getManager();
				$em -> persist($message);
				$em -> flush();

				$this -> addFlash('message', 'Message envoyé avec succès.');
				return $this -> redirectToRoute('app_micro_post_messages');
			}
$messages = $doctrine ->getRepository(Messages::class)->findBy([],['id'=>'desc']);
			return $this -> render('messages/index.html.twig', [
				'controller_name' => 'MessagesController', 'form' => $form -> createView(),$messages
			]);
		}


		#[Route('/micro-post/messages/received', name: 'app_micro_post_messages_received')]
		public function received(): Response
		{
			return $this -> render('messages/index.html.twig');
		}


		#[Route('/micro-post/messages/sent', name: 'app_micro_post_messages_sent')]
		public function sent(): Response
		{
			return $this -> render('messages/sent.html.twig');
		}




		#[Route('/micro-post/messages/delete/{id}', name: 'app_micro_post_messages_delete')]
		public function delete(Messages $message, PersistenceManagerRegistry $doctrine): Response
		{
			$em = $doctrine -> getManager();
			$em -> remove($message);
			$em -> flush();

			return $this -> redirectToRoute('app_micro_post_messages_received');
		}
	}