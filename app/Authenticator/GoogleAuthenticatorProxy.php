<?php


namespace App\Authenticator;


use Sonata\GoogleAuthenticator\GoogleAuthenticator;

class GoogleAuthenticatorProxy
{



    /**
     * Check if code is valid
     *
     * @param string $secret
     * @param string $code
     * 
     * @return boolean
     */
    public function checkCode(string $secret, string $code)
    {
        $authenticator = app(GoogleAuthenticator::class);
        
        return $authenticator->checkCode($secret, $code);

    }
}