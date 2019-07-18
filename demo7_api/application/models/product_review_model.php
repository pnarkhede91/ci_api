<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Product_review_model extends CI_Model{
      
	function __construct(){
		parent::__construct();
	}
	
	var $order_column = array("id", "p.product_name","u.fname","r.title","r.price","r.value","r.quality","r.comment"); 
		
	public function get_all_product_review_data()
	{
		$sSearch = $this->input->post('search');
		$order=$this->input->post('order')['0'];
		$iDisplayStart=$this->input->post('start');
		$iDisplayLength=$this->input->post('length');
		
		$this->db->select('r.id,r.product_id,r.user_id,r.title,r.price,r.value,r.quality,r.comment,r.is_admin_approve,CONCAT_WS(" ",u.fname,u.mname,u.lname) as user_name,p.product_name',false);
		//filtering data by search value
		if(isset($sSearch['value']))  
	    {  
			$where_like= "(	r.title LIKE '%".$sSearch['value']."%' OR r.price LIKE '%".$sSearch['value']."%' OR r.value LIKE '%".$sSearch['value']."%' or r.quality LIKE '%".$sSearch['value']."%' OR r.comment LIKE '%".$sSearch['value']."%' OR u.fname LIKE '%".$sSearch['value']."%' OR u.mname LIKE '%".$sSearch['value']."%' OR u.lname LIKE '%".$sSearch['value']."%' OR p.product_name LIKE '%".$sSearch['value']."%')";
			$this->db->where($where_like); 
	    } 
		//ordering column
		(isset($_POST["order"]))?$this->db->order_by($this->order_column[$order['column']], $order['dir']):$this->db->order_by('id', 'DESC');	
		
		//get the count of records after filtering 
		$this->db->from(TBL_PRODUCT_REVIEW.' AS r');
		$this->db->join(TBL_PRODUCT.' AS p', 'p.id = r.product_id', 'LEFT');
		$this->db->join(TBL_USERS.' AS u', 'u.id = r.user_id', 'LEFT');
		$this->db->where('r.is_deleted',0);
		$this->db->where('p.is_delete',0);
		$this->db->where('u.is_delete',0);
		$recordfilter_query = $this->db->get();  
		$recordsFiltered=$recordfilter_query->num_rows();
	
		//fetch all record		
		$this->db->select('r.id,r.product_id,r.user_id,r.title,r.price,r.value,r.quality,r.comment,r.is_admin_approve,CONCAT_WS(" ",u.fname,u.mname,u.lname) as user_name,p.product_name',false);
		//filtering data by search value
		if(isset($sSearch['value']))  
	    {  
			$where_like= "(	r.title LIKE '%".$sSearch['value']."%' OR r.price LIKE '%".$sSearch['value']."%' OR r.value LIKE '%".$sSearch['value']."%' or r.quality LIKE '%".$sSearch['value']."%' OR r.comment LIKE '%".$sSearch['value']."%' OR u.fname LIKE '%".$sSearch['value']."%' OR u.mname LIKE '%".$sSearch['value']."%' OR u.lname LIKE '%".$sSearch['value']."%' OR p.product_name LIKE '%".$sSearch['value']."%')";
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
		$this->db->from(TBL_PRODUCT_REVIEW.' AS r');
		$this->db->join(TBL_PRODUCT.' AS p', 'p.id = r.product_id', 'LEFT');
		$this->db->join(TBL_USERS.' AS u', 'u.id = r.user_id', 'LEFT');
		$this->db->where('r.is_deleted',0);
		$this->db->where('p.is_delete',0);
		$this->db->where('u.is_delete',0);
		$recordfilter_query = $this->db->get();  
		$fetch_data=$recordfilter_query->result();  
		$data = array();
		$cnt=$this->input->post('start')+1;		   
	    foreach($fetch_data as $row)  
	    {  
			$sub_array = array();  
			$sub_array[] =$cnt;
			$sub_array[] = $row->product_name;
			$sub_array[] = $row->user_name;  
			$sub_array[] = $row->title;  
			$sub_array[] = $row->price;  
			$sub_array[] = $row->value;  
			$sub_array[] = $row->quality;  
			$sub_array[] = $row->comment;  
			$sub_array[] = ($row->is_admin_approve)?'<td><a class="status_inactive" onclick="update_approved_status('."'".$row->id."'".',0)" href="javascript:void(0)"><span class="label label-success">Approve</span></a></td>':'<td><a class="status_active"  onclick="update_approved_status('."'".$row->id."'".',1)"  href="javascript:void(0)"><span class="label label-danger">Pending</span></a></td>';	
			$sub_array[] = '<a href="javascript:void(0)" title="Edit" onclick="delete_product_review('."'".$row->id."'".')"><i class="fa fa-trash-o fa-lg" aria-hidden="true"></i></a>';  
		    $data[] = $sub_array;  
			$cnt++;
	    }
 
		//get the total record count
		$this->db->select('r.id');
			$this->db->from(TBL_PRODUCT_REVIEW.' AS r');
		$this->db->join(TBL_PRODUCT.' AS p', 'p.id = r.product_id', 'LEFT');
		$this->db->join(TBL_USERS.' AS u', 'u.id = r.user_id', 'LEFT');
		$this->db->where('r.is_deleted',0);
		$this->db->where('p.is_delete',0);
		$this->db->where('u.is_delete',0);
		$recordsTotal=$this->db->count_all_results();
		
		$output = array(  
			"draw"            => intval($this->input->post("draw")),  
			"recordsTotal"    => $recordsTotal,  
			"recordsFiltered" => $recordsFiltered,  
			"data"            => $data  
	    );  
		return $output;  
	}
	
	public function delete_product_review($id,$data)
	{
		$result=array();
		$this->db->where('id', $id);
		if($this->db->update(TBL_PRODUCT_REVIEW, $data))
		{
			$result['message']="Product review information deleted successfully.";
			$result['status']="success";
		}
		else
		{
			$result['message']="Error! while delete product review information.";
			$result['status']="error";
		}	
		return $result;		
	}
	
	public function update_approved_status($data,$id)
	{
		$result=array();
		$this->db->where('id',$id);
		if($this->db->update(TBL_PRODUCT_REVIEW, $data))
		{
			$result['message']="Product review approve status updated successfully.";
			$result['status']="success";
		}
		else
		{
			$result['message']="Error! while update product review approve status.";
			$result['status']="error";
		}	
		return $result;
	}
	
	public function add_product_review($data)
	{
		$result=array();
		$this->db->where($data);
		if($this->db->get(TBL_PRODUCT_REVIEW)->num_rows() > 0){
			$result['message']="Error! You have laready submitted same review on this product.";
			$result['status']="error";
		}else{
			$data['created']=DB_CURRENT_DATE;
			$data['modified']=DB_CURRENT_DATE;
			if($this->db->insert(TBL_PRODUCT_REVIEW, $data))
			{
				$result['message']="Product review submitted successfully.";
				$result['status']="success";
			}
			else
			{
				$result['message']="Error! while subnitting product review.";
				$result['status']="error";
			}	
		}
		return $result;
	}
	
	var $user_order_column = array("id", "p.product_name","u.fname","r.title","r.price","r.value","r.quality","r.comment"); 
		
	public function get_all_user_product_review_data()
	{
		$sSearch = $this->input->post('search');
		$order=$this->input->post('order')['0'];
		$iDisplayStart=$this->input->post('start');
		$iDisplayLength=$this->input->post('length');
		
		$this->db->select('r.id,r.product_id,r.user_id,r.title,r.price,r.value,r.quality,r.comment,r.is_admin_approve,CONCAT_WS(" ",u.fname,u.mname,u.lname) as user_name,p.product_name',false);
		//filtering data by search value
		if(isset($sSearch['value']))  
	    {  
			$where_like= "(	r.title LIKE '%".$sSearch['value']."%' OR r.price LIKE '%".$sSearch['value']."%' OR r.value LIKE '%".$sSearch['value']."%' or r.quality LIKE '%".$sSearch['value']."%' OR r.comment LIKE '%".$sSearch['value']."%' OR u.fname LIKE '%".$sSearch['value']."%' OR u.mname LIKE '%".$sSearch['value']."%' OR u.lname LIKE '%".$sSearch['value']."%' OR p.product_name LIKE '%".$sSearch['value']."%')";
			$this->db->where($where_like); 
	    } 
		//ordering column
		(isset($_POST["order"]))?$this->db->order_by($this->user_order_column[$order['column']], $order['dir']):$this->db->order_by('id', 'DESC');	
		
		//get the count of records after filtering 
		$this->db->from(TBL_PRODUCT_REVIEW.' AS r');
		$this->db->join(TBL_PRODUCT.' AS p', 'p.id = r.product_id', 'LEFT');
		$this->db->join(TBL_USERS.' AS u', 'u.id = r.user_id', 'LEFT');
		$this->db->where('r.is_deleted',0);
		$this->db->where('p.is_delete',0);
		$this->db->where('u.is_delete',0);
		$this->db->where('p.added_by',$this->session->userdata('user_id'));
		$recordfilter_query = $this->db->get();  
		$recordsFiltered=$recordfilter_query->num_rows();
	
		//fetch all record		
		$this->db->select('r.id,r.product_id,r.user_id,r.title,r.price,r.value,r.quality,r.comment,r.is_admin_approve,CONCAT_WS(" ",u.fname,u.mname,u.lname) as user_name,p.product_name',false);
		//filtering data by search value
		if(isset($sSearch['value']))  
	    {  
			$where_like= "(	r.title LIKE '%".$sSearch['value']."%' OR r.price LIKE '%".$sSearch['value']."%' OR r.value LIKE '%".$sSearch['value']."%' or r.quality LIKE '%".$sSearch['value']."%' OR r.comment LIKE '%".$sSearch['value']."%' OR u.fname LIKE '%".$sSearch['value']."%' OR u.mname LIKE '%".$sSearch['value']."%' OR u.lname LIKE '%".$sSearch['value']."%' OR p.product_name LIKE '%".$sSearch['value']."%')";
			$this->db->where($where_like);
	    } 
		//ordering column
		(isset($_POST["order"]))?$this->db->order_by($this->user_order_column[$order['column']], $order['dir']):$this->db->order_by('id', 'DESC');	
		
		
		//paging data table
		if($this->input->post("length") != -1)  
        {  
            $this->db->limit($this->input->post('length'), $this->input->post('start'));  
        }
		
		//fetch all user data
		$this->db->from(TBL_PRODUCT_REVIEW.' AS r');
		$this->db->join(TBL_PRODUCT.' AS p', 'p.id = r.product_id', 'LEFT');
		$this->db->join(TBL_USERS.' AS u', 'u.id = r.user_id', 'LEFT');
		$this->db->where('r.is_deleted',0);
		$this->db->where('p.is_delete',0);
		$this->db->where('u.is_delete',0);
		$this->db->where('p.added_by',$this->session->userdata('user_id'));
		$recordfilter_query = $this->db->get();  
		$fetch_data=$recordfilter_query->result();  
		//echo $this->db->last_query();
		$data = array();
		$cnt=$this->input->post('start')+1;		   
	    foreach($fetch_data as $row)  
	    {  
			$sub_array = array();  
			$sub_array[] =$cnt;
			$sub_array[] = $row->product_name;
			$sub_array[] = $row->user_name;  
			$sub_array[] = $row->title;  
			$sub_array[] = $row->price;  
			$sub_array[] = $row->value;  
			$sub_array[] = $row->quality;  
			$sub_array[] = $row->comment;  
			$sub_array[] = ($row->is_admin_approve)?'<td><a class="status_inactive" href="javascript:void(0)"><span class="label label-success">Approved</span></a></td>':'<td><a class="status_active"  href="javascript:void(0)"><span class="label label-danger">Pending</span></a></td>';	
			//$sub_array[] = '<a href="javascript:void(0)" title="Edit" onclick="delete_product_review('."'".$row->id."'".')"><i class="fa fa-trash-o fa-lg" aria-hidden="true"></i></a>';  
		    $data[] = $sub_array;  
			$cnt++;
	    }
 
		//get the total record count
		$this->db->select('r.id');
			$this->db->from(TBL_PRODUCT_REVIEW.' AS r');
		$this->db->join(TBL_PRODUCT.' AS p', 'p.id = r.product_id', 'LEFT');
		$this->db->join(TBL_USERS.' AS u', 'u.id = r.user_id', 'LEFT');
		$this->db->where('r.is_deleted',0);
		$this->db->where('p.is_delete',0);
		$this->db->where('u.is_delete',0);
		$this->db->where('p.added_by',$this->session->userdata('user_id'));
		$recordsTotal=$this->db->count_all_results();
		
		$output = array(  
			"draw"            => intval($this->input->post("draw")),  
			"recordsTotal"    => $recordsTotal,  
			"recordsFiltered" => $recordsFiltered,  
			"data"            => $data  
	    );  
		return $output;  
	}
}
?>