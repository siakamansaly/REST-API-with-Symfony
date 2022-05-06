<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserVoter extends Voter
{
    public const USER_EDIT = 'USER_EDIT';
    public const USER_DELETE = 'USER_DELETE';
    public const USER_ADD = 'USER_ADD';
    public const USER_VIEW = 'USER_VIEW';
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::USER_EDIT, self::USER_DELETE, self::USER_ADD, self::USER_VIEW])
            && $subject instanceof \App\Entity\User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        // if the user is admin, they can do anything
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        // if the user is anonymous, do not grant access
        if (!$token->getUser() instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::USER_EDIT:
                return $this->canEditUser($subject, $token->getUser());
                break;
            case self::USER_DELETE:
                return $this->canManageUser($subject, $token->getUser());
                break;
            case self::USER_ADD:
                return $this->canManageUser($subject, $token->getUser());
                break;
            case self::USER_VIEW:
                return $this->canManageUser($subject, $token->getUser());
                break;
        }

        return false;
    }

    private function canEditUser(User $currentUser, User $user): bool
    {
        if($this->security->isGranted('ROLE_CUSTOMER') && $currentUser->getCustomer() === $user->getCustomer()) {
            return true;
        }
        return $user === $currentUser;
    }

    private function canManageUser(User $currentUser, User $user): bool
    {
        if($this->security->isGranted('ROLE_CUSTOMER') && $currentUser->getCustomer() === $user->getCustomer()) {
            return true;
        }
        return false;
    }

}
