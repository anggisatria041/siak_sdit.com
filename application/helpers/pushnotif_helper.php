<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function sendmobilenotif($tokentujuan, $title, $isi, $action) {
    if($_SERVER['SERVER_NAME'] == 'stfms.vadhana.co.id'){
        $registrationIds = $tokentujuan;

        $API_ACCESS_KEY = 'AAAA2OjzanE:APA91bHMWqWqmehpZfbYfIKBSnXpMjPMaiket8qk-exKFpBRYEg_yWly4EC3NF-_XfVJHv75vdDhgJWWDZPLoiJv9G_UVlThJKArS8EJNj5Yb13dtxMniNHSjvrFaU9vZxmOMeYfzyPf'; // server key diambilkan dari firebase console di bagian Server key yang ada di tab cloud messaging

        $url = 'https://fcm.googleapis.com/fcm/send';

        // prepare the message
        $data = [
            'title' => $title,
            'body' => $isi,
            'event_type' => $action
        ];

        $notif = [
            'vibrate' => 1,
            'sound' => 1,
            'title' => $title,
            'body' => $isi,
            'priority' => 'high',
        ];

        $fields = array(
            "registration_ids" => $registrationIds,
            "notification" => $notif,
            "data" => $data,
        );
        $headers = array(
            'Authorization: key=' . $API_ACCESS_KEY,
            'Content-Type: application/json'
        );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
