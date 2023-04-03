<?php

	namespace App\Entity;
               
               	use App\Repository\EventRepository;
               	use Doctrine\Common\Collections\ArrayCollection;
               	use Doctrine\Common\Collections\Collection;
               	use Doctrine\DBAL\Types\Types;
               	use Doctrine\ORM\Mapping as ORM;
               
               	#[ORM\Entity(repositoryClass: EventRepository::class)]
               	class Event
               	{
               		#[ORM\Id]
               		#[ORM\GeneratedValue]
               		#[ORM\Column]
               		private ?int $id = null;
               
               		#[ORM\Column(length: 255)]
               		private ?string $name = null;
               
               		#[ORM\Column(length: 50)]
               		private ?string $type = null;
               
               		#[ORM\Column(type: Types::TEXT, nullable: true)]
               		private ?string $participant = null;
               
               		#[ORM\Column(type: Types::DATETIME_MUTABLE)]
               		private ?\DateTimeInterface $date = null;
               
               		#[ORM\Column(length: 255, nullable: true)]
               		private ?string $image = null;
               
               
               		#[ORM\ManyToOne(inversedBy: 'UserEvent')]
               		#[ORM\JoinColumn(nullable: false)]
               		private ?User $author = null;
               
               		#[ORM\Column(length: 400)]
               		private ?string $description = null;
               
               		#[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'likedEvents')]
               		private Collection $likedByEvent;
            
                 #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'Participed')]
                 private Collection $ParticipedBy;
               
               		public function __construct()
               		{
               			$this -> likedByEvent = new ArrayCollection();
                        $this->ParticipedBy = new ArrayCollection();
               		}
               
               
               		public function getId(): ?int
               		{
               			return $this -> id;
               		}
               
               		public function getName(): ?string
               		{
               			return $this -> name;
               		}
               
               		public function setName(string $name): self
               		{
               			$this -> name = $name;
               
               			return $this;
               		}
               
               		public function getType(): ?string
               		{
               			return $this -> type;
               		}
               
               		public function setType(string $type): self
               		{
               			$this -> type = $type;
               
               			return $this;
               		}
               
               		public function getParticipant(): ?string
               		{
               			return $this -> participant;
               		}
               
               		public function setParticipant(?string $participant): self
               		{
               			$this -> participant = $participant;
               
               			return $this;
               		}
               
               		public function getDate(): ?\DateTimeInterface
               		{
               			return $this -> date;
               		}
               
               		public function setDate(\DateTimeInterface $date): self
               		{
               			$this -> date = $date;
               
               			return $this;
               		}
               
               		public function getImage(): ?string
               		{
               			return $this -> image;
               		}
               
               		public function setImage(?string $image): self
               		{
               			$this -> image = $image;
               
               			return $this;
               		}
               
               		public function getAuthor(): ?User
               		{
               			return $this -> author;
               		}
               
               		public function setAuthor(?User $author): self
               		{
               			$this -> author = $author;
               
               			return $this;
               		}
               
               		public function getDescription(): ?string
               		{
               			return $this -> description;
               		}
               
               		public function setDescription(string $description): self
               		{
               			$this -> description = $description;
               
               			return $this;
               		}
               
               		/**
               		 * @return Collection<int, User>
               		 */
               		public function getLikedByEvent(): Collection
               		{
               			return $this -> likedByEvent;
               		}
               
               		public function addLikedByEvent(User $likedByEvent): self
               		{
               			if (!$this -> likedByEvent -> contains($likedByEvent)) {
               				$this -> likedByEvent -> add($likedByEvent);
               			}
               
               			return $this;
               		}
               
               		public function removeLikedByEvent(User $likedByEvent): self
               		{
               			$this -> likedByEvent -> removeElement($likedByEvent);
               
               			return $this;
               		}
      
                 /**
                  * @return Collection<int, User>
                  */
                 public function getParticipedBy(): Collection
                 {
                     return $this->ParticipedBy;
                 }
   
                 public function addParticipedBy(User $participedBy): self
                 {
                     if (!$this->ParticipedBy->contains($participedBy)) {
                         $this->ParticipedBy->add($participedBy);
                     }
   
                     return $this;
                 }

                 public function removeParticipedBy(User $participedBy): self
                 {
                     $this->ParticipedBy->removeElement($participedBy);

                     return $this;
                 }
               
               
               	}
