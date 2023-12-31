<?php

	namespace App\Entity;
               
               	use App\Repository\UserRepository;
               	use Doctrine\Common\Collections\ArrayCollection;
               	use Doctrine\Common\Collections\Collection;
               	use Doctrine\DBAL\Types\Types;
               	use Doctrine\ORM\Mapping as ORM;
               	use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
               	use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
               	use Symfony\Component\Security\Core\User\UserInterface;
               
               	#[ORM\Entity(repositoryClass: UserRepository::class)]
               	#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
               	class User implements UserInterface, PasswordAuthenticatedUserInterface
               	{
               		#[ORM\Id]
               		#[ORM\GeneratedValue]
               		#[ORM\Column]
               		private ?int $id = null;
               
               		#[ORM\Column(length: 180, unique: true)]
               		private ?string $email = null;
               
               		#[ORM\Column]
               		private array $roles = [];
               
               		/**
               		 * @var string The hashed password
               		 */
               		#[ORM\Column]
               		private ?string $password = null;
               
               		#[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
               		private ?UserProfile $userProfile = null;
               
               		#[ORM\ManyToMany(targetEntity: MicroPost::class, mappedBy: 'likedBy')]
               		private Collection $liked;
               
               		#[ORM\OneToMany(mappedBy: 'author', targetEntity: MicroPost::class)]
               		private Collection $posts;
               
               		#[ORM\OneToMany(mappedBy: 'author', targetEntity: Comment::class)]
               		private Collection $comments;
               
               		#[ORM\Column(type: 'boolean')]
               		private $isVerified = false;
               
               		#[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
               		private ?\DateTimeInterface $bannedUntil = null;
               
               		#[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'followers')]
               		#[ORM\JoinTable('followers')]
               		private Collection $follows;
               
               		#[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'follows')]
               		private Collection $followers;
               
               		#[ORM\OneToMany(mappedBy: 'sender', targetEntity: Messages::class, orphanRemoval: true)]
               		private Collection $sent;
               
               		#[ORM\OneToMany(mappedBy: 'recipient', targetEntity: Messages::class, orphanRemoval: true)]
               		private Collection $received;
               
               		#[ORM\OneToMany(mappedBy: 'author', targetEntity: Event::class)]
               		private Collection $UserEvent;
               
               
               		#[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'likedByEvent')]
               		private Collection $likedEvents;
            
                 #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'ParticipedBy')]
                 private Collection $Participed;
               
               		public function __construct()
               		{
               			$this -> liked = new ArrayCollection();
               			$this -> posts = new ArrayCollection();
               			$this -> comments = new ArrayCollection();
               			$this -> follows = new ArrayCollection();
               			$this -> followers = new ArrayCollection();
               			$this -> sent = new ArrayCollection();
               			$this -> received = new ArrayCollection();
               			$this -> UserEvent = new ArrayCollection();
               			$this -> likedEvents = new ArrayCollection();
                        $this->Participed = new ArrayCollection();
               		}
               
               		public function getId(): ?int
               		{
               			return $this -> id;
               		}
               
               		public function getEmail(): ?string
               		{
               			return $this -> email;
               		}
               
               		public function setEmail(string $email): self
               		{
               			$this -> email = $email;
               
               			return $this;
               		}
               
               		/**
               		 * A visual identifier that represents this user.
               		 *
               		 * @see UserInterface
               		 */
               		public function getUserIdentifier(): string
               		{
               			return (string)$this -> email;
               		}
               
               		/**
               		 * @see UserInterface
               		 */
               		public function getRoles(): array
               		{
               			$roles = $this -> roles;
               			// guarantee every user at least has ROLE_USER
               			$roles[] = 'ROLE_USER';
               
               			if ($this -> isVerified()) {
               				$roles = ['ROLE_WRITER'];
               			}
               			return array_unique($roles);
               		}
               
               		public function setRoles(array $roles): self
               		{
               			$this -> roles = $roles;
               
               			return $this;
               		}
               
               		/**
               		 * @see PasswordAuthenticatedUserInterface
               		 */
               		public function getPassword(): string
               		{
               			return $this -> password;
               		}
               
               		public function setPassword(string $password): self
               		{
               			$this -> password = $password;
               
               			return $this;
               		}
               
               		/**
               		 * @see UserInterface
               		 */
               		public function eraseCredentials()
               		{
               			// If you store any temporary, sensitive data on the user, clear it here
               			// $this->plainPassword = null;
               		}
               
               		public function getUserProfile(): ?UserProfile
               		{
               			return $this -> userProfile;
               		}
               
               		public function setUserProfile(UserProfile $userProfile): self
               		{
               			// set the owning side of the relation if necessary
               			if ($userProfile -> getUser() !== $this) {
               				$userProfile -> setUser($this);
               			}
               
               			$this -> userProfile = $userProfile;
               
               			return $this;
               		}
               
               		/**
               		 * @return Collection<int, MicroPost>
               		 */
               		public function getLiked(): Collection
               		{
               			return $this -> liked;
               		}
               
               		public function addLiked(MicroPost $liked): self
               		{
               			if (!$this -> liked -> contains($liked)) {
               				$this -> liked -> add($liked);
               				$liked -> addLikedBy($this);
               			}
               
               			return $this;
               		}
               
               		public function removeLiked(MicroPost $liked): self
               		{
               			if ($this -> liked -> removeElement($liked)) {
               				$liked -> removeLikedBy($this);
               			}
               
               			return $this;
               		}
               
               		/**
               		 * @return Collection<int, MicroPost>
               		 */
               		public function getPosts(): Collection
               		{
               			return $this -> posts;
               		}
               
               		public function addPost(MicroPost $post): self
               		{
               			if (!$this -> posts -> contains($post)) {
               				$this -> posts -> add($post);
               				$post -> setAuthor($this);
               			}
               
               			return $this;
               		}
               
               		public function removePost(MicroPost $post): self
               		{
               			if ($this -> posts -> removeElement($post)) {
               				// set the owning side to null (unless already changed)
               				if ($post -> getAuthor() === $this) {
               					$post -> setAuthor(null);
               				}
               			}
               
               			return $this;
               		}
               
               		/**
               		 * @return Collection<int, Comment>
               		 */
               		public function getComments(): Collection
               		{
               			return $this -> comments;
               		}
               
               		public function addComment(Comment $comment): self
               		{
               			if (!$this -> comments -> contains($comment)) {
               				$this -> comments -> add($comment);
               				$comment -> setAuthor($this);
               			}
               
               			return $this;
               		}
               
               		public function removeComment(Comment $comment): self
               		{
               			if ($this -> comments -> removeElement($comment)) {
               				// set the owning side to null (unless already changed)
               				if ($comment -> getAuthor() === $this) {
               					$comment -> setAuthor(null);
               				}
               			}
               
               			return $this;
               		}
               
               		public function isVerified(): bool
               		{
               			return $this -> isVerified;
               		}
               
               		public function setIsVerified(bool $isVerified): self
               		{
               			$this -> isVerified = $isVerified;
               
               			return $this;
               		}
               
               		public function getBannedUntil(): ?\DateTimeInterface
               		{
               			return $this -> bannedUntil;
               		}
               
               		public function setBannedUntil(?\DateTimeInterface $bannedUntil): self
               		{
               			$this -> bannedUntil = $bannedUntil;
               
               			return $this;
               		}
               
               		/**
               		 * @return Collection<int, self>
               		 */
               		public function getFollows(): Collection
               		{
               			return $this -> follows;
               		}
               
               		public function follow(self $follow): self
               		{
               			if (!$this -> follows -> contains($follow)) {
               				$this -> follows -> add($follow);
               			}
               
               			return $this;
               		}
               
               		public function unfollow(self $follow): self
               		{
               			$this -> follows -> removeElement($follow);
               
               			return $this;
               		}
               
               		/**
               		 * @return Collection<int, self>
               		 */
               		public function getFollowers(): Collection
               		{
               			return $this -> followers;
               		}
               
               		public function addFollower(self $follower): self
               		{
               			if (!$this -> followers -> contains($follower)) {
               				$this -> followers -> add($follower);
               				$follower -> follow($this);
               			}
               
               			return $this;
               		}
               
               		public function removeFollower(self $follower): self
               		{
               			if ($this -> followers -> removeElement($follower)) {
               				$follower -> unfollow($this);
               			}
               
               			return $this;
               		}
               
               		/**
               		 * @return Collection<int, Messages>
               		 */
               		public function getSent(): Collection
               		{
               			return $this -> sent;
               		}
               
               		public function addSent(Messages $sent): self
               		{
               			if (!$this -> sent -> contains($sent)) {
               				$this -> sent -> add($sent);
               				$sent -> setSender($this);
               			}
               
               			return $this;
               		}
               
               		public function removeSent(Messages $sent): self
               		{
               			if ($this -> sent -> removeElement($sent)) {
               				// set the owning side to null (unless already changed)
               				if ($sent -> getSender() === $this) {
               					$sent -> setSender(null);
               				}
               			}
               
               			return $this;
               		}
               
               		/**
               		 * @return Collection<int, Messages>
               		 */
               		public function getReceived(): Collection
               		{
               			return $this -> received;
               		}
               
               		public function addReceived(Messages $received): self
               		{
               			if (!$this -> received -> contains($received)) {
               				$this -> received -> add($received);
               				$received -> setRecipient($this);
               			}
               
               			return $this;
               		}
               
               		public function removeReceived(Messages $received): self
               		{
               			if ($this -> received -> removeElement($received)) {
               				// set the owning side to null (unless already changed)
               				if ($received -> getRecipient() === $this) {
               					$received -> setRecipient(null);
               				}
               			}
               			return $this;
               		}
               
               		/**
               		 * @return Collection<int, Event>
               		 */
               		public function getUserEvent(): Collection
               		{
               			return $this -> UserEvent;
               		}
               
               		public function addUserEvent(Event $userEvent): self
               		{
               			if (!$this -> UserEvent -> contains($userEvent)) {
               				$this -> UserEvent -> add($userEvent);
               				$userEvent -> setAuthor($this);
               			}
               
               			return $this;
               		}
               
               		public function removeUserEvent(Event $userEvent): self
               		{
               			if ($this -> UserEvent -> removeElement($userEvent)) {
               				// set the owning side to null (unless already changed)
               				if ($userEvent -> getAuthor() === $this) {
               					$userEvent -> setAuthor(null);
               				}
               			}
               
               			return $this;
               		}
               
               
               		/**
               		 * @return Collection<int, Event>
               		 */
               		public function getLikedEvents(): Collection
               		{
               			return $this -> likedEvents;
               		}
      
                 /**
                  * @return Collection<int, Event>
                  */
                 public function getParticiped(): Collection
                 {
                     return $this->Participed;
                 }
   
                 public function addParticiped(Event $participed): self
                 {
                     if (!$this->Participed->contains($participed)) {
                         $this->Participed->add($participed);
                         $participed->addParticipedBy($this);
                     }
   
                     return $this;
                 }

                 public function removeParticiped(Event $participed): self
                 {
                     if ($this->Participed->removeElement($participed)) {
                         $participed->removeParticipedBy($this);
                     }

                     return $this;
                 }
               	}
