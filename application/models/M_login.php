<?php
class M_login extends CI_Model {

    public function __construct()
    {
            // Call the CI_Model constructor
            parent::__construct();
    }

    //게시글 입력
    public function logincheck($data) {
        $query = "SELECT 1 AS OK
            FROM member
            WHERE MEMBER_ID = ?
            AND MEMBER_PW = ?";

        $result=$this->db->query($query, array($data['id'], $data['pw']));
        return $result->row_array(); 
    }

}
?>        