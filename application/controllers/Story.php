<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Story extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->model(array('Storydetail_model','storymodel'));
    }

    // Main Page(New Story)
    public function index( $story_idx, $date_number, $storyplace_idx ) {
        //$_SESSION['create_story'] = false;

        $story_info = $this->Storydetail_model->get_story_info($story_idx);                                        // 스토리 정보
        $story_by_date_info = $this->Storydetail_model->get_story_by_date_info($story_idx);                        // 스토리 일자별 정보

        $date_number = isset($date_number) ? $date_number : null;
        $storyplace_idx = isset($storyplace_idx) ? $storyplace_idx : null;
        var_dump($story_idx);
        var_dump($date_number);
        var_dump($storyplace_idx);

        $story_by_place_info = $this->Storydetail_model->get_story_by_place_info($story_idx, $date_number, $storyplace_idx);             // 스토리 첫번째 일자의 장소에 대한 정보
        $story_by_place_good_info = $this->Storydetail_model->get_story_by_place_good_info($story_idx, $date_number, $storyplace_idx);   // 스토리 첫번째 일자의 장소에 대한 좋아요 정보
        // $story_by_place_image_info = $this->Storydetail_model->get_story_by_place_image_info($story_idx, $storyplace_idx);    // 스토리 첫번째 일자의 장소에 대한 이미지 정보
        // $story_by_place_image_reply_info = $this->Storydetail_model->get_story_by_place_image_reply_info($story_idx, $storyplace_idx);   // 스토리 첫번째 장소에 대한 첫번째 이미지의 댓글 정보
        // $story_by_date_place_list = $this->Storydetail_model->get_story_by_date_place_list_info($story_idx, $date_number);    // 스토리 특정 일자의 장소 리스트
        // $story_by_place_is_good_info = $this->Storydetail_model->get_story_by_place_is_good_info($story_by_place_info[0]->StoryPlaceIdx, $_SESSION['MemberIdx']);
        var_dump($story_by_place_good_info);

        // $this->load->view('../views/public/header.php');
        // $this->load->view('../views/story/storyDetail.php', $data);
        // $this->load->view('../views/public/footer.php');
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
