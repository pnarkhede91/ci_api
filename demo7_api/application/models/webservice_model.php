<?php
class webservice_model extends CI_model
{
	function signUp()
	{
		$name=$this->input->post('name');
		$address=$this->input->post('address');
		$email=$this->input->post('email');
		$username=$this->input->post('username');
		$password=$this->input->post('password');
		$city_id=$this->input->post('city_id');
		$country_id=$this->input->post('country_id');
		$occupation=$this->input->post('occupation');
		$firebase_id=$this->input->post('firebase_id');
		$user_type=$this->input->post('user_type');
		
		//check email id exist of not
		$this->db->where('email',$email);	
		$this->db->or_where('username',$username);			
		$result=$this->db->get('tbl_users');
		$num_rows=$result->num_rows();
		if($num_rows<=0)
		{
			
			if( $name!='' && $address!='' && $email != '' && $username != '' && $city_id!='' && $country_id!='' && $occupation!='' && $firebase_id!='' && $user_type!='')
			{
				$image		=	'';
				if(isset($_FILES['user_img']))
				{	$config['upload_path'] = 'uploads/';
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['max_size']	= '100';
					$config['file_name']=rand();
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if (!$this->upload->do_upload('user_img'))
					{
						$data['error'] = $this->upload->display_errors();
					}
					else
					{
						$upload_data = array('upload_data' => $this->upload->data());
						$image	=$upload_data['upload_data']['file_name'];
					}
				}
			
				$this->db->insert('tbl_users',array('name'=>$name,'email'=>$email,'username'=>$username,'password'=>md5($password),'address'=>$address,'city_id'=>$city_id,'country_id'=>$country_id,'occupation'=>$occupation,'firebase_id'=>$firebase_id,'user_type'=>$user_type,'profile_img'=>$image, 'created'=>date("Y-m-d"), 'modified'=>date("Y-m-d")));
				$insert_id=(int)$this->db->insert_id();
				
				if($user_type=='0')
				{
					$this->db->insert('tbl_borrower',array('user_id'=>$insert_id,'borrower_name'=>$name, 'created'=>date("Y-m-d"), 'modified'=>date("Y-m-d")));//borrower registartion
				}
				else if($user_type=='1')
				{
					$this->db->insert('tbl_lender',array('user_id'=>$insert_id,'lender_name'=>$name, 'created'=>date("Y-m-d"), 'modified'=>date("Y-m-d")));//lender registartion
				}
				else if($user_type=='2')
				{
					$this->db->insert('tbl_borrower',array('user_id'=>$insert_id,'borrower_name'=>$name, 'created'=>date("Y-m-d"), 'modified'=>date("Y-m-d")));//borrower registartion
					$this->db->insert('tbl_lender',array('user_id'=>$insert_id,'lender_name'=>$name, 'created'=>date("Y-m-d"), 'modified'=>date("Y-m-d")));//lender registartion
				}
				
				$this->db->select('id,email,username');
				$this->db->where('id',$insert_id);		
				$fetch_query=$this->db->get('tbl_users');
				$result=$fetch_query->row_array(); 
				return $result;  
			}
			else
			{
				return 0;
			}				
		}
		else
		{
			return -1;
		}
		
	}
	
	public function login()
	{
		$username=$this->input->get('username');
		$password=$this->input->get('password');
		
		$this->db->where('user',$username);
		$this->db->where('pass',$password);

		$result=$this->db->get('employee_table');
		return $result->row_array();
	}
	
	public function getDistrictList()
	{
		$result=$this->db->get('district_table');
		//print_r($result);
		return $result->result_array();
	}
	
	public function getTalukaList()
	{
		$this->db->where('fk_dt_id',$this->input->get('dt_id'));
		$result=$this->db->get('taluka_table');
		//print_r($result);
		return $result->result_array();
	}
	public function getPlaceList()
	{
		$this->db->where('fk_tt_id',$this->input->get('tt_id'));
		$result=$this->db->get('place_table');
		//print_r($result);
		return $result->result_array();
	}
	public function getCropList()
	{
		
		$result=$this->db->get('crop');
		//print_r($result);
		return $result->result_array();
	}
	
