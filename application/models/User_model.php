<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

interface CRUD_Interface{
    public function consultar($id);
    public function incluir($dados); 
    public function atualizar($dados);
    public function exclusao($id);
}

class User_model extends CI_Model implements CRUD_Interface{
    // Construtor:
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function consultar($id=NULL){
        $this->db->select('id, nome, cpf, email, telefone, sexo, dataNascimento');
        $this->db->from('user');
        if($id){
            $this->db->where('id',$id);
        }
        $this->db->order_by('nome');
        $query = $this->db->get();
        $result = $query->result_array();    
        if(isset($result[0])){
            return($result);
        }else{
            return(array());
        }        
    }

    public function incluir($dados){
        $this->db->insert('user',$dados);
        return(1);
    }
 
    public function atualizar($dados){
        $this->db->where('id',$dados['id']);
        $this->db->update('user',$dados);
    }

    public function exclusao($id){
        $this->db->delete('user',array('id'=>$id));
    }
}