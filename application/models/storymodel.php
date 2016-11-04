<?php
/* Story는 여행 계획 전에 생성할 수 있고 여행 중의 사진을 관리해주는 앨범 개념 */
class Storymodel extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function get_member_info( $member_idx ) {
        $this->db->where('MemberIdx', $member_idx);
        return $this->db->get('member')->result();
    }

    // Story Category All
    public function StoryCategoryAll() {
        $sql = "SELECT * FROM story Limit 20";

        return $this->db->query($sql)->result();
    }

    // Story Category Check
    public function StoryCategory( $latest, $late, $best, $month, $formation ) {
        if($latest == 1) {
            $sqlLatest = "ORDER BY StoryStartDate DESC";
        } else if($late == 1) {
            $sqlLatest = "ORDER BY StoryIdx DESC";
        } else {
            $sqlLatest = "";
        }

        if($month != 0) {
            $sqlMonth = "AND MONTH(StoryStartDate) like '$month'";
        /*} else if($season == 2) {
            $sqlSeason = "AND MONTH(StoryStartDate) in (6,7,8)";
        } else if($season == 3) {
            $sqlSeason = "AND MONTH(StoryStartDate) in (9,10,11)";
        } else if($season == 4) {
            $sqlSeason = "AND MONTH(StoryStartDate) in (12,1,2)";*/
        } else {
            $sqlMonth = "";
        }

        if($formation != 0) {
            $sqlFormation = "AND StoryFormation like '$formation'";
        } else {
            $sqlFormation = "";
        }
        if($best == 1) {
            if($latest == 1) {
                $sqlLatest = "GROUP BY sg.StoryIdx ORDER BY count(sg.StoryIdx) DESC, s.StoryStartDate DESC";
            } else if($late == 1) {
                $sqlLatest = "GROUP BY sg.StoryIdx ORDER BY count(sg.StoryIdx) DESC, s.StoryIdx DESC";
            } else {
                $sqlLatest = "GROUP BY sg.StoryIdx ORDER BY count(sg.StoryIdx) DESC";
            }

            if($month != 0) {
                $sqlMonth = "AND MONTH(s.StoryStartDate) like '$month'";
            /*} else if($season == 2) {
                $sqlSeason = "AND MONTH(s.StoryStartDate) in (6,7,8)";
            } else if($season == 3) {
                $sqlSeason = "AND MONTH(s.StoryStartDate) in (9,10,11)";
            } else if($season == 4) {
                $sqlSeason = "AND MONTH(s.StoryStartDate) in (12,1,2)";*/
            } else {
                $sqlMonth = "";
            }

            if($formation == 0) {
                $sqlFormation = "";
            } else {
                $sqlFormation = "AND s.StoryFormation like '$formation'";
            }

            $sql = "SELECT s.StoryIdx, s.StoryName, s.StoryRepresentImage, s.StoryRepresentImageExt, s.StoryStartDate, s.MemberIdx, s.StoryPublicCheck FROM story s, storygood sg WHERE s.StoryIdx = sg.StoryIdx AND s.StoryPublicCheck = '1'";
            $sql .= $sqlMonth . $sqlFormation . $sqlLatest . " Limit 20";


        } else {
            $sql = "SELECT * FROM story WHERE StoryPublicCheck = '1'";
            $sql .= $sqlMonth . $sqlFormation . $sqlLatest . " Limit 20";
        }

        return $this->db->query($sql)->result();
    }



    // My Story
    public function StorySelectMy( $values ) {
        $sql = "SELECT s.StoryIdx, s.StoryName, s.StoryRepresentImage, s.StoryRepresentImageExt, s.StoryStartDate, s.MemberIdx, s.StoryFormation, s.StoryPublicCheck, m.MemberNickname FROM story s, member m WHERE m.MemberIdx = s.MemberIdx AND (s.MemberIdx = $values OR s.StoryIdx = ANY(SELECT StoryIdx FROM companion WHERE MemberIdx = $values))";

        return $this->db->query($sql)->result();
    }

    // Count Story Good
    public function StoryCountGood() {
        $sql = "SELECT count(StoryIdx) countGood, StoryIdx FROM storygood GROUP BY StoryIdx";

        return $this->db->query($sql)->result();
    }


    // Story Share On
    public function StoryPublicOn( $values ) {
        $sql = "UPDATE story SET StoryPublicCheck = '1' WHERE StoryIdx = $values";

        $this->db->query($sql);
    }

    // Story Share Off
    public function StoryPublicOff( $values ) {
        $sql = "UPDATE story SET StoryPublicCheck = '0' WHERE StoryIdx = $values";

        $this->db->query($sql);
    }

    // Story Companion List
    public function StorySelectCompanion() {
        $sql = "SELECT m.MemberNickname, co.StoryIdx FROM member m, companion co WHERE co.MemberIdx = m.MemberIdx";

        return $this->db->query($sql)->result();
    }

    // -------------------------------------------------------- 용범's model ----------------------------------------------------------- //

    // StoryStart페이지의 해당 스토리의 정보출력
    // 매개변수 $StoryIdx : 해당 스토리 번호
    public function StoryStartSelect( $StoryIdx ) {
        $query = $this->db->query("SELECT * FROM story WHERE StoryIdx='{$StoryIdx}'");
        // row()함수는 쿼리 결과가 한줄일 때 사용한다. 여러줄이 결과로 반환될 시 첫줄만 반환
        return $query->row();
    }

    // StoryStart페이지의 해당 스토리의 동행자 출력
    // 매개변수 $StoryIdx : 해당 스토리 번호
    public function StoryCompanionSelect( $StoryIdx ) {
        $query = $this->db->query(
            "SELECT M.MemberIdx,
                          M.MemberNickname,
                          M.MemberProfile,
                          M.MemberProfileExt
                          FROM member M,
                               companion C
                               WHERE C.StoryIdx=$StoryIdx
                                     &&
                                     M.MemberIdx=C.MemberIdx"
        );
        // result()함수는 쿼리 결과가 여러줄 일때 사용한다. 레코드 출력시 foreach문 사용
        $Companions['result']=$query->result();
        // num_rows()함수는 쿼리 결과의 갯수를 반환한다.
        $Companions['num_rows']=$query->num_rows();
        return $Companions;
    }

    // StoryStart페이지의 해당 스토리의 일정 출력
    // 매개변수 $StoryIdx : 해당 스토리 번호, 해당 스토리의 해당 일차수
    public function StoryPlaceSelect( $StoryIdx , $StoryPlaceDateNumber ) {
        if($StoryPlaceDateNumber!=0){
            $query = $this->db->query(
                "SELECT SP.StoryPlaceIdx,
                          SP.StoryIdx,
                          SP.PlaceIdx,
                          SP.StoryPlaceDate,
                          SP.StoryPlaceDateNumber,
                          SP.StoryPlaceStartTime,
                          SP.StoryPlaceMemo,
                          P.PlaceName,
                          P.PlaceTel,
                          P.PlaceExplain,
                          P.PlaceLatitude,
                          P.PlaceLongtitude
                          FROM storyplace SP,
                               place P
                               WHERE SP.StoryIdx=$StoryIdx
                                     &&
                                     SP.PlaceIdx=P.PlaceIdx
                                     &&
                                     SP.StoryPlaceDateNumber=$StoryPlaceDateNumber
                                     ORDER BY SP.StoryPlaceStartTime ASC
                                     "
            );
        } else {
            $query = $this->db->query(
                "SELECT SP.StoryPlaceIdx,
                          SP.StoryIdx,
                          SP.PlaceIdx,
                          SP.StoryPlaceDate,
                          SP.StoryPlaceDateNumber,
                          SP.StoryPlaceStartTime,
                          SP.StoryPlaceMemo,
                          P.PlaceName,
                          P.PlaceTel,
                          P.PlaceExplain,
                          P.PlaceLatitude,
                          P.PlaceLongtitude
                          FROM storyplace SP,
                               place P
                               WHERE SP.StoryIdx=$StoryIdx
                                     &&
                                     SP.PlaceIdx=P.PlaceIdx
                                     ORDER BY SP.StoryPlaceStartTime ASC
                                     ");
        }

        $Places['result']=$query->result();
        return $Places;
    }

    // StoryStart페이지의 해당 스토리의 일정 차수 가장 작은 수와 큰 수 출력
    // 매개변수 $StoryIdx : 해당 스토리 번호
    public function StoryPlaceStoryPlaceDateNumberMaxAndMinSelect( $StoryIdx ){
        $query = $this->db->query(
            "SELECT MAX(StoryPlaceDateNumber) StoryPlaceMaxDateNumber,
                          MIN(StoryPlaceDateNumber) StoryPlaceMinDateNumber
                          FROM storyplace
                               WHERE StoryIdx=$StoryIdx"
        );

        $MaxMinPlaceDateNumber['result'] = $query->row();
        return $MaxMinPlaceDateNumber;
    }

    // StoryStart페이지의 해당 스토리의 준비물 출력
    // 매개변수 $StoryIdx : 해당 스토리 번호, 로그인 사용자의 번호
    public function StoryMaterialSelect( $StoryIdx, $LoginMemberIdx ) {
        $query = $this->db->query(
            "SELECT *
                          FROM material
                               WHERE StoryIdx=$StoryIdx
                                     &&
                                     MemberIdx=$LoginMemberIdx"
        );

        $Materials['result']=$query->result();
        $Materials['num_rows']=$query->num_rows();
        return $Materials;
    }

    public function StoryLastDateSelect($StoryIdx,$MaxPlaceDateNumber){
        // var_dump($MaxPlaceDateNumber);
        $query = $this->db->query(
            "SELECT date_add(StoryStartDate, interval $MaxPlaceDateNumber day) LastDate FROM story WHERE StoryIdx = $StoryIdx"
        );
        $StoryLastDate['result']=$query->row();
        return $StoryLastDate;
    }

    // -------------------------------------------------------- 용범's model 끝----------------------------------------------------------- //
}
