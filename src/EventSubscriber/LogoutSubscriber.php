<?php

namespace App\EventSubscriber;

use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LogoutSubscriber implements EventSubscriberInterface
{
    public function onLogoutEvent($event)
    {
        if(in_array('application/json', $event->getRequest()->getAcceptableContentTypes())) {
            $response = new JsonResponse('You have been logged out', Response::HTTP_NO_CONTENT);
            $event->setResponse($response);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            LogoutEvent::class => 'onLogoutEvent',
        ];
    }
}
