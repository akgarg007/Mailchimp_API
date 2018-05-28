<?php
//API Details
$apiKey = 'a6d89dfa40f65bd0a1ca41673cfe6a2e-us11';
$listId = '76c07cc268';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];

    if($email) {
        //Create mailchimp API url
        $memberId = md5(strtolower($email));
        $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;

        //Member info
        $data = array(
            'email_address'=>$email,
            'status' => 'subscribed',
            'merge_fields'  => [
                'FNAME'     => $firstname,
                'LNAME'     => $lastname
            ]
            );
        $jsonString = json_encode($data);

        // send a HTTP POST request with curl
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonString);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        print_r($httpCode);    

        //Collecting the status
        switch ($httpCode) {
            case 200:
                $msg = 'Success, newsletter subcribed using mailchimp API';
                break;
            case 214:
                $msg = 'Already Subscribed';
                break;
            default:
                $msg = 'Oops, please try again.[msg_code='.$httpCode.']';
                break;
        }
    }

    echo $msg;
 
}