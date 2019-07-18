<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Group_chat_model extends CI_Model{

      

	function __construct(){

		parent::__construct();

	}

	

	public function save_group_chat()

	{
		print_r($_POST);
					echo '<pre>'; print_r($this->input->post());
		$data = array(

					'product_group_id'=>0,

					'user_id'=>$tis->session->userdata('user_id'),

					'comment'=>$this->input->post('comment'),

					'created'=>DB_CURRENT_DATE,

					'modified'=>DB_CURRENT_DATE

				);

				

		echo '<pre>';

		print_r($data);

	}

}

?>