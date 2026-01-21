<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;

class LocaleListener
{
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        $preferredLanguage = $request->getPreferredLanguage(['fr', 'en']);

        if ($preferredLanguage) {
            $request->setLocale($preferredLanguage);
        }
    }
}
