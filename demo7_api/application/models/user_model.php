<?php defined('BASEPATH') OR exit('No direct script access allowed');
class User_model extends CI_Model{
      
	function __construct(){
		parent::__construct();
	}
	
	var $order_column = array("id", "fname", "email", "mobile","gender","type"); 
	
	public function login($role=0)
	{
		if($role>0){
			$this->db->where('type',$role);
		}
		$this->db->where(['email'=>$this->input->post('login_email'),'password'=>$this->input->post('login_password')]);
		$result = $this->db->get(TBL_USERS);
		//echo $result->num_rows; die();
		if($result->num_rows > 0){
			$user = $result->row_array();
			$this->session->set_userdata([
				'user_id'=>$user['id'],
				'user_fname'=>$user['fname'],
				'user_full_name'=>$user['fname'].' '.$user['mname'].' '.$user['lname'],
				'user_email'=>$user['email'],
				'user_type'=>$user['type'],
				'seller_request'=>$user['seller_request'],
				'user_loggedin'=>1,
			]);
			return 1;
		}else{
			return 0;
		}		
	}
	
	public function get_all_usersData()
	{
		$sSearch = $this->input->post('search');
		$order=$this->input->post('order')['0'];
		$iDisplayStart=$this->input->post('start');
		$iDisplayLength=$this->input->post('length');
		 
		//filtering data by search value
		if(isset($sSearch['value']))  
	    {  
			$where_like= "(	fname LIKE '%".$sSearch['value']."%' OR mname LIKE '%".$sSearch['value']."%' OR  lname LIKE '%".$sSearch['value']."%' OR  email  LIKE '%".$sSearch['value']."%' OR  mobile LIKE '%".$sSearch['value']."%')";
			$this->db->where($where_like); 
	    } 
		//ordering column
		(isset($_POST["order"]))?$this->db->order_by($this->order_column[$order['column']], $order['dir']):$this->db->order_by('id', 'DESC');	
		
		//get the count of records after filtering 
		$this->db->where('is_delete',0);
		$this->db->where('type !=','1');
		$recordfilter_query = $this->db->get(TBL_USERS);  
		$recordsFiltered=$recordfilter_query->num_rows();
		
		//filtering data by search value
		if(isset($sSearch['value']))  
	    {  
			$where_like= "(	fname LIKE '%".$sSearch['value']."%' OR mname LIKE '%".$sSearch['value']."%' OR  lname LIKE '%".$sSearch['value']."%' OR  email  LIKE '%".$sSearch['value']."%' OR  mobile LIKE '%".$sSearch['value']."%')";
			$this->db->where($where_like); 
	    } 
		//ordering column
		(isset($_POST["order"]))?$this->db->order_by($this->order_column[$order['column']], $order['dir']):$this->db->order_by('id', 'DESC');	
		
		
		//paging data table
		if (isset($iDisplayStart) && $iDisplayLength != '-1') {
			$this->db->limit($iDisplayLength, $iDisplayStart);
        }
		
		//fetch all user data
		$this->db->where('is_delete',0);
		$this->db->where('type !=','1');		
        $fetch_query = $this->db->get(TBL_USERS);  
		$fetch_data=$fetch_query->result();  

		$data = array();
		$cnt=$this->input->post('start')+1;		   
	    foreach($fetch_data as $row)  
	    {  
			$gender = ($row->gender==0 ? 'Male' : 'Female'); // returns true
			$sub_array = array();  
			$sub_array[] =$cnt;
			$sub_array[] = $row->fname." ".$row->mname." ".$row->lname;  
			$sub_array[] = $row->email;  
			$sub_array[] = $row->mobile;  
			$sub_array[] = $gender;
			$sub_array[] = ($row->type==2)?'Marchant':'Customer';
			$sub_array[] = ($row->is_active)?'<td><span class="label label-success">Active</span></td>':'<td><span class="label label-danger">Inactive</span></td>';				
			$sub_array[] = '<a href="'.base_url().ADMIN_END.'/users/update_user/'.base64_encode($row->id).'" title="Edit"><i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i></a>
			<a href="javascript:void(0)" title="Edit" onclick="delete_user('."'".$row->id."'".')"><i class="fa fa-trash-o fa-lg" aria-hidden="true"></i></a>';  
		    $data[] = $sub_array;  
			$cnt++;
	    }
		
		//get the total record count
		$this->db->where('is_delete',0); 
		$this->db->where('type !=','1');
		$recordsTotal=$this->db->count_all_results(TBL_USERS);
		
		$output = array(  
			"draw"            => intval($this->input->post("draw")),  
			"recordsTotal"    => $recordsTotal,  
			"recordsFiltered" => $recordsFiltered,  
			"data"            => $data  
	    );  
		return $output;  
	}
	
