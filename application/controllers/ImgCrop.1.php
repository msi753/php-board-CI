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
                $config['create_thumb'] = TRUE;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = $_POST['width'];
                $config['height'] = $_POST['height'];
                $config['x_axis'] = $_POST['x'];
                $config['y_axis'] = $_POST['y'];
                if($_POST['rotate']<0) {
                        $config['rotate']=ABS($_POST['rotate']);
                } else {
                        $config['rotate']=360-$_POST['rotate'];
                }

                // $_POST['rotate']=ABS($_POST['rotate']);
                // if(($_POST['rotate'])==0||$_POST['rotate']==90) {
                //         $config['rotation_angle'] = $_POST['rotate']+180;
                // } else {
                //         $config['rotation_angle'] = $_POST['rotate']-180;
                // }

        
                //config설정
                $this->load->library('image_lib', $config);
                //$this->image_lib->crop();

                //실행
                $this->image_lib->initialize($config);

                //회전하기
                if($_POST['rotate']!=0) {
                        print_r($config);
                        if ( ! $this->image_lib->rotate()) {
                                echo $this->image_lib->display_errors('<p>', '</p>');
                        }
                }
                

                $config['source_image'] = 'crop_img/'.$_POST['image_name']; 
                $config['new_image'] = 'crop_img/'.$_POST['image_name'];

                $this->load->library('image_lib', $config);
                //$this->image_lib->crop();

                $this->image_lib->initialize($config); 

                //자르기
                if ( ! $this->image_lib->crop()) {
                        echo $this->image_lib->display_errors('<p>', '</p>');
                }


	}



	
}
