<?php

namespace App\Services;

use Illuminate\Database\QueryException;
use App\Models\User;

class UserService extends BaseService
{

    public function createUser($email)
    {
        try
        {
            $auth_code = bin2hex(random_bytes(20));

            $user = new User();
            $user->email = $email;
            $user->auth_code = $auth_code;
            $user->auth_code_ttl = time() + $this->container->get('settings')['auth_code_ttl'];

            $user->save();

        }
        catch (QueryException $e)
        {
            $this->container->get('logger')->error("DB exception: " . $e->getMessage() . ", query: " . $e->getSql());
            $status = 500;
        }


    }

}