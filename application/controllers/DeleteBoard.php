<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DeleteBoard extends CI_Controller {

    public function __construct() {
		parent::__construct();
		
        //모델 로드하기
		$this->load->model('M_board');

    }
    
	public function index() {
        //글 번호 
        $no = $this->input->get('no');
        echo "글 번호: ".$no."<br>";

        $this->load->view('delete_board', $_GET);
		
    }

    //삭제 버튼 누르기
    public function deleteProcess() {
        //post방식으로 no와 pw를 받아옴
        $result=$this->M_board->deleteBoard($_POST);
        print_r($result);

        //리다이렉트
        redirect('http://audtla.com/ListBoard');

    }
	
}
