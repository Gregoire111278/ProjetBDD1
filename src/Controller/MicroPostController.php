<?php

	namespace App\Controller;

	use App\Entity\User;
	use App\Entity\Comment;
	use App\Entity\MicroPost;
	use App\Form\CommentType;
	use App\Form\MicroPostType;
	use App\Repository\CommentRepository;
	use App\Repository\MicroPostRepository;
	use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
	use Symfony\Component\HttpFoundation\File\Exception\FileException;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\String\Slugger\SluggerInterface;


	class MicroPostController extends AbstractController
	{
		#[Route('/micro-post', name: 'app_micro_post')]
		#[IsGranted('IS_AUTHENTICATED_FULLY')]
		public function index(Request $request, MicroPostRepository $posts, SluggerInterface $slugger): Response
		{
			/** @var User $user */
			$user = $this -> getUser();
			$form = $this -> createForm(MicroPostType::class, new MicroPost());
			$form -> handleRequest($request);

			if ($form -> isSubmitted() && $form -> isValid()) {

				$postImage = $form -> get('postImage') -> getData();
				$post = $form -> getData();

				if ($postImage) {
					$originalFileName = pathinfo(
						$postImage -> getClientOriginalName(),
						PATHINFO_FILENAME
					);
					$safeFilename = $slugger -> slug($originalFileName);
					$newFileName = $safeFilename . '-' . uniqid() . '.' . $postImage -> guessExtension();

					try {
						$postImage -> move(
							$this -> getParameter('images_directory'),
							$newFileName
						);
					} catch (FileException $e) {
					}
					$post -> setAuthor($this -> getUser());
					$post -> setImage($newFileName);
					$posts -> save($post, true);
					$this -> addFlash('success', 'your micro post have been added');
					return $this -> redirectToRoute('app_micro_post');
				}
				else {
					$post -> setAuthor($this -> getUser());

					$posts -> save($post, true);
					$this -> addFlash('success', 'your micro post have been added');
					return $this -> redirectToRoute('app_micro_post');
				}

			}
			return $this -> render('/micro_post/index.html.twig', ['form' => $form -> createView(),
				'posts' => $posts -> findAllWithComments()]);
		}

		#[Route('/micro-post/top-liked', name: 'app_micro_post_topliked')]
		#[IsGranted('IS_AUTHENTICATED_FULLY')]
		public function topLiked(Request $request, MicroPostRepository $posts, SluggerInterface $slugger): Response
		{

			$form = $this -> createForm(MicroPostType::class, new MicroPost());
			$form -> handleRequest($request);

			if ($form -> isSubmitted() && $form -> isValid()) {

				$postImage = $form -> get('postImage') -> getData();
				$post = $form -> getData();

				if ($postImage) {
					$originalFileName = pathinfo(
						$postImage -> getClientOriginalName(),
						PATHINFO_FILENAME
					);
					$safeFilename = $slugger -> slug($originalFileName);
					$newFileName = $safeFilename . '-' . uniqid() . '.' . $postImage -> guessExtension();

					try {
						$postImage -> move(
							$this -> getParameter('images_directory'),
							$newFileName
						);
					} catch (FileException $e) {
					}
					$post -> setAuthor($this -> getUser());
					$post -> setImage($newFileName);
					$posts -> save($post, true);
					$this -> addFlash('success', 'your micro post have been added');
					return $this -> redirectToRoute('app_micro_post');
				}
				else {
					$post -> setAuthor($this -> getUser());

					$posts -> save($post, true);
					$this -> addFlash('success', 'your micro post have been added');
					return $this -> redirectToRoute('app_micro_post');
				}

			}
			return $this -> render('/micro_post/top_liked.html.twig', ['form' => $form -> createView(),
				'posts' => $posts -> findAllWithMinLikes(2)]);

		}

		#[Route('/micro-post/follows', name: 'app_micro_post_follows')]
		#[IsGranted('IS_AUTHENTICATED_FULLY')]
		public function follows(Request $request, MicroPostRepository $posts, SluggerInterface $slugger): Response
		{

			$form = $this -> createForm(MicroPostType::class, new MicroPost());
			$form -> handleRequest($request);

			if ($form -> isSubmitted() && $form -> isValid()) {

				$postImage = $form -> get('postImage') -> getData();
				$post = $form -> getData();

				if ($postImage) {
					$originalFileName = pathinfo(
						$postImage -> getClientOriginalName(),
						PATHINFO_FILENAME
					);
					$safeFilename = $slugger -> slug($originalFileName);
					$newFileName = $safeFilename . '-' . uniqid() . '.' . $postImage -> guessExtension();

					try {
						$postImage -> move(
							$this -> getParameter('images_directory'),
							$newFileName
						);
					} catch (FileException $e) {
					}
					$post -> setAuthor($this -> getUser());
					$post -> setImage($newFileName);
					$posts -> save($post, true);
					$this -> addFlash('success', 'your micro post have been added');
					return $this -> redirectToRoute('app_micro_post');
				}
				else {
					$post -> setAuthor($this -> getUser());

					$posts -> save($post, true);
					$this -> addFlash('success', 'your micro post have been added');
					return $this -> redirectToRoute('app_micro_post');
				}

			}
			/** @var User $user */
			$currentUser = $this -> getUser();
			return $this -> render('/micro_post/follows.html.twig', ['form' => $form -> createView(),
					'posts' => $posts -> findAllByAuthors(
						$currentUser -> getFollows()),
				]
			);
		}

		#[Route('/micro-post/{post}', name: 'app_micro_post_show')]
		#[IsGranted(MicroPost::VIEW, 'post')]
		public function showOne(MicroPost $post): Response
		{
			return $this -> render('micro_post/show.html.twig', [
				'post' => $post,
			]);
		}

		#[Route('/micro-post/delete/{id}', name: 'app_micro_post_delete')]
		public function deletePost(MicroPost $post, PersistenceManagerRegistry $doctrine): Response
		{
			$em = $doctrine -> getManager();
			$em -> remove($post);
			$em -> flush();

			return $this -> redirectToRoute('app_micro_post');
		}

		#[Route('/micro-post/{post}/edit', name: 'app_micro_post_edit')]
		#[IsGranted(MicroPost::EDIT, 'post')]
		public function edit(MicroPost $post, Request $request, MicroPostRepository $posts): Response
		{

			$form = $this -> createForm(MicroPostType::class, $post);
			$form -> handleRequest($request);

			$this -> denyAccessUnlessGranted(MicroPost::EDIT, $post);
			if ($form -> isSubmitted() && $form -> isValid()) {
				$post = $form -> getData();
				$posts -> save($post, true);
				$this -> addFlash('success', 'your micro post have been updated');
				return $this -> redirectToRoute('app_micro_post');
			}

			return $this -> renderForm(
				'micro_post/edit.html.twig',
				[
					'form' => $form,
					'post' => $post
				]
			);
		}

		#[Route('/micro-post/{post}/comment', name: 'app_micro_post_comment')]
		public function addComment(MicroPost $post, Request $request, CommentRepository $comments): Response
		{
			$form = $this -> createForm(CommentType::class, new Comment());
			$form -> handleRequest($request);

			if ($form -> isSubmitted() && $form -> isValid()) {
				$comment = $form -> getData();
				$comment -> setPost($post);
				$comment -> setAuthor($this -> getUser());
				$comments -> save($comment, true);
				$this -> addFlash('success', 'your comment have been updated');
				return $this -> redirectToRoute('app_micro_post_show', ['post' => $post -> getId()]);
			}

			return $this -> renderForm(
				'micro_post/comment.html.twig',
				[
					'form' => $form,
					'post' => $post
				]
			);
		}
	}