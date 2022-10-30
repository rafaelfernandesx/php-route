<?php

class JWT
{
    public static function decode(string $jwt, string $secret):\stdClass
    {
        $timestamp = time();

        if (empty($secret)) {
            throw new \Exception('Secret can\'t be empty');
        }
        $jwt = explode('.', $jwt);
        if (count($jwt) != 3) {
            throw new \Exception('Wrong number of segments');
        }
        list($header64, $body64, $signature64) = $jwt;
        if (null === ($header = json_decode(static::base64UrlDecode($header64)))) {
            throw new \Exception('Invalid header encoding');
        }
        if (null === $payload = json_decode(static::base64UrlDecode($body64))) {
            throw new \Exception('Invalid payload encoding');
        }
        if (false === ($sig = static::base64UrlDecode($signature64))) {
            throw new \Exception('Invalid signature encoding');
        }
        if (empty($header->alg)) {
            throw new \Exception('Empty algorithm');
        }

        if (!static::verify("$header64.$body64", $sig, $secret)) {
            throw new \Exception('Signature verification failed');
        }

        if (isset($payload->nbf) && $payload->nbf > $timestamp) {
            throw new \Exception(
                'Cannot handle token before to ' . date('Y-m-d H:i:s:v', $payload->nbf)
            );
        }

        if (isset($payload->iat) && $payload->iat > $timestamp) {
            throw new \Exception(
                'Cannot handle token after to ' . date('Y-m-d H:i:s:v', $payload->iat)
            );
        }

        if (isset($payload->exp) && $timestamp >= $payload->exp) {
            throw new \Exception('Expired token');
        }

        return $payload;
    }

    public static function encode(array $payload, string $key):string
    {
        $header = array('typ' => 'JWT', 'alg' => 'HS256');

        $segments = array();
        $segments[] = static::base64UrlEncode(json_encode($header));
        $segments[] = static::base64UrlEncode(json_encode($payload));
        $signing_input = implode('.', $segments);

        $signature = hash_hmac('sha256', $signing_input, $key, true);
        $segments[] = static::base64UrlEncode($signature);

        return implode('.', $segments);
    }

    private static function verify(string $msg, string $signature, string $key): bool
    {
        $hash = hash_hmac('sha256', $msg, $key, true);
        if ($hash === $signature) {
            return true;
        }
        return false;
    }

    public static function base64UrlDecode(string $data):string
    {
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($data)) % 4));
    }

    public static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
