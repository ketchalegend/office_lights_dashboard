<?php
/**
 * Created by PhpStorm.
 * User: eketchabepa
 * Date: 02.03.2019
 * Time: 22:04
 */


namespace App\Security;

use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        // user is deleted, show a generic Account Not Found message.
        if ($user->isDeleted()) {
            throw new AuthenticationCredentialsNotFoundException('...');

            // or to customize the message shown
            throw new CustomUserMessageAuthenticationException(
                'Your account was deleted. Sorry about that!'
            );
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        // user account is expired, the user may be notified
        if ($user->isExpired()) {
            throw new AccountExpiredException('...');
        }
    }
}