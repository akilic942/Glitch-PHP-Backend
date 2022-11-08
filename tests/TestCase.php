<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $_apiHost = 'http://web';

    protected $_apiRoute = '/api/';

    protected function _getApiRoute() {
        return $this->_apiHost . '/api/';
    }

    public function getAccessToken(User $user = null) {

        if(!$user){
            $user = User::factory()->create();
        }

        $token = auth()->login($user);

        return $token;
    }
}
