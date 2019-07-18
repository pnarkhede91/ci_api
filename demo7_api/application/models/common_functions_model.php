<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Common_functions_model extends CI_Model{

      

	function __construct(){

		parent::__construct();

	}

	

	public function offer_label_array($selected=0)

	{

		$list = ['1'=>'Sale','2'=>'New','3'=>'Discount'];

		return ($selected>0)?$list[$selected]:$list;

	}

	

	public function offer_label_ddl($selected=0)

	{

		$offer_label_list=$this->offer_label_array();

		$options='<option value="">Select Offer Label</option>';

		foreach($offer_label_list as $key=>$value)

		{

			$selected_option = ($selected > 0 && $selected == $key)?'selected="selected"':'';

			$options .= '<option value="'.$key.'" '.$selected_option.'>'.$value.'</option>';

		}

		return $options;

	}

	

	public function product_types_array()

	{

		return ['1'=>'Latest Product','2'=>'Best Seller','3'=>'Featured Product'];

	}

	

	public function product_types_ddl($selected=0)

	{

		$product_types_list=$this->product_types_array();

		$options='<option value="">Select Product Type</option>';

		foreach($product_types_list as $key=>$value)

		{

			$selected_option = ($selected > 0 && $selected == $key)?'selected="selected"':'';

			$options .= '<option value="'.$key.'" '.$selected_option.'>'.$value.'</option>';

		}

		return $options;

	}

	

	public function get_menu_category_list($selected = 0)

	{

		$category_list = $this->db->where('is_active',IS_ACTIVE_YES)->where('is_delete',IS_DELETED_NO)->get(TBL_CATEGORY)->result_array();

		$category_options = '';

		foreach($category_list as $category)

		{

			$selected_option = ($selected > 0 && $selected == $category['id'])?'selected="selected"':'';

			$category_options .= '<option value="'.$category['id'].'" '.$selected_option.'>'.$category['name'].'</option>';

		}

		return $category_options;

	}

	

	public function get_brands_listing()

	{

		return $this->db->where('is_active',IS_ACTIVE_YES)->where('is_delete',IS_DELETED_NO)->get(TBL_BRANDS)->result_array();

	}

	

	public function get_country()

	{

		return $this->db->where(['is_active'=>IS_ACTIVE_YES,'is_deleted'=>IS_DELETED_NO])->get(TBL_COUNTRY);

	}

	

	public function get_country_ddl($selected=0)

	{

		$country = $this->get_country();

		$options = '<option value="">Select Country</option>';

		if($country->num_rows()>0){

			$country_data = $country->result_array();			

			foreach($country_data as $country){

				$selected_option=($selected==$country['id'])?'selected':'';

				$options .='<option value="'.$country['id'].'" '.$selected_option.'>'.$country['name'].'</option>';

			}

		}

		return $options;

	}

	

	public function get_state($country_id=0)

	{

		$this->db->where(['is_active'=>IS_ACTIVE_YES,'is_deleted'=>IS_DELETED_NO]);

		if($country_id > 0){

			$this->db->where('country_id',$country_id);

		}

		return $this->db->get(TBL_STATE);

	}

	

	public function get_state_ddl($country_id=0)

	{

		$state = $this->get_state($country_id);

		$options = '<option value="">Select State</option>';

		if($state->num_rows()>0){

			$state_data = $state->result_array();			

			foreach($state_data as $state){

				$options .='<option value="'.$state['id'].'">'.$state['name'].'</option>';

			}

		}

		return $options;

	}

	

	public function get_city($state_id=0)

	{

		$this->db->where(['is_active'=>IS_ACTIVE_YES,'is_deleted'=>IS_DELETED_NO]);

		if($state_id > 0){

			$this->db->where('state_id',$state_id);

		}

		return $this->db->get(TBL_CITY);

	}

	

	public function get_city_ddl($state_id=0)

	{

		$city = $this->get_city($state_id);

		$options = '<option value="">Select City</option>';

		if($city->num_rows()>0){

			$city_data = $city->result_array();			

			foreach($city_data as $city){

				$options .='<option value="'.$city['id'].'">'.$city['name'].'</option>';

			}

		}

		return $options;

	}

	

	public function get_order_number()

	{

		$order_number = $this->db->select('ref_number')->get(TBL_ORDER_REFERENCE)->row('ref_number');

		$this->db->set('ref_number',$order_number+1);

		$this->db->update(TBL_ORDER_REFERENCE);

		return $order_number;

	}

	

	public function get_tax_amount($total_amount)

	{

		return number_format(($total_amount*TAX_PERCENTAGE/100),2);

	}

	

	public function get_cart_item_count()

	{

		$this->load->model('product_model');

		return $this->product_model->get_cart_item();

	}

	

	public function get_best_seller_products()

	{

		$this->db->select('*,IF((discount_start_date<="'.DB_CURRENT_DATE.'" && discount_end_date>="'.DB_CURRENT_DATE.'"),1,0) as discount_status',false);

		$this->db->where(['is_active'=>IS_ACTIVE_YES,'is_delete'=>IS_DELETED_NO,'admin_approved'=>IS_ADMIN_APPROVE_YES,'product_type'=>2]);

		$this->db->limit(10);

		return $this->db->get(TBL_PRODUCT)->result_array();

	}

	

	public function get_featured_products()

	{

		$this->db->select('*,IF((discount_start_date<="'.DB_CURRENT_DATE.'" && discount_end_date>="'.DB_CURRENT_DATE.'"),1,0) as discount_status',false);

		$this->db->where(['is_active'=>IS_ACTIVE_YES,'is_delete'=>IS_DELETED_NO,'admin_approved'=>IS_ADMIN_APPROVE_YES,'product_type'=>3]);

		$this->db->limit(10);

		return $this->db->get(TBL_PRODUCT)->result_array();

	}

	

	public function user_type_array($selected=0)

	{

		$list = ['1'=>'Admin','2'=>'Merchant','3'=>'Customer'];

		return ($selected>0)?$list[$selected]:$list;

	}

	

	public function user_type_ddl($selected=0,$include_admin=0)

	{

		$user_type_list=$this->user_type_array();

		$options='<option value="">Select Type</option>';

		foreach($user_type_list as $key=>$value)

		{

			if($key != $include_admin){

				$selected_option = ($selected > 0 && $selected == $key)?'selected="selected"':'';

				$options .= '<option value="'.$key.'" '.$selected_option.'>'.$value.'</option>';

			}

		}

		return $options;

	}

	

	public function check_user_login()

	{

		return ($this->session->userdata('user_loggedin')==1)?1:0;

	}

	

	public function send_email($to,$from,$from_name,$subject,$message)

	{

		//Load email library

		$this->load->library('email');

		

		//SMTP & mail configuration

		$config = array(

			'protocol'  => PROTOCOL,

			'smtp_host' => SMTP_HOST,

			'smtp_port' => SMTP_PORT,

			'smtp_user' => SMTP_USER,

			'smtp_pass' => SMTP_PASS,

			'mailtype'  => MAILTYPE,

			'charset'   => CHARSET

		);		

		$this->email->initialize($config);

		$this->email->set_mailtype("html");

		$this->email->set_newline("\r\n");

		

		$this->load->library('encrypt');// to avoid mail spam

		

		$this->email->to($to);

		$this->email->from($from,$from_name);

		$this->email->subject($subject);

		$this->email->message($message);



		//Send email

		return $this->email->send();

	}

	

	public function forgot_password_email_content()

	{

		return '

				Hi #USER_FNAME#,

		';

	}

	

	public function get_category_list()

	{

		return $this->db->where('is_active',IS_ACTIVE_YES)->where('is_delete',IS_DELETED_NO)->get(TBL_CATEGORY)->result_array();

	}

	

	public function get_subcategory_list($category_id)

	{

		return $this->db->where('is_active',IS_ACTIVE_YES)->where('is_delete',IS_DELETED_NO)->where('category_id',$category_id)->get(TBL_SUB_CATEGORY)->result_array();

	}

	

	public function get_product_review_rating($product_id)

	{

		$rating = $this->db->select('CEIL((AVG(`price`)+AVG(`value`)+AVG(`quality`))/3) as rating')->where('is_admin_approve',IS_ADMIN_APPROVE_YES)->where('is_deleted',IS_DELETED_NO)->where('product_id',$product_id)->get(TBL_PRODUCT_REVIEW)->row('rating');

		return ($rating>5)?5:(($rating!=null)?$rating:0);

	}

	

	public function get_shipping_address($order_id)

	{

		return $this->db->where(['order_id'=>$order_id])->get(TBL_BILLING_ADDRESS)->row_array();

	}

	

	public function order_status_array()

	{

		return [0=>'Pending', 1=>'Cancel', 2=>'Delivered'];

	}

	

	public function get_order_status($status_id)

	{

		$order_status = $this->order_status_array($status_id);		

		return $order_status[$status_id];

	}

	

	public function check_user_is_loggedin()

	{

		return ($this->session->userdata('user_loggedin')==0)?redirect(base_url()):'';

		/*if($this->session->userdata('user_loggedin')==1 && $this->session->userdata('user_type')==1){

			return 1; // admin

		}else if($this->session->userdata('user_loggedin')==1 && $this->session->userdata('user_type')==2){

			return 2; // seller

		}if($this->session->userdata('user_loggedin')==1 && $this->session->userdata('user_type')==3){

			return 3; // customer

		}else{

			return 0;

		}*/

	}

	

	public function get_city_name($city_id)

	{

		return $this->db->select('name')->where(['is_active'=>1,'is_deleted'=>0,'id'=>$city_id])->get(TBL_CITY)->row('name');

	}

	

	public function get_state_name($state_id)

	{

		return $this->db->select('name')->where(['is_active'=>1,'is_deleted'=>0,'id'=>$state_id])->get(TBL_STATE)->row('name');

	}

	

	public function get_country_name($country_id)

	{

		return $this->db->select('name')->where(['is_active'=>1,'is_deleted'=>0,'id'=>$country_id])->get(TBL_COUNTRY)->row('name');

	}

	public function check_user($user_id)
	{
		
		return($this->db->get_where(TBL_PRODUCT_GROUP, array('main_seller_id' => $user_id)))?1:0;
	}

}

?>