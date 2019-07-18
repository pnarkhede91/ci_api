<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Subscriber_model extends CI_Model{
      
	function __construct(){
		parent::__construct();
	}
	
	var $order_column = array("id", "email"); 
		
	public function get_all_subscriber_data()
	{
		$sSearch = $this->input->post('search');
		$order=$this->input->post('order')['0'];
		$iDisplayStart=$this->input->post('start');
		$iDisplayLength=$this->input->post('length');
		
		$this->db->select('id,email',false);
		//filtering data by search value
		if(isset($sSearch['value']))  
	    {  
			$where_like= "(	email LIKE '%".$sSearch['value']."%')";
			$this->db->where($where_like); 
	    } 
		//ordering column
		(isset($_POST["order"]))?$this->db->order_by($this->order_column[$order['column']], $order['dir']):$this->db->order_by('id', 'DESC');	
		
		//get the count of records after filtering 
		$this->db->from(TBL_SUBSCRIBER);
		$this->db->where('is_deleted',0);
		$recordfilter_query = $this->db->get();  
		$recordsFiltered=$recordfilter_query->num_rows();
	
		//fetch all record		
		$this->db->select('id,email',false);
		//filtering data by search value
		if(isset($sSearch['value']))  
	    {  
			$where_like= "(	email LIKE '%".$sSearch['value']."%')";
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
		$this->db->from(TBL_SUBSCRIBER);
		$this->db->where('is_deleted',0);
		$recordfilter_query = $this->db->get();  
		$fetch_data=$recordfilter_query->result();  
		$data = array();
		$cnt=$this->input->post('start')+1;		   
	    foreach($fetch_data as $row)  
	    {  
			$sub_array = array();  
			$sub_array[] =$cnt;
			$sub_array[] = $row->email;
			$sub_array[] = '<a href="javascript:void(0)" title="Edit" onclick="delete_subscriber('."'".$row->id."'".')"><i class="fa fa-trash-o fa-lg" aria-hidden="true"></i></a>';  
		    $data[] = $sub_array;  
			$cnt++;
	    }
 
		//get the total record count
		$recordsTotal=$this->db->count_all_results(TBL_SUBSCRIBER);
		
		$output = array(  
			"draw"            => intval($this->input->post("draw")),  
			"recordsTotal"    => $recordsTotal,  
			"recordsFiltered" => $recordsFiltered,  
			"data"            => $data  
	    );  
		return $output;  
	}
	
	public function delete_subscriber($id,$data)
	{
		$result=array();
		$this->db->where('id', $id);
		if($this->db->update(TBL_SUBSCRIBER, $data))
		{
			$result['message']="Subscriber information deleted successfully.";
			$result['status']="success";
		}
		else
		{
			$result['message']="Error! while delete subscriber information.";
			$result['status']="error";
		}	
		return $result;		
	}
	
	public function add_news_letter_subscriber($news_letter_email)
	{
		if($this->db->where('email', $news_letter_email)->where('is_deleted',0)->get(TBL_SUBSCRIBER)->num_rows() == 0){
			$data=array('email'=>$news_letter_email, 'is_deleted'=>0,'created'=>DB_CURRENT_DATE,'modified'=>DB_CURRENT_DATE);
			return ($this->db->insert(TBL_SUBSCRIBER, $data))?1:0;
		}
		return 1;
	}
		
}
?>