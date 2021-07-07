<?php
require_once '../vendor/stefangabos/zebra_curl/Zebra_cURL.php';

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
            'Content-Type: application/json'
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
                    $this->result->file = $res->file;
                    $this->result->duration = $res->duration;
                }else{
                    $this->result->success = false;
                    $this->result->message = 'Error! Please try again later';
                }
            }else{
                $this->result->success = false;
                $this->result->message = 'Server Error! Please try again later';
            }
        });
        return $this->result;
    }

    private function reupload(){

    }
}
##EXAMPLE
//$test = new Text2SpeechFil('edilson ag si ow di tan ah','fil-PH-Wavenet-C');
//var_dump($test->t2s());