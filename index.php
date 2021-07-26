<?php
require 'lib/Text2SpeechFil.php';
if($_POST){
    $request = file_get_contents('php://input');
    $tospeech = new Text2SpeechFil($request,'fil-PH-Wavenet-C');
    print(json_encode($tospeech->t2s(),JSON_UNESCAPED_SLASHES));
}else{
    print(file_get_contents('./assets/front.html'));
}
