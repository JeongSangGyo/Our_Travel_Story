<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plan_model extends CI_Model {
   function __construct() {
     parent::__construct();
   }

   // 새로운 스토리 추가
   public function input_new_story( $create_story_info ) {
     $this->db->insert('story', $create_story_info);
     return $this->db->insert_id();
   }

   // 새로운 동행자 추가
   public function input_new_companion( $companion_info ) {
     return $this->db->insert('companion', $companion_info);
   }

   public function set_story_start_date( $story_idx, $story_start_date ) {
     $sql = "UPDATE story SET StoryStartDate = '$story_start_date' WHERE StoryIdx = $story_idx";
     $this->db->query($sql);
   }

   public function set_story_event_date( $story_idx, $story_start_date_gap ) {
     $date_gap = (int) $story_start_date_gap;
     if ($date_gap < 0) {
       $temp = abs($date_gap);
       $sql = "UPDATE storyplace SET StoryPlaceStartTime = StoryPlaceStartTime - interval $temp day WHERE StoryIdx = $story_idx";
       $this->db->query($sql);

       $sql = "UPDATE storyplace SET StoryPlaceEndTime = StoryPlaceEndTime - interval $temp day WHERE StoryIdx = $story_idx";
       $this->db->query($sql);

       $sql = "UPDATE storyplace SET StoryPlaceDate = StoryPlaceDate - interval $temp day WHERE StoryIdx = $story_idx";
       return $this->db->query($sql);
     } else {
       $temp = abs($date_gap);
       $sql = "UPDATE storyplace SET StoryPlaceStartTime = StoryPlaceStartTime + interval $temp day WHERE StoryIdx = $story_idx";
       $this->db->query($sql);

       $sql = "UPDATE storyplace SET StoryPlaceEndTime = StoryPlaceEndTime + interval $temp day WHERE StoryIdx = $story_idx";
       $this->db->query($sql);

       $sql = "UPDATE storyplace SET StoryPlaceDate = StoryPlaceDate + interval $temp day WHERE StoryIdx = $story_idx";
       return $this->db->query($sql);
     }
   }

   // 새로운 장소 추가
   public function input_new_place( $place_info ) {
     $this->db->insert('place', $place_info);
     return $this->db->insert_id();
   }

   // 장소에 대한 이미지 추가
   public function set_place_image( $place_image_info ) {
      $this->db->insert('placeimage', $place_image_info);
   }

   // 장소명 중복 체크
   public function is_place_name( $place_name ) {
      return $this->db->get_where('place', array('PlaceName' => $place_name))->result();
   }

   // 카테고리 명으로 해당 카테고리의 Idx를 리턴
   public function get_place_category_idx( $category_name ) {
     $sql = "SELECT CategoryPlaceIdx FROM categoryplace WHERE CategoryPlaceName like '$category_name'";
     return $this->db->query($sql)->result();
   }

   // 카테고리를 기준으로 장소 검색
   public function get_place_category_search( $search_place_category ) {
     if (!$search_place_category) {
       $search_place = $this->db->get('place')->result();
     } else {
       $this->db->where('CategoryPlaceIdx', $search_place_category);
       $search_place = $this->db->get('place')->result();
     }

     return $this->get_image_info($search_place);
   }

   // 국가를 기준으로 장소 검색
   public function get_place_nation_search( $search_place_nation, $search_place_category ) {
     if (!$search_place_category) {
       $this->db->like('PlaceNation', $search_place_nation);
       $search_place = $this->db->get('place')->result();
     } else {
       $this->db->like('PlaceNation', $search_place_nation);
       $this->db->where('CategoryPlaceIdx', $search_place_category);
       $search_place = $this->db->get('place')->result();
     }

     return $this->get_image_info($search_place);
   }

   // 지역을 기준으로 장소 검색
   public function get_place_region_search( $search_place_region, $search_place_category ) {
     if (!$search_place_category) {
       $this->db->like('PlaceRegion', $search_place_region);
       $search_place = $this->db->get('place')->result();
     } else {
       $this->db->like('PlaceRegion', $search_place_region);
       $this->db->where('CategoryPlaceIdx', $search_place_category);
       $search_place = $this->db->get('place')->result();
     }

     return $this->get_image_info($search_place);
   }

   // 이름을 기준으로 장소 검색
   public function get_place_name_search( $search_place_name, $search_place_category ) {
     if (!$search_place_category) {
       $this->db->like('PlaceName', $search_place_name);
       $search_place = $this->db->get('place')->result();
     } else {
       $sql = "SELECT * FROM place WHERE PlaceName like '%$search_place_name%' AND CategoryPlaceIdx = $search_place_category";
       $this->db->like('PlaceName', $search_place_name);
       $this->db->where('CategoryPlaceIdx', $search_place_category);
       $search_place = $this->db->get('place')->result();
     }

     return $this->get_image_info($search_place);
   }

   // 국가나 지역, 이름을 기준으로 장소 검색
   public function get_place_whole_search( $search_place_nation_region, $search_place_name, $search_place_category ) {
     $place_nation_region = $search_place_nation_region;
     $place_name = $search_place_name;

     $place_nation_region_cnt = strlen($place_nation_region);

     if ($place_nation_region_cnt == 2) {
       // 국가로 검색했다면
       if (!$search_place_category) {
         $this->db->like('PlaceNation', $place_nation_region);
         $this->db->like('PlaceName', $place_name);
         $search_place = $this->db->get('place')->result();
       } else {
         $this->db->like('PlaceNation', $place_nation_region);
         $this->db->like('PlaceName', $place_name);
         $this->db->where('CategoryPlaceIdx', $search_place_category);
         $search_place = $this->db->get('place')->result();
       }
     } else {
       // 지역으로 검색했다면
       if (!$search_place_category) {
         $this->db->like('PlaceRegion', $place_nation_region);
         $this->db->like('PlaceName', $place_name);
         $search_place = $this->db->get('place')->result();
       } else {
         $this->db->like('PlaceRegion', $place_nation_region);
         $this->db->like('PlaceName', $place_name);
         $this->db->where('CategoryPlaceIdx', $search_place_category);
         $search_place = $this->db->get('place')->result();
       }
     }
     
     return $this->get_image_info($search_place);
   }

   public function get_image_info( $search_place ) {
     $cnt = count($search_place);
     for($iCount = 0; $iCount < $cnt; $iCount++) {
       $temp_place_idx = $search_place[$iCount]->PlaceIdx;
       $this->db->where('PlaceIdx', $temp_place_idx);
       $temp_search_place_images = $this->db->get('placeimage')->result();
       $search_place[$iCount]->PlaceImages = $temp_search_place_images;
     }

     return $search_place;
   }

   /* -------------------- 준비물 시작 ------------------ */

   // 나의 준비물 검색
   public function get_my_material( $story_idx, $member_idx ) {
     $sql = "SELECT StoryIdx, MaterialCategoryIdx, MaterialName FROM material WHERE MemberIdx = $member_idx AND StoryIdx = $story_idx";
     $story_material = $this->db->query($sql)->result();

     $sql = "SELECT distinct MaterialName, StoryIdx, MaterialCategoryIdx FROM material WHERE MemberIdx = $member_idx AND StoryIdx != $story_idx AND MaterialCategoryIdx = 0 GROUP BY MaterialName";
     $recommend_material = $this->db->query($sql)->result();

     if ($story_material) {
       foreach($story_material as $row_story) {
         foreach($recommend_material as $row_recommend) {
           if (strcmp($row_story->MaterialName, $row_recommend->MaterialName) == -1) {
             $row_recommend->MaterialCategoryIdx = '-1';
             $story_material[] = $row_recommend;
             break;
           }
         }
       }
       return $story_material;
     } else {
       foreach($recommend_material as $row_recommend) {
         $row_recommend->MaterialCategoryIdx = '-1';
       }
       return $recommend_material;
     }
   }

   // 준비물의 카테고리 idx 반환
   public function get_material_category_idx( $material_category ) {
     $sql = "SELECT MaterialCategoryIdx FROM materialcategory WHERE MaterialCategoryName like '$material_category'";
     return $this->db->query($sql)->result();
   }

   // 나의 준비물 추가
   public function add_my_material( $add_material_info ) {
     return $this->db->insert('material', $add_material_info);
   }

   // 나의 준비물 제거
   public function delete_my_material( $delete_material_info ) {
     return $this->db->delete('material', $delete_material_info);
   }

   /* -------------------- 준비물 끝 ------------------ */

   /* -------------------- 동행자 시작 ------------------ */

   // 특정 스토리에 속한 동행자들의 정보를 받아온다.
   public function get_story_companion( $story_idx ) {
     $this->db->select('*');
     $this->db->from('companion');
     $this->db->join('member', 'member.MemberIdx = companion.MemberIdx');
     $this->db->where('StoryIdx', $story_idx);
     return $this->db->get()->result();
   }

   // 실시간으로 검색한 문자열과 일치하는 회원 정보를 받아온다.
   public function get_realtime_companion( $story_idx, $companion_nickname ) {
     $sql = "SELECT MemberNickname
                    FROM member
                    WHERE MemberIdx not in (SELECT m.MemberIdx
                                                   FROM member m, companion c
                                                   WHERE m.MemberIdx = c.MemberIdx
                                                   AND c.StoryIdx = $story_idx)
                    AND MemberNickname like '%$companion_nickname%'
                    ORDER BY MemberIdx DESC
                    limit 0, 5";
     return $this->db->query($sql)->result();
   }

   // 확실하게 검색된 회원의 정보를 받아온다.
   public function get_realtime_companion_result( $companion_nickname ) {
     $this->db->like('MemberNickname', $companion_nickname, 'none');
     return $this->db->get('member')->result();
   }

   // 검색된 회원을 클릭했을 때, 해당 회원의 정보를 받아온다.
   public function get_click_member_info( $member_nickname ) {
     $this->db->where('MemberNickname', $member_nickname);
     return $this->db->get('member')->result();
   }

   // 검색된 회원 중에 추가 버튼을 눌렀을 때, 해당 스토리에 동행자로 추가해준다.
   public function add_request_companion( $story_idx, $member_nickname ) {
     $this->db->select('MemberIdx');
     $this->db->from('member');
     $this->db->like('MemberNickname', $member_nickname, 'none');
     $result = $this->db->get()->result();

     $data = array(
       'StoryIdx' => $story_idx,
       'MemberIdx' => $result[0]->MemberIdx,
       'CompanionJoinCheck' => '1'
     );

     $this->db->insert('companion', $data);
   }

   /* -------------------- 동행자 끝 ------------------ */

   // 스토리 정보 검색
   public function get_story( $story_idx ) {
     $sql = "SELECT * FROM story WHERE StoryIdx = $story_idx";
     return $this->db->query($sql)->result();
   }

   // 스토리 계획에 추가된 장소 검색
   public function get_story_place( $story_idx ) {
     $sql = "SELECT * FROM storyplace sp, place p WHERE sp.PlaceIdx = p.PlaceIdx AND sp.StoryIdx = $story_idx ORDER BY sp.StoryPlaceStartTime";
     return $this->db->query($sql)->result();
   }

   // 스토리에서 특정 날자를 눌렀을 때 넘겨줄 장소 정보
   public function get_specific_date_story_place( $story_idx, $story_date ) {
     $sql = "SELECT PlaceIdx FROM storyplace WHERE StoryIdx = $story_idx AND StoryPlaceDate like '$story_date'";
     $specific_date_story_place = $this->db->query($sql)->result();

     $result = [];
     $iCount = 0;
     foreach ($specific_date_story_place as $row) {
       $sql = "SELECT * FROM storyplace sp, place p WHERE sp.PlaceIdx = p.PlaceIdx AND p.PlaceIdx = $row->PlaceIdx ORDER BY sp.StoryPlaceStartTime";
       $specific_place_info = $this->db->query($sql)->result();
       $result[$iCount] = $specific_place_info[0];
       $iCount++;
     }

     return $result;
   }

   //
   public function get_story_place_date_number( $add_story_idx, $add_place_date ) {
     $this->db->select('StoryStartDate');
     $this->db->from('story');
     $this->db->where('StoryIdx', $add_story_idx);
     $result = $this->db->get()->result();
     $story_start_date = (string) $result[0]->StoryStartDate;

     $sql = "SELECT TO_DAYS('$add_place_date') - TO_DAYS('$story_start_date') + 1 as date_time";
     return $this->db->query($sql)->result();
   }

   // 스토리 계획에 장소 추가
   public function add_story_place( $add_story_place_info ) {
     $this->db->insert('storyplace', $add_story_place_info);
     $story_place_idx = $this->db->insert_id();

     $this->db->select('*');
     $this->db->from('storyplace');
     $this->db->join('place', 'place.PlaceIdx = storyplace.PlaceIdx');
     $this->db->where('storyplace.StoryPlaceIdx', $story_place_idx);
     return $this->db->get()->result();
   }

   // 스토리 계획에 장소 제거
   public function del_story_place( $del_story_place_info ) {
     return $this->db->delete('storyplace', $del_story_place_info);
   }

   // 특정 계획 시간 수정
   public function set_story_place( $update_condition, $set_story_place_info ) {
     return $this->db->update('storyplace', $set_story_place_info, $update_condition);
   }

   // 공유된 모든 장소 검색
   public function get_story_whole_bookmark_place( $story_idx ) {
     $this->db->select('*');
     $this->db->from('placebookmark');
     $this->db->join('place', 'place.PlaceIdx = placebookmark.PlaceIdx');
     $this->db->where('StoryIdx', $story_idx);
     return $this->db->get()->result();
   }

   // 공유된 장소 추가
   public function add_story_bookmark_place( $add_story_bookmark_place_info ) {
     return $this->db->insert('placebookmark', $add_story_bookmark_place_info);
   }

   // 공유된 장소 삭제
   public function del_story_bookmark_place( $del_story_bookmark_place_info ) {
     return $this->db->delete('placebookmark', $del_story_bookmark_place_info);
   }

   // 공유된 특정 장소 검색
   public function get_story_share_place( $place_idx ) {
     $sql = "SELECT * FROM place WHERE PlaceIdx = $place_idx";
     return $this->db->query($sql)->result();
   }

   /* 장소의 자세한 정보 */
   public function get_detail_place_info( $place_idx ) {
     $this->db->select('*');
     $this->db->from('place');
     $this->db->join('member', 'member.MemberIdx = place.MemberIdx');
     $this->db->where('PlaceIdx', $place_idx);
     return $this->db->get()->result();
   }

   // 장소에 등록된 이미지들 검색
   public function get_detail_place_images_info( $place_idx ) {
     $this->db->where('PlaceIdx', $place_idx);
     return $this->db->get('placeimage')->result();
   }

   public function get_detail_place_story_info( $place_idx ) {
     $this->db->select('*');
     $this->db->from('storyplace');
     $this->db->join('place', 'place.PlaceIdx = storyplace.PlaceIdx');
     $this->db->where('storyplace.PlaceIdx', $place_idx);
     return $this->db->get()->result();
   }

   /* 장소의 자세한 정보 끝 */
 }
