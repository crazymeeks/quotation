<?php

namespace App\Token;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use DateTimeImmutable;


class Token
{


    /**
     * @var string
     */
    const ALGO = 'HS256';

    private static function jwtKey()
    {
        return config('app.jwt_key');
    }

    /**
     * Encode to jwt
     *
     * @param mixed $payload
     * 
     * @return string
     */
    public static function create($payload = null)
    {

        // refresh token
        if (is_array($payload) && isset($payload['iat'])) {
            return JWT::encode($payload, self::jwtKey(), self::ALGO);
        }

        $issuedAt = new DateTimeImmutable();

        $expire = $issuedAt->modify('+60 minutes')->getTimestamp();

        $payload = array_merge([
            'iss' => config('app.url'),
            'aud' => config('app.url'),
            'iat' => strtotime(now()), // timestamp of token issuing
            'nbf' => strtotime(now()), // timestamp of when the token should start being considered valid. Should be equal to or greater than iat.
            'exp' => $expire

        ], ['data' => $payload]);
        
        $jwt = JWT::encode($payload, self::jwtKey(), self::ALGO);

        return $jwt;
    }

    /**
     * Decode jwt
     *
     * @param string $jwt
     * 
     * @return \stdClass The JWT's payload as a PHP object
     */
    public static function decode(string $jwt)
    {
        $decoded = JWT::decode($jwt, new Key(self::jwtKey(), self::ALGO));
        
        return $decoded;
    }

    /**
     * Refresh token
     * 
     * @param string $expiredToken
     *
     * @return string
     */
    public static function refreshedToken(string $expiredToken)
    {
        JWT::$leeway = 720000;// 1hr
        $decoded = (array) self::decode($expiredToken);
        // TODO: test if token is blacklisted
        $decoded['iat'] = time();
        $decoded['exp'] = time() + 3400;
        $refreshedToken = self::create($decoded);

        return $refreshedToken;
    }
}