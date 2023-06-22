<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

// class LogoutEventSubscriber implements EventSubscriberInterface
// {
//     public function __construct(private UrlGeneratorInterface $urlGenerator, private FlashBagInterface $flashBag)
//     {
//     }

//     public function onLogoutEvent(LogoutEvent $event): void
//     {
//         $this->flashBag->add('success', 'Logged out successfully');
//         $event->setResponse(new RedirectResponse($this->urlGenerator->generate('app_home')));
//     }

//     public static function getSubscribedEvents(): array
//     {
//         return [
//             LogoutEvent::class => 'onLogoutEvent',
//         ];
//     }
// }