<?php

	namespace App\Entity;

	use App\Repository\MessagesRepository;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Entity(repositoryClass: MessagesRepository::class)]
	class Messages
	{
		#[ORM\Id]
		#[ORM\GeneratedValue]
		#[ORM\Column]
		private ?int $id = null;


		#[ORM\Column(type: Types::TEXT)]
		private ?string $message = null;

		#[ORM\Column]
		private ?\DateTimeImmutable $created_at = null;



		#[ORM\ManyToOne(inversedBy: 'sent')]
		#[ORM\JoinColumn(nullable: false)]
		private ?User $sender = null;

		#[ORM\ManyToOne(inversedBy: 'received')]
		#[ORM\JoinColumn(nullable: false)]
		private ?User $recipient = null;

		public function __construct()
		{
			$this -> created_at = new \DateTimeImmutable();
		}

		public function getId(): ?int
		{
			return $this -> id;
		}


		public function getMessage(): ?string
		{
			return $this -> message;
		}

		public function setMessage(string $message): self
		{
			$this -> message = $message;

			return $this;
		}

		public function getCreatedAt(): ?\DateTimeImmutable
		{
			return $this -> created_at;
		}

		public function setCreatedAt(\DateTimeImmutable $created_at): self
		{
			$this -> created_at = $created_at;

			return $this;
		}





		public function getSender(): ?User
		{
			return $this -> sender;
		}

		public function setSender(?User $sender): self
		{
			$this -> sender = $sender;
			return $this;
		}

		public function getRecipient(): ?User
		{
			return $this -> recipient;
		}

		public function setRecipient(?User $recipient): self
		{
			$this -> recipient = $recipient;

			return $this;
		}
	}
