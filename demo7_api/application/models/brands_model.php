<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Brands_model extends CI_Model{
      
	function __construct(){
		parent::__construct();
	}
	
	var $order_column = array("id", "id", "name","is_active"); 
		
	public function get_all_brandsData()
	{
		$sSearch = $this->input->post('search');
		$order=$this->input->post('order')['0'];
		$iDisplayStart=$this->input->post('start');
		$iDisplayLength=$this->input->post('length');
		 
		//filtering data by search value
		if(isset($sSearch['value']))  
	    {  
			$where_like= "(	name LIKE '%".$sSearch['value']."%')";
			$this->db->where($where_like); 
	    } 
		//ordering column
		(isset($_POST["order"]))?$this->db->order_by($this->order_column[$order['column']], $order['dir']):$this->db->order_by('id', 'DESC');	
		
		//get the count of records after filtering 
		$this->db->where('is_delete',0);
		$recordfilter_query = $this->db->get(TBL_BRANDS);  
		$recordsFiltered=$recordfilter_query->num_rows();
		
		//filtering data by search value
		if(isset($sSearch['value']))  
	    {  
			$where_like= "(	name LIKE '%".$sSearch['value']."%')";
			$this->db->where($where_like); 
	    } 
		//ordering column
		(isset($_POST["order"]))?$this->db->order_by($this->order_column[$order['column']], $order['dir']):$this->db->order_by('id', 'DESC');	
		
		
		//paging data table
		if($this->input->post("length") != -1)  
        {  
            $this->db->limit($this->input->post('length'), $this->input->post('start'));  
        }
		
		//fetch all user data
		$this->db->where('is_delete',0);		
        $fetch_query = $this->db->get(TBL_BRANDS);  
		$fetch_data=$fetch_query->result();  
		$data = array();
		$cnt=$this->input->post('start')+1;		   
	    foreach($fetch_data as $row)  
	    {  
			$sub_array = array();  
			$sub_array[] =$cnt;
			$sub_array[] = ($row->image)?'<img src="'.base_url().BRAND_IMAGES.DS.$row->image.'" width="50" height="50"/>':'<img src="'.base_url().'img/no_image.png" width="50" height="50"/>';
			$sub_array[] = $row->name;  		
			$sub_array[] = ($row->is_active)?'<td><a class="status_inactive" onclick="update_status('."'".$row->id."'".',0)" href="javascript:void(0)"><span class="label label-success">Active</span></a></td>':'<td><a class="status_active"  onclick="update_status('."'".$row->id."'".',1)"  href="javascript:void(0)"><span class="label label-danger">Inactive</span></a></td>';							
			$sub_array[] = '<a href="'.base_url().ADMIN_END.'/brands/update_brand/'.base64_encode($row->id).'" title="Edit"><i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i></a>
			<a href="javascript:void(0)" title="Edit" onclick="delete_brand('."'".$row->id."'".')"><i class="fa fa-trash-o fa-lg" aria-hidden="true"></i></a>';  
		    $data[] = $sub_array;  
			$cnt++;
	    }
 
		//get the total record count
		$this->db->where('is_delete',0); 
		$recordsTotal=$this->db->count_all_results(TBL_BRANDS);
		
		$output = array(  
			"draw"            => intval($this->input->post("draw")),  
			"recordsTotal"    => $recordsTotal,  
			"recordsFiltered" => $recordsFiltered,  
			"data"            => $data  
	    );  
		return $output;  
	}
	
	public function add_brand($data)
	{
		$result='';			
		$this->db->where('name',$data['name']);
		$this->db->where('is_delete',0); 
        $brand_info=$this->db->get(TBL_BRANDS)->row(); 
		
		if($brand_info)
		{
			$this->session->set_flashdata('error', $data['name']." already exists.");
			$result=0;
		}
		else
		{
			if($this->db->insert(TBL_BRANDS, $data))
			{
				$this->session->set_flashdata('success'," Brands has been added successfully.");
				$result=1;
			}
			else
			{
				$this->session->set_flashdata('error',"Error while add brand.");
				$result=-1;
			}	
		}
		return $result;		
	}
	
	public function get_brandByID($id)
    {
		$this->db->where('id',$id);
        return $this->db->get(TBL_BRANDS)->row(); 
    }
	
	public function update($id,$data)
	{
		$this->db->where('name',$data['name']);
		$this->db->where('id !=',$id);
		$this->db->where('is_delete',0); 
        $user_info=$this->db->get(TBL_BRANDS)->row(); 
		
		if( $user_info)
		{
			$this->session->set_flashdata('error', $data['name']." already exists.");
			return 0;
		}
		else
		{
			$this->db->where('id',$id);
			if($this->db->update(TBL_BRANDS, $data))
			{
				$this->session->set_flashdata('success', 'Brand information has been updated successfully.');
			}
			else
			{
				$this->session->set_flashdata('error', 'Error! while update brand information.');
			}	
			return 1;
		}		
	}	

	public function delete_brand($id,$data)
	{
		$result=array();
		$this->db->where('id', $id);
		if($this->db->update(TBL_BRANDS, $data))
		{
			$result['message']="Brand information deleted successfully.";
			$result['status']="success";
		}
		else
		{
			$result['message']="Error! while delete brand information.";
			$result['status']="error";
		}	
		return $result;		
	}
	
	public function update_status($data,$id)
	{
		$result=array();
		$this->db->where('id',$id);
		if($this->db->update(TBL_BRANDS, $data))
		{
			$result['message']="Brand status updated successfully.";
			$result['status']="success";
			//$this->session->set_flashdata('success', 'Brand status updated successfully.');
		}
		else
		{
			$result['message']="Error! while update brand status.";
			$result['status']="error";
			//$this->session->set_flashdata('error', 'Error! while update brand status.');
		}	
		return $result;
	}
}
?>