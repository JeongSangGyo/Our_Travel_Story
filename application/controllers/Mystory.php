<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mystory extends CI_Controller {
	function __construct() {
    parent::__construct();
    $this->load->helper('url');
    $this->load->database();
    $this->load->model('storymodel');
	}

  // My Story Page
  public function index( $MemberIdx ) {
    $_SESSION['create_story'] = true;

    if(isset($_SESSION['MemberIdx'])) {
        if($_SESSION['MemberIdx'] == $MemberIdx){
            $_SESSION['nowTab'] = 'mystory';
        } else {
            $_SESSION['nowTab'] = '';
        }
    } else {
        $_SESSION['nowTab'] = '';
    }

    $MyStoryList = $this->storymodel->StorySelectMy($MemberIdx);
    $Companion = $this->storymodel->StorySelectCompanion();
    $member_info = $this->storymodel->get_member_info($MemberIdx);

    $this->load->view('public/header');
    $this->load->view('/mystory/index', array('MyStoryList' => $MyStoryList, 'Companion' => $Companion, 'MemberInfo' => $member_info));
    $this->load->view('/public/footer');
  }

  // Sharing check
  public function PublicCheck( $StoryIdx, $check ) {
    if ($check == 0) {
        $this->storymodel->StoryPublicOn($StoryIdx);
        $alert = "share_on";
    } else {
        $this->storymodel->StoryPublicOff($StoryIdx);
        $alert = "share_off";
    }

    $this->load->view('/notice/alert', array('alert' => $alert));
  }
}
?>
