<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ListBoard extends CI_Controller {


    public function __construct()
    {
		parent::__construct();

        //pagination 로드하기
        $this->load->library('pagination');
        //모델 로드하기
		$this->load->model('M_board');

	}

	public function index() {

        echo $this->session->MEMBER_ID."님 환영합니다^0^/";

        //전체 url
        $config['base_url']= 'http://audtla.com/ListBoard/index';        
        //uri가 3부분 ex)ListBoard/index/1
        $config['uri_segment'] = 3; 
        //숫자가 양 옆으로 2개씩 ex)12,3,45
        $config['num_links'] = 2;
        //uri에 페이지 숫자 추가
        $config['use_page_numbers'] = TRUE;
        //true인 경우 uri가 audtla.com/ListBoard?index=1 형식으로 바뀜
        $config['page_query_string']=FALSE;
        //페이지 당 열 개수
        $data['per_page']=$config['per_page']= 10;
        $config['per_page']= 10;
        //현재페이지, uri에서 3번째 segment에서 값을 가져온다(1을 추가하면 값이 없을 때 1을 대신 넣어준다)
        $data['pageNum']=$pageNum = $this->uri->segment(3,1);
        $pageNum = $this->uri->segment(3,1);            

        // 검색 기능 추가
        // category의 값이 TITLE이나 CONTENTS가 들어왔을 때만 검색 결과를 반환하도록

        //전체 열 개수 total_entry메서드 실행
        $config['total_rows'] = $this->M_board->total_entry($_GET);
        //select_entry메서드 실행
        $result=$this->M_board->select_entry($_GET, $pageNum, $data['per_page']);

        //pagination설정
        $this->pagination->initialize($config);
        //링크 생성
        $data['pagenav'] = $this->pagination->create_links();
        
        foreach ($result->result_array() as $row) {
            $LOOP[]=array(
                'NO'=>$row['NO'],
                'TITLE'=>$row['TITLE'],
                'WRITER'=>$row['WRITER'],
                'HIT'=>$row['HIT'],
                'DATE'=>$row['DATE'],
                'COMMENT_CNT'=>$row['COMMENT_CNT']
            );
        }

        $data['LOOP'] = $LOOP;

        $this->load->view('list_board',$data);

    }

}
