<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ImgCrop extends CI_Controller {

    public function __construct()
    {
		parent::__construct();

		//model 로드
		$this->load->model('M_board');

                //이미지 라이브러리 객체 초기화
                $this->load->library('image_lib');
	}

	public function index() {
                print_r($_POST);

                //$config['image_library'] = 'imagemagick'; 얘는 별도의 설치가 필요한 라이브러리
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'uploads/'.$_POST['image_name'];
                $config['new_image'] = 'crop_img/'.$_POST['image_name'];
                $config['maintain_ratio'] = FALSE;      //true로 하면 이미지가 세로일 때 세로 모양으로 비율 맞춰 크롭된다
                $config['width'] = $_POST['width'];
                $config['height'] = $_POST['height'];
                $config['x_axis'] = $_POST['x'];
                $config['y_axis'] = $_POST['y'];
                if($_POST['rotate']<0) {
                        $config['rotation_angle']=ABS($_POST['rotate']);        //코드이그나이터에서는 90,180,270만을 옵션으로 갖기 때문에
                } else {
                        $config['rotation_angle']=360-$_POST['rotate'];         //php에서는 시계반대방향으로 되기 때문에
                }
        
                //config설정
                //$this->load->library('image_lib', $config);
                //$this->image_lib->crop();

                //회전하기(rotate가 0이 아닐 때만 회전 실행)
                if($_POST['rotate']!=0) {

                        $this->image_lib->initialize($config);
                        if ( ! $this->image_lib->rotate()) {
                                echo $this->image_lib->display_errors('<p>', '</p>');
                        }

                }

                //자르기 시작
                //rotate의 값에 따라 $config['source_image'], $config['new_image']의 값을 설정해준다
                if($_POST['rotate']!=0) {
                        $config['source_image'] = 'crop_img/'.$_POST['image_name']; 
                        $config['new_image'] = 'crop_img/j_'.$_POST['image_name'];
                } else {
                        $config['source_image'] = 'uploads/'.$_POST['image_name'];
                        $config['new_image'] = 'crop_img/'.$_POST['image_name'];
                }

                print_r($config);
                //$this->load->library('image_lib', $config);
                //$this->image_lib->crop();

                $this->image_lib->initialize($config); 

                if ( ! $this->image_lib->crop()) {
                        echo $this->image_lib->display_errors('<p>', '</p>');
                }

	}



	
}
