<?php

namespace App\Services;

use Illuminate\Database\QueryException;
use App\Models\User;

class UserService extends BaseService
{
    public function createOrUpdateUser($email, $auth_code_ttl)
    {
        try
        {
            $user = User::where('email', $email)->first();
            $new_user = false;

            if (is_null($user))
            {
                $auth_code = bin2hex(
                    random_bytes(20)
                );
                $user = new User();
                $user->email = $email;
                $user->auth_code = $auth_code;

                $new_user = true;
            }

            $user->auth_code_ttl = time() + $auth_code_ttl;
            $user->save();

            return ['new_user' => $new_user, 'auth_code' => $user['auth_code']];

        }
        catch (QueryException $e)
        {
            $this->logger->error("DB exception: " . $e->getMessage() . ", query: " . $e->getSql());
        }
        catch (\Exception $e)
        {
            $this->logger->error("Cannot createOrUpdate user, exception: " . $e->getMessage());
        }

        return false;
    }

}