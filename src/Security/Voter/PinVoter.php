<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Pin;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class PinVoter extends Voter
{
    public const EDIT = 'PIN_EDIT';
    public const VIEW = 'PIN_VIEW';
    public const DELETE = 'PIN_DELETE';


    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        // return in_array($attribute, [self::EDIT, self::VIEW])
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE]) && $subject instanceof Pin;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                // Check if the user has the necessary role to edit pins
                return $this->isUserAllowedToEdit($user, $subject);
            case self::VIEW:
                // Check if the user has the necessary role to view pins
                return $this->isUserAllowedToView($user, $subject);
            case self::DELETE:
                return $this->isUserAllowedToDelete($user, $subject);
            }
        return false;
    }

    private function isUserAllowedToEdit(User $user, Pin $pin): bool
    {
        var_dump($user->getRoles()); // Vérifiez les rôles de l'utilisateur
        var_dump($pin->getUser()); // Vérifiez le propriétaire du pin
    

        // Vérifier si l'utilisateur a le rôle approprié pour éditer les pins
        if ($user->hasRole('ROLE_ADMIN')) {
            return true; // L'administrateur peut éditer tous les pins
        }
    
        // Vérifier si l'utilisateur est le propriétaire du pin
        if ($user === $pin->getUser()) {
            return true; // Le propriétaire peut éditer son propre pin
        }
    
        // Ajoutez d'autres vérifications personnalisées selon vos besoins
    
        return false; // Par défaut, l'utilisateur n'est pas autorisé à éditer le pin
    }

    private function isUserAllowedToView(User $user, Pin $pin): bool
    {
        var_dump($user->getRoles()); // Vérifiez les rôles de l'utilisateur
        var_dump($pin->getUser()); // Vérifiez le propriétaire du pin
    

        // Vérifier si l'utilisateur a le rôle approprié pour voir les pins
        if ($user->hasRole('ROLE_ADMIN')) {
            return true; // L'administrateur peut voir tous les pins
        }
    
        // Vérifier si l'utilisateur est le propriétaire du pin
        if ($user === $pin->getUser()) {
            return true; // Le propriétaire peut voir son propre pin
        }
    
        // Ajoutez d'autres vérifications personnalisées selon vos besoins
    
        return false; // Par défaut, l'utilisateur n'est pas autorisé à voir le pin
    }

    private function isUserAllowedToDelete(User $user, Pin $pin): bool
{
    // Vérifier si l'utilisateur a le rôle approprié pour supprimer les pins
    if ($user->hasRole('ROLE_ADMIN')) {
        return true; // L'administrateur peut supprimer tous les pins
    }

    // Vérifier si l'utilisateur est le propriétaire du pin
    if ($user->hasRole('ROLE_USER') && $user === $pin->getUser()) {
        return true; // Le propriétaire peut supprimer son propre pin
    }

    // Ajoutez d'autres vérifications personnalisées selon vos besoins

    return false; // Par défaut, l'utilisateur n'est pas autorisé à supprimer le pin
}


}
