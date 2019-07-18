<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Order_model extends CI_Model{
      
	function __construct(){
		parent::__construct();
	}
	
	public function place_order()
	{
		$order_status=0;
		
		// get product details from cart
		$cart_details = $this->product_model->get_cart_items_by_ids($this->session->userdata('cart_ids'));
		$total_amount = str_replace(',','',$this->session->userdata('total_amount'));
		
		
		$tax_amount = $this->common_functions_model->get_tax_amount($total_amount);
		$total_amount_with_tax = $total_amount+$tax_amount;
		// save order 
		$order_data=array(
						'order_number'=>$this->session->userdata('order_number'),
						'user_id'=>$this->session->userdata('user_id'),
						'ip_address'=>$this->input->ip_address(),
						'total_amount'=>$total_amount,
						'tax'=>TAX_PERCENTAGE,
						'tax_amount'=>$tax_amount,
						'total_amount_with_tax'=>$total_amount_with_tax,
						'created'=>DB_CURRENT_DATE,
						'modified'=>DB_CURRENT_DATE
					);
		$result = $this->db->insert(TBL_ORDERS,$order_data);
		if($result){
			$order_status=1;
			$order_id = $this->db->insert_id();	
			
			// save order details
			$order_details_data=array();
			foreach($cart_details as $cart_detail){
				
				$product_total_amount = (($cart_detail['discount_status']==1))?($cart_detail['quantity']*$cart_detail['discount_price']):($cart_detail['quantity']*$cart_detail['price']);
				
				$order_details_data[]=['order_id'=>$order_id,'product_id'=>$cart_detail['product_id'],'unit_price'=>$cart_detail['unit_price'],'quantity'=>$cart_detail['quantity'],'total_amount'=>$product_total_amount];
			}
			$result = $this->db->insert_batch(TBL_ORDER_DETAILS,$order_details_data);
			if($result){
				$order_status=1;
				// save billing address
				$billing_address=$this->input->post('billing');
				$billing_address_data=array(
										'first_name'=>$billing_address['firstname'],
										'last_name'=>$billing_address['lastname'],
										'middle_name'=>$billing_address['middlename'],
										'company'=>$billing_address['company'],
										'phone_number'=>$billing_address['phone_number'],
										'email'=>$billing_address['email'],
										'address'=>$billing_address['address'],
										'country_id'=>$billing_address['country_id'],
										'state_id'=>$billing_address['state'],
										'city_id'=>$billing_address['city'],
										'postcode'=>$billing_address['postcode'],
										'save_in_address_book'=>$billing_address['save_in_address_book'],
										'user_id'=>$this->session->userdata('user_id'),
										'order_id'=>$order_id,
										'created'=>DB_CURRENT_DATE,
										'modified'=>DB_CURRENT_DATE
									);
				$result = $this->db->insert(TBL_BILLING_ADDRESS,$billing_address_data);
				if($result){
					$order_status=1;
					
					//TODO::send email to admin & seller
					
					$this->product_model->remove_ordered_items_from_cart();
					
					$this->session->set_userdata('order_number','');
					$this->session->set_userdata('cart_ids','');
				}else{
					$order_status=0;
				}
			}else{
				$order_status=0;
			}
		}
		return $order_status;
	}
	
	var $order_column = array("id", "id", "o.order_number","user_name","o.total_amount","o.tax_amount","o.total_amount_with_tax","o.status","o.created"); 	
	public function get_all_orders_data()
	{
		$sSearch = $this->input->post('search');
		$order=$this->input->post('order')['0'];
		$iDisplayStart=$this->input->post('start');
		$iDisplayLength=$this->input->post('length');
		
		$this->db->select('o.id, o.order_number, CONCAT_WS(" ",u.fname,u.mname,u.lname) AS user_name, o.total_amount, o.tax_amount, o.total_amount_with_tax, o.status, o.created',false);
		//filtering data by search value
		if(isset($sSearch['value']))  
	    {  
			$where_like= "(o.order_number LIKE '%".$sSearch['value']."%' OR u.fname LIKE '%".$sSearch['value']."%' OR u.mname LIKE '%".$sSearch['value']."%' OR u.lname LIKE '%".$sSearch['value']."%' OR o.total_amount LIKE '%".$sSearch['value']."%' OR o.tax_amount LIKE '%".$sSearch['value']."%' OR o.total_amount_with_tax LIKE '%".$sSearch['value']."%')";
			$this->db->where($where_like); 
	    } 
		//ordering column
		(isset($_POST["order"]))?$this->db->order_by($this->order_column[$order['column']], $order['dir']):$this->db->order_by('id', 'DESC');	
		
		//get the count of records after filtering 
		$this->db->from(TBL_ORDERS.' AS o');
		$this->db->join(TBL_USERS.' AS u', 'u.id = o.user_id', 'LEFT');
		//$this->db->join(TBL_CATEGORY.' AS c', 'c.id = s.category_id', 'LEFT');
		//$this->db->join(TBL_BRANDS.' AS b', 'b.id = c.brand_id', 'LEFT');
		//$this->db->where('o.is_delete',0);
		$this->db->where('u.is_delete',0);
		//$this->db->where('b.is_delete',0);
		//$this->db->where('p.is_delete',0);
		$recordfilter_query = $this->db->get();  
		$recordsFiltered=$recordfilter_query->num_rows();
		
		//fetch all record
		$this->db->select('o.id, o.order_number, CONCAT_WS(" ",u.fname,u.mname,u.lname) AS user_name, o.total_amount, o.tax_amount, o.total_amount_with_tax, o.status, o.created',false);
		//filtering data by search value
		if(isset($sSearch['value']))  
	    {  
			$where_like= "(o.order_number LIKE '%".$sSearch['value']."%' OR u.fname LIKE '%".$sSearch['value']."%' OR u.mname LIKE '%".$sSearch['value']."%' OR u.lname LIKE '%".$sSearch['value']."%' OR o.total_amount LIKE '%".$sSearch['value']."%' OR o.tax_amount LIKE '%".$sSearch['value']."%' OR o.total_amount_with_tax LIKE '%".$sSearch['value']."%')";
			$this->db->where($where_like); 
	    } 
		//ordering column
		(isset($_POST["order"]))?$this->db->order_by($this->order_column[$order['column']], $order['dir']):$this->db->order_by('id', 'DESC');	
		
		//get the count of records after filtering 
		$this->db->from(TBL_ORDERS.' AS o');
		$this->db->join(TBL_USERS.' AS u', 'u.id = o.user_id', 'LEFT');
		//$this->db->join(TBL_CATEGORY.' AS c', 'c.id = s.category_id', 'LEFT');
		//$this->db->join(TBL_BRANDS.' AS b', 'b.id = c.brand_id', 'LEFT');
		//$this->db->where('o.is_delete',0);
		$this->db->where('u.is_delete',0);
		//$this->db->where('b.is_delete',0);
		//$this->db->where('p.is_delete',0);
		if (isset($iDisplayStart) && $iDisplayLength != '-1') {
			$this->db->limit($iDisplayLength, $iDisplayStart);
        }
		$recordfilter_query = $this->db->get();  
		$fetch_data=$recordfilter_query->result();  
		$data = array();
		$cnt=$this->input->post('start')+1;		   
	    foreach($fetch_data as $row)  
	    {  
			$sub_array = array();  
			$sub_array[] =$cnt;
			$sub_array[] = $row->order_number;
			$sub_array[] = $row->user_name;  
			$sub_array[] = $row->total_amount;  
			$sub_array[] = $row->tax_amount;  
			$sub_array[] = $row->total_amount_with_tax;  
			$sub_array[] = '<select name="admin_approve_status" id="admin_approve_status" onchange="update_status(this.value,'.$row->id.')"><option value="0">Pending</option><option value="1" '.(($row->status==1)?'selected="selected"':'').'>Cancelled</option><option value="2" '.(($row->status==2)?'selected="selected"':'').'>Delivered</option></select>';
			$sub_array[] = '<a href="'.base_url().ADMIN_END.'/order/order_details/'.base64_encode($row->id).'" title="View"><i class="fa fa-eye fa-lg" aria-hidden="true"></i></a>';
		    $data[] = $sub_array;  
			$cnt++;
	    }
 
		//get the total record count
		$this->db->select('id');
		$this->db->from(TBL_ORDERS.' AS o');
		$this->db->join(TBL_USERS.' AS u', 'u.id = o.user_id', 'LEFT');
		//$this->db->join(TBL_CATEGORY.' AS c', 'c.id = s.category_id', 'LEFT');
		//$this->db->join(TBL_BRANDS.' AS b', 'b.id = c.brand_id', 'LEFT');
		//$this->db->where('o.is_delete',0);
		$this->db->where('u.is_delete',0);
		//$this->db->where('b.is_delete',0);
		//$this->db->where('p.is_delete',0);
		$recordsTotal=$this->db->count_all_results();
		
		$output = array(  
			"draw"            => intval($this->input->post("draw")),  
			"recordsTotal"    => $recordsTotal,  
			"recordsFiltered" => $recordsFiltered,  
			"data"            => $data  
	    );  
		return $output;  
	}
	
	public function update_status($data,$id)
	{
		$result=array();
		$this->db->where('id',$id);
		if($this->db->update(TBL_ORDERS, $data))
		{
			$result['message']="Order status updated successfully.";
			$result['status']="success";
		}
		else
		{
			$result['message']="Error! while update order status.";
			$result['status']="error";
		}	
		return $result;
	}
	
	public function order_details($order_id)
	{
		//od.product_id, od.unit_price, od.quantity, od.total_amount,
		$this->db->select('o.id, o.order_number, o.user_id, o.ip_address, o.total_amount as order_total_amount, o.tax_amount, o.total_amount_with_tax, o.status, o.created, opd.payment_method, opd.transaction_number, opd.transaction_message, opd.transaction_status, opd.transaction_date, CONCAT_WS(" ",ba.first_name, ba.middle_name, ba.last_name) AS billing_name, ba.phone_number, ba.email, ba.address, ba.country_id, ba.state_id, ba.city_id, ba.postcode',false);
		$this->db->from(TBL_ORDERS.' AS o');
		//$this->db->join(TBL_ORDER_DETAILS.' AS od','od.order_id=o.id','left');
		$this->db->join(TBL_ORDER_PAYMENT_DETAILS.' AS opd','opd.order_id=o.id','left');
		$this->db->join(TBL_BILLING_ADDRESS.' AS ba','ba.order_id=o.id','left');
		$this->db->where('o.id',$order_id);
		return $this->db->get()->row_array();
		//echo $this->db->last_query(); die;
	}
	
	public function order_item_details($order_id)
	{
		$this->db->select('od.id as order_detail_id, od.order_id, od.unit_price, od.quantity, od.total_amount, p.product_name');
		$this->db->where('order_id',$order_id);
		$this->db->from(TBL_ORDER_DETAILS.' AS od');
		$this->db->join(TBL_PRODUCT.' AS p','p.id=od.product_id','left');
		return $this->db->get()->result_array();
	}
	
	var $marchant_order_column = array("id", "id", "o.order_number","user_name","o.total_amount","o.tax_amount","o.total_amount_with_tax","o.status","o.created"); 	
	public function get_all_marchant_orders_data()
	{
		$logged_in = ($this->session->userdata('user_type') == 3)?1:0;
		
		$is_merchant = ($this->session->userdata('user_type') == 2)?1:0;
		
		$sSearch = $this->input->post('search');
		$order=$this->input->post('order')['0'];
		$iDisplayStart=$this->input->post('start');
		$iDisplayLength=$this->input->post('length');
		
		$this->db->select('o.id, o.order_number, CONCAT_WS(" ",u.fname,u.mname,u.lname) AS user_name, o.total_amount, o.tax_amount, o.total_amount_with_tax, o.status, o.created',false);
		//filtering data by search value
		if(isset($sSearch['value']))  
	    {  
			$where_like= "(o.order_number LIKE '%".$sSearch['value']."%' OR u.fname LIKE '%".$sSearch['value']."%' OR u.mname LIKE '%".$sSearch['value']."%' OR u.lname LIKE '%".$sSearch['value']."%' OR o.total_amount LIKE '%".$sSearch['value']."%' OR o.tax_amount LIKE '%".$sSearch['value']."%' OR o.total_amount_with_tax LIKE '%".$sSearch['value']."%')";
			$this->db->where($where_like); 
	    } 
		//ordering column
		(isset($_POST["order"]))?$this->db->order_by($this->marchant_order_column[$order['column']], $order['dir']):$this->db->order_by('id', 'DESC');	
		
		//get the count of records after filtering 
		$this->db->from(TBL_ORDERS.' AS o');
		$this->db->join(TBL_USERS.' AS u', 'u.id = o.user_id', 'LEFT');
		$this->db->join(TBL_ORDER_DETAILS.' AS od', 'o.id = od.order_id', 'LEFT');
		$this->db->join(TBL_PRODUCT.' AS p', 'p.id = od.product_id', 'LEFT');
		if($is_merchant){
			$this->db->where('p.added_by',$this->session->userdata('user_id'));
		}
		$this->db->where('u.is_delete',0);
		//$this->db->where('b.is_delete',0);
		//$this->db->where('p.is_delete',0);
		$recordfilter_query = $this->db->get();  
		$recordsFiltered=$recordfilter_query->num_rows();
		
		//fetch all record
		$this->db->select('o.id, o.order_number, CONCAT_WS(" ",u.fname,u.mname,u.lname) AS user_name, o.total_amount, o.tax_amount, o.total_amount_with_tax, o.status, o.created',false);
		//filtering data by search value
		if(isset($sSearch['value']))  
	    {  
			$where_like= "(o.order_number LIKE '%".$sSearch['value']."%' OR u.fname LIKE '%".$sSearch['value']."%' OR u.mname LIKE '%".$sSearch['value']."%' OR u.lname LIKE '%".$sSearch['value']."%' OR o.total_amount LIKE '%".$sSearch['value']."%' OR o.tax_amount LIKE '%".$sSearch['value']."%' OR o.total_amount_with_tax LIKE '%".$sSearch['value']."%')";
			$this->db->where($where_like); 
	    } 
		//ordering column
		(isset($_POST["order"]))?$this->db->order_by($this->marchant_order_column[$order['column']], $order['dir']):$this->db->order_by('id', 'DESC');	
		
		//get the count of records after filtering 
		$this->db->from(TBL_ORDERS.' AS o');
		$this->db->join(TBL_USERS.' AS u', 'u.id = o.user_id', 'LEFT');
		$this->db->join(TBL_ORDER_DETAILS.' AS od', 'o.id = od.order_id', 'LEFT');
		$this->db->join(TBL_PRODUCT.' AS p', 'p.id = od.product_id', 'LEFT');
		if($is_merchant){
			$this->db->where('p.added_by',$this->session->userdata('user_id'));
		}
		$this->db->where('u.is_delete',0);
		//$this->db->where('b.is_delete',0);
		//$this->db->where('p.is_delete',0);
		if (isset($iDisplayStart) && $iDisplayLength != '-1') {
			$this->db->limit($iDisplayLength, $iDisplayStart);
        }
		$recordfilter_query = $this->db->get();  
		$fetch_data=$recordfilter_query->result();  
		$data = array();
		$cnt=$this->input->post('start')+1;
		
	    foreach($fetch_data as $row)  
	    {  
			$sub_array = array();  
			$sub_array[] =$cnt;
			$sub_array[] = $row->order_number;
			$sub_array[] = $row->user_name;  
			$sub_array[] = $row->total_amount;  
			$sub_array[] = $row->tax_amount;  
			$sub_array[] = $row->total_amount_with_tax;  
			$sub_array[] = ($logged_in)?(($row->status==1)?'Canceled':(($row->status==2)?'Delivered':'Pending')):'<select name="admin_approve_status" id="admin_approve_status" onchange="update_status(this.value,'.$row->id.')"><option value="0">Pending</option><option value="1" '.(($row->status==1)?'selected="selected"':'').'>Canceled</option><option value="2" '.(($row->status==2)?'selected="selected"':'').'>Delivered</option></select>';
			$sub_array[] = '<a href="'.base_url().'order/order_details/'.base64_encode($row->id).'" title="View"><i class="fa fa-eye fa-lg" aria-hidden="true"></i></a>';
		    $data[] = $sub_array;  
			$cnt++;
	    }
 
		//get the total record count
		$this->db->select('id');
		$this->db->from(TBL_ORDERS.' AS o');
		$this->db->join(TBL_USERS.' AS u', 'u.id = o.user_id', 'LEFT');
		$this->db->join(TBL_ORDER_DETAILS.' AS od', 'o.id = od.order_id', 'LEFT');
		$this->db->join(TBL_PRODUCT.' AS p', 'p.id = od.product_id', 'LEFT');
		if($is_merchant){
			$this->db->where('p.added_by',$this->session->userdata('user_id'));
		}
		$this->db->where('u.is_delete',0);
		//$this->db->where('b.is_delete',0);
		//$this->db->where('p.is_delete',0);
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