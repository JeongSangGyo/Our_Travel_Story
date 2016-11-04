<?php
class Advmodel extends CI_Model
{
    function __construct()
    {
        parent::__construct();


    }

    // 신청된 광고를 등록한다
    function add_adv( $AdvInfo ) {
        $sql = "INSERT INTO advsetting VALUES('', {$AdvInfo['member_idx']}, '{$AdvInfo['adv_name']}', '{$AdvInfo['adv_lat']}', '{$AdvInfo['adv_lng']}', '{$AdvInfo['adv_address']}', {$AdvInfo['adv_radius']}, '{$AdvInfo['adv_image']}', '{$AdvInfo['adv_imageExt']}', '{$AdvInfo['adv_comment']}')";

        $this->db->query($sql);
    }

    // 자신의 위치가 해당 위치의 반경 안에 들어오면 광고를 보낸다
    function push_adv() {
        /*
        SELECT 가져올컬럼,

	    (6371*acos(cos(radians(lat좌표값))*cos(radians(slLat))*cos(radians(slLng)

	    -radians(lng좌표값))+sin(radians(lat좌표값))*sin(radians(slLat))))

	    AS distance

        FROM 대상테이블

        HAVING distance <= 거리   1 = 1km

        ORDER BY distance

        LIMIT 0,1000
        */
    }

    // 앱이 실행될 때 보내줄 위치정보
    function init_location() {
        $sql = "SELECT AdvIdx, AdvLat, AdvLng, AdvRadius FROM advsetting";

        return $this->db->query($sql)->result();
    }

    // 반경 내에 들어온 사용자에게 보내줄 광고 상세정보
    function adv_info( $AdvIdx ) {
        $sql = "SELECT * FROM advsetting WHERE AdvIdx = $AdvIdx";

        return $this->db->query($sql)->result();
    }



}
