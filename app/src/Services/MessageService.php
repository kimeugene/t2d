<?php

namespace App\Services;

use App\Models\Message;
use Illuminate\Database\QueryException;

class MessageService extends BaseService
{
    public function addMessage($text, $user_id, $plate_id)
    {
        try
        {
            $message = new Message();
            $message->text = $text;
            $message->user_id = $user_id;
            $message->plate_id = $plate_id;
            $message->save();

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

    public function getMessages($plate_id)
    {
        try
        {
            return Message::where('plate_id', $plate_id)->get();
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

