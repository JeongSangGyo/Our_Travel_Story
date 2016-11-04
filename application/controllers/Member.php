<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends CI_Controller {
    function __construct() {
      parent::__construct();
      $this->load->helper('url');
      $this->load->database();
      $this->load->model('membermodel');
    }

    // 로그인
    function Login() {
      $MemberInfo['MemberLoginEmail'] = $_POST['MemberEmail'];
      $MemberInfo['MemberLoginPassword'] = $_POST['MemberPassword'];

      $check = $this->membermodel->MemberLogin($MemberInfo);
      $alert = "error";

      if($check) {
        $alert = "login";

        // 로그인 성공 시 세션에 회원정보 저장
        $_SESSION['MemberIdx'] = $check['MemberIdx'];
        $_SESSION['MemberEmail'] = $check['MemberEmail'];
        $_SESSION['MemberPassword'] = $check['MemberPassword'];
        $_SESSION['MemberNickname'] = $check['MemberNickname'];
        $_SESSION['MemberProfile'] = $check['MemberProfile'];
        $_SESSION['MemberProfileExt'] = $check['MemberProfileExt'];
        $_SESSION['MemberJoindate'] = $check['MemberJoindate'];

        $this->load->view('/notice/alert', array('alert' => $alert));
      } else {
        $this->load->view('/notice/alert', array('alert' => $alert));
      }
    }

    function LoginPage() {
        $this->load->view('/public/header');
        $this->load->view('/main/login');
    }

    function JoinPage() {
        $this->load->view('/public/header');
        $this->load->view('/main/join');
    }
    
    // 로그아웃
    function Logout() {
      if($_SESSION['nowTab'] == 'mystory') {
        // My Story 탭에서 로그아웃 할 경우 메인 페이지로 돌아가도록 한다.
        $alert = "logout_init";
      } else {
        $alert = "logout";
      }

      // 로그아웃 시 세션 제거
      unset($_SESSION['MemberIdx']);
      unset($_SESSION['MemberEmail']);
      unset($_SESSION['MemberNickname']);
      unset($_SESSION['MemberPassword']);
      unset($_SESSION['MemberProfile']);
      unset($_SESSION['MemberProfileExt']);

      $this->load->view('/notice/alert', array('alert' => $alert));
    }

    // 회원가입 및 이메일 인증 메시지 전송
    function Join() {
      $MemberInfo['MemberJoinEmail'] = $_POST['MemberJoinEmail'];
      $MemberInfo['MemberJoinNickname'] = $_POST['MemberJoinNickname'];
      $MemberInfo['MemberJoinPassword'] = $_POST['MemberJoinPassword'];

      $alert = "error";

      if($this->membermodel->MemberInsertJoin($MemberInfo)) {
        $userEmail = $MemberInfo['MemberJoinEmail'];
        $userIdx = $this->membermodel->MemberConfirmEmail($userEmail);

        $this->load->library('email');
        $this->email->from('tkdry4911@gmail.com', 'OurTravelStory');
        /*$this->email->reply_to('tkdry4911@gmail.com');*/
        $this->email->to($userEmail);
        /*$this->email->cc('tkdry4911@daum.net');*/
        $this->email->subject('Email Test');
        $this->email->message("<a href='http://167.88.115.33/Member/EmailConfirm/".$userIdx['MemberIdx']."'>Welcome to Our Travel Story!!</a>");
        /*$this->email->message("<a href='http://localhost/Member/EmailConfirm/".$userEmail."'>Welcome to Our Travel Story!!</a>");*/
        // $this->email->attach('/path/to/file1.png');

        if($this->email->send()) {
          $alert = "join";
        } else {
          $alert = "error";
        }
      }

      $this->load->view('/notice/alert', array('alert' => $alert));
    }

    // 이메일 인증처리
    function EmailConfirm( $userIdx ) {
      $alert = "error";

      if($this->membermodel->MemberConfirmUpdate($userIdx)) {
        $alert = "confirm";
      }

      $this->load->view('/notice/alert', array('alert' => $alert));
    }

    // 회원정보 수정
    function Update() {
      $alert = "error";

      $MemberInfo['MemberIdx'] = $_SESSION['MemberIdx'];
      $MemberInfo['MemberUpdatePassword'] = $_POST['MemberUpdatePassword'];

      if($this->membermodel->MemberUpdate($MemberInfo)) {
        $alert = "update";
      }

      $this->load->view('/notice/alert', array('alert' => $alert));
    }

    // 회원탈퇴
    function leave() {
        /*$userEmail = $_POST['MemberJoinEmail'];
        $result = $this->membermodel->MemberConfirmIdx($userEmail);
        var_dump($result);*/
    }

}
