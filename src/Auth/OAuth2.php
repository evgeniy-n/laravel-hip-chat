<?php

namespace EvgeniyN\LaravelHipChat\Auth;

use GorkaLaucirica\HipchatAPIv2Client\Auth\OAuth2 as BaseOAuth2;

class OAuth2 extends BaseOAuth2
{
    public function setToken($token)
    {
        $this->authToken = $token;
    }
}
