<?php

namespace App\Services;

use Aws\Sns\SnsClient;
use Aws\Sns\Exception\SnsException;
use Aws\Exception\AwsException;



class PhoneService extends BaseService
{
    public function send_text($phone_num, $auth_code)
    {
        $this->logger->info("Senging text to: " . $phone_num);

        try
        {
            $client = new SnsClient([
                'version' => 'latest',
                'region'  => 'us-east-1'
            ]);

            $result = $client->publish([
                'Message' => $auth_code,
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