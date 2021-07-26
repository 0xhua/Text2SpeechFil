<?php
require_once './vendor/stefangabos/zebra_curl/Zebra_cURL.php';
class Text2SpeechFil
{
    private $text;
    private $voice;
    private static $baseUrl = 'https://play.ht/api/transcribe';
    private $result;

    /**
     * Text2SpeechFil constructor.
     * @param $text
     * @param $voice
     */

    public function __construct($text, $voice)
    {
        $this->text = $text;
        $this->voice = $voice;
        $this->result = new stdClass();

    }

    public function t2s(){
        $curl = new Zebra_cURL();
        $curl->ssl(false);
        $curl->option(CURLOPT_HTTPHEADER,[
            'Content-Type: application/json',
            'cookie: crisp-client%2Fsession%2F'.$this->generateRandomString(2).'1ec052-4af0-4fbb-'.$this->generateRandomString(4).'-66551f587ef0=session_e45df4d0-536c-4be4-8ff5-2e53d256e62d'
        ]);
        $curl->post(
            array(
                self::$baseUrl => json_encode(array(
                    'userId'=>'public-access',
                    'ssml' => "<speak><p>'.$this->text.'</p></speak>",
                    'voice' =>  $this->voice,
                    'narrationStyle'=> 'regular',
                    'globalSpeed' => '100%',
                    'globalVolume'=> '+0dB',
                    'platform'=>'landing_demo',
                ))
            ), function ($result){
            if ($result->response[1] == CURLE_OK) {
                if ($result->info['http_code'] == 200) {
                    $res = json_decode($result->body);
                    $this->result->success = true;
                    $this->result->text = $this->text;
                    $this->result->file = stripslashes($res->file);
                    $this->result->duration = $res->duration;
                }else{
                    $this->result->success = false;
                    $this->result->message = 'Error! Please try again later';
                    $this->result->text = $this->text;
                }
            }else{
                $this->result->success = false;
                $this->result->message = 'Server Error! Please try again later';
                $this->result->text = $this->text;
            }
        });
        return $this->result;
    }

    private function reupload(){

    }
    private function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}