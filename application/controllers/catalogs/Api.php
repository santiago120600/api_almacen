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
            $response = $this->DAO->selectEntity('tb_careers',array('id_career'=>$this->get('pId'), 'status_career'=>'Activo'), TRUE);
        }else{
            $response = $this->DAO->selectEntity('tb_careers', array('status_career'=>'Activo'));
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

    function careers_put(){
        if($this->get('pId')){
            $data = $this->put();
            $data += ["pId"=>$this->get('pId')];
            $this->form_validation->set_data($data);

            $this->form_validation->set_rules('pName','Nombre','required|max_length[80]|min_length[3]');
            $this->form_validation->set_rules('pId','Clave','required|callback_valid_career');

            if($this->form_validation->run()){
                $data = array(
                    "name_career"=>$this->put('pName')
                );
                $response = $this->DAO->saveOrUpdate('tb_careers',$data ,array('id_career'=>$this->get('pId')));
            }else{
                 $response = array(
                     "status"=>"error",
                     "message"=>"Información enviada incorrectamente.",
                     "validations"=>$this->form_validation->error_array(),
                     "data"=>null
                 );
            }
        }else{
             $response = array(
                 "status"=>"error",
                 "message"=>"parametro pId no enviado",
                 "validations"=>array(),
                 "data"=>null
             );
        }  
        $this->response($response,200);
    }

    function valid_career($value){
        $careers_exists = $this->DAO->selectEntity('tb_careers',array('id_career'=>$value),TRUE);
        if($careers_exists['data']){
            return TRUE;
        }else{
            $this->form_validation->set_message('valid_career','El campo {field} no existe en el banco de datos');
            return FALSE;
        }
    }

    function careers_delete(){
        if($this->get('pId')){
            if($this->valid_career($this->get('pId'))){
                $data = array(
                    "status_career"=>'Inactivo'
                );
                $response = $this->DAO->saveOrUpdate('tb_careers',$data ,array('id_career'=>$this->get('pId')));
            }else{
                 $response = array(
                     "status"=>"error",
                     "message"=>"El campo Clave no existe en el banco de datos",
                     "validations"=>array(),
                     "data"=>null
                 );
            }
        }else{
             $response = array(
                 "status"=>"error",
                 "message"=>"parametro pId no enviado",
                 "validations"=>array(),
                 "data"=>null
             );
        }
        $this->response($response,200);
    }


}
