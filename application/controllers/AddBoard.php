<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//내부에서 함수를 호출할 경우 'private'을 써주고 '_메서드명'에 언더바를 붙여서 구분해준다

//클래스명은 대문자로 시작해야 한다
class AddBoard extends CI_Controller {
	//$POST 변수를 전역변수처럼 사용하기 위해 먼저 선언
	protected $POST;
    public function __construct()
    {
		parent::__construct();

		//config>autoload.php 파일에서 database를 자동으로 로드

		//model 로드
		$this->load->model('M_board');

		//파일 업로드하는 헬퍼 추가
		$this->load->helper(array('form', 'url'));
		//업로드 클래스 초기화
		$this->load->library('upload');

		//post방식으로 input에 입력한 값을 받아온다
		$this->POST['NO'] = $this->input->post("no");
		$this->POST['TITLE'] = $this->input->post("title");
		$this->POST['CONTENTS'] = $this->input->post("contents");
		$this->POST['PW'] = $this->input->post("pw");
		$this->POST['WRITER'] = $this->input->post("writer");
		$this->POST['IP'] = $this->input->ip_address();
	}

	public function index() {
		//이거 안씀 echo 'Hello World!<br>';

		//업데이트 시 no값을 받아서 add_board화면에 뿌려준다
		$no=$this->input->get('no');
		$result=$this->M_board->dbSelect($no);

		//첨부파일(IMAGE_ID)가 있으면 사진과 PATH를 보여준다
		if($result['IMAGE_ID']!=null){
			$result_img=$this->M_board->dbSelectImg($no);
			$data['PATH'] = htmlspecialchars($result_img['PATH']);
			$data['IMAGE_NAME'] = htmlspecialchars($result_img['IMAGE_NAME']);
		}

		//dbSelect메서드 호출하고 받아온 결과
		$data['TITLE'] = htmlspecialchars($result['TITLE']);
		$data['CONTENTS'] = htmlspecialchars($result['CONTENTS']);
		$data['PW'] = htmlspecialchars($result['PW']);
		$data['WRITER'] = htmlspecialchars($result['WRITER']);
		$data['NO'] = htmlspecialchars($result['NO']);
		$data['IMAGE_ID'] = htmlspecialchars($result['IMAGE_ID']);

		//view폴더에 있는 add_board.php를 로드한다
		$this->load->view('add_board', $data);

	}

	public function save() {
		//파일 업로드
		$result = $this->_do_upload();
		print_r($result)."<br>";
		
		//업로드 실패거나 없을 경우
		if($result==0){
			$this->POST['IMAGE_ID'] = null;
		} else {
			//이미지 입력하면 이미지아이디 받아오기
			//$this->POST['IMAGE_ID']하면 $POST배열에 변수 추가하고 imgage_id에도 값을 넣는다
			$this->POST['IMAGE_ID'] = $this->_dbInsertImg($result);
		}

		//no가 있을 땐 수정, 없을 땐 입력
		if($this->POST['NO']) {
			$this->_dbUpdate();
		} else {
			$this->_dbInsert();
		}
		
		//리다이렉트하는 두 가지 방법
		//redirect('http://audtla.com/ListBoard');
		echo "<script>alert('얍');location.href='http://audtla.com/ListBoard';</script>";
	}

    //DB에 값 입력
    private function _dbInsert() {
        //print_r($this->POST);
        $this->M_board->_dbInsert($this->POST);
    }

	//DB에 값 수정
    private function _dbUpdate() {
        $this->M_board->_dbUpdate($this->POST);
	}

	//파일 업로드
	private function _do_upload() {
			$config['upload_path']          = './uploads/';
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 0;
			$config['max_width']            = 0;
			$config['max_height']           = 0;
			$config['overwrite']			= FALSE;	//같은 이름의 파일이 이미 존재한다면 파일명에 숫자가 추가로 붙는다

			$this->load->library('upload', $config);

			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload()) {
				$error = array('error' => $this->upload->display_errors());
				return 0;
				//$this->load->view('add_board', $error);

			} else {
				array('upload_data' => $this->upload->data());
				$imgData=$this->upload->data();
				return $imgData;
			}
	}

	//첨부파일 있을 때 게시글 입력하기
	private function _dbInsertImg($result) {
		return $this->M_board->_dbInsertImg($result);
	}


}
