<?php
/**
 * Created by PhpStorm.
 * User: kepoly
 * Date: 3/21/2016
 * Time: 3:49 PM
 */

// set post fields
$post = file_get_contents("php://input");


if(isset($post)) {
    $post = json_decode($post);
    $email = $post->name;
    $list = $post->list;
}

$payload = [
    'channel' => '#emaillist',
    'username' => 'webhookbot',
    'text'   => 'Please add email: ' . $email . ' to the: ' . $list . ' List',
    'icon_emoji' => ':ghost:'
];


$payload =json_encode($payload);
$url = "https://hooks.slack.com/services/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";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt ($ch, CURLOPT_CAINFO, "C:\\devWeb2\\perl\\vendor\\lib\\Mozilla\\CA\\cacert.pem");
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$payload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response  = curl_exec($ch);
curl_close($ch);
