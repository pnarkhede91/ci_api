<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Login_model extends CI_Model{
      
	function __construct(){
		parent::__construct();
	}
	
	/*public function login()
	{
		$this->db->where(['email'=>$this->input->post('login_email'),'password'=>md5($this->input->post('login_password'))]);
		$result = $this->db->get(TBL_USERS);
		//echo $result->num_rows; die();
		if($result->num_rows > 0){
			$user = $result->row_array();
			$this->session->set_userdata([
				'user_name'=>$user['name'],
				'user_email'=>$user['email'],
				'user_loggedin'=>1,
			]);
			return 1;
		}else{
			return 0;
		}		
	}*/
}
?>