	public function farmerRecordEntry()
	{
		$dt_id=$this->input->get('dt_id');
		$farmer_name=$this->input->get('farmer_name');
		$fk_crop_id=$this->input->get('fk_crop_id');
		$follow_up=$this->input->get('follow_up');
		$follow_up_date=$this->input->get('follow_up_date');
		$mobile_no=$this->input->get('mobile_no');
		$tt_id=$this->input->get('tt_id');
		$loginId=$this->input->get('loginId');
		$pt_id=$this->input->get('pt_id');
		$acarage=$this->input->get('acarage');
		
		if($loginId !="")
		{
			return $result=$this->db->query("INSERT INTO `temp_farmer_daily_report`( `dt_id`, `tt_id`, `farmer_name`, `mobile_no`, `fk_crop_id`, `follow_up`, `follow_up_date`,`fk_login_id`,`pt_id`,`acarage`) VALUES ('".$dt_id."','".$tt_id."','".$farmer_name."','".$mobile_no."','".$fk_crop_id."','".$follow_up."','".$follow_up_date."','".$loginId."','".$pt_id."','".$acarage."')");
		}
		
	}
	public function farmerRecordUpdate()
	{
		$dt_id=$this->input->get('dt_id');
		$farmer_name=$this->input->get('farmer_name');
		$fk_crop_id=$this->input->get('fk_crop_id');
		$follow_up=$this->input->get('follow_up');
		$follow_up_date=$this->input->get('follow_up_date');
		$mobile_no=$this->input->get('mobile_no');
		$tt_id=$this->input->get('tt_id');
		$loginId=$this->input->get('loginId');
		$pt_id=$this->input->get('pt_id');
		$acarage=$this->input->get('acarage');
		$tempf_id=$this->input->get('tempf_id');
		
		$this->db->where('tempf_id',$tempf_id);
		return $this->db->update('temp_farmer_daily_report',array('farmer_name'=>$farmer_name,'mobile_no'=>$mobile_no,'fk_crop_id'=>$fk_crop_id,'follow_up'=>$follow_up,'follow_up_date'=>$follow_up_date, 'pt_id'=>$pt_id,'acarage'=>$acarage,'dt_id'=>$dt_id,'tt_id'=>$tt_id));
		
		
	}
	
