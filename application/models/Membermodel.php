<?php
class Membermodel extends CI_Model {
    function __construct()
    {
        parent::__construct();

    }

    // 닉네임 중복확인
    public function MemberNicknameCheck( $values ) {
        return $this->db->get_where('member', array('MemberNickname' => $values))->num_rows();
    }

    // 이메일 중복확인
    public function MemberEmailCheck( $values ){
        return $this->db->get_where('member', array('MemberEmail' => $values))->num_rows();
    }

    // 이메일 인증(해당 회원번호)
    public function MemberConfirmEmail( $values ) {
        $sql = "SELECT MemberIdx FROM member WHERE MemberEmail = '{$values}'";
        return $this->db->query($sql)->row_array();
    }

    // 이메일 인증처리
    public function MemberConfirmUpdate( $userIdx ) {
        $sql = "UPDATE member SET MemberEmailCheck = '1' WHERE MemberIdx = $userIdx";
        return $this->db->query($sql);
    }

    // 회원가입
    public function MemberInsertJoin( $values ) {
        $sql = "INSERT INTO member VALUES ('','{$values['MemberJoinEmail']}', '{$values['MemberJoinPassword']}', '{$values['MemberJoinNickname']}', '', '', CURRENT_DATE(), '0')";
        return $this->db->query($sql);
    }

    // 이미지 업로드를 위한 함수
    public function MemberUpdateJoin( $values ) {
        $sql = "UPDATE member SET MemberProfile = '{$values['MemberJoinProfile']}', MemberProfileExt = '{$values['MemberJoinProfileExt']}' WHERE MemberIdx = {$values['MemberIdx']}";
        $this->db->query($sql);
    }

    // 로그인
    public function MemberLogin( $values ) {
        return $this->db->get_where('member', array('MemberEmail' => $values['MemberLoginEmail'], 'MemberPassword' => $values['MemberLoginPassword'], 'MemberEmailCheck' => '1'))->row_array();
    }

    // 회원정보 수정
    public function MemberUpdate( $values ) {
        $sql = "UPDATE member SET MemberPassword = '{$values['MemberUpdatePassword']}' WHERE MemberIdx = {$values['MemberIdx']}";   //, MemberNickname = '{$values['MemberUpdateNickname']}'
        return $this->db->query($sql);
    }

    // 회원 삭제
    public function MemberDelete( $values ) {
        $sql = "DELETE FROM member WHERE MemberIdx = $values";
        $this->db->query($sql);
    }
}
