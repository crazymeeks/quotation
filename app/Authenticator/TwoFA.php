<?php

namespace App\Authenticator;

use App\Models\User;
use App\Authenticator\Secret;
use Sonata\GoogleAuthenticator\GoogleQrUrl;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

class TwoFA
{


    /**
     * Per user generated secret key
     *
     * @var \App\Authenticator\Secret
     */
    protected $secret;

    /**
     * @var \Sonata\GoogleAuthenticator\GoogleAuthenticator
     */
    protected $googleAuthenticator;

    public function __construct(GoogleAuthenticator $googleAuthenticator)
    {
        $this->googleAuthenticator = $googleAuthenticator;
        $this->secret = new Secret();
    }


    /**
     * Generate QR code
     *
     * @param string $email
     * 
     * @return string
     */
    public function getQrCode(string $email)
    {
        $secret = $this->googleAuthenticator->generateSecret();
        
        $this->secret->setSecret($secret);

        $imageSrc = GoogleQrUrl::generate($email, $secret, config('app.name'));

        return $imageSrc;
    }

    /**
     * Get encrypted generated 2FA secret key
     *
     * @return string|null
     */
    public function getSecrect()
    {
        return $this->secret->get();
    }

}