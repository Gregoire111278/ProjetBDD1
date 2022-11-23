<?php

namespace App\Security\Voter;

use App\Entity\MicroPost;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class MicroPostVoter extends Voter
{
public function __construct(private Security $security)
{
}

	protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [MicroPost::EDIT, MicroPost::VIEW])
            && $subject instanceof \App\Entity\MicroPost;
    }

	/** @param MicroPost $subject */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
		/** @var User $user */
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }
$isAuth = $user instanceof UserInterface;
		
		if($this->security->isGranted('ROLE_ADMIN')) {
			return true;
		}

        switch ($attribute) {
            case MicroPost::EDIT:
                return $isAuth
	                &&
	                ($subject-> getAuthor()->getId() ===$user->getId())||
	                $this->security->isGranted('ROLE_EDITOR'   )
	                ;
                break;
            case MicroPost::VIEW:
	            // logic to determine if the user can VIEW
                 return true;
                break;
        }

        return false;
    }
}

