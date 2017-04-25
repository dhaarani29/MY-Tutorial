<?php

namespace QuizzingPlatform\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

class CustomErrorHandler {

    // Defines doctrine entity manager in constructor.
    public function __construct(Application $app) {

        $app->error(function (\Exception $e, Request $request, $code) use ($app) {

            $message = array();

            $message['code'] = $code;

            $message['message'] = $e->getMessage();

            $app['log']->writeLog($message);

            $jsonErrorMessage = json_encode($message);

            return new Response($jsonErrorMessage);
        });
    }

}
