<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Location_model extends CI_Model{      
	function __construct(){
		parent::__construct();
	}
	
	var $country_column = array("id", "name","is_active"); 
	var $state_column = array("id", "country_id","name","is_active"); 
	var $city_column = array("id", "country_id", "state_id","name","is_active"); 
	
	public function get_all_country()
	{
		$sSearch = $this->input->post('search');
		$order=$this->input->post('order')['0'];
		$iDisplayStart=$this->input->post('start');
		$iDisplayLength=$this->input->post('length');
		 
		//filtering data by search value
		if(isset($sSearch['value']))  
	    {  
			$where_like= "(name LIKE '%".$sSearch['value']."%')";
			$this->db->where($where_like);
			/*$this->db->like("name", $sSearch['value']); */ 
	    } 
		//ordering column
		(isset($_POST["order"]))?$this->db->order_by($this->country_column[$order['column']], $order['dir']):$this->db->order_by('id', 'DESC');	
		
		//get the count of records after filtering 
		$recordfilter_query = $this->db->get(TBL_COUNTRY);  
		$recordsFiltered=$recordfilter_query->num_rows();
		
		//paging data table
		if($this->input->post("length") != -1)  
        {  
            $this->db->limit($this->input->post('length'), $this->input->post('start'));  
        }
		
		//fetch all user data
		$fetch_query = $this->db->get(TBL_COUNTRY);  
		$fetch_data=$fetch_query->result();  

		$data = array();
		$cnt=$this->input->post('start')+1;		   
	    foreach($fetch_data as $row)  
	    {  
			$sub_array = array();  
			$sub_array[] =$cnt;
			$sub_array[] = $row->name; 
			//$sub_array[] = ($row->is_active)?'<td><span class="label label-success">Active</span></td>':'<td><span class="label label-danger">Inactive</span></td>';	
			$sub_array[] = ($row->is_active)?'<td><a class="status_inactive" onclick="update_status('."'".$row->id."'".',0)" href="javascript:void(0)"><span class="label label-success">Active</span></a></td>':'<td><a class="status_active"  onclick="update_status('."'".$row->id."'".',1)"  href="javascript:void(0)"><span class="label label-danger">Inactive</span></a></td>';			
			$sub_array[] = '<a href="javascript:void(0)" title="Edit" onclick="get_country('."'".$row->id."'".')"><i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i></a>';  
		    $data[] = $sub_array;  
			$cnt++;
	    }
		
		$output = array(  
			"draw"            => intval($this->input->post("draw")),  
			"recordsTotal"    => $this->db->count_all_results(TBL_COUNTRY),  
			"recordsFiltered" => $recordsFiltered,  
			"data"            => $data  
	    );  
		return $output;  
	}
	
	public function get_countryby_id($id)
    {
		$this->db->where('id',$id);
        return $this->db->get(TBL_COUNTRY)->row(); 
    }
	
	public function update_country($id,$data)
	{
		$result['message']='';	
		$result['status']='';			
		$this->db->where('name',$data['name']);
		$this->db->where('id !=',$id);
        $country_info=$this->db->get(TBL_COUNTRY)->row(); 
		
		if($country_info)
		{
			$result['message']=$data['name']." already exists.";
			$result['status']="error";
		}
		else
		{
			$this->db->where('id',$id);
			if($this->db->update(TBL_COUNTRY, $data))
			{
				$result['message']="Country has been updated successfully.";
				$result['status']="success";
			}
			else
			{
				$result['message']="Error while update country.";
				$result['status']="error";
			}	
		}
		return $result;		
	}
	
	public function add_country($data)
	{
		$result['message']='';	
		$result['status']='';			
		$this->db->where('name',$data['name']);
        $country_info=$this->db->get(TBL_COUNTRY)->row(); 
		
		if( $country_info)
		{
			$result['message']=$data['name']." already exists.";
			$result['status']="error";
		}
		else
		{
			if($this->db->insert(TBL_COUNTRY, $data))
			{
				$result['message']="Country has been added successfully.";
				$result['status']="success";
			}
			else
			{
				$result['message']="Error while add country.";
				$result['status']="error";
			}	
		}
		return $result;		
	}
	
	public function get_all_state()
	{
		$sSearch = $this->input->post('search');
		$order=$this->input->post('order')['0'];
		$iDisplayStart=$this->input->post('start');
		$iDisplayLength=$this->input->post('length');
		
		$this->db->select(TBL_STATE.'.id,'.TBL_COUNTRY.'.name as county_name,'.TBL_STATE.'.name as state_name,'.TBL_STATE.'.is_active ');
		//filtering data by search value
		if(isset($sSearch['value']))  
	    {  
			$where_like= "(".TBL_COUNTRY.".name LIKE '%".$sSearch['value']."%' OR ".TBL_STATE.".name LIKE '%".$sSearch['value']."%' )";
			$this->db->where($where_like);
			/*$this->db->like("name", $sSearch['value']); */ 
	    } 
		//ordering column
		(isset($_POST["order"]))?$this->db->order_by(TBL_STATE.".".$this->state_column[$order['column']], $order['dir']):$this->db->order_by(TBL_STATE.'id', 'DESC');	
		
		//get the count of records after filtering 
		$this->db->from(TBL_STATE);
		$this->db->join(TBL_COUNTRY, TBL_COUNTRY.'.id = '.TBL_STATE.'.country_id', 'left');
		$recordfilter_query = $this->db->get();  
		$recordsFiltered=$recordfilter_query->num_rows();
		
		//paging data table
		if($this->input->post("length") != -1)  
        {  
            $this->db->limit($this->input->post('length'), $this->input->post('start'));  
        }
		
		//fetch all user data
		$this->db->select(TBL_STATE.'.id,'.TBL_COUNTRY.'.name as county_name,'.TBL_STATE.'.name as state_name,'.TBL_STATE.'.is_active ');		
		$this->db->from(TBL_STATE);	
		//filtering data by search value
		if(isset($sSearch['value']))  
	    {  
			$where_like= "(".TBL_COUNTRY.".name LIKE '%".$sSearch['value']."%' OR ".TBL_STATE.".name LIKE '%".$sSearch['value']."%' )";
			$this->db->where($where_like);
	    } 
		$this->db->join(TBL_COUNTRY, TBL_COUNTRY.'.id = '.TBL_STATE.'.country_id', 'left');
		$fetch_query = $this->db->get();  
		$fetch_data=$fetch_query->result(); 
		
		$data = array();
		$cnt=$this->input->post('start')+1;		   
	    foreach($fetch_data as $row)  
	    {  
			$sub_array = array();  
			$sub_array[] =$cnt;
			$sub_array[] = $row->county_name; 
			$sub_array[] = $row->state_name; 
			//$sub_array[] = ($row->is_active)?'<td><span class="label label-success">Active</span></td>':'<td><span class="label label-danger">Inactive</span></td>';	
			$sub_array[] = ($row->is_active)?'<td><a class="status_inactive" onclick="update_status('."'".$row->id."'".',0)" href="javascript:void(0)"><span class="label label-success">Active</span></a></td>':'<td><a class="status_active"  onclick="update_status('."'".$row->id."'".',1)"  href="javascript:void(0)"><span class="label label-danger">Inactive</span></a></td>';					
			$sub_array[] = '<a href="javascript:void(0)" title="Edit" onclick="get_state('."'".$row->id."'".')"><i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i></a>';  
		    $data[] = $sub_array;  
			$cnt++;
	    }
		
		$output = array(  
			"draw"            => intval($this->input->post("draw")),  
			"recordsTotal"    => $this->db->count_all_results(TBL_STATE),  
			"recordsFiltered" => $recordsFiltered,  
			"data"            => $data  
	    );  
		return $output;  
	}
	
	public function get_country()
	{
					$this->db->where('is_active',1);
		$fetch_query = $this->db->get(TBL_COUNTRY);  
		$country_data=$fetch_query->result(); 
		return $country_data;
	}
	
	public function get_stateby_id($id)
    {
		$this->db->where('id',$id);
        return $this->db->get(TBL_STATE)->row(); 
    }
	
	public function update_state($id,$data)
	{
		$result['message']='';	
		$result['status']='';			
		$this->db->where('name',$data['name']);
		$this->db->where('id !=',$id);
        $country_info=$this->db->get(TBL_STATE)->row(); 
		
		if($country_info)
		{
			$result['message']=$data['name']." already exists.";
			$result['status']="error";
		}
		else
		{
			$this->db->where('id',$id);
			if($this->db->update(TBL_STATE, $data))
			{
				$result['message']="State has been updated successfully.";
				$result['status']="success";
			}
			else
			{
				$result['message']="Error while update state.";
				$result['status']="error";
			}	
		}
		return $result;		
	}
	
	public function add_state($data)
	{
		$result['message']='';	
		$result['status']='';			
		$this->db->where('name',$data['name']);
        $state_info=$this->db->get(TBL_STATE)->row(); 
		
		if($state_info)
		{
			$result['message']=$data['name']." already exists.";
			$result['status']="error";
		}
		else
		{
			if($this->db->insert(TBL_STATE, $data))
			{
				$result['message']="State added successfully.";
				$result['status']="success";
			}
			else
			{
				$result['message']="Error while add state.";
				$result['status']="error";
			}	
		}
		return $result;		
	}
	
	public function get_all_city()
	{
		$sSearch = $this->input->post('search');
		$order=$this->input->post('order')['0'];
		$iDisplayStart=$this->input->post('start');
		$iDisplayLength=$this->input->post('length');
		
		$this->db->select(TBL_CITY.'.id,'.TBL_COUNTRY.'.name as county_name,'.TBL_STATE.'.name as state_name,'.TBL_CITY.'.name as city_name,'.TBL_CITY.'.is_active ');
		//filtering data by search value
		if(isset($sSearch['value']))  
	    {  
			$where_like= "(".TBL_COUNTRY.".name LIKE '%".$sSearch['value']."%' OR ".TBL_STATE.".name LIKE '%".$sSearch['value']."%' OR ".TBL_CITY.".name LIKE '%".$sSearch['value']."%')";
			$this->db->where($where_like);
			/*$this->db->like("name", $sSearch['value']); */ 
	    } 
		//ordering column
		(isset($_POST["order"]))?$this->db->order_by(TBL_CITY.".".$this->city_column[$order['column']], $order['dir']):$this->db->order_by(TBL_CITY.'id', 'DESC');	
		
		//get the count of records after filtering 
		$this->db->from(TBL_CITY);
		$this->db->join(TBL_COUNTRY, TBL_COUNTRY.'.id = '.TBL_CITY.'.country_id', 'left');
		$this->db->join(TBL_STATE, TBL_STATE.'.id = '.TBL_CITY.'.state_id', 'left');
		$recordfilter_query = $this->db->get();  
		$recordsFiltered=$recordfilter_query->num_rows();
		
		//paging data table
		if($this->input->post("length") != -1)  
        {  
            $this->db->limit($this->input->post('length'), $this->input->post('start'));  
        }
		
		//fetch all user data
		$this->db->select(TBL_CITY.'.id,'.TBL_COUNTRY.'.name as county_name,'.TBL_STATE.'.name as state_name,'.TBL_CITY.'.name as city_name,'.TBL_CITY.'.is_active ');
		$this->db->from(TBL_CITY);	
		//filtering data by search value
		if(isset($sSearch['value']))  
	    {  
			$where_like= "(".TBL_COUNTRY.".name LIKE '%".$sSearch['value']."%' OR ".TBL_STATE.".name LIKE '%".$sSearch['value']."%' OR ".TBL_CITY.".name LIKE '%".$sSearch['value']."%')";
			$this->db->where($where_like);
	    } 
		$this->db->join(TBL_COUNTRY, TBL_COUNTRY.'.id = '.TBL_CITY.'.country_id', 'left');
		$this->db->join(TBL_STATE, TBL_STATE.'.id = '.TBL_CITY.'.state_id', 'left');
		$fetch_query = $this->db->get();  
		$fetch_data=$fetch_query->result(); 
		
		$data = array();
		$cnt=$this->input->post('start')+1;		   
	    foreach($fetch_data as $row)  
	    {  
			$sub_array = array();  
			$sub_array[] =$cnt;
			$sub_array[] = $row->county_name; 
			$sub_array[] = $row->state_name; 
			$sub_array[] = $row->city_name; 
			//$sub_array[] = ($row->is_active)?'<td><span class="label label-success">Active</span></td>':'<td><span class="label label-danger">Inactive</span></td>';	
			$sub_array[] = ($row->is_active)?'<td><a class="status_inactive" onclick="update_status('."'".$row->id."'".',0)" href="javascript:void(0)"><span class="label label-success">Active</span></a></td>':'<td><a class="status_active"  onclick="update_status('."'".$row->id."'".',1)"  href="javascript:void(0)"><span class="label label-danger">Inactive</span></a></td>';					
			$sub_array[] = '<a href="javascript:void(0)" title="Edit" onclick="get_city('."'".$row->id."'".')"><i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i></a>';  
		    $data[] = $sub_array;  
			$cnt++;
	    }
		
		$output = array(  
			"draw"            => intval($this->input->post("draw")),  
			"recordsTotal"    => $this->db->count_all_results(TBL_CITY),  
			"recordsFiltered" => $recordsFiltered,  
			"data"            => $data  
	    );  
		return $output;  
	}
	
	public function get_statesByCountry($country_id)
	{
		
					$this->db->where('country_id',$country_id);
					$this->db->where('is_active',1);
		$fetch_query = $this->db->get(TBL_STATE);  
		$state_data=$fetch_query->result(); 
		return $state_data;
	}
	
	public function get_cityby_id($id)
    {
		$this->db->where('id',$id);
		return $this->db->get(TBL_CITY)->row(); 
    }
	
	public function update_city($id,$data)
	{
		$result['message']='';	
		$result['status']='';			
		$this->db->where('name',$data['name']);
		$this->db->where('id !=',$id);
        $country_info=$this->db->get(TBL_CITY)->row(); 
		
		if($country_info)
		{
			$result['message']=$data['name']." already exists.";
			$result['status']="error";
		}
		else
		{
			$this->db->where('id',$id);
			if($this->db->update(TBL_CITY, $data))
			{
				$result['message']="City has been updated successfully.";
				$result['status']="success";
			}
			else
			{
				$result['message']="Error while update city.";
				$result['status']="error";
			}	
		}
		return $result;		
	}
	
	public function add_city($data)
	{
		$result['message']='';	
		$result['status']='';			
		$this->db->where('name',$data['name']);
        $state_info=$this->db->get(TBL_CITY)->row(); 
		
		if($state_info)
		{
			$result['message']=$data['name']." already exists.";
			$result['status']="error";
		}
		else
		{
			if($this->db->insert(TBL_CITY, $data))
			{
				$result['message']="City added successfully.";
				$result['status']="success";
			}
			else
			{
				$result['message']="Error while add city.";
				$result['status']="error";
			}	
		}
		return $result;		
	}
	
	public function update_country_status($data,$id)
	{
		$result=array();
		$this->db->where('id',$id);
		if($this->db->update(TBL_COUNTRY, $data))
		{
			$result['message']="Country status updated successfully.";
			$result['status']="success";
		}
		else
		{
			$result['message']="Error! while update country status.";
			$result['status']="error";
		}	
		return $result;
	}
	
	public function update_state_status($data,$id)
	{
		$result=array();
		$this->db->where('id',$id);
		if($this->db->update(TBL_STATE, $data))
		{
			$result['message']="State status updated successfully.";
			$result['status']="success";
		}
		else
		{
			$result['message']="Error! while update state status.";
			$result['status']="error";
		}	
		return $result;
	}
	
	public function update_city_status($data,$id)
	{
		$result=array();
		$this->db->where('id',$id);
		if($this->db->update(TBL_CITY, $data))
		{
			$result['message']="City status updated successfully.";
			$result['status']="success";
		}
		else
		{
			$result['message']="Error! while update city status.";
			$result['status']="error";
		}	
		return $result;
	}
}

?>	