<?php
require APPPATH . 'core/MY_RootController.php';

defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_RootController {
    function __construct(){
        parent:: __construct();
        $this->load->model('DAO');
    }

    function careers_get(){
        if($this->get('pId')){
            $response = $this->DAO->selectEntity('tb_careers',array('id_career'=>$this->get('pId')), TRUE);
        }else{
            $response = $this->DAO->selectEntity('tb_careers');
        }
        $this->response($response,200);
    }

    function careers_post(){
        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('pName','Nombre','required|max_length[80]|min_length[3]');
        if($this->form_validation->run()){
            $data = array(
                "name_career"=>$this->post('pName')
            );
            $response = $this->DAO->saveOrUpdate('tb_careers',$data);
        }else{
             $response = array(
                 "status"=>"error",
                 "message"=>"Información enviada incorrectamente.",
                 "validations"=>$this->form_validation->error_array(),
                 "data"=>null
             );
        }
        $this->response($response,200);
    }


}
