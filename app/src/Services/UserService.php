<?php

namespace App\Services;

use App\Models\Phone;
use Illuminate\Database\QueryException;
use App\Models\User;
use App\Models\Plate;

class UserService extends BaseService
{
    /**
     * @param $email
     * @param $auth_code_ttl
     * @return User|bool
     */
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

    /**
     * @param $column
     * @param $value
     * @return bool
     */
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

    /**
     * @param $code
     * @return bool
     */
    public function verifyEmail($code)
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

    /**
     * @param $code
     * @return bool
     */
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

    /**
     * @param $user
     * @param $plate
     * @param $state
     * @return bool
     */
    public function addPlate($user, $plate, $state)
    {
        try
        {
            $plate_model = new Plate();
            $plate_model->user_id = $user['id'];
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

    /**
     * @param $user
     * @return bool
     */
    public function getPlates($user)
    {
        try
        {
            return Plate::where('user_id', $user['id'])->get();
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

    /**
     * @param $user
     * @param $plate_id
     * @return bool
     */
    public function deletePlate($user, $plate_id)
    {
        try
        {
            $plate = Plate::where('user_id', $user['id'])->where('id', $plate_id)->first();
            if ($plate)
            {
                $plate->delete();
                return true;
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

    /**
     * @param $user
     * @param $phone
     * @return bool
     */
    public function addPhone($user, $phone, $ttl)
    {
        try
        {
            $code = rand(100000, 999999);
            $phone_model = new Phone();
            $phone_model->user_id = $user['id'];
            $phone_model->auth_code = $code;
            $phone_model->auth_code_ttl = $ttl;
            $phone_model->phone = $phone;
            $phone_model->confirmed = null;

            $phone_model->save();

            return $phone_model;
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

    /**
     * @param $user
     * @param $verification_code
     * @return bool
     */
    public function confirmPhone($user, $verification_code)
    {
        try
        {
            $phone = Phone::where('user_id', $user['id'])->where('auth_code', $verification_code)->first();
            if ($phone)
            {
                if (is_null($phone['confirmed']))
                {
                    $phone->confirmed = date("Y-m-d H:i:s");
                    $phone->save();
                }
                return true;
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
}

