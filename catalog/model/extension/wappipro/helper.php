<?php


class ModelExtensionWappiProHelper extends Model
{
    public function sendSms($to, $body): bool
    {

        $apiKey   = $this->model_setting_setting->getSettingValue('wappipro_apiKey');
        $username = $this->model_setting_setting->getSettingValue('wappipro_username');

        if (!empty($apiKey)) {

            $req = array();
            $req['postfields'] = json_encode(array(
                'recipient' => $to,
                'body' => $body,
            ));

            $req['header'] = array(
                "accept: application/json",
                "Authorization: " .  $apiKey,
                "Content-Type: application/json",
            );

            $req['url'] = 'https://wappi.pro/api/sync/message/send?profile_id=' . $username;

            try {
                $answer = json_decode($this->curlito(false, $req), true);
                if (is_int($answer)) {
                    return true;
                }
            } catch (Exception $e) {
                return false;
            }
        }

        return false;
    }


    private function curlito($wait, $req, $method = '')
    {

        $curl = curl_init();
        $option = array(
            CURLOPT_URL => $req['url'],
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $req['postfields'],
            CURLOPT_HTTPHEADER => $req['header'],
        );

        if ($wait) {
            $option[CURLOPT_TIMEOUT] = 30;
        } else {
            $option[CURLOPT_TIMEOUT_MS] = 100;
            $option[CURLOPT_HEADER] = 0;
        }

        curl_setopt_array($curl, $option);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return 200;
        }
    }
}