	public function add_user($data)
	{
		$result='';			
		$this->db->where('email',$data['email']);
		$this->db->where('is_delete',0); 
        $user_info=$this->db->get(TBL_USERS)->row(); 
		
		if($user_info)
		{
			$this->session->set_flashdata('error', $data['email']." already exists.");
			$result=0;
		}
		else
		{
			if($this->db->insert(TBL_USERS, $data))
			{
				$this->session->set_flashdata('success',$data['email']." user added successfully.");
				$result=1;
			}
			else
			{
				$this->session->set_flashdata('error',"Error while add user.");
				$result=-1;
			}	
		}
		return $result;		
	}
	
	public function get_userby_id($id)
    {
		$this->db->where('id',$id);
        return $this->db->get(TBL_USERS)->row(); 
    }
	
	public function update($id,$data)
	{
		$result['message']='';	
		$result['status']='';			
		$this->db->where('email',$data['email']);
		$this->db->where('id !=',$id);
		$this->db->where('is_delete',0); 
        $user_info=$this->db->get(TBL_USERS)->row(); 
		
		if( $user_info)
		{
			$this->session->set_flashdata('error', $data['email']." email-id already exists.");
			return 0;
		}
		else
		{
			$this->db->where('id',$id);
			if($this->db->update(TBL_USERS, $data))
			{
				$this->session->set_flashdata('success', $data['email'].' user information updated successfully.');
			}
			else
			{
				$this->session->set_flashdata('error', 'Error! while update user information.');
			}	
			return 1;
		}		
	}
	

	public function delete_user($data)
	{
		$result=array();
		$this->db->where('id',$this->input->post('id'));
		if($this->db->update(TBL_USERS, $data))
		{
			$result['message']="User information deleted successfully.";
			$result['status']="success";
		}
		else
		{
			$result['message']="Error! while delete user information.";
			$result['status']="error";
		}	
		return $result;	
	}
	
	public function user_register()
	{
		$user_register = $this->input->post('register');
		
		$this->db->where('email',$user_register['email']);
		$this->db->where('is_delete',0); 
        $row_count=$this->db->get(TBL_USERS)->num_rows();
		$result=0;
		if($row_count==0){
			$data=array(
					'fname'=>$user_register['fname'],
					'mname'=>$user_register['mname'],
					'lname'=>$user_register['lname'],
					'mobile'=>$user_register['mobile'],
					'email'=>$user_register['email'],
					'password'=>MD5($user_register['password']),
					'type'=>$user_register['user_type'],
					'created'=>DB_CURRENT_DATE,
					'modified'=>DB_CURRENT_DATE
				);
			$result = ($this->db->insert(TBL_USERS, $data))?1:0;
		}else{
			$result=-1;
		}
		return $result;
		/*print_r($this->input->post());
		die('in');*/
	}
	
	public function forgot_password()
	{
		$this->db->where('email',$this->input->post('email'));
		$this->db->where('is_delete',0); 
        $user_data=$this->db->get(TBL_USERS);
		$result=0;
		if($user_data->num_rows()>0){	
			$user_result = $user_data->row_array();
			
			$to='kailas.bedarkar@gmail.com';
			$from='kailas@test.com';
			$from_name='Kailas';
			$subject='How to send email via SMTP server in CodeIgniter';
			
			//Email content
			$message = '<h1>Sending email via SMTP server</h1>';
			$message .= '<p>This email has sent via SMTP server from CodeIgniter application.</p>';
			return $this->common_functions_model->send_email($to,$from,$from_name,$subject,$message);
		}
		return $result;
	}
	
	public function get_newsletter_status()
	{
		return (($this->db->where(['email'=>$this->session->userdata('user_email'),'is_deleted'=>IS_DELETED_NO])->get(TBL_SUBSCRIBER)->num_rows()) > 0)?1:0;
	}
	
	public function update_newsletter_status($status)
	{
		if($status==0){
			$data = array( 
				'is_deleted'=> IS_DELETED_YES, 
				'modified'=>DB_CURRENT_DATE
			);
			$this->db->where('email', $this->session->userdata('user_email'));
			return $this->db->update(TBL_SUBSCRIBER, $data);
		}else{
			if($this->db->where(['email'=>$this->session->userdata('user_email'),'is_deleted'=>IS_DELETED_YES])->get(TBL_SUBSCRIBER)->num_rows()){
				$data = array( 
					'is_deleted'=> IS_DELETED_NO, 
					'modified'=>DB_CURRENT_DATE
				);
				$this->db->where('email', $this->session->userdata('user_email'));
				return $this->db->update(TBL_SUBSCRIBER, $data);
			}else{
				$data=array(
						'email'=>$this->session->userdata('user_email'),
						'is_deleted'=>IS_DELETED_NO,
						'created'=>DB_CURRENT_DATE,
						'modified'=>DB_CURRENT_DATE
					);
				return $this->db->insert(TBL_SUBSCRIBER, $data);
			}
		}
	}
	
	public function get_account_info()
	{
		return $this->db->where(['id'=>$this->session->userdata('user_id')])->get(TBL_USERS)->row_array();
	}
	
