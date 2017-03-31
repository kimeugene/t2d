<?php

namespace App\Services;

use Aws\Ses\SesClient;
use Aws\Ses\Exception as SesException;
use Aws\Exception\AwsException;



class EmailService extends BaseService
{
    public function send_email($email_address, $auth_code, $new_user = true)
    {
        $this->logger->info("Senging email to: " . $email_address);

        try
        {
            $sender = "no-reply@text2driver.com";
            $client = new SesClient([
                'version' => 'latest',
                'region'  => 'us-east-1'
            ]);

            $result = $client->sendEmail([
                'Destination' => [
                    'BccAddresses' => [
                        $sender
                    ],
                    'ToAddresses' => [
                        $email_address
                    ],
                ],
                'Message' => [
                    'Body' => [
                        'Html' => [
                            'Charset' => 'UTF-8',
                            'Data' => 'Welcome to cartexted.com. Click <a href="http://cartexted.com/email?code=' . $auth_code . '>here</a> to continue',
                        ],
                        'Text' => [
                            'Charset' => 'UTF-8',
                            'Data' => 'This is the message body in text format.',
                        ],
                    ],
                    'Subject' => [
                        'Charset' => 'UTF-8',
                        'Data' => 'welcome',
                    ],
                ],
                'ReplyToAddresses' => [
                    $sender
                ],
                'Source' => $sender
            ]);

            if (isset($result['MessageId']))
            {
                $this->logger->info("Result: " . $result['MessageId']);
                return true;
            }
        }
        catch (SesException $e)
        {
            $this->logger->error(sprintf('%s: %s', "Could not send email (SES)", $e->getMessage()));
        }
        catch (AwsException $e)
        {
            $this->logger->error(sprintf('%s: %s', "Could not send email (AWS)", $e->getMessage()));
        }
        catch (\Exception $e)
        {
            $this->logger->error(sprintf('%s: %s', "Could not send email", $e->getMessage()));
        }

        return false;
    }

}