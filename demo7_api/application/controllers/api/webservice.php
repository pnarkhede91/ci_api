<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");
class webservice extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('webservice_model');
		date_default_timezone_set('Asia/Kolkata');
		 header("Access-Control-Allow-Origin: *");
 // header("Access-Control-Allow-Headers: X-Custom-Header");
		header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
	}
	public function index()
	{
		echo "Welcome....";
	}
	
	public function signUp()
	{
		$result=$this->webservice_model->signUp();
		if($result==-1)
		{
			echo json_encode(array('result'=>'failed','message'=>'username or email already exist. Please try once again.'));
			exit;
		}
		else if($result==0)
		{
			echo json_encode(array('result'=>'failed','message'=>'Error in signup process. Please try once again.'));			
			exit;
		}
		else
		{
			echo json_encode(array('result'=>'success','message'=>'Resgiration process done successfully..','Data'=>array('login_id'=>$result['id'],'email'=>$result['email'],'username'=>$result['username'])));
			exit;
		}
	}
	
	public function login()
	{
		$result=$this->webservice_model->login();
		
		if($result)
		{
			
			echo json_encode(
							array('result'=>'success',
								'message'=>'Login process done successfully.',
								'Data'=>array('login_id'=>$result['et_id'],'email'=>$result['et_email'],'username'=>$result['et_name'])));
			
			exit;
		}
		else
		{
			echo json_encode(array('result'=>'failed','message'=>'Error in Login process. Please try once again.'));
			exit;
		}
	}
	
	public function getDistrictList()
	{
		
		$result=$this->webservice_model->getDistrictList();
		
		
		
		if($result)
		{
			echo json_encode(array('result'=>'success','message'=>'Successfully get country  details.','data'=>$result));
        	exit;			
		}		
        else
		{
			echo json_encode(array('result'=>'failed','message'=>'Error in Fetching process. Please try once again.'));
			exit;
		}
	}
	
	public function getTalukaList()
	{
		$result=$this->webservice_model->getTalukaList();
		if($result)
		{
			echo json_encode(array('result'=>'success','message'=>'Successfully get country  details.','data'=>$result));
        	exit;			
		}		
        else
		{
			echo json_encode(array('result'=>'failed','message'=>'Error in Fetching process. Please try once again.'));
			exit;
		}
	}
	public function getPlaceList()
	{
		$result=$this->webservice_model->getPlaceList();
		if($result)
		{
			echo json_encode(array('result'=>'success','message'=>'Successfully get country  details.','data'=>$result));
        	exit;			
		}		
        else
		{
			echo json_encode(array('result'=>'failed','message'=>'Error in Fetching process. Please try once again.'));
			exit;
		}
	}
	
	public function getCropList()
	{
		$result=$this->webservice_model->getCropList();
		
		if($result)
		{
			echo json_encode(array('result'=>'success','message'=>'Successfully get country  details.','data'=>$result));
		}
		else
		{
			echo json_encode(array('result'=>'failed','message'=>'Error in Fetching process. Please try once again.'));
			exit;
		}
		
		
	}
	public function farmerRecordEntry()
	{
		$result=$this->webservice_model->farmerRecordEntry();
		
		if($result)
		{
			echo json_encode(array('result'=>'success','message'=>'Successfully store.'));
		}
		else
		{
			echo json_encode(array('result'=>'failed','message'=>'Error in Fetching process. Please try once again.'));
			exit;
		}
	}
	public function farmerRecordEntryList()
	{
		$result=$this->webservice_model->farmerRecordEntryList();
		
		if($result)
		{
			echo json_encode(array('result'=>'success','message'=>'Successfully store.','data'=>$result));
		}
		else
		{
			echo json_encode(array('result'=>'failed','message'=>'Error in Fetching process. Please try once again.'));
			exit;
		}
	}
	public function RemoveFarmerRecord()
	{
		$result=$this->webservice_model->RemoveFarmerRecord();
		
		if($result)
		{
			echo json_encode(array('result'=>'success','message'=>'Successfully store.','data'=>$result));
		}
		else
		{
			echo json_encode(array('result'=>'failed','message'=>'Error in Fetching process. Please try once again.'));
			exit;
		}
	}
	public function farmerRecordUpdate()
	{
		$result=$this->webservice_model->farmerRecordUpdate();
		
		if($result)
		{
			echo json_encode(array('result'=>'success','message'=>'Successfully store.'));
		}
		else
		{
			echo json_encode(array('result'=>'failed','message'=>'Error in Fetching process. Please try once again.'));
			exit;
		}
	}
	
	public function getPermission()
	{
		$per=["ADMIN", "EDITOR","OFFICER"];
		echo json_encode($per);
	}
	
	public function checkReportList()
	{
		$result=$this->webservice_model->checkReportList();
		
		if($result)
		{
			echo json_encode(array('result'=>'success','message'=>'Successfully store.','data'=>$result));
		}
		else
		{
			echo json_encode(array('result'=>'failed','message'=>'Error in Fetching process. Please try once again.'));
			exit;
		}
	}
	public function uploadFile()
	{
		$data=json_decode($_REQUEST['dt_id1']);
		
		foreach($data as $key => $value)
		{
			 echo $key."=".$value."<br>";
		}
		
		
		exit;
		//int_r($_FILES);
		$config['upload_path']='uploads/';
		$config['allowed_types']        = 'gif|jpg|png';
		$config['allowed_types']        = 'gif|jpg|png';
        //$config['max_size']             = 1000000;
        //$config['max_width']            = 1024;
        //$config['max_height']           = 768;
		$this->load->library('upload', $config);
		if($this->upload->do_upload('imageFile'))
		{
			echo json_encode(array('result'=>'success','message'=>'Profile updated successfully.',));
			exit;
			
		}
		else
		{
			 $error = array('error' => $this->upload->display_errors());

                        print_r($error);
		}
		
	}
	

                
	
	
	public function profile()
	{
		$result=$this->webservice_model->update_profile();
		if($result==-1)
		{
			echo json_encode(array('result'=>'failed','message'=>'username or email already exist. Please try once again.'));
			exit;
		}
		else if($result>0)
		{
			echo json_encode(array('result'=>'success','message'=>'Profile updated successfully.'));
			exit;
			
		}
		else
		{
			echo json_encode(array('result'=>'failed','message'=>'Error while update profile. Please try once again.'));			
			exit;
		}
	}
	
	public function changePassword()
	{
		$result=$this->webservice_model->change_password();
		if($result==-1)
		{
			echo json_encode(array('result'=>'failed','message'=>'Username not exist. Please try once again.'));
			exit;
		}
		else if($result==0)
		{
			echo json_encode(array('result'=>'failed','message'=>'Error while change the password. Please try once again.'));
			exit;
		}
		else if($result==2)
		{
			echo json_encode(array('result'=>'failed','message'=>'Password does not match. Please try once again.'));			
			exit;
		}
		else
		{
			echo json_encode(array('result'=>'success','message'=>'Password changed successfully.'));
			exit;
		}
	}
	
	public function fetch_country()
	{
		$result=$this->webservice_model->fetch_country();
		if($result)
		{
			echo json_encode(array('result'=>'success','message'=>'Successfully get country  details.','data'=>$result));
        	exit;			
		}		
        else
		{
			echo json_encode(array('result'=>'failed','message'=>'Error in Fetching process. Please try once again.'));
			exit;
		}
	}
	
	public function fetch_state()
	{
		$result=$this->webservice_model->fetch_state();
		if($result)
		{
			echo json_encode(array('result'=>'success','message'=>'Successfully get state  details.','data'=>$result));
        	exit;			
		}		
        else
		{
			echo json_encode(array('result'=>'failed','message'=>'Error in Fetching process. Please try once again.'));
			exit;
		}
	}
	
	public function fetch_city()
	{
		$result=$this->webservice_model->fetch_city();
		if($result)
		{
			echo json_encode(array('result'=>'success','message'=>'Successfully get city details.','data'=>$result));
        	exit;			
		}		
        else
		{
			echo json_encode(array('result'=>'failed','message'=>'Error in Fetching process. Please try once again.'));
			exit;
		}
	}
	
	public function lender_list()
	{
		$result=$this->webservice_model->lender_list();
		if($result)
		{
			echo json_encode(array('result'=>'success','message'=>'Successfully get lender list.','data'=>$result));
        	exit;			
		}		
        else
		{
			echo json_encode(array('result'=>'failed','message'=>'Error in Fetching process. Please try once again.'));
			exit;
		}
	}
	
	public function borrower_list()
	{
		$result=$this->webservice_model->borrower_list();
		if($result)
		{
			echo json_encode(array('result'=>'success','message'=>'Successfully get lender list.','data'=>$result));
        	exit;			
		}		
        else
		{
			echo json_encode(array('result'=>'failed','message'=>'Error in Fetching process. Please try once again.'));
			exit;
		}
	}
	
	public function add_bank_details()
	{
		$result=$this->webservice_model->add_bank_details();
		if($result==-1)
		{
			echo json_encode(array('result'=>'failed','message'=>'Bank details already available for user. Please try once again.'));
			exit;
		}
		else if($result==0)
		{
			echo json_encode(array('result'=>'failed','message'=>'Error in add bank details process. Please try once again.'));			
			exit;
		}
		else
		{
			echo json_encode(array('result'=>'success','message'=>'Bank Details added successfully.'));
			exit;
		}
	}
	
	public function update_bank_details()
	{
		$result=$this->webservice_model->update_bank_details();
		if($result==-1)
		{
			echo json_encode(array('result'=>'failed','message'=>'Bank details already available. Please try once again.'));
			exit;
		}
		else if($result>0)
		{
			echo json_encode(array('result'=>'success','message'=>'Bank details updated successfully.'));
			exit;
			
		}
		else
		{
			echo json_encode(array('result'=>'failed','message'=>'Error while update bank details. Please try once again.'));			
			exit;
		}
	}
	
	public function update_firebase_id()
	{
		$result=$this->webservice_model->update_firebase_id();
		if($result>0)
		{
			echo json_encode(array('result'=>'success','message'=>'Firebase Id updated successfully.'));
			exit;
			
		}
		else
		{
			echo json_encode(array('result'=>'failed','message'=>'Error while update firebase id. Please try once again.'));			
			exit;
		}
	}
	
	public function borrower_request()
	{
		$result=$this->webservice_model->borrower_request();
		if($result>0)
		{
			echo json_encode(array('result'=>'success','message'=>'Borrower request send successfully.'));
			exit;
			
		}
		else
		{
			echo json_encode(array('result'=>'failed','message'=>'Error while send request. Please try once again.'));			
			exit;
		}	
	}
}
?>