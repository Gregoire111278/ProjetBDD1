<?php

	namespace App\Controller;

	use App\Entity\Comment;
	use App\Entity\MicroPost;
	use App\Entity\User;
	use App\Entity\UserProfile;
	use App\Repository\CommentRepository;
	use App\Repository\MicroPostRepository;
	use App\Repository\UserProfileRepository;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;

	class HelloController extends AbstractController
	{
		private array $messages = [
			['message' => 'Hello', 'created' => '2022/11/12'],
			['message' => 'Hi', 'created' => '2022/10/12'],
			['message' => 'Bye!', 'created' => '2021/05/12']
		];

		#[Route('/', name: 'app_index')]
		public function index(MicroPostRepository $posts, CommentRepository $comments/*UserProfileRepository $profiles*/): Response
		{
			/*$post = new MicroPost();
			$post->setTitle('hello');
			$post->setText('hello');
			$post->setCreated(new \DateTime());*/
			/*$post = $posts -> find(19);
			$comment = $post -> getComments()[0];
			$comment->setPost(null);
			$comments->save($comment,true);*/
			/* dd($post);*/
			/*$comment = new Comment();
			$comment ->setText('hello');
			$comment->setPost($post);
			$comments->save($comment,true);
			//$post->addComment($comment);
			//$posts->save($post,true);


				$user = new User();
		$user -> setEmail('email@emaill.com');
				$user -> setPassword('123');

			$profile = new UserProfile();
				$profile -> setUser($user);
				$profiles -> save($profile, true);

			$profile = $profiles->find(1);
			$profiles->remove($profile, true);*/

			return $this -> render('hello/index.html.twig',
				[
					'messages' => $this -> messages,
					'limit' => 3
				]
			);
		}

		#[Route('/messages/{id<\d+>}', name: 'app_ShowOne')]
		public function showOne($id): Response
		{
			return $this -> render(
				'hello/show_one.html.twig', [
				'message' => $this -> messages[$id],

			]);
//return  new Response($this->messages[$id]);
		}
	}
