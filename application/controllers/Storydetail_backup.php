<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StoryDetail extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('ourtravelstory_m');
        $this->load->helper(array('url','date'));
    }

    public function index() {
        $this->load->view('../views/public/header.php');
        $this->load->view('../views/main/storyMain.php');
        $this->load->view('../views/public/footer.php');

    }

    // 스토리 상세보기 메인 페이지
    public function storyDetail($storyIdx, $placeDate, $storyplaceIdx) {
        $_SESSION['nowTab'] = 'mystory';
        if($storyplaceIdx == 0){
            $storyplaceIdx = $this->ourtravelstory_m->StoryPlaceIdxCheck($storyIdx);
            $storyplaceIdx = $storyplaceIdx[0]->StoryPlaceIdx;
        }

        $StoryPlaceInfo = $this->ourtravelstory_m->StoryPlaceInfo($storyIdx,$placeDate,$storyplaceIdx);         // 스토리 장소에 대한 정보를 받아옴(스토리 및 장소 조인)
        $Story = $this->ourtravelstory_m->StoryInfo($storyIdx);                                                      // 스토리에 대한 정보를 받아옴
        $StoryPlaceGoodCount = $this->ourtravelstory_m->get_StoryPlaceGoodCount($storyplaceIdx);                    //장소별 좋아요 수
        $StoryReply = $this->ourtravelstory_m->get_StoryPlaceReply($storyplaceIdx);                                 //각각의 장소별 리플
        $StoryPlaceImage = $this->ourtravelstory_m->StoryPlaceImage($storyIdx, $storyplaceIdx);                     //장소별 이미지 추가
        $StoryPlace = $this->ourtravelstory_m->StoryPlace($storyIdx);                                               // 스토리 장소에 대한 정보를 받아옴(스토리)
        $StoryPlaceMax = $this->ourtravelstory_m->get_storyMaxData($storyIdx);                                               // 스토리 장소에 대한 정보를 받아옴(스토리)
        $StoryPlaceName = $this->ourtravelstory_m->get_StoryPlaceName($storyIdx,$placeDate);                        //일차별장소 출력
        $StoryPlaceBookmarkCheck = $this->ourtravelstory_m->get_storyPlaceBookmarkCheck(1,$storyplaceIdx);          //개인별 특정 장소 즐겨찾기 추가
        $this->load->view('../views/public/header.php');
        $this->load->view('../views/main/storyDetail.php', array('Story' => $Story, 'StoryReply' => $StoryReply, 'StoryPlaceImage' => $StoryPlaceImage, 'StoryPlace' => $StoryPlace,'StoryPlaceMax'=>$StoryPlaceMax, 'StoryPlaceInfo' => $StoryPlaceInfo, 'StoryPlaceName' => $StoryPlaceName,'StoryPlaceGoodCount' => $StoryPlaceGoodCount,'StoryPlaceBookmarkCheck' => $StoryPlaceBookmarkCheck));
        $this->load->view('../views/public/footer.php');
    }

    // 사진 편집
    public function storyPlaceImageMemoModify($storyplaceImageIdx){
        $StoryPlaceSelectImage = $this->ourtravelstory_m->get_storyPlaceSelectImage($storyplaceImageIdx);           //편집할 이미지 이름
        $this->load->view('../views/main/storyPlaceImageMemoModify.php',array('StoryPlaceSelectImage' => $StoryPlaceSelectImage));
    }

    // 사진 클릭시 장소별 사진 코멘트 보여주기
    public function storyPlaceImageMemoSelect(){
        $result = array();
        $callback=$_GET['callback'];
        $image_idx= $_GET['image_idx'];
        $story_idx= $_GET['story_idx'];


        $StoryMemo = $this->ourtravelstory_m->get_storyPlaceMemo($story_idx,$image_idx);

        $result=array('result'=>$StoryMemo,'image_id'=>$image_idx,'story_id'=>$story_idx);

        echo $callback."(".json_encode($result).")";
    }

    // 사진 재 클릭시 장소 리플 보여주기
    public function storyPlaceReplySelect(){
        $result = array();
        $callback=$_GET['callback'];
        $storyplace_idx= $_GET['storyplace_idx'];

        $StoryReply = $this->ourtravelstory_m->get_StoryPlaceReply($storyplace_idx);

        $result = array('result' => $StoryReply);

        echo $callback."(".json_encode($result).")";
    }


    // 나의 북마크에 특정 장소 추가
    public function add_my_place() {
        $result = array();
        $callback=$_GET['callback'];
        $place_idx = $_GET['place_idx'];
        $user_idx= $_GET['user_id'];

        $result=array('result'=>$user_idx);

        $this->ourtravelstory_m->get_storyPlaceBookmarkInsert($user_idx,$place_idx);

        echo $callback."(".json_encode($result).")";
    }
    // 나의 북마크에 특정 장소 제거
    public function delete_my_place() {
        $result = array();
        $callback=$_GET['callback'];
        $place_idx = $_GET['place_idx'];
        $user_idx= $_GET['user_id'];

        $result=array('result'=>$user_idx);

        $this->ourtravelstory_m->get_storyPlaceBookmarkDelete($user_idx,$place_idx);

        echo $callback."(".json_encode($result).")";
    }
    // 특정 장소 좋아요 추가
    public function add_place_good() {
        $result = array();
        $callback=$_GET['callback'];
        $place_idx = $_GET['place_idx'];
        $user_idx= $_GET['user_id'];

        $result=array('result'=>$user_idx);

        $this->ourtravelstory_m->get_storyPlaceGoodAdd($user_idx,$place_idx);
        $placeGoodCount = $this->ourtravelstory_m->get_StoryPlaceGoodCount($place_idx);

        $result=array('result'=>$placeGoodCount);

        echo $callback."(".json_encode($result).")";
    }
    // 특정 장소 좋아요 제거
    public function delete_place_good() {
        $result = array();
        $callback=$_GET['callback'];
        $place_idx = $_GET['place_idx'];
        $user_idx= $_GET['user_id'];


        $this->ourtravelstory_m->get_storyPlaceGoodDelete($user_idx,$place_idx);
        $placeGoodCount = $this->ourtravelstory_m->get_StoryPlaceGoodCount($place_idx);

        $result=array('result'=>$placeGoodCount);

        echo $callback."(".json_encode($result).")";
    }
    
    public function addPlaceReply(){
        $result = array();
        $callback=$_GET['callback'];
        $reply_content = $_GET['reply_content'];
        $user_id= $_GET['user_id'];
        $place_id= $_GET['place_id'];


        $this->ourtravelstory_m->get_storyPlaceReplyInsert($user_id,$reply_content,$place_id);
        $reply = $this->ourtravelstory_m->get_StoryPlaceReply($place_id);

        $result=array('result'=>$reply);

        echo $callback."(".json_encode($result).")";
    }
    
    public function addImageReply(){
        $result = array();
        $callback=$_GET['callback'];
        $reply_content = $_GET['reply_content'];
        $user_id= $_GET['user_id'];
        $image_id= $_GET['image_id'];
        $story_id= $_GET['story_id'];


        $this->ourtravelstory_m->get_storyImageReplyInsert($user_id,$reply_content,$image_id,$story_id);
        $reply = $this->ourtravelstory_m->get_storyPlaceMemo($story_id,$image_id);

        $result=array('result'=>$reply,'image_id'=>$image_id,'story_id'=>$story_id);

        echo $callback."(".json_encode($result).")";
    }
}
?>