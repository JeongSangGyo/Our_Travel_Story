<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class ourtravelstory_m extends CI_Model{
    function __construct()
    {
        parent::__construct();
    }

    function StoryPlaceIdxCheck($storyIdx){
        $sql = "SELECT StoryPlaceIdx FROM storyplace WHERE StoryIdx =$storyIdx order by `StoryPlaceDateNumber` ASC,`StoryPlaceStartTime`";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }
    //
    function get_storyMaxData($storyIdx)
    {
        $sql = "SELECT MAX(StoryPlaceDateNumber) StoryMaxData FROM storyplace WHERE storyIdx = $storyIdx";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }
    //
    function StoryPlaceInfo($storyIdx,$placeDate,$storyplaceIdx)
    {
        $sql = "SELECT sp.storyplaceIdx, sp.placeIdx, p.placeName, p.placeTel, p.placeNation, p.placeRegion, p.placeExplain, p.placeFootprint, p.placeLatitude, p.placeLongtitude FROM place p,storyplace sp WHERE sp.placeIdx = p.placeIdx AND sp.storyPlaceDateNumber = $placeDate AND sp.storyIdx = $storyIdx AND sp.storyplaceIdx = $storyplaceIdx ORDER BY storyPlaceIdx";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }
    //
    function get_StoryPlaceName($storyIdx,$placeDate){
        $sql = "SELECT sp.storyPlaceDateNumber, sp.storyplaceIdx, sp.placeIdx, p.placeName, p.placeTel, p.placeNation, p.placeRegion, p.placeExplain, p.placeFootprint, sp.storyplacememo FROM place p,storyplace sp WHERE sp.placeIdx = p.placeIdx AND sp.storyPlaceDateNumber = $placeDate AND sp.storyIdx = $storyIdx order by `StoryPlaceDateNumber` ASC,`StoryPlaceStartTime`";
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
        $sql = "SELECT m.MemberNickname,spr.StoryPlaceReplyContent,spr.StoryPlaceReplyRegistTime FROM storyplacereply spr,member m WHERE spr.MemberIdx = m.MemberIdx AND StoryPlaceIdx = $storyplaceIdx ORDER BY StoryPlaceReplyIdx DESC; ";
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
        $sql = "SELECT * FROM storyplace WHERE StoryIdx = $storyIdx ORDER BY `StoryPlaceDateNumber`,`StoryPlaceStartTime";
        
        return $this->db->query($sql)->result();
    }
    //
    public function StoryInfo($storyIdx) {
        $sql = "SELECT * FROM story WHERE StoryIdx = $storyIdx";
        
        return $this->db->query($sql)->result();
    }

    //
    function get_storyPlaceMemo($storyidx,$storyplaceimageidx){
        $sql = "select spim.StoryPlaceImageMemo,m.MemberNickname from storyplaceimagememo spim,member m where m.MemberIdx = spim.MemberIdx AND spim.StoryIdx = $storyidx AND spim.StoryPlaceImageIdx=$storyplaceimageidx  ORDER BY StoryPlaceImageMemoIdx DESC";
        
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
    function get_storyPlaceReplyInsert($user_id,$reply_content,$place_id){
        $sql = "INSERT INTO storyplacereply (MemberIdx,StoryPlaceReplyContent,StoryPlaceReplyRegistTime,StoryPlaceIdx) VALUES ($user_id,$reply_content,'2016-06-02',$place_id)";
        $this->db->query($sql);
    }
    //
    function get_storyImageReplyInsert($user_id,$reply_content,$image_id,$story_id){
        $sql = "INSERT INTO storyplaceimagememo (StoryPlaceImageIdx ,StoryIdx ,MemberIdx ,StoryPlaceImageMemo) VALUES ($image_id,$story_id,$user_id,$reply_content)";
        $this->db->query($sql);
    }

}