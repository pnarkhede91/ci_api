<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Group_chat_model extends CI_Model{

      

	function __construct(){

		parent::__construct();

	}

	

	public function save_group_chat()

	{
		//print_r($_POST);
		//echo '<pre>'; print_r($this->input->post());
		$data = array();
		$product_id = base64_decode($this->uri->segment(3));
		

		$this->db->select('*');
		$this->db->where('product_id',$product_id);
		$result = $this->db->get(TBL_PRODUCT_GROUP)->row();
		//echo'<pre>';print_r($result);
		
		if(!empty($result))
		{
			$data['product_group_id'] = $result->id;
			$member_id = $result->member_ids.",".$this->session->userdata("user_id");
			//UPDATE mytable SET spares = CONCAT(spares, ',', '818') WHERE id = 1
			/*$user_details = array(
				'member_ids' => CONCAT($data['member_ids'], ',', '$this->session->userdata("user_id")'),
			);
			echo'<pre>';print_r($user_details);
			$this->db->update(TBL_PRODUCT_GROUP, $user_details, array('product_id' => $product_id));
			echo $this->db->last_query();exit;*/
			$user_details = array(
				'member_ids' =>$member_id,
			);
			
			//$user_details['member_id'] = implode(',', $this->session->userdata('user_id'));

			$this->db->update(TBL_PRODUCT_GROUP, $user_details, array('product_id' => $product_id));

		}else{
			$data['product_group_id'] = 0;

		}
		$data['user_id'] = $this->session->userdata('user_id');
		$data['comment'] = $this->input->post('comment');
		$data['created'] = DB_CURRENT_DATE;
		$data['modified'] = DB_CURRENT_DATE;
		/*echo'<pre>';print_r($data);
		die('sdfs');*/
		/*$data = array(

					'product_group_id'=>0,

					'user_id'=>$this->session->userdata('user_id'),

					'comment'=>$this->input->post('comment'),

					'created'=>DB_CURRENT_DATE,

					'modified'=>DB_CURRENT_DATE

				);*/
		return($this->db->insert(TBL_PRODUCT_GROUP_CHAT,$data))?1:0;

	}

	public function get_record_by_id()
	{
		$product_id = base64_decode($this->uri->segment(3));
		$this->db->select('pgc.id as group_chat_id,pgc.comment as comment,pgc.created as chatedatetime,u.fname as fname,u.lname as lname,u.profile_img as user_profile_image');
		$this->db->from(TBL_PRODUCT_GROUP_CHAT.' as pgc');
		$this->db->join(TBL_PRODUCT_GROUP.' as pg','pg.id = pgc.product_group_id','left');
		$this->db->join(TBL_USERS.' as u','u.id = pgc.user_id','left');
		$this->db->where('pg.product_id',$product_id);
		$this->db->where('u.is_delete',0);
		//$this->db->get();
		//echo $this->db->last_query();
		$result = $this->db->get()->result_array();
		return $result;
	}

	public function get_group_members()
	{
		$this->db->select('u.fname as fname,u.lname as lname');
		$this->db->from(TBL_PRODUCT_GROUP_CHAT.' as pgc');
		$this->db->join(TBL_PRODUCT_GROUP.' as pg','pg.id = pgc.product_group_id','left');
		$this->db->join(TBL_USERS.' as u','u.id = pgc.user_id','left');
		$this->db->where('u.is_delete',0);
		$this->db->group_by('pgc.user_id');
		$result = $this->db->get()->result_array();
		return $result;
	}
	
	/*public function get_seller_id()
	{
		$this->db->select('user_id');
		$this->db->from(TBL_PRODUCT_GROUP_CHAT);
		$this->db->order_by('id','asc');
		$this->db->limit(1);
		$result = $this->db->get()->row();
		//echo'<pre>';
		//print_r($result);
		return $result;
	}*/

}

?>