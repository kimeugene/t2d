<?php

namespace App\Services;

use Illuminate\Database\QueryException;
use App\Models\User;
use App\Models\Plate;

class UserService extends BaseService
{
    public function createOrUpdateUser($email, $auth_code_ttl)
    {
        try
        {
            $user = $this->getUserBy('email', $email);

            $auth_code = bin2hex(
                random_bytes(20)
            );

            if (!$user)
            {
                $user = new User();
                $user->email = $email;
                $user->auth_code = $auth_code;
                $user->auth_code_ttl = time() + $auth_code_ttl;
                $user->confirmed = null;
            }
            // auth code expired? renew the auth code
            elseif (time() - $user['auth_code_ttl'] > 0)
            {
                $user->auth_code = $auth_code;
                $user->auth_code_ttl = time() + $auth_code_ttl;
            }

            $user->save();

            return $user;

        }
        catch (QueryException $e)
        {
            $this->logger->error("DB exception: " . $e->getMessage() . ", query: " . $e->getSql());
        }
        catch (\Exception $e)
        {
            $this->logger->error(__FUNCTION__ . " failed, exception: " . $e->getMessage());
        }

        return false;
    }

    public function getUserBy($column, $value)
    {
        try
        {
            $user = User::where($column, $value)->first();
            if (!is_null($user))
            {
                return $user;
            }
        }
        catch (QueryException $e)
        {
            $this->logger->error("DB exception: " . $e->getMessage() . ", query: " . $e->getSql());
        }
        catch (\Exception $e)
        {
            $this->logger->error(__FUNCTION__ . " failed, exception: " . $e->getMessage());
        }

        return false;
    }

    public function confirmEmail($code)
    {
        $user = $this->getUserBy('auth_code', $code);

        if ($user)
        {
            if ($user['confirmed'])
            {
                return true;
            }
            else
            {
                $user = $this->getUserBy('email', $user['email']);
                if ($user)
                {
                    $user->confirmed = date("Y-m-d H:i:s");
                    $user->save();
                    return true;
                }
                else
                {
                    $this->logger->error("this should never happen: user id: " . $user['id']);
                }
            }
        }

        return false;
    }

    public function isValid($code)
    {
        $user = $this->getUserBy('auth_code', $code);

        if ($user)
        {
            if ($user['confirmed'] && (time() - $user['auth_code_ttl'] < 0))
            {
                return $user;
            }
        }

        return false;
    }

    public function addPlate($user, $plate, $state)
    {
        try
        {
            $plate_model = new Plate();
            $plate_model->email = $user['email'];
            $plate_model->plate = $plate;
            $plate_model->state = $state;

            $plate_model->save();

            return true;
        }
        catch (QueryException $e)
        {
            $this->logger->error("DB exception: " . $e->getMessage() . ", query: " . $e->getSql());
        }
        catch (\Exception $e)
        {
            $this->logger->error(__FUNCTION__ . " failed, exception: " . $e->getMessage());
        }

        return false;
    }

    public function getPlates($user)
    {
        try
        {
            return Plate::where('email', $user['email'])->get();
        }
        catch (QueryException $e)
        {
            $this->logger->error("DB exception: " . $e->getMessage() . ", query: " . $e->getSql());
        }
        catch (\Exception $e)
        {
            $this->logger->error(__FUNCTION__ . " failed, exception: " . $e->getMessage());
        }

        return false;

    }
}

