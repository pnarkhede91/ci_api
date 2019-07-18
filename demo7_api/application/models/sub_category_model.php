<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Sub_category_model extends CI_Model{
      
	function __construct(){
		parent::__construct();
	}
	
	var $order_column = array("id","b.name","c.name","s.name","is_active"); 
		
	public function get_brand_list()
	{
		$this->db->where('is_delete',0);
        return $this->db->get(TBL_BRANDS)->result(); 
	}
	
	public function get_all_subcategory_data()
	{
		$sSearch = $this->input->post('search');
		$order=$this->input->post('order')['0'];
		$iDisplayStart=$this->input->post('start');
		$iDisplayLength=$this->input->post('length');
		
		$this->db->select('s.id,s.name as sub_category_name,c.name as category_name,s.is_active,b.name as brand_name');
		//filtering data by search value
		if(isset($sSearch['value']))  
	    {  
			$where_like= "(	s.name LIKE '%".$sSearch['value']."%' OR c.name LIKE '%".$sSearch['value']."%' OR b.name LIKE '%".$sSearch['value']."%')";
			$this->db->where($where_like); 
	    } 
		//ordering column
		(isset($_POST["order"]))?$this->db->order_by($this->order_column[$order['column']], $order['dir']):$this->db->order_by('id', 'DESC');	
		
		//get the count of records after filtering 
		$this->db->from(TBL_SUB_CATEGORY.' AS s');
		$this->db->join(TBL_CATEGORY.' AS c', 'c.id = s.category_id', 'LEFT');
		$this->db->join(TBL_BRANDS.' AS b', 'b.id = c.brand_id', 'LEFT');
		$this->db->where('s.is_delete',0);
		$this->db->where('c.is_delete',0);
		$this->db->where('b.is_delete',0);
		$recordfilter_query = $this->db->get();  
		$recordsFiltered=$recordfilter_query->num_rows();
		
		//fetch all record
		$this->db->select('s.id,s.name as sub_category_name,c.name as category_name,s.is_active,b.name as brand_name');
		//filtering data by search value
		if(isset($sSearch['value']))  
	    {  
			$where_like= "(	s.name LIKE '%".$sSearch['value']."%' OR c.name LIKE '%".$sSearch['value']."%' OR b.name LIKE '%".$sSearch['value']."%')";
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
		$this->db->from(TBL_SUB_CATEGORY.' AS s');
		$this->db->join(TBL_CATEGORY.' AS c', 'c.id = s.category_id', 'LEFT');
		$this->db->join(TBL_BRANDS.' AS b', 'b.id = c.brand_id', 'LEFT');
		$this->db->where('s.is_delete',0);
		$this->db->where('c.is_delete',0);
		$this->db->where('b.is_delete',0);
		$recordfilter_query = $this->db->get();  
		$fetch_data=$recordfilter_query->result();  
		$data = array();
		$cnt=$this->input->post('start')+1;		   
	    foreach($fetch_data as $row)  
	    {  
			$sub_array = array();  
			$sub_array[] =$cnt;
			$sub_array[] = $row->brand_name;
			$sub_array[] = $row->category_name;  
			$sub_array[] = $row->sub_category_name;  
			//$sub_array[] = ($row->is_active)?'<td><span class="label label-success">Active</span></td>':'<td><span class="label label-danger">Inactive</span></td>';	
			$sub_array[] = ($row->is_active)?'<td><a class="status_inactive" onclick="update_status('."'".$row->id."'".',0)" href="javascript:void(0)"><span class="label label-success">Active</span></a></td>':'<td><a class="status_active"  onclick="update_status('."'".$row->id."'".',1)"  href="javascript:void(0)"><span class="label label-danger">Inactive</span></a></td>';	
			$sub_array[] = '<a href="'.base_url().ADMIN_END.'/sub_category/update_sub_category/'.base64_encode($row->id).'" title="Edit"><i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i></a>
			<a href="javascript:void(0)" title="Edit" onclick="delete_sub_category('."'".$row->id."'".')"><i class="fa fa-trash-o fa-lg" aria-hidden="true"></i></a>';  
		    $data[] = $sub_array;  
			$cnt++;
	    }
 
		//get the total record count
		$this->db->select('id');
		$this->db->from(TBL_SUB_CATEGORY.' AS s');
		$this->db->join(TBL_CATEGORY.' AS c', 'c.id = s.category_id', 'LEFT');
		$this->db->join(TBL_BRANDS.' AS b', 'b.id = c.brand_id', 'LEFT');
		$this->db->where('s.is_delete',0);
		$this->db->where('c.is_delete',0);
		$this->db->where('b.is_delete',0);
		$recordsTotal=$this->db->count_all_results();
		
		$output = array(  
			"draw"            => intval($this->input->post("draw")),  
			"recordsTotal"    => $recordsTotal,  
			"recordsFiltered" => $recordsFiltered,  
			"data"            => $data  
	    );  
		return $output;  
	}
	
	public function add_sub_category($data)
	{
		$result='';			
		$this->db->where('name',$data['sub_category_name']);
		$this->db->where('category_id',$data['category_id']);
		$this->db->where('brand_id',$data['brand_id']);
		$this->db->where('is_delete',0); 
        $category_info=$this->db->get(TBL_SUB_CATEGORY)->row(); 
		
		if($category_info)
		{
			$this->session->set_flashdata('error', $data['name']." already exists.");
			$result=0;
		}
		else
		{
			if($this->db->insert(TBL_SUB_CATEGORY, $data))
			{
				$this->session->set_flashdata('success', $data['name']." added successfully.");
				$result=1;
			}
			else
			{
				$this->session->set_flashdata('error',"Error while add sub category.");
				$result=-1;
			}	
		}
		return $result;		
	}
	
	public function fetch_category_by_brand_id()
	{
		$this->db->where('is_delete',0);
		$this->db->where('brand_id',$this->input->post('id'));
        return $this->db->get(TBL_CATEGORY)->result(); 
	}
	
	public function get_category_by_brand_id($brand_id)
	{
		$this->db->where('is_delete',0);
		$this->db->where('brand_id',$brand_id);
        return $this->db->get(TBL_CATEGORY)->result(); 
	}
	
	public function get_sub_categoryByID($id)
    {
		$this->db->where('id',$id);
        return $this->db->get(TBL_SUB_CATEGORY)->row(); 
    }
	
	public function update($id,$data)
	{
		$result['message']='';	
		$result['status']='';			
		$this->db->where('name',$data['sub_category_name']);
		$this->db->where('category_id',$data['category_id']);
		$this->db->where('brand_id',$data['brand_id']);
		$this->db->where('id !=',$id);
		$this->db->where('is_delete',0); 
        $category_info=$this->db->get(TBL_SUB_CATEGORY)->row(); 
		
		if($category_info)
		{
			$this->session->set_flashdata('error', $data['name']." already exists.");
			return 0;
		}
		else
		{
			$this->db->where('id',$id);
			if($this->db->update(TBL_SUB_CATEGORY, $data))
			{
				$this->session->set_flashdata('success', $data['name'].' updated successfully.');
			}
			else
			{
				$this->session->set_flashdata('error', 'Error! while update sub category information.');
			}	
			return 1;
		}		
	}	

	public function delete_sub_category($id,$data)
	{
		$result=array();
		$this->db->where('id', $id);
		if($this->db->update(TBL_SUB_CATEGORY, $data))
		{
			$result['message']="Sub category information deleted successfully.";
			$result['status']="success";
		}
		else
		{
			$result['message']="Error! while delete sub category information.";
			$result['status']="error";
		}	
		return $result;		
	}
	
	public function update_status($data,$id)
	{
		$result=array();
		$this->db->where('id',$id);
		if($this->db->update(TBL_SUB_CATEGORY, $data))
		{
			$result['message']="Sub category status updated successfully.";
			$result['status']="success";
		}
		else
		{
			$result['message']="Error! while update sub category status.";
			$result['status']="error";
		}	
		return $result;
	}
}
?>