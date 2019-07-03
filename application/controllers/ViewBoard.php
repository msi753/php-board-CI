<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ViewBoard extends CI_Controller {

    public function __construct() {
		parent::__construct();
		
		//get방식으로 no글번호 받아오기
		$this->no=$this->input->get('no');
        //모델 로드하기
		$this->load->model('M_board');

    }
    
	public function index() {
		//뷰 셀렉트 하기
        $result=$this->M_board->viewSelect($this->no);
		//뷰 누르면 조회수 증가시키기
		$this->M_board->viewHit($this->no);
		
		$data['NO'] = htmlspecialchars($result['NO']);
		$data['HIT'] =  htmlspecialchars($result['HIT']);
		$data['IP'] =  htmlspecialchars($result['IP']);
		$data['TITLE'] =  htmlspecialchars($result['TITLE']);
        $data['CONTENTS'] =  htmlspecialchars($result['CONTENTS']);
        $data['WRITER'] =  htmlspecialchars($result['WRITER']);
		$data['DATE'] =  htmlspecialchars($result['DATE']);
		$data['IMAGE_NAME'] =  htmlspecialchars($result['IMAGE_NAME']);
		$data['PATH'] =  htmlspecialchars($result['PATH']);
		$data['IMAGE_ID'] = htmlspecialchars($result['IMAGE_ID']);

		//처음 화면에서 댓글 5개 불러오기 
		$result=$this->M_board->selectComment($data);
		foreach ($result->result_array() as $row) {
			$LOOP[]=array(
                'NO'=>$row['NO'],
                'WRITER'=>$row['WRITER'],
                'CONTENTS'=>$row['CONTENTS'],
                'DATE'=>$row['DATE']
            );
		}
        $data['LOOP'] = $LOOP;

		//댓글 결과 저장해서 뿌려주기
		//print_r($result);
		//exit;
		$this->load->view('view_board.php', $data);
	}

	public function save() {
		$data['COMMENT'] = $this->input->post('comment');
		$data['BOARD_NO'] = $this->input->post('board_no');
		$data['WRITER'] = $this->session->MEMBER_ID;
		$this->M_board->addComment($data);
		$this->M_board->commentCntUpdate($data);

		
		//이전 페이지(뷰)로 리다이렉트
		redirect('http://audtla.com/ViewBoard?no='.$data['BOARD_NO']);
	}

	//더보기 버튼 눌렀을 때
	public function addBtn() {
		//ajax get방식으로 보낸 값 받아오기
		$this->input->get('NO');
		$this->input->get('cnt');

		$result=$this->M_board->moreComment($_GET);

        foreach ($result->result_array() as $row) {
            $LOOP[]=array(
                'NO'=>$row['NO'],
                'WRITER'=>$row['WRITER'],
                'CONTENTS'=>$row['CONTENTS'],
                'DATE'=>$row['DATE']
            );
        }

        $data['LOOP'] = $LOOP;

		$this->load->view('comment', $data);
	}

	//댓글 삭제 버튼 누르면
	public function deleteComment() {
		//ajax get방식으로 보낸 값 받아오기
		$no = $this->input->get('NO');
		
		$result=$this->M_board->deleteComment($no);
		print_r($result);


	}

	
}
