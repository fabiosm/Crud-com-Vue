<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	public function __construct() {
        parent::__construct();
        // Carregar Models:
        $this->load->model('user_model');

		if(isset($_SERVER['HTTP_ORIGIN'])){
			header("Access-Control-Allow-Origin: *");
			header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
			header("Access-Control-Allow-Headers: Origin, Authorization, X-Requested-With, Content-Type, Accept");
			header('Access-Control-Allow-Credentials: true');      
		}  
		if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
			if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))          
				header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
			if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
				header("Access-Control-Allow-Headers: Origin, Authorization, X-Requested-With, Content-Type, Accept");
			exit();
		}
    }

	public function exibir(){
		$users = $this->user_model->consultar();
        foreach($users AS $i=>$user){
            $users[$i]['dataNascimento'] = date('d/m/Y', strtotime($user['dataNascimento']));
        }
		echo json_encode($users);
	}

	public function get_user($id){
        $users = $this->user_model->consultar($id);
		echo json_encode($users[0]);
	}

	public function criar(){
		$result = json_decode(file_get_contents("php://input"), true);	
		echo $this->user_model->incluir($result['user']);
	}

    public function editar(){
        $result = json_decode(file_get_contents("php://input"), true);	
		echo $this->user_model->atualizar($result['user']);    
    }

	public function apagar($id){
		echo $this->user_model->exclusao($id);
	}
}
