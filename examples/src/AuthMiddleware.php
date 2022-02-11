<?php

require __DIR__.'/JWT.php';

use Interface\PiraRouteMiddleware;
use Routing\Request;

class AuthMiddleware implements PiraRouteMiddleware
{

    public static function handler(Request $request)
    {

        try {

            $token = $request->header('Authorization');
            if (empty($token)) {
                throw new \Exception('Token não enviado.');
            }

            $token = explode(' ', $token)[1];

            $data = JWT::decode($token, 'secret');
            if (empty($data)) {
                throw new \Exception('JWT invalida ou expirada.');
            }
            $request->setEnv('user', $data);
        } catch (\Exception $e) {
            header("Content-type: application/json");
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }
}