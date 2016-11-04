<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adv extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url','form'));
        $this->load->database();
        $this->load->model('advmodel');
    }

    public function index() {
        if(@$_SESSION['MemberIdx'] == 2) {
            $this->load->view('/public/header');
            $this->load->view('/adv/index');
        } else {
            $alert = "error";
            $this->load->view('/notice/alert', array('alert' => $alert));
        }
    }

    // 광고 등록 ( 위도, 경도, 주소, 반경, 이미지 정보 )
    function add_adv() {
        $AdvInfo['adv_lat'] = $_POST['adv_lat'];
        $AdvInfo['adv_lng'] = $_POST['adv_lng'];
        $AdvInfo['adv_name'] = $_POST['adv_name'];
        $AdvInfo['adv_address'] = $_POST['adv_address'];
        $AdvInfo['adv_radius'] = $_POST['radius'];
        $AdvInfo['member_idx'] = $_POST['memberidx'];
        $AdvInfo['adv_image'] = substr($_FILES['adv_image']['name'],0,strrpos($_FILES['adv_image']['name'],"."));
        $AdvInfo['adv_imageExt'] = pathinfo($_FILES['adv_image']['name'], PATHINFO_EXTENSION);
        $AdvInfo['adv_comment'] = $_POST['adv_comment'];
        /*$AdvInfo['adv_date'] = $_POST['adv_date'];*/

        $this->advmodel->add_adv($AdvInfo);

        $config['upload_path']          = '/opt/lampp/htdocs/images/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 1000;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;


        /*var_dump($_FILES);*/
        $this->load->library('upload', $config);

        $field_name = "adv_image";

        if(!$this->upload->do_upload($field_name)){
            $alert = "error";
        }else{
            $alert = "success";
        }

        $this->load->view('/notice/alert', array('alert'=>$alert));
    }

    // 앱이 실행될 때 가져올 위치 정보(등록된 광고)
    function location_info() {
        $initValue = $this->advmodel->init_location();

        /*$Count = 0;
        foreach( $initValue as $row ) {
            $data[$i][$Count] = $row;
            $Count++;
        }*/

        echo json_encode($initValue, JSON_UNESCAPED_UNICODE);
    }

    // 사용자가 반경 안에 들어왔을 때 보내줄 광고의 상세정보
    function adv_push( $get_AdvIdx ) {
        $AdvIdx = (int)$get_AdvIdx;

        $AdvInfo = $this->advmodel->adv_Info( $AdvIdx );

        echo json_encode($AdvInfo, JSON_UNESCAPED_UNICODE);
    }

    /*function get_location( ) {
        $lat = $_GET['present_lat'];
        $lng = $_GET['present_lng'];
        var_dump($lat, $lng);
        //die();

        $dugong[0] = "듀공 ㅎㅎ(lat) : ".$lat;
        $dugong[1] = "듀공 ㅎㅎ(lng) : ".$lng;

        print(json_encode($dugong,JSON_UNESCAPED_UNICODE));
    }*/


}