	public function farmerRecordEntryList()
	{
		$loginId=$this->input->get('loginId');
	
		
			$result=$this->db->query("SELECT `tempf_id`, TFDR.`dt_id`, TFDR.`tt_id`, `farmer_name`, `mobile_no`, `fk_crop_id`, `follow_up`, `follow_up_date`, `fk_login_id` ,TFDR.`pt_id`,`crop_name`,`dt_district`,`tt_taluka`,`pt_place`,`acarage`
			FROM `temp_farmer_daily_report` TFDR
			LEFT JOIN district_table DT
			ON TFDR.dt_id=DT.dt_id 
			LEFT JOIN taluka_table TT
			ON TFDR.tt_id=TT.tt_id 
			LEFT JOIN place_table PT
			ON TFDR.pt_id=PT.pt_id 
			LEFT JOIN crop C
			ON TFDR.fk_crop_id=C.crop_id 
			
			WHERE fk_login_id='".$loginId."'");
			
			return $result->result_array();
		
		
	}
	
		public function checkReportList()
	{
		$loginId=$this->input->get('loginId');
	
		
			$result=$this->db->query("SELECT `dwr_id`, `fk_et_id`, `dwr_date`, `dwr_time`,`et_name` 
			FROM `dwr_table` DT,
				`employee_table` ET
			WHERE DT.fk_et_id=ET.et_id AND fk_et_id='".$loginId."'");
			
			return $result->result_array();
		
		
	}
	
	public function RemoveFarmerRecord()
	{
		$tempf_id=$this->input->get('tempf_id');
	
		
		return $result=$this->db->query("DELETE FROM `temp_farmer_daily_report` WHERE `tempf_id`='".$tempf_id."'");
			
		
		
		
	}
	
	
	public function update_profile()
	{
		$login_id=$this->input->post('login_id');
		$name=$this->input->post('name');
		$username=$this->input->post('username');
		$address=$this->input->post('address');
		$email=$this->input->post('email');
		$city_id=$this->input->post('city_id');
		$country_id=$this->input->post('country_id');
		$occupation=$this->input->post('occupation');
		
		//check email id exist of not
		$this->db->where('username',$username);
		$this->db->or_where('email',$email);
		$this->db->where('id !=',$login_id);		
		$result=$this->db->get('tbl_users');
		$num_rows=$result->num_rows();
		
		if($num_rows<=0)
		{
			if( $login_id!='' && $name!='' && $username!='' && $address!='' && $email != '' && $city_id!='' && $country_id!='' && $occupation!='')
			{
				$this->db->where('id',$login_id);		
				$this->db->update('tbl_users',array('name'=>$name,'email'=>$email,'username'=>$username,'address'=>$address,'city_id'=>$city_id,'country_id'=>$country_id,'occupation'=>$occupation, 'modified'=>date("Y-m-d")));
				return 1;  
			}
			else
			{
				return 0;
			}				
		}
		else
		{
			return -1;
		}
	}
	
	public function change_password()
	{
		$username=$this->input->post('username');
		$password=$this->input->post('password');
		$confirm_password=$this->input->post('confirm_password');
		
		//check email id exist of not
		$this->db->where('username',$username);		
		$result=$this->db->get('tbl_users');
		$num_rows=$result->num_rows();
		
		if($num_rows>0)
		{
			if($username!='' && $password!='' && $confirm_password!= '')
			{
				if($password==$confirm_password)
				{
					$this->db->where('username',$username);		
					$this->db->update('tbl_users',array('password'=>md5($confirm_password), 'modified'=>date("Y-m-d")));
					return 1;
				}
				else
				{
					return 2;
				}
			}
			else
			{
				return 0;
			}				
		}
		else
		{
			return -1;
		}
	}
	
	public function fetch_country()
	{
		$this->db->where('is_active','1');
		$country_data=$this->db->get('tbl_country');
		return $country_data->result_array();
	}
	
	public function fetch_state()
	{
		$country_id=$this->input->post('country_id');
		
		$this->db->select('tbl_state.id,tbl_state.name as state_name,tbl_state.country_id,tbl_state.is_active');
		if($country_id!='')
		{
			$this->db->where('tbl_state.country_id',$country_id);
		}
		$this->db->where('tbl_country.is_active','1');
		$this->db->where('tbl_state.is_active','1');
		$this->db->join('tbl_country','tbl_country.id=tbl_state.country_id');
		$state_data=$this->db->get('tbl_state');
		return $state_data->result_array();
	}
	
	public function fetch_city()
	{
		$state_id=$this->input->post('state_id');
		
		$this->db->select('tbl_city.id,tbl_city.name as city_name,tbl_city.state_id,tbl_city.is_active');
		if($state_id!='')
		{
			$this->db->where('tbl_city.state_id',$state_id);
		}
		$this->db->where('tbl_city.is_active','1');
		$this->db->where('tbl_state.is_active','1');
		$this->db->join('tbl_state','tbl_state.id=tbl_city.state_id');		
		$city_data=$this->db->get('tbl_city');
		return $city_data->result_array();
	}
	
	public function lender_list()
	{
		$login_id=$this->input->post('login_id');
		if($login_id!='' )
		{
			$this->db->where('user_id !=',$login_id);
			$lender_data=$this->db->get('tbl_lender');
			return $lender_data->result_array();
		}
		else
		{
			return -1;
		}
	}
	
	public function borrower_list()
	{
		$login_id=$this->input->post('login_id');
		
		if($login_id!='' )
		{
			$this->db->where('user_id !=',$login_id);
			$borrower_data=$this->db->get('tbl_borrower');
			return $borrower_data->result_array();
		}
		else
		{
			return -1;
		}
	}
	
	public function add_bank_details()
	{
		$login_id=$this->input->post('login_id');
		$bank_name=$this->input->post('bank_name');
		$branch_name=$this->input->post('branch_name');
		$ifsc_code=$this->input->post('ifsc_code');
		$account_name=$this->input->post('account_name');
		$account_number=$this->input->post('account_number');
		
		if( $bank_name!='' && $branch_name!='' && $ifsc_code != '' && $account_name != '' && $account_number!='')
		{
			$this->db->where('user_id',$login_id);
			$this->db->where('account_number',$account_number);
			$bank_details=$this->db->get('tbl_bank');
			$num_rows=$bank_details->num_rows();
			if($num_rows<=0)
			{
				$this->db->insert('tbl_bank',array('user_id'=>$login_id,'bank_name'=>$bank_name,'branch_name'=>$branch_name,'ifsc_code'=>$ifsc_code,'account_name '=>$account_name,'account_number'=>$account_number, 'created'=>date("Y-m-d h:i:s"), 'modified'=>date("Y-m-d h:i:s")));
				$insert_id=(int)$this->db->insert_id();
				return $insert_id;  
			}
			else
			{
				return 0;
			}
		}
		else
		{
			return -1;
		}	
	}
	
	public function update_bank_details()
	{
		$login_id=$this->input->post('login_id');
		$bank_name=$this->input->post('bank_name');
		$branch_name=$this->input->post('branch_name');
		$ifsc_code=$this->input->post('ifsc_code');
		$account_name=$this->input->post('account_name');
		$account_number=$this->input->post('account_number');
		
		if( $bank_name!='' && $branch_name!='' && $ifsc_code != '' && $account_name != '' && $account_number!='')
		{
			$this->db->where('user_id !=',$login_id);
			$this->db->where('account_number',$account_number);
			$bank_details=$this->db->get('tbl_bank');
			$num_rows=$bank_details->num_rows();
			if($num_rows<=0)
			{
				$this->db->where('user_id',$login_id);
				$this->db->update('tbl_bank',array('bank_name'=>$bank_name,'branch_name'=>$branch_name,'ifsc_code'=>$ifsc_code,'account_name '=>$account_name,'account_number'=>$account_number, 'modified'=>date("Y-m-d h:i:s")));
				return 1;  
			}
			else
			{
				return 0;
			}
		}
		else
		{
			return -1;
		}	
	}
	
	public function update_firebase_id()
	{
		$login_id=$this->input->post('login_id');
		$firebase_id=$this->input->post('firebase_id');
		if($login_id!='' && $firebase_id!='')
		{
			$this->db->where('id',$login_id);
			$this->db->update('tbl_users',array('firebase_id'=>$firebase_id, 'modified'=>date("Y-m-d h:i:s")));
			return 1;  
		}
		else
		{
			return 0;
		}	
	}
	
	public function borrower_request()
	{
		$login_id=$this->input->post('login_id');
		$amount=$this->input->post('amount');
		$days=$this->input->post('days');
		$interest_rate=$this->input->post('interest_rate');
		if($login_id!='' && $amount!='' && $days!='' && $interest_rate!='')
		{
			$this->db->insert('tbl_borrower_request',array('user_id'=>$login_id,'amount'=>$amount,'days'=>$days,'rate'=>$interest_rate,'status'=>1, 'created'=>date("Y-m-d h:i:s"), 'modified'=>date("Y-m-d h:i:s")));
			$insert_id=(int)$this->db->insert_id();
			
			//get the lender
			$this->db->select('id,name,email,firebase_id');
			$this->db->where('is_active','1');
			$this->db->where('user_type','1');
			$result=$this->db->get('tbl_users');
			$lender_info=$result->result_array();
			$token=array();
			foreach($lender_info as $lender)
			{
				array_push($token, $lender['firebase_id']);				
			}
			$message = array(
							"Title" => "Borrower Request",
							"Message" => "Borrower have send request to borrow a loan 25000 for 10 days"
							);  
			$this->send_android_notification($token,$message);
			return $insert_id;  
		}
		else
		{
			return 0;
		}
	}
	
	public function send_android_notification($token,$message)
	{
		// Set POST variables
		$url = 'https://fcm.googleapis.com/fcm/send';
		$headers = array(
			'Authorization: key=' .FIREBASE_API_KEY,
			'Content-Type: application/json'
		);
		
		$fields = array(
				'to' => $token,
				'data' => $message
			);
			
		// Open connection
		$ch = curl_init();
 
		// Set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($ch);
		if ($result === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}
		echo '<pre>'; print_r($result); //die();
		// Close connection
		curl_close($ch);
		return 1;
			
	}
	
	/*public function sendPushNotification($cid,$pid,$title,$meesage='',$img='')
	{
		//pushnotification
		$this->db->select('firebase_id,user_name');
		$this->db->from('tbl_app_user');
		$this->db->where('constituency_id', $cid);
		$this->db->like('Panchayath_id', $pid);
		$user_data = $this->db->get();
		$app_user= $user_data->result_array();
		
		// push notification		
		$payload = array();
		$payload['app by'] = 'Borrower lender';
		//getpush
		$res=array();
		$res['data']['title'] = $title;
		$res['data']['is_background'] = FALSE;
		$res['data']['message'] = (($meesage != '')?$meesage:'You have new notification.');//$this->input->post('tender_details');
		$res['data']['image'] = $img;
		$res['data']['payload'] = $payload;
		$res['data']['timestamp'] = date('Y-m-d G:i:s');//json
	
		foreach($app_user as $user_row)
		{
			//echo '<br>'.$user_row['user_name'].'=>'.$user_row['firebase_id'].'<br><br>';
			$fields = array(
				'to' => $user_row['firebase_id'],
				'data' => $res
			);
			
			// Set POST variables
			$url = 'https://fcm.googleapis.com/fcm/send';
	 
			$headers = array(
				'Authorization: key=' .FIREBASE_API_KEY,
				'Content-Type: application/json'
			);
			
			// Open connection
			$ch = curl_init();
	 
			// Set the url, number of POST vars, POST data
			curl_setopt($ch, CURLOPT_URL, $url);
	 
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	 
			// Disabling SSL Certificate support temporarly
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	 
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	 
			// Execute post
			$result = curl_exec($ch);
			if ($result === FALSE) {
				die('Curl failed: ' . curl_error($ch));
			}
			//echo '<pre>'; print_r($result); //die();
			// Close connection
			curl_close($ch);
			
		}
		return 1;
    }*/
}
?>