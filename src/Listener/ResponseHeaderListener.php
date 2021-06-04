<?php 

namespace App\Listener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

class ResponseHeaderListener
{
    public function addHeaderToResponse(ResponseEvent $event) {
        
        $response = $event->getResponse();

        $response->headers->set('x-toto', 'coucou');
    }
   
}