	public function update_user_profile()
	{
		$profile = $this->input->post('profile');
		$data=array(
				'fname'=>$profile['firstname'],
				'mname'=>$profile['middlename'],
				'lname'=>$profile['lastname'],
				'mobile'=>$profile['mobile'],
				'gender'=>$profile['geder'],
				'country_id'=>$profile['country_id'],
				'state_id'=>$profile['state_id'],
				'city_id'=>$profile['city_id'],
				'post_code'=>$profile['post_code'],
				'address'=>$profile['address'],
				'dob'=>date(DB_CURRENT_DATE_FORMAT,strtotime($profile['dob'])),
				'modified'=>DB_CURRENT_DATE
			);
		
		if($profile['password']!=''){
			$data['password']=MD5($profile['password']);
		}
		
		$this->db->where(['id'=>$this->session->userdata('user_id')]);
		return $this->db->update(TBL_USERS,$data);		
	}
	
	public function become_seller_request()
	{
		$data=array(
				'seller_request	'=>1,
				'modified'=>DB_CURRENT_DATE
			);
		$this->db->where(['id'=>$this->session->userdata('user_id')]);
		return ($this->db->update(TBL_USERS,$data))?1:0;
	}
	
	var $seller_request_order_column = array("id", "fname", "status", "type");
	public function get_fetch_seller_request()
	{
		$sSearch = $this->input->post('search');
		$order=$this->input->post('order')['0'];
		$iDisplayStart=$this->input->post('start');
		$iDisplayLength=$this->input->post('length');
		 
		//filtering data by search value
		if(isset($sSearch['value']))  
	    {  
			$where_like= "(	fname LIKE '%".$sSearch['value']."%' OR mname LIKE '%".$sSearch['value']."%' OR  lname LIKE '%".$sSearch['value']."%' OR  email  LIKE '%".$sSearch['value']."%' OR  mobile LIKE '%".$sSearch['value']."%')";
			$this->db->where($where_like); 
	    } 
		//ordering column
		(isset($_POST["order"]))?$this->db->order_by($this->seller_request_order_column[$order['column']], $order['dir']):$this->db->order_by('id', 'DESC');	
		
		//get the count of records after filtering 
		$this->db->where('seller_request',1);
		$this->db->where('is_delete',0);
		$this->db->where('type !=','1');
		$recordfilter_query = $this->db->get(TBL_USERS);  
		$recordsFiltered=$recordfilter_query->num_rows();
		
		//filtering data by search value
		if(isset($sSearch['value']))  
	    {  
			$where_like= "(	fname LIKE '%".$sSearch['value']."%' OR mname LIKE '%".$sSearch['value']."%' OR  lname LIKE '%".$sSearch['value']."%' OR  email  LIKE '%".$sSearch['value']."%' OR  mobile LIKE '%".$sSearch['value']."%')";
			$this->db->where($where_like); 
	    } 
		//ordering column
		(isset($_POST["order"]))?$this->db->order_by($this->seller_request_order_column[$order['column']], $order['dir']):$this->db->order_by('id', 'DESC');	
		
		
		//paging data table
		if (isset($iDisplayStart) && $iDisplayLength != '-1') {
			$this->db->limit($iDisplayLength, $iDisplayStart);
        }
		
		//fetch all user data
		$this->db->where('seller_request',1);
		$this->db->where('is_delete',0);
		$this->db->where('type !=','1');		
        $fetch_query = $this->db->get(TBL_USERS);  
		$fetch_data=$fetch_query->result();  

		$data = array();
		$cnt=$this->input->post('start')+1;		   
	    foreach($fetch_data as $row)  
	    {  
			$selected=($row->type==2)?'selected="selected"':'';
			$sub_array = array();  
			$sub_array[] =$cnt;
			$sub_array[] = $row->fname." ".$row->mname." ".$row->lname;  
			$sub_array[] = ($row->is_active)?'<td><span class="label label-success">Active</span></td>':'<td><span class="label label-danger">Inactive</span></td>';
			$sub_array[] = '<select id="type_changes" onchange="change_type(this.value,'.$row->id.');"><option value="3">Customer</option><option value="2" '.$selected.'>Marchant</option></select>';
		    $data[] = $sub_array;  
			$cnt++;
	    }
		
		//get the total record count
		$this->db->where('seller_request',1);
		$this->db->where('is_delete',0); 
		$this->db->where('type !=','1');
		$recordsTotal=$this->db->count_all_results(TBL_USERS);
		
		$output = array(  
			"draw"            => intval($this->input->post("draw")),  
			"recordsTotal"    => $recordsTotal,  
			"recordsFiltered" => $recordsFiltered,  
			"data"            => $data  
	    );  
		return $output;  
	}
	
	public function change_customer_type($type,$uid)
	{
		$data=array('seller_request'=>0,'type'=>$type,'modified'=>DB_CURRENT_DATE);
		$this->db->where(['id'=>$uid,'seller_request'=>1]);
		return ($this->db->update(TBL_USERS,$data))?1:0;
	}
	
	
}
?>