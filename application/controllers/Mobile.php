<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Mobile extends CI_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');

        $this->load->database();

        $this->load->model('mobilemodel');
    }

    public function index() {

    }

    public function getStoryPlace( $storyIdx ) {

        $datenum = $this->mobilemodel->getMaxDateNum($storyIdx);

        $data = array();

        for($i = 1; $i <= (int)$datenum['maxnum']; $i++) {
            $tempQuery = $this->mobilemodel->getPlaceInfoByStoryIdxAndDateNum($storyIdx, $i);

            $iCount = 0;
            foreach( $tempQuery as $row ) {
              $data[$i][$iCount] = $row;
              $iCount++;
            }

            // for($ii = 0; $ii < count($tempQuery); $ii++) {
            //     mysqli_data_seek($tempQuery, $ii);
            //     $data[$i][$ii] = mysqli_fetch_array($tempQuery);
            // }
        }
        //var_dump($data);
        //var_dump($tempQuery);

        //$result = array('data' => $data);
        print(json_encode($data,JSON_UNESCAPED_UNICODE));
    }

    public function SearchPlace($lat, $lon, $category, $range) {
        $searchedPlace = $this->mobilemodel->getPlaceInfoByLatLngAndRange($lat, $lon, $category, $range);

        $data = array();
        $i = 0;
        foreach ($searchedPlace as $row) {
          $data[$i] = $row;
          $i++;
        }

        print(json_encode($data,JSON_UNESCAPED_UNICODE));
    }

    public function fcm($lat, $lon, $message, $user) {
        
        $url = 'https://fcm.googleapis.com/fcm/send';
        $headers = array(
                    'Authorization:key = AIzaSyCI0Ei0SHxsq3J3xlRxveN0_Q2eeFJoVzE',
                                'Content-Type: application/json'
                        );
        $arr['data'] = array();

        $arr['data']['lat'] = $lat;
        $arr['data']['lon'] = $lon;
        $arr['data']['message'] = $message;
        $arr['data']['user'] = $user;
        $arr['data']['title'] = "OurTravelStory";
        $arr['registration_ids'] = array();
        $arr['registration_ids'][0] = "c7zWQvWzA2Y:APA91bGWtRS4_PvKIAYflrJj7P_XlatWCSsrM9CQ4k_D-_e9dAb05xn2_Ma7b54V9RqSqnxqCtEKsIq_Fb4ZyC0Zvn9hBfbplB5w4l-IH1N3Eh9rROLAfKobwRKrntB_Y0kyCFMyXHnN";

        $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_POST, true);
              curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr));
              $result = curl_exec($ch);           
                                                                      
        echo $result;

        if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
}

}
