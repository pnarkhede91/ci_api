<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index()
	{
		$data=array();
		$this->load->model('common_functions_model');
		$data['best_seller_list']=$this->common_functions_model->get_best_seller_products();
		$data['featured_list']=$this->common_functions_model->get_featured_products();
		$this->load->view('header');
		$this->load->view('index',$data);
		$this->load->view('footer');
	}
	
	public function news_letter_subscriber()
	{
		$result=0;
		if(isset($_POST['news_letter_email']) && $_POST['news_letter_email'] !='' && filter_var($_POST['news_letter_email'], FILTER_VALIDATE_EMAIL)){
			$this->load->model('Subscriber_model');
			$result = $this->Subscriber_model->add_news_letter_subscriber($_POST['news_letter_email']);
		}
		echo json_encode(array('result'=>$result)); exit;
	}
	
	public function search_product()
	{
		$data=array();
		$this->load->model('product_model');
		$this->load->model('common_functions_model');
		$product_name=$this->input->post('search_product_name');
		$category_id=$this->input->post('category_id');
		//echo $category_id; die();
		$data['category_data'] = $this->product_model->get_product_category($category_id);
		$data['product_data'] = $this->product_model->search_product($product_name,$category_id);
		$this->load->view('header');
		$this->load->view('product',$data);
		$this->load->view('footer');
	}
	
	public function product_view()
	{
		$this->load->model('common_functions_model');
		$this->load->model('product_model');
		$data=array();
		$product_details = $this->product_model->get_product_details_by_id();
		//echo '<pre>'; print_r($product_details); die();
		$data['product_data']=$product_details['product_data'];
		$data['product_img_video']=$product_details['product_img_video'];
		$data['product_review']=$product_details['product_review'];
		$this->load->view('header');
		$this->load->view('product_view',$data);
		$this->load->view('footer');
	}
	
	public function add_product_to_cart()
	{
		$this->load->model('common_functions_model');
		$this->load->model('product_model');
		$id=base64_decode($this->uri->segment(3));
		if($id=='')
		{
			redirect(base_url());
		}
		
		$result=$this->product_model->add_product_to_cart_by_id();
		redirect(base_url().'home/search_product');
	}
	
	public function add_product_to_wishlist()
	{
		$this->load->model('common_functions_model');
		$this->load->model('product_model');
		$id=base64_decode($this->uri->segment(3));
		if($id=='')
		{
			redirect(base_url());
		}
		
		$result=$this->product_model->add_product_to_wishlist_by_id();
		redirect(base_url().'home/search_product');
	}
	
	public function add_to_cart()
	{	
		$this->load->model('product_model');
		$product_id=base64_decode($this->input->post('pid'));
		if($product_id=='')
		{
			redirect(base_url());
		}
		
		$result = $this->product_model->add_to_cart($product_id);
		if($result){
			redirect(base_url().'home/cart');
		}else{
			redirect(base_url().'home/product_view/'.$this->input->post('pid'));
		}
		/*echo '<pre>';
		print_r($this->input->post());
		die('out');*/
	}
	
	public function product_review()
	{
		$this->load->model('product_review_model');
		$id=base64_decode($this->uri->segment(3));
		if($id=='')
		{
			redirect(base_url());
		}
		
		$data = array(
					'product_id'=>$id,
					'user_id'=>(int)$this->session->userdata('user_id'),
					'name'=>$this->input->post('name'),
					'email'=>$this->input->post('email'),
					'title'=>$this->input->post('summary'),
					'price'=>$this->input->post('ratings_price'),
					'value'=>$this->input->post('ratings_value'),
					'quality'=>$this->input->post('ratings_quality'),
					'comment'=>$this->input->post('review')
				);
		
		$result = $this->product_review_model->add_product_review($data,$id);
		
		echo json_encode($result);//(array($result)); exit;
	}
	
	public function cart()
	{
		$this->load->model('product_model');
		$data=array();
		$data['cart'] = $this->product_model->get_cart_item();
		$this->load->view('header');
		$this->load->view('cart',$data);
		$this->load->view('footer');
	}
	
	public function update_cart()
	{
		$this->load->model('product_model');
		echo $this->product_model->update_cart();
		exit;
	}
	
	public function remove_cart_item()
	{
		$this->load->model('product_model');
		echo $this->product_model->remove_cart_item();
		exit;
	}
	
	public function empty_cart()
	{
		$this->load->model('product_model');
		echo $this->product_model->empty_cart();
		exit;
	}
	
	public function checkout()
	{
		$this->load->model('product_model');
		
		$data=array();		
		
		$data['cart'] = $this->product_model->get_cart_item();
		//echo '<pre>'; print_r($data['cart']); die();
		if(COUNT($data['cart'])==0){
			redirect('home/cart');
		}
		$data['country_ddl'] = $this->common_functions_model->get_country_ddl();
		if($this->session->userdata('order_number')==''){
			$data['order_number']=$this->common_functions_model->get_order_number();
			$this->session->set_userdata('order_number',$data['order_number']);
		}else{
			$data['order_number']=$this->session->userdata('order_number');
		}
		$this->load->view('header');
		$this->load->view('checkout',$data);
		$this->load->view('footer');
		//echo 'in'; die();
	}
	
	public function load_state_ddl()
	{
		echo $this->common_functions_model->get_state_ddl($this->input->post('country_id')); exit;
	}
	
	public function load_city_ddl()
	{
		echo $this->common_functions_model->get_city_ddl($this->input->post('state_id')); exit;
	}
	
	public function place_order()
	{
		if($this->session->userdata('order_number')!='' && $this->session->userdata('cart_ids')!='' && $this->session->userdata('user_id')!=''){
			$this->load->model('order_model');
			$this->load->model('product_model');
			$order_number = $this->session->userdata('order_number');
			$result = $this->order_model->place_order();
			$this->session->set_userdata('place_order_no',$order_number);
			if($result){
				redirect('home/order_success');
			}else{
				redirect('home/order_failed');
			}
		}else{
			redirect('home/cart');
		}
		
	}
	
	public function checkout_login()
	{
		$this->load->model('user_model');
		$role=$this->input->post('user_type');
		$result = $this->user_model->login($role);
		if($result==1){
			$this->load->model('product_model');
			$result = $this->product_model->update_cart_user_id();
		}
		echo $result;
		exit;
	}
	
	public function logout()
	{
		$user_data = $this->session->all_userdata();
        foreach ($user_data as $key => $value) {
            if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
                $this->session->unset_userdata($key);
            }
        }
		$this->session->sess_destroy();
		redirect(base_url());
	}
	
	public function order_failed()
	{
		$this->load->view('header');
		$this->load->view('order_failed');
		$this->load->view('footer');
	}
	
	public function order_success()
	{
		$this->load->view('header');
		$this->load->view('order_success');
		$this->load->view('footer');
	}
	
	public function login()
	{
		if($this->common_functions_model->check_user_login())
		{
			redirect(base_url());
		}
		$this->load->view('header');
		$this->load->view('login');
		$this->load->view('footer');
	}
	
	public function user_login()
	{
		$this->load->model('user_model');
		$role=$this->input->post('user_type');
		$result = $this->user_model->login($role);
		if($result==1){
			$this->load->model('product_model');
			$result = $this->product_model->update_cart_user_id();
		}
		echo $result;
		exit;
	}
	
	public function user_register()
	{
		
		$this->load->model('user_model');
		$result = $this->user_model->user_register();
		echo $result;
		exit;
	}
	
	public function forgot_password()
	{
		$this->load->model('user_model');
		$result = $this->user_model->forgot_password();
		echo $result;
		exit;
	}
	
	public function product_group_chat()
	{
		$product_id = base64_decode($this->uri->segment(3));
		if((int)$product_id > 0){
			$error = '';
			if(isset($_POST['submit']))
			{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('comment', 'comment', 'required');
				if($this->form_validation->run() == TRUE)
                {
					$this->load->model('group_chat_model');
					$result = $this->group_chat_model->save_group_chat();
					/*if($result == 1)
					{
						redirect(base_url());
					}*/
                    /*print_r($_POST);
					echo '<pre>'; print_r($this->input->post());
					die;*/
                }else{
					//$this->session->set_flashdata('comment_error', 'Comment can not be blank.');
					//echo $this->session->flashdata('comment_error'); die;
					$error = validation_errors();
				}
			}
			$data = $this->group_chat_model->get_record_by_id();
			echo'<pre>';print_r($data);
			$this->load->view('header');
			$this->load->view('product_group_chat',array('error'=>$error));
			$this->load->view('footer');
		}else{
			redirect(base_url());
		}		
	}
}