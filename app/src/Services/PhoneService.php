<?php

namespace App\Services;

use Aws\Sns\SnsClient;
use Aws\Sns\Exception\SnsException;
use Aws\Exception\AwsException;



class PhoneService extends BaseService
{
    /**
     * @param $phone_num
     * @param $text
     * @return bool
     */
    public function send_text($phone_num, $text)
    {
        // TODO: check if the phone if confirmed
        $this->logger->info("Senging text to: " . $phone_num);

        try
        {
            $client = new SnsClient([
                'version' => 'latest',
                'region'  => 'us-east-1'
            ]);

            $result = $client->publish([
                'Message' => $text,
                'PhoneNumber' => $phone_num,
            ]);


            if (isset($result['MessageId']))
            {
                $this->logger->info("Result: " . $result['MessageId']);
                return true;
            }
        }
        catch (SnsException $e)
        {
            $this->logger->error(sprintf('%s: %s', "Could not send text (SNS)", $e->getMessage()));
        }
        catch (AwsException $e)
        {
            $this->logger->error(sprintf('%s: %s', "Could not send text (AWS)", $e->getMessage()));
        }
        catch (\Exception $e)
        {
            $this->logger->error(sprintf('%s: %s', "Could not send text", $e->getMessage()));
        }

        return false;
    }

}