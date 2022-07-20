<?php
// ============================================================================
// NEXMU SMS class to send SMS messages from the platform
// jribeiro @ 2022-01-25
// Docs: https://developer.vonage.com/messaging/sms/overview
// ============================================================================

namespace App\Libraries;

// ============================================================================
class NexmoSMS
{
    // ========================================================================
    public function sendSMS($number, $message, $callback_url = null)
    {
        // --------------------------------------
        // check if the SMS is to be sent
        if (!SEND_SMS) {
            return [
                'status' => 'NOT SENT',
                'info' => 'SMS sending is disabled.'
            ];
        }

        // --------------------------------------
        // check if there is an override number to be used in all communications
        if (!empty(OVERRIDE_SMS_NUMBER)) {
            $number = OVERRIDE_SMS_NUMBER;
        }

        // --------------------------------------
        // send resquest to Nexmu API to send the SMS message

        $curl_post_fields = [
            'api_key' => NEXMO_API_KEY,
            'api_secret' => NEXMO_API_SECRET,
            'from' => NEXMO_SENDER,
            'to' => '351' . $number,
            'text' => $message,
        ];

        // add callback url if specified
        if (!empty($callback_url)) {
            $curl_post_fields["callback"] = $callback_url;
        }

        // generates the curl request
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://rest.nexmo.com/sms/" . NEXMO_FORMAT,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($curl_post_fields),
            CURLOPT_RETURNTRANSFER => true
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {

            // if there was an error in the request
            return [
                'status' => 'ERROR',
                'message' => $err,
                'data' => []
            ];
        } else {

            // if there was a request success
            return [
                'status' => 'SUCCESS',
                'message' => 'SUCCESS',
                'data' => $this->prepare_request_response($response)
            ];
        }
    }

    // ========================================================================
    private function prepare_request_response($data)
    {
        // returns a prepared response after cleaning the Nexmu API response information
        $data = json_decode($data, true);
        if (key_exists('messages', $data)) {
            for ($i = 0; $i < count($data['messages']); $i++) {

                // remaining-balance
                if (key_exists('remaining-balance', $data['messages'][$i])) {
                    unset($data['messages'][$i]['remaining-balance']);
                }

                // message-price
                if (key_exists('message-price', $data['messages'][$i])) {
                    unset($data['messages'][$i]['message-price']);
                }
            }
        }

        return $data;
    }

    // ========================================================================
    // SMS MESSAGES
    // ========================================================================
    public function send_sms_pre_registration($mobile, $message)
    {
        // send SMS message on a pre-registration process
        return $this->sendSMS($mobile, $message, NEXMO_API_CALLBACK);
    }

    // ========================================================================
    public function send_sms_recover_password($mobile, $message)
    {
        // send SMS message on recover password process
        return $this->sendSMS($mobile, $message, NEXMO_API_CALLBACK);
    }

    // ========================================================================
    public function send_sms_mfa_code($mobile, $message)
    {
        // send SMS message with MFA code
        return $this->sendSMS($mobile, $message, NEXMO_API_CALLBACK);
    }
}