<?php
class M_board extends CI_Model {

    public function __construct()
    {
            // Call the CI_Model constructor
            parent::__construct();
    }

    //게시글 입력
    public function _dbInsert($data) {
        $this->db->set('TITLE', $data['TITLE']);
        $this->db->set('CONTENTS', $data['CONTENTS']);
        $this->db->set('PW', $data['PW']);
        $this->db->set('WRITER', $data['WRITER']);
        $this->db->set('IP', $data['IP']);
        $this->db->set('IMAGE_ID', $data['IMAGE_ID']);
        $this->db->set('DATE', "now()", false); //false라서 문자열이 아니고 함수
        $this->db->insert('board');

    }

    //게시글 수정
    public function _dbUpdate($data) {
        //update board set title='title' where no='no'
        $this->db->set('TITLE', $data['TITLE']);
        $this->db->set('CONTENTS', $data['CONTENTS']);
        $this->db->set('PW', $data['PW']);
        $this->db->set('WRITER', $data['WRITER']);
        $this->db->set('IP', $data['IP']);
        $this->db->set('IMAGE_ID', $data['IMAGE_ID']);
        $this->db->where('NO',$data['NO']);
        $this->db->update('board');
    }

    //게시글 내용 수정할 때 사용
    public function dbSelect($no) {
        $query = "SELECT 
                NO, 
                TITLE, 
                CONTENTS, 
                PW, 
                WRITER, 
                IMAGE_ID
            FROM board
            WHERE NO=?";

        $result=$this->db->query($query, array($no));
        return $result->row_array();
    }

    //첨부파일이 있을 때 수정화면에 보여줄 내용
    public function dbSelectImg($no) {
        $query = "SELECT
                IMAGE_NAME,
                PATH
            FROM board A LEFT OUTER JOIN image B
            ON A.IMAGE_ID = B.IMAGE_ID
            WHERE NO = ?";

        $result=$this->db->query($query, array($no));
        return $result->row_array();             
    }

    //목록 보여주기(pagination 때문에 이제 안씀)
    public function dbList() {
        $query = "SELECT 
            NO, TITLE, WRITER, HIT, DATE, COMMENT_CNT
            FROM board
            ORDER BY DATE DESC";

        $result = $this->db->query($query);
        return $result;
    }

    // total (전체 게시글 수 카운트)
    public function total_entry($data) {
        if($data['category']&&$data['keyword']){
            $queryAdd="WHERE ".$data['category']." LIKE '%" .$this->db->escape_like_str($data['keyword'])."%'";
        }
        $query="SELECT COUNT(*) AS CNT
            FROM board
            ".$queryAdd;
        $result = $this->db->query($query);
        $result=$result->row_array();
        return $result['CNT'];
    }

   // select (페이지 자르고 리스트 출력) 페이징
    public function select_entry($data, $pageNum, $per_page) {
        if($data['category']&&$data['keyword']){
            $queryAdd="WHERE ".$data['category']." LIKE '%" .$this->db->escape_like_str($data['keyword'])."%'";
        }        
        $start_num = ($pageNum-1)*$per_page;
        $query="SELECT 
            NO, TITLE, WRITER, DATE, HIT, COMMENT_CNT
            FROM board
            ".$queryAdd."
            ORDER BY DATE DESC
            LIMIT ?, ?";
        $result = $this->db->query($query, array(
            (int)$start_num,
            (int)$per_page
        ));
        return $result;
    }

   // view 가져올 때 사용
   public function viewSelect($no) {
        $query = "SELECT
                NO,
                HIT,
                IP,
                TITLE,
                CONTENTS,
                WRITER,
                DATE,
                IMAGE_NAME,
                PATH
            FROM board A LEFT OUTER JOIN image B
            ON A.IMAGE_ID = B.IMAGE_ID
            WHERE NO = ?";

        $result=$this->db->query($query, array($no));
        return $result->row_array();        
   }

   //view 누르면 조회수 증가시키기
   public function viewHit($no) {
       $query="UPDATE board 
            SET HIT = HIT+1
            WHERE NO=?";
        $this->db->query($query, array($no));
   }

   //파일첨부해서 글 입력할 때
   public function _dbInsertImg($result) {
        $this->db->set('IMAGE_NAME', $result['file_name']);
        $this->db->set('PATH', "http://audtla.com/uploads/".$result['file_name']);
        $this->db->set('SIZE', $result['file_size']);
        $this->db->set('REG_TIME', "now()", false);
        $this->db->insert('image');
        
        return $this->db->insert_id();
   }

   //댓글 달기
    public function addComment($data) {
        $this->db->set('BOARD_NO', $data['BOARD_NO']);
        $this->db->set('CONTENTS', $data['COMMENT']);
        $this->db->set('WRITER', $data['WRITER']);
        $this->db->set('DATE', "now()", false);
        $this->db->insert('comment');        
    }

    //댓글 개수 업데이트
    public function commentCntUpdate($data) {
        $query = "UPDATE board
                SET COMMENT_CNT=(SELECT COUNT(*) 
                                FROM comment 
                                WHERE BOARD_NO=?)
                WHERE NO=?";   

        $this->db->query($query, array($data['BOARD_NO'], $data['BOARD_NO']));  
    }

    //처음 화면에서 댓글 5개만 가져오기
    public function selectComment($data) {
        $query = "SELECT
                A.NO,
                A.WRITER,
                A.CONTENTS,
                A.DATE
            FROM comment A LEFT OUTER JOIN board B
            ON A.BOARD_NO = B.NO
            WHERE B.NO = ?
            ORDER BY A.NO DESC
            LIMIT 5";

        $result=$this->db->query($query, array($data['NO']));
        return $result;        
    }

    //더보기 눌러서 댓글 5개씩 더 가져오기
    public function moreComment($data) {
        $query = "SELECT
                A.NO,
                A.WRITER,
                A.CONTENTS,
                A.DATE
            FROM comment A LEFT OUTER JOIN board B
            ON A.BOARD_NO = B.NO
            WHERE B.NO = ?
            ORDER BY A.NO DESC
            LIMIT ?, 5";   

        $result=$this->db->query($query, array($data['NO'], (int)$data['cnt']));
        return $result;
        
    }

    //댓글 삭제하기
    public function deleteComment($no) {
        $query = "DELETE 
            FROM comment
            WHERE no=?";

        $result=$this->db->query($query, array($no));
        return $result;    
    }
     
    //글 삭제하기 (board테이블에서 YN필드를 N로)
    public function deleteBoard($data) {
        $query="SELECT 
                COUNT(*) AS total
            FROM board
            WHERE NO = ?
            AND PW = ?";

        $result=$this->db->query($query, array($data['no'], $data['pw']));    
        $row=$result->row_array();

        //total이 1이면 no와 pw가 일치해서 YN을 0(삭제)로 변경
        if($row['total']>0) {
            $query = "UPDATE board 
                SET YN = 0 
                WHERE NO = ?";

            $result=$this->db->query($query, array($data['no']));    

        } else {
            echo "<script>alert('비밀번호오류');
                history.back(); </script>";
        }
    }


}
?>