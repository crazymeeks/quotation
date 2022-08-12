<?php

namespace App\Authenticator;

class Secret
{

    /** @var string */
    protected $generatedSecret = null;


    /**
     * Set generated secret key per user
     *
     * @param string $secret
     * 
     * @return $this
     */
    public function setSecret(string $secret)
    {
        $this->generatedSecret = $secret;
        return $this;
    }

    /**
     * Get generated 2FA secret key
     *
     * @return string
     */
    public function get()
    {
        return $this->generatedSecret;
    }
}