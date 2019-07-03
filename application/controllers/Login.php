<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        //자동로드한 세션 접근하기
        //config>autoload.php $autoload['libraries'] = array('database', 'session'); //수정 
        $this->session; 

        //모델 로드하기
		$this->load->model('M_login');
    }
    
	public function index() {	
        $this->load->view('login');

	}

    public function access() {
        $this->input->post('id');
        $this->input->post('pw');

        $data=$this->M_login->logincheck($_POST);

        if($data['OK']){
            $newdata = array(
                'MEMBER_ID' => $_POST['id'],
                'logged_in' => TRUE
            );
            $this->session->set_userdata($newdata);
            
            //리스트로 리다이렉트
		    redirect('http://audtla.com/ListBoard');
        }else{
            echo "<script>alert('로그인 실패!');</script>";
            echo "<script>history.back();</script>";
        }

    }

    public function logout() {
        $this->session;
        $this->session->sess_destroy();  //세션 사용 종료
        redirect('http://audtla.com/ListBoard');
    }

	
}
