<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Ourtravelstory_m extends CI_Model{
    function __construct()
    {
        parent::__construct();
    }
    
    // 해당 스토리 정보
    function get_StoryInfo($storyIdx){
        $sql = "SELECT s.storyName, s.storyPublicCheck, s.storyRepresentImage, s.storyRepresentImageExt, s.storyStartDate, s.storyformation, m.memberNickname FROM story s,member m WHERE m.memberIdx = s.memberIdx AND s.storyIdx = $storyIdx";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
        
    }
    //스토리별 동행자
    function get_StoryCompanion($storyIdx){
        $sql = "SELECT m.memberNickname, m.memberProfile, m.memberProfileExt, m.memberEmail, m.memberNickname, m.memberJoindate FROM member m,companion c WHERE m.memberIdx = c.memberIdx AND storyIdx = $storyIdx";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }
    //스토리장소별 정보
    function get_StoryPlaceInfo($storyIdx,$storyDate){
        $sql = "SELECT s.storyPlaceIdx, s.storyPlaceStartTime, s.storyPlaceEndTime, s.storyPlaceDateNumber, p.placeIdx, p.placeName, p.placeExplain, p.placeLatitude, p.placeLongtitude, p.placeNation, p.placeTel, pi.PlaceImageName, pi.PlaceImageExt from placeimage pi, storyplace s, place p WHERE storyIdx = $storyIdx AND s.StoryPlaceDateNumber = $storyDate AND s.placeIdx = p.placeIdx AND p.placeIdx = pi.placeIdx GROUP BY s.placeIdx ORDER BY s.StoryPlaceStartTime";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }
    //스토리 일차
    function get_StoryDate($storyIdx){
        $sql = "SELECT MAX(storyPlaceDateNumber) storyDate, storyIdx FROM storyplace WHERE storyIdx = $storyIdx";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }
    //스토리 좋아요 수
    function get_StoryGood($storyIdx){
        $sql = "SELECT COUNT(storyIdx) storyGoodCount FROM storygood WHERE storyIdx = $storyIdx";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }
    //스토리 슬라이드쇼 이미지 정보
    function get_StorySliderImage($storyIdx){
        $sql = "SELECT storyPlaceImageName, storyPlaceImageExt FROM storyplaceimage WHERE storyIdx = $storyIdx";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }
    //스토리 댓글 정보
    function get_StoryReply($storyIdx){
        $sql = "SELECT sr.storyReplyContent, sr.storyReplyRegistTime, m.memberNickname FROM storyreply sr, member m WHERE sr.memberIdx = m.memberIdx AND storyIdx = $storyIdx";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }
    //스토리 댓글 입력
    function get_StoryReplyInsert($user_id,$reply_content,$story_id){
        $sql = "INSERT INTO storyreply (MemberIdx,StoryReplyContent,StoryReplyRegistTime,StoryIdx) VALUES ($user_id,$reply_content,'2016-06-02',$story_id)";
        $this->db->query($sql);
    }
    


    function StoryPlaceIdxCheck($storyIdx,$storyDate){
        $sql = "SELECT StoryPlaceIdx FROM storyplace WHERE StoryIdx = $storyIdx AND StoryPlaceDateNumber = $storyDate ORDER BY StoryPlaceStartTime";
        $query = $this->db->query($sql); 
        $result = $query->result();

        return $result;
    }
    //
    function get_storyData($storyIdx)
    {
        $sql = "SELECT StoryPlaceDateNumber FROM storyplace WHERE storyIdx = $storyIdx";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }
    //
    function StoryPlaceInfo($storyIdx,$placeDate,$storyplaceIdx)
    {
        $sql = "SELECT sp.storyplaceIdx, sp.placeIdx, p.placeName, p.placeTel, p.placeNation, p.placeRegion, p.placeExplain, p.placeFootprint, p.placeLatitude, p.placeLongtitude FROM place p,storyplace sp WHERE sp.placeIdx = p.placeIdx AND sp.storyPlaceDateNumber = $placeDate AND sp.storyIdx = $storyIdx AND sp.storyplaceIdx = $storyplaceIdx ORDER BY sp.storyPlaceIdx asc";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }
    //
    function get_StoryPlaceName($storyIdx,$placeDate){
        $sql = "SELECT sp.storyPlaceDateNumber, sp.storyplaceIdx, sp.placeIdx, p.placeName, p.placeTel, p.placeNation, p.placeRegion, p.placeExplain, p.placeFootprint, sp.storyplacememo FROM place p,storyplace sp WHERE sp.placeIdx = p.placeIdx AND sp.storyPlaceDateNumber = $placeDate AND sp.storyIdx = $storyIdx ORDER BY sp.StoryPlaceStartTime  ASC";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }
    //
    function get_StoryPlaceGoodCount($storyplaceIdx){
        $sql = "SELECT COUNT(StoryPlaceIdx) StoryPlaceGoodCount FROM storyplacegood WHERE StoryPlaceIdx = $storyplaceIdx";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }
    //
    function get_StoryPlaceReply($storyplaceIdx){
        $sql = "SELECT m.MemberNickname,spr.StoryPlaceReplyContent,spr.StoryPlaceReplyRegistTime FROM storyplacereply spr,member m WHERE spr.MemberIdx = m.MemberIdx AND StoryPlaceIdx = $storyplaceIdx ORDER BY spr.StoryPlaceReplyIdx DESC; ";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }
    //
    function StoryPlaceImage($storyIdx, $storyplaceIdx) {
        $sql = "SELECT * FROM storyplaceimage WHERE StoryIdx = $storyIdx AND StoryPlaceIdx = $storyplaceIdx";

        return $this->db->query($sql)->result();
    }
    //
    public function StoryPlace($storyIdx) {
        $sql = "SELECT * FROM storyplace WHERE StoryIdx = $storyIdx";

        return $this->db->query($sql)->result();
    }
    //
    public function StoryInfo($storyIdx) {
        $sql = "SELECT * FROM story WHERE StoryIdx = $storyIdx";

        return $this->db->query($sql)->result();
    }

    //
    function get_storyPlaceMemo($storyidx,$storyplaceimageidx){
        $sql = "SELECT spim.StoryPlaceImageMemo,m.MemberNickname from storyplaceimagememo spim,member m where m.MemberIdx = spim.MemberIdx AND spim.StoryIdx = $storyidx AND spim.StoryPlaceImageIdx=$storyplaceimageidx  ORDER BY spim.StoryPlaceImageMemoIdx DESC";

        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }
    //
    function get_storyPlaceSelectImage($storyplaceimageidx){
        $sql = "SELECT StoryPlaceImageName,StoryPlaceImageExt FROM storyplaceimage WHERE StoryPlaceImageIdx =$storyplaceimageidx";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }
    //
    function get_storyPlaceBookmarkCheck($user_idx,$place_idx){
        $sql = "SELECT * FROM placemybookmark where MemberIdx = $user_idx AND PlaceIdx = $place_idx";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }
    //
    function get_storyPlaceBookmarkDelete($user_idx,$place_idx){
        $sql = "DELETE FROM placemybookmark where MemberIdx = $user_idx AND PlaceIdx = $place_idx";
        $this->db->query($sql);
    }
    //
    function get_storyPlaceBookmarkInsert($user_idx,$place_idx){
        $sql = "INSERT INTO placemybookmark (MemberIdx,PlaceIdx) VALUES ($user_idx,$place_idx)";
        $this->db->query($sql);
    }
    //
    function get_storyPlaceGoodAdd($user_idx,$place_idx){
        $sql = "INSERT INTO storyplacegood (MemberIdx,StoryPlaceIdx) VALUES ($user_idx,$place_idx)";
        $this->db->query($sql);
    }
    //
    function get_storyPlaceGoodDelete($user_idx,$place_idx){
        $sql = "DELETE FROM storyplacegood WHERE MemberIdx = $user_idx AND StoryPlaceIdx = $place_idx";
        $this->db->query($sql);
    }
    //
    function get_StoryPlaceReplyInsert($user_id,$reply_content,$place_id){
        $sql = "INSERT INTO storyplacereply (MemberIdx,StoryPlaceReplyContent,StoryPlaceReplyRegistTime,StoryPlaceIdx) VALUES ($user_id,$reply_content,'2016-06-02',$place_id)";
        $this->db->query($sql);
    }
    //
    function get_storyImageReplyInsert($user_id,$reply_content,$image_id,$story_id){
        $sql = "INSERT INTO storyplaceimagememo (StoryPlaceImageIdx ,StoryIdx ,MemberIdx ,StoryPlaceImageMemo) VALUES ($image_id,$story_id,$user_id,$reply_content)";
        $this->db->query($sql);
    }
    //
    function get_storyMaxData($storyIdx)
    {
        $sql = "SELECT MAX(StoryPlaceDateNumber) StoryMaxData FROM storyplace WHERE storyIdx = $storyIdx";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }
    function get_storyDateFirstPlace($storyIdx, $storyDate)
    {
        $sql = "SELECT storyPlaceIdx FROM storyplace WHERE storyIdx = $storyIdx AND StoryPlaceDateNumber = $storyDate ORDER BY StoryPlaceStartTime";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }
}
