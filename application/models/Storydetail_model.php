<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Storydetail_model extends CI_Model {
   function __construct() {
     parent::__construct();
   }

   // 스토리의 정보를 얻어온다.
   public function get_story_info( $story_idx ) {
     $this->db->where('StoryIdx', $story_idx);
     return $this->db->get('Story')->result();
   }

   // 스토리의 일자별 정보를 얻어온다.
   public function get_story_by_date_info( $story_idx ) {
     $this->db->select('StoryPlaceDateNumber');
     $this->db->where('StoryIdx', $story_idx);
     $this->db->group_by('StoryPlaceDateNumber');
     return $this->db->get('StoryPlace')->result();
   }

   // 스토리의 특정 일자의 장소 리스트를 얻어온다.
   public function get_story_by_date_place_list_info( $story_idx, $date_number ) {
     $this->db->select('*');
     $this->db->from('StoryPlace');
     $this->db->join('Place', 'Place.PlaceIdx = StoryPlace.PlaceIdx');
     $this->db->where('StoryIdx', $story_idx);
     $this->db->where('StoryPlaceDateNumber', $date_number);
     return $this->db->get()->result();
   }

   // 스토리의 특정 일자에 속하는 특정 장소에 대한 정보를 얻어온다.
   public function get_story_by_place_info( $story_idx, $date_number, $storyplace_idx ) {
     if (!$storyplace_idx) {
       // 처음 들어왔을 경우
       $this->db->where('StoryIdx', $story_idx);
       $this->db->limit(1);
       $result = $this->db->get('StoryPlace')->result();

       $this->db->select('*');
       $this->db->from('StoryPlace');
       $this->db->join('Place', 'Place.PlaceIdx = StoryPlace.PlaceIdx');
       $this->db->where('StoryIdx', $story_idx);
       $this->db->where('StoryPlaceDateNumber', $date_number);
       $this->db->where('StoryPlaceIdx', $result[0]->StoryPlaceIdx);
       return $this->db->get()->result();
     } else {
       // 들어와서 다른 장소를 클릭했을 경우
       $this->db->select('*');
       $this->db->from('StoryPlace');
       $this->db->join('Place', 'Place.PlaceIdx = StoryPlace.PlaceIdx');
       $this->db->where('StoryIdx', $story_idx);
       $this->db->where('StoryPlaceDateNumber', $date_number);
       $this->db->where('StoryPlaceIdx', $storyplace_idx);
     }
   }

   // 스토리의 특정 일자에 속하는 특정 장소에 대한 좋아요 정보를 얻어온다.
   public function get_story_by_place_good_info( $story_idx, $date_number, $storyplace_idx) {
     if (!$storyplace_idx) {
       // 처음 들어왔을 경우
       $this->db->where('StoryIdx', $story_idx);
       $this->db->limit(1);
       $result = $this->db->get('StoryPlace')->result();

       $this->db->from('StoryPlaceGood');
       $this->db->where('StoryPlaceIdx', $result[0]->StoryPlaceIdx);
       return $this->db->count_all_results();
     } else {
      // 들어와서 다른 장소를 클릭했을 경우
      $this->db->from('StoryPlaceGood');
      $this->db->where('StoryPlaceIdx', $storyplace_idx);
      return $this->db->count_all_results();
     }
   }

   // 스토리의 특정 일자에 속하는 특정 장소에 대한 현재 접속한 유저의 체크 유무 정보를 얻어온다.
   public function get_story_by_place_is_good_info( $storyplace_idx, $member_idx ) {
     $this->db->where('StoryPlaceIdx', $storyplace_idx);
     $this->db->where('MemberIdx', $member_idx);
     return $this->db->get('StoryPlaceGood')->result();
   }

   // 스토리의 특정 일자에 속하는 특정 장소에 대한 이미지 정보를 얻어온다.
   public function get_story_by_place_image_info( $story_idx, $storyplace_idx ) {
     if (!$storyplace_idx) {
       // 처음 들어왔을 경우
       $this->db->where('StoryIdx', $story_idx);
       $this->db->limit(1);
       $result = $this->db->get('StoryPlace')->result();

       $this->db->where('StoryIdx', $story_idx);
       $this->db->where('StoryPlaceIdx', $result[0]->StoryPlaceIdx);
       return $this->db->get('StoryPlaceImage')->result();
     } else {
       // 들어와서 다른 장소를 클릭했을 경우
       $this->db->where('StoryIdx', $story_idx);
       $this->db->where('StoryPlaceIdx', $storyplace_idx);
       return $this->db->get('StoryPlaceImage')->result();
     }
   }

   // 스토리의 특정 일자에 속하는 특정 장소에 대한 이미지의 댓글을 얻어온다.
   public function get_story_by_place_image_reply_info( $story_idx, $storyplace_idx ) {
     if (!$storyplace_idx) {
       // 처음 들어왔을 경우
       $this->db->where('StoryIdx', $story_idx);
       $this->db->limit(1);
       $result = $this->db->get('StoryPlace')->result();

       $this->db->where('StoryIdx', $story_idx);
       $this->db->where('StoryPlaceIdx', $result[0]->StoryPlaceIdx);
       $this->db->limit(1);
       $result = $this->db->get('StoryPlaceImage')->result();

       $this->db->select('*');
       $this->db->from('StoryPlaceImageMemo');
       $this->db->join('Member', 'Member.MemberIdx = StoryPlaceImageMemo.MemberIdx');
       $this->db->where('StoryPlaceImageIdx', $result[0]->StoryPlaceImageIdx);
       return $this->db->get()->result();
     } else {
       // 들어와서 다른 장소를 클릭했을 경우
       $this->db->where('StoryIdx', $story_idx);
       $this->db->where('StoryPlaceIdx', $storyplace_idx);
       $this->db->limit(1);
       $result = $this->db->get('StoryPlaceImage')->result();

       $this->db->where('StoryPlaceImageIdx', $result[0]->StoryPlaceImageIdx);
       return $this->db->get('StoryPlaceImageMemo')->result();
     }
   }
 }
