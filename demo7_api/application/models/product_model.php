<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model{

      

	function __construct(){

		parent::__construct();

	}

	

	var $order_column = array("id", "id", "b.name","c.name","s.name","product_name","price","quantity","discount_price"); 

		

	public function get_brand_list()

	{

		$this->db->where('is_delete',0);

        return $this->db->get(TBL_BRANDS)->result(); 

	}

	

	public function get_all_product_data()

	{

		$sSearch = $this->input->post('search');

		$order=$this->input->post('order')['0'];

		$iDisplayStart=$this->input->post('start');

		$iDisplayLength=$this->input->post('length');

		

		$this->db->select('p.id,s.name as sub_category_name,c.name as category_name,p.is_active,b.name as brand_name,p.product_name,p.price,p.quantity,p.discount_price,p.discount_start_date,p.discount_end_date');

		//filtering data by search value

		if(isset($sSearch['value']))  

	    {  

			$where_like= "(s.name LIKE '%".$sSearch['value']."%' OR c.name LIKE '%".$sSearch['value']."%' OR b.name LIKE '%".$sSearch['value']."%' OR p.product_name LIKE '%".$sSearch['value']."%' OR p.price LIKE '%".$sSearch['value']."%' OR p.quantity LIKE '%".$sSearch['value']."%' OR p.discount_price LIKE '%".$sSearch['value']."%')";

			$this->db->where($where_like); 

	    } 

		//ordering column

		(isset($_POST["order"]))?$this->db->order_by($this->order_column[$order['column']], $order['dir']):$this->db->order_by('id', 'DESC');	

		

		//get the count of records after filtering 

		$this->db->from(TBL_PRODUCT.' AS p');

		$this->db->join(TBL_SUB_CATEGORY.' AS s', 's.id = p.sub_category_id', 'LEFT');

		$this->db->join(TBL_CATEGORY.' AS c', 'c.id = s.category_id', 'LEFT');

		$this->db->join(TBL_BRANDS.' AS b', 'b.id = c.brand_id', 'LEFT');

		$this->db->where('s.is_delete',0);

		$this->db->where('c.is_delete',0);

		$this->db->where('b.is_delete',0);

		$this->db->where('p.is_delete',0);

		$recordfilter_query = $this->db->get();  

		$recordsFiltered=$recordfilter_query->num_rows();

		

		//fetch all record

		$this->db->select('p.id,s.name as sub_category_name,c.name as category_name,p.is_active,b.name as brand_name,p.product_name,p.price,p.quantity,p.discount_price,p.discount_start_date,p.discount_end_date');

		//filtering data by search value

		if(isset($sSearch['value']))  

	    {  

			$where_like= "(s.name LIKE '%".$sSearch['value']."%' OR c.name LIKE '%".$sSearch['value']."%' OR b.name LIKE '%".$sSearch['value']."%' OR p.product_name LIKE '%".$sSearch['value']."%' OR p.price LIKE '%".$sSearch['value']."%' OR p.quantity LIKE '%".$sSearch['value']."%' OR p.discount_price LIKE '%".$sSearch['value']."%')";

			$this->db->where($where_like); 

	    } 

		//ordering column

		(isset($_POST["order"]))?$this->db->order_by($this->order_column[$order['column']], $order['dir']):$this->db->order_by('id', 'DESC');	

		

		//get the count of records after filtering 

		$this->db->from(TBL_PRODUCT.' AS p');

		$this->db->join(TBL_SUB_CATEGORY.' AS s', 's.id = p.sub_category_id', 'LEFT');

		$this->db->join(TBL_CATEGORY.' AS c', 'c.id = s.category_id', 'LEFT');

		$this->db->join(TBL_BRANDS.' AS b', 'b.id = c.brand_id', 'LEFT');

		$this->db->where('s.is_delete',0);

		$this->db->where('c.is_delete',0);

		$this->db->where('b.is_delete',0);

		$this->db->where('p.is_delete',0);

		if (isset($iDisplayStart) && $iDisplayLength != '-1') {

			$this->db->limit($iDisplayLength, $iDisplayStart);

        }

		$recordfilter_query = $this->db->get();  

		$fetch_data=$recordfilter_query->result();  

		$data = array();

		$cnt=$this->input->post('start')+1;		   

	    foreach($fetch_data as $row)  

	    {  

			if($row->discount_price)

			{

				$discount_status=$row->discount_price.' ['.$row->discount_start_date.' To '.$row->discount_end_date.']';

			}

			else

			{

				$discount_status='NA';

			}

			

			$sub_array = array();  

			$sub_array[] =$cnt;

			$sub_array[] = $row->brand_name;

			$sub_array[] = $row->category_name;  

			$sub_array[] = $row->sub_category_name;  

			$sub_array[] = $row->product_name;  

			$sub_array[] = $row->price;  

			$sub_array[] = $row->quantity;  

			$sub_array[] = $discount_status;  

			//$sub_array[] = ($row->is_active)?'<td><span class="label label-success">Active</span></td>':'<td><span class="label label-danger">Inactive</span></td>';	

			$sub_array[] = ($row->is_active)?'<td><a class="status_inactive" onclick="update_status('."'".$row->id."'".',0)" href="javascript:void(0)"><span class="label label-success">Active</span></a></td>':'<td><a class="status_active"  onclick="update_status('."'".$row->id."'".',1)"  href="javascript:void(0)"><span class="label label-danger">Inactive</span></a></td>';	

			$sub_array[] = '<a href="'.base_url().ADMIN_END.'/product/update_product/'.base64_encode($row->id).'" title="Edit"><i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i></a>

			<a href="'.base_url().ADMIN_END.'/product/upload_file/'.base64_encode($row->id).'" title="Upload Image/Video"><i class="fa fa-cloud-upload fa-lg" aria-hidden="true"></i></a>

			<a href="javascript:void(0)" title="Delete" onclick="delete_product('."'".$row->id."'".')"><i class="fa fa-trash-o fa-lg" aria-hidden="true"></i></a>';  

		    $data[] = $sub_array;  

			$cnt++;

	    }

 

		//get the total record count

		$this->db->select('id');

		$this->db->from(TBL_PRODUCT.' AS p');

		$this->db->join(TBL_SUB_CATEGORY.' AS s', 's.id = p.sub_category_id', 'LEFT');

		$this->db->join(TBL_CATEGORY.' AS c', 'c.id = s.category_id', 'LEFT');

		$this->db->join(TBL_BRANDS.' AS b', 'b.id = c.brand_id', 'LEFT');

		$this->db->where('s.is_delete',0);

		$this->db->where('c.is_delete',0);

		$this->db->where('b.is_delete',0);

		$this->db->where('p.is_delete',0);

		$recordsTotal=$this->db->count_all_results();

		

		$output = array(  

			"draw"            => intval($this->input->post("draw")),  

			"recordsTotal"    => $recordsTotal,  

			"recordsFiltered" => $recordsFiltered,  

			"data"            => $data  

	    );  

		return $output;  

	}

	

	public function add_product($data)

	{

		$result='';			

		$this->db->where('sub_category_id',$data['sub_category_id']);

		$this->db->where('category_id',$data['category_id']);

		$this->db->where('brand_id',$data['brand_id']);

		$this->db->where('product_name',$data['product_name']);

		$this->db->where('is_delete',0); 

        $product_info=$this->db->get(TBL_PRODUCT)->row(); 

		

		if($product_info)

		{

			$this->session->set_flashdata('error', $data['product_name']." already exists.");

			$result=0;

		}

		else

		{

			if($this->db->insert(TBL_PRODUCT, $data))

			{
				// code by gaurav 2018-03-10
				
					$product_id = $this->db->insert_id();

					$product_group = array(
						'main_seller_id' => $this->session->userdata('user_id'),
						'member_ids' => $this->session->userdata('user_id'),
						'product_id' => $product_id,
						'created' => date("Y-m-d h:i:s"),
						'modified' => date("Y-m-d h:i:s"),
					);
					if($this->db->insert(TBL_PRODUCT_GROUP, $product_group))
					{
						$this->session->set_flashdata('success', $data['product_name']." added successfully.");

						$result=1;	
					}else{
						$result=-1;					
					}
			}

			else

			{

				$this->session->set_flashdata('error',"Error while add product.");

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

	

	public function fetch_sub_category_by_category_id()

	{

		$this->db->where('is_delete',0);

		$this->db->where('brand_id',$this->input->post('brand_id'));

		$this->db->where('category_id',$this->input->post('category_id'));

        return $this->db->get(TBL_SUB_CATEGORY)->result(); 

		//echo $this->db->last_query();

	}

	

	public function get_category_by_brand_id($brand_id)

	{

		$this->db->where('is_delete',0);

		$this->db->where('brand_id',$brand_id);

        return $this->db->get(TBL_CATEGORY)->result(); 

	}

	

	public function get_sub_category_by_category_id($brand_id,$category_id)

	{

		$this->db->where('is_delete',0);

		$this->db->where('brand_id',$brand_id);

		$this->db->where('category_id',$category_id);

        return $this->db->get(TBL_SUB_CATEGORY)->result(); 

	}

	

	public function get_productByID($id)

    {

		$this->db->where('id',$id);

        return $this->db->get(TBL_PRODUCT)->row(); 

    }

	

	public function update($id,$data)

	{

		$result['message']='';	

		$result['status']='';			

		$this->db->where('sub_category_id',$data['sub_category_name']);

		$this->db->where('category_id',$data['category_id']);

		$this->db->where('brand_id',$data['brand_id']);

		$this->db->where('product_name',$data['product_name']);

		$this->db->where('id !=',$id);

		$this->db->where('is_delete',0); 

        $product_info=$this->db->get(TBL_PRODUCT)->row(); 

		

		if($product_info)

		{

			$this->session->set_flashdata('error', $data['product_name']." already exists.");

			return 0;

		}

		else

		{

			$this->db->where('id',$id);

			if($this->db->update(TBL_PRODUCT, $data))

			{

				$this->session->set_flashdata('success', $data['product_name'].' updated successfully.');

			}

			else

			{

				$this->session->set_flashdata('error', 'Error! while update product information.');

			}	

			return 1;

		}		

	}	



	public function delete_sub_category($id,$data)

	{

		$this->db->where('id', $id);

		if($this->db->update(TBL_PRODUCT, $data))

		{

			$this->session->set_flashdata('success', 'Sub category information has been deleted successfully.');

		}

		else

		{

			$this->session->set_flashdata('error', 'Error! while delete sub category information.');

		}		

	}

	

	public function update_status($data,$id)

	{

		$result=array();

		$this->db->where('id',$id);

		if($this->db->update(TBL_PRODUCT, $data))

		{

			$result['message']="Product status updated successfully.";

			$result['status']="success";

		}

		else

		{

			$result['message']="Error! while update product status.";

			$result['status']="error";

		}	

		return $result;

	}

	

	public function update_product_file($data)

	{

		if($data['file_type']==0)

		{

			$this->db->select('id,product_id,img_video');

			$this->db->where('file_type',$data['file_type']);

			$this->db->where('product_id',$data['product_id']);

			$image_file_count=$this->db->get(TBL_PRODUCT_IMG_VIDEO)->num_rows();

			

			if($image_file_count>=5)

			{

				$this->session->set_flashdata('error', 'You have already select 5 image.Please remove previous file');

			}

			else

			{

				if($this->db->insert(TBL_PRODUCT_IMG_VIDEO, $data))

				{

					$this->session->set_flashdata('success', 'File upload successfully.');

				}

				else

				{

					$this->session->set_flashdata('error', 'Error! while upload product file.');

				}	

			}

		}

		else if($data['file_type']==1)

		{

			$this->db->select('id,product_id,img_video');

			$this->db->where('file_type',$data['file_type']);

			$this->db->where('product_id',$data['product_id']);

			$video_file_count=$this->db->get(TBL_PRODUCT_IMG_VIDEO)->num_rows();

			

			if($video_file_count>=1)

			{

				$this->session->set_flashdata('error', 'You have already video file.Please delete previous file');

			}

			else

			{

				if($this->db->insert(TBL_PRODUCT_IMG_VIDEO, $data))

				{

					$this->session->set_flashdata('success','File upload successfully.');

				}

				else

				{

					$this->session->set_flashdata('error', 'Error! while upload video file.');

				}	

			}

		}

		return 1;

	}

	

	public function get_productFileByID($id)

	{

		$this->db->select('id,product_id,img_video');

		$this->db->where('product_id',$id);

        return $this->db->get(TBL_PRODUCT_IMG_VIDEO)->result(); 

	}

	

	public function delete_product_file($id)

	{

		$this->db->select('id,img_video');

		$this->db->where('id',$id);

        $product_info=$this->db->get(TBL_PRODUCT_IMG_VIDEO)->row(); 

		

		$path=UPLOADS.DS.PRODUCT.DS.$product_info->img_video;

		$this->db->where('id', $id);

		if($this->db->delete(TBL_PRODUCT_IMG_VIDEO))

		{

			@unlink($path);

			return 1;

		}else

		{

			return 0;

		}			

	}

	

	

	/* front view */

	public function search_product($product_name,$category_id)

	{

		$this->db->select('p.id,s.name as sub_category_name,c.name as category_name,p.is_active,b.name as brand_name,p.product_name,p.price,p.quantity,p.discount_price,p.discount_start_date,p.discount_end_date,IF((discount_start_date<="'.DB_CURRENT_DATE.'" && discount_end_date>="'.DB_CURRENT_DATE.'"),1,0) as discount_status',false);

		$this->db->from(TBL_PRODUCT.' AS p');

		$this->db->join(TBL_SUB_CATEGORY.' AS s', 's.id = p.sub_category_id', 'LEFT');

		$this->db->join(TBL_CATEGORY.' AS c', 'c.id = s.category_id', 'LEFT');

		$this->db->join(TBL_BRANDS.' AS b', 'b.id = c.brand_id', 'LEFT');

		$this->db->where('s.is_delete',0);

		$this->db->where('c.is_delete',0);

		$this->db->where('b.is_delete',0);

		$this->db->where('p.is_delete',0);

		if($category_id!=0)

		{

			$this->db->where('p.category_id',$category_id);

		}

		$this->db->like('p.product_name', $product_name);

		$product_data = $this->db->get()->result(); 

		/*echo "<pre>";

		print_r($product_data);

		exit;*/

		return $product_data;

	}

	

	public function get_product_image($id)

	{

		$this->db->select('id,product_id,img_video');

		$this->db->where('product_id',$id);

		$this->db->where('file_type',0);

		$product_image_data = $this->db->get(TBL_PRODUCT_IMG_VIDEO)->row(); 

		return $product_image_data;

	}

	

	public function get_product_category($category_id)

	{

		$this->db->select('id,name');

		$this->db->where('is_active',IS_ACTIVE_YES);

		$this->db->where('is_delete',IS_DELETED_NO);

		if($category_id!=0)

		{

			$this->db->where('id',$category_id);

		}

		$product_image_data = $this->db->get(TBL_CATEGORY)->row(); 

		return $product_image_data;

	}

	

	public function get_product_details_by_id()

	{

		$id=base64_decode($this->uri->segment(3));

		

		$this->db->select('p.id,c.name as category_name,p.is_active,p.short_description,p.description,p.product_name,p.price,p.quantity,p.discount_price,p.discount_start_date,p.discount_end_date,p.privacy_policy_file_name');

		$this->db->from(TBL_PRODUCT.' AS p');

		$this->db->join(TBL_CATEGORY.' AS c', 'c.id = p.category_id', 'LEFT');

		$this->db->where('c.is_delete',0);

		$this->db->where('p.is_delete',0);

		$this->db->where('p.id', $id);

		$product_data = $this->db->get()->row(); 

		

		$product_img_video = $this->db->select('img_video,file_type')->from(TBL_PRODUCT_IMG_VIDEO)->where('product_id',$id)->get()->result_array();

		

		$this->db->select('CONCAT_WS(" ",fname,mname,lname) as user_name, pr.name,pr.email,title,price,value,quality,comment,pr.created,pr.modified',false);

		$this->db->from(TBL_PRODUCT_REVIEW.' AS pr');

		$this->db->join(TBL_USERS.' AS u', 'u.id = pr.user_id', 'LEFT');

		$this->db->where(['is_admin_approve'=>IS_ADMIN_APPROVE_YES,'is_deleted'=>IS_DELETED_NO,'pr.product_id'=>$id]);

		$product_review = $this->db->get()->result_array();

		

		//echo $this->db->last_query(); die();

		

		return ['product_data'=>$product_data,'product_img_video'=>$product_img_video,'product_review'=>$product_review];

	}

	

	public function add_product_to_cart_by_id()

	{

		$id=base64_decode($this->uri->segment(3));

		$ip_address=$_SERVER['REMOTE_ADDR'];

		$data = array(

				'product_id' => $id,

				'ip_address' => $ip_address,

				'created' =>date("Y-m-d h:i:s")

			);

		if($this->db->insert(TBL_CART, $data))

		{

			$this->session->set_flashdata('success',"Product added to cart successfully.");

			$result=1;

		}

		else

		{

			$this->session->set_flashdata('error',"Error while add product to cart.");

			$result=-1;

		}	

	}

	

	public function add_product_to_wishlist_by_id()

	{

		$id=base64_decode($this->uri->segment(3));

		$ip_address=$_SERVER['REMOTE_ADDR'];

		$data = array(

				'product_id' => $id,

				'ip_address' => $ip_address,

				'created' =>date("Y-m-d h:i:s")

			);

		if($this->db->insert(TBL_WISHLIST, $data))

		{

			$this->session->set_flashdata('success',"Product added to wish list successfully.");

			$result=1;

		}

		else

		{

			$this->session->set_flashdata('error',"Error while add product to wish list.");

			$result=-1;

		}	

	}

	

	public function add_to_cart($product_id)

	{

		$this->db->select('price,discount_price,IF((discount_start_date<="'.DB_CURRENT_DATE.'" && discount_end_date>="'.DB_CURRENT_DATE.'"),1,0) as discount_status',false);

		$this->db->where('id',$product_id);

		//$this->db->where('(discount_start_date<="'.DB_CURRENT_DATE.'" AND discount_end_date>="'.DB_CURRENT_DATE.'")');

		$product_price = $this->db->get(TBL_PRODUCT)->row();

		/*echo '<pre>'; print_r($product_price); 

		echo $this->db->last_query();

		die();*/

		$quantity = $this->input->post('qty');

		$total_price = ($product_price->discount_status)?($product_price->discount_price * $quantity):($product_price->price * $quantity);

		$data = array(

				'user_id' => (int)$this->session->userdata('user_id'),

				'ip_address' => $this->input->ip_address(),

				'product_id' => $product_id,

				'quantity' => $quantity,

				'unit_price' => $product_price->price,

				'discount_price' => $product_price->discount_price,

				'discount_status' => $product_price->discount_status,

				'total_price' => $total_price,

				'created' => DB_CURRENT_DATE,

				'modified' => DB_CURRENT_DATE,

			);

		

		return ($this->db->insert(TBL_CART,$data))?1:0;			

		/*	

		echo '<pre>'.$pid;

		print_r($this->input->post());

		die('in');*/

	}

	

	public function get_cart_item()

	{

		$user_id = (int)$this->session->userdata('user_id');

		$ip_address = $this->input->ip_address();

		

		//$this->db->select('price,discount_price,IF((discount_start_date<="'.DB_CURRENT_DATE.'" && discount_end_date>="'.DB_CURRENT_DATE.'"),1,0) as discount_status',false);

		

		$this->db->select('c.id,c.product_id, c.quantity, c.unit_price, c.discount_price as cart_discount_price, c.discount_status as cart_discount_status, c.total_price, p.product_name, p.price,p.discount_price,IF((discount_start_date<="'.DB_CURRENT_DATE.'" && discount_end_date>="'.DB_CURRENT_DATE.'"),1,0) as discount_status',false);

		$this->db->from(TBL_CART.' AS c');

		$this->db->join(TBL_PRODUCT.' AS p', 'p.id = c.product_id', 'LEFT');

		

		if($user_id>0){

			$this->db->where(['user_id'=>$user_id]);

		}else{

			$this->db->where(['ip_address'=>$ip_address,'user_id'=>$user_id]);

		}

		//$this->db->or_where(['piv.file_type'=>PRODUCT_TYPE_IMG]);

		

		return $this->db->get()->result_array();

		//echo '<pre>'; print_r($result); print_r($product_img_video); die();

	}

	

	public function update_cart()

	{

		$data=array('quantity'=>$this->input->post('qty'));

		$this->db->where(['id'=>$this->input->post('cid'),'product_id'=>$this->input->post('pid')]);

		return ($this->db->update(TBL_CART,$data))?1:0;

	}

	

	public function remove_cart_item()

	{

		$this->db->where('id', $this->input->post('cid'));

		return ($this->db->delete(TBL_CART))?1:0; 

	}

	

	public function empty_cart()

	{

		return ($this->db->empty_table(TBL_CART))?1:0;

	}

	

	public function get_cart_items_by_ids($ids)

	{

		$ids = explode(',',$ids);

		$this->db->select('c.id,c.product_id, c.quantity, c.unit_price, c.discount_price as cart_discount_price, c.discount_status as cart_discount_status, c.total_price, p.product_name, p.price,p.discount_price,IF((discount_start_date<="'.DB_CURRENT_DATE.'" && discount_end_date>="'.DB_CURRENT_DATE.'"),1,0) as discount_status',false);

		$this->db->from(TBL_CART.' AS c');

		$this->db->join(TBL_PRODUCT.' AS p', 'p.id = c.product_id', 'LEFT');

		$this->db->where_in('c.id',$ids);

		return $this->db->get()->result_array();

		/*echo $this->db->last_query();

		echo '<pre>';

		print_r($result);

		die();*/

	}

	

	public function update_cart_user_id()

	{

		$ids = explode(',',$this->session->userdata('cart_ids'));

		$this->db->set('user_id',$this->session->userdata('user_id'));

		$this->db->where_in('id',$ids);

		return $this->db->update(TBL_CART);

	}

	

	public function remove_ordered_items_from_cart()

	{

		$ids = explode(',',$this->session->userdata('cart_ids'));

		$this->db->where_in('id',$ids);

		return $this->db->delete(TBL_CART);

	}

	

	public function get_recent_orders()

	{

		return $this->db->where(['user_id'=>$this->session->userdata('user_id'),'status !='=>1])->order_by("created","desc")->limit(5)->get(TBL_ORDERS)->result_array();

	}

	

	public function get_my_orders()

	{

		return $this->db->where(['user_id'=>$this->session->userdata('user_id')])->order_by("created","desc")->get(TBL_ORDERS)->result_array();

	}

	

	public function get_my_wishlist()

	{

		$this->db->from(TBL_WISHLIST.' as w');

		$this->db->join(TBL_PRODUCT.' as p','w.product_id=p.id','left');

		$this->db->where(['user_id'=>$this->session->userdata('user_id')]);

		return $this->db->get()->result_array();

	}

	

	public function get_all_user_product_data()

	{

		$sSearch = $this->input->post('search');

		$order=$this->input->post('order')['0'];

		$iDisplayStart=$this->input->post('start');

		$iDisplayLength=$this->input->post('length');

		

		$this->db->select('p.id,s.name as sub_category_name,c.name as category_name,p.is_active,b.name as brand_name,p.product_name,p.price,p.quantity,p.discount_price,p.discount_start_date,p.discount_end_date,p.admin_approved');

		//filtering data by search value

		if(isset($sSearch['value']))  

	    {  

			$where_like= "(s.name LIKE '%".$sSearch['value']."%' OR c.name LIKE '%".$sSearch['value']."%' OR b.name LIKE '%".$sSearch['value']."%' OR p.product_name LIKE '%".$sSearch['value']."%' OR p.price LIKE '%".$sSearch['value']."%' OR p.quantity LIKE '%".$sSearch['value']."%' OR p.discount_price LIKE '%".$sSearch['value']."%')";

			$this->db->where($where_like); 

	    } 

		//ordering column

		(isset($_POST["order"]))?$this->db->order_by($this->order_column[$order['column']], $order['dir']):$this->db->order_by('id', 'DESC');	

		

		//get the count of records after filtering 

		$this->db->from(TBL_PRODUCT.' AS p');

		$this->db->join(TBL_SUB_CATEGORY.' AS s', 's.id = p.sub_category_id', 'LEFT');

		$this->db->join(TBL_CATEGORY.' AS c', 'c.id = s.category_id', 'LEFT');

		$this->db->join(TBL_BRANDS.' AS b', 'b.id = c.brand_id', 'LEFT');

		$this->db->where('s.is_delete',0);

		$this->db->where('c.is_delete',0);

		$this->db->where('b.is_delete',0);

		$this->db->where('p.is_delete',0);

		$this->db->where('p.added_by',$this->session->userdata('user_id'));

		$recordfilter_query = $this->db->get();  

		$recordsFiltered=$recordfilter_query->num_rows();

		

		//fetch all record

		$this->db->select('p.id,s.name as sub_category_name,c.name as category_name,p.is_active,b.name as brand_name,p.product_name,p.price,p.quantity,p.discount_price,p.discount_start_date,p.discount_end_date,p.admin_approved');

		//filtering data by search value

		if(isset($sSearch['value']))  

	    {  

			$where_like= "(s.name LIKE '%".$sSearch['value']."%' OR c.name LIKE '%".$sSearch['value']."%' OR b.name LIKE '%".$sSearch['value']."%' OR p.product_name LIKE '%".$sSearch['value']."%' OR p.price LIKE '%".$sSearch['value']."%' OR p.quantity LIKE '%".$sSearch['value']."%' OR p.discount_price LIKE '%".$sSearch['value']."%')";

			$this->db->where($where_like); 

	    } 

		//ordering column

		(isset($_POST["order"]))?$this->db->order_by($this->order_column[$order['column']], $order['dir']):$this->db->order_by('id', 'DESC');	

		

		//get the count of records after filtering 

		$this->db->from(TBL_PRODUCT.' AS p');

		$this->db->join(TBL_SUB_CATEGORY.' AS s', 's.id = p.sub_category_id', 'LEFT');

		$this->db->join(TBL_CATEGORY.' AS c', 'c.id = s.category_id', 'LEFT');

		$this->db->join(TBL_BRANDS.' AS b', 'b.id = c.brand_id', 'LEFT');

		$this->db->where('s.is_delete',0);

		$this->db->where('c.is_delete',0);

		$this->db->where('b.is_delete',0);

		$this->db->where('p.is_delete',0);

		$this->db->where('p.added_by',$this->session->userdata('user_id'));

		if (isset($iDisplayStart) && $iDisplayLength != '-1') {

			$this->db->limit($iDisplayLength, $iDisplayStart);

        }

		$recordfilter_query = $this->db->get();  

		$fetch_data=$recordfilter_query->result();  

		$data = array();

		$cnt=$this->input->post('start')+1;		   

	    foreach($fetch_data as $row)  

	    {  

			if($row->discount_price)

			{

				$discount_status=$row->discount_price.' ['.$row->discount_start_date.' To '.$row->discount_end_date.']';

			}

			else

			{

				$discount_status='NA';

			}

			

			$sub_array = array();  

			$sub_array[] =$cnt;

			$sub_array[] = $row->brand_name;

			$sub_array[] = $row->category_name;  

			$sub_array[] = $row->sub_category_name;  

			$sub_array[] = $row->product_name;  

			$sub_array[] = $row->price;  

			$sub_array[] = $row->quantity;  

			$sub_array[] = $discount_status;  

			$sub_array[] = ($row->admin_approved==1)?'<td><span class="label label-success">Approved</span></td>':(($row->admin_approved==0)?'<td><span class="label label-warning">Pending</span></td>':'<td><span class="label label-danger">Rejected</span></td>');	

			$sub_array[] = ($row->is_active)?'<td><a class="status_inactive" onclick="update_status('."'".$row->id."'".',0)" href="javascript:void(0)"><span class="label label-success">Active</span></a></td>':'<td><a class="status_active" onclick="update_status('."'".$row->id."'".',1)"  href="javascript:void(0)"><span class="label label-danger">Inactive</span></a></td>';	

			$sub_array[] = '<a href="'.base_url().'user/update_product/'.base64_encode($row->id).'" title="Edit"><i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i></a>

			<a href="'.base_url().'user/upload_file/'.base64_encode($row->id).'" title="Upload Image/Video"><i class="fa fa-cloud-upload fa-lg" aria-hidden="true"></i></a>

			<a href="javascript:void(0)" title="Delete" onclick="delete_product('."'".$row->id."'".')"><i class="fa fa-trash-o fa-lg" aria-hidden="true"></i></a>';  

		    $data[] = $sub_array;  

			$cnt++;

	    }

 

		//get the total record count

		$this->db->select('id');

		$this->db->from(TBL_PRODUCT.' AS p');

		$this->db->join(TBL_SUB_CATEGORY.' AS s', 's.id = p.sub_category_id', 'LEFT');

		$this->db->join(TBL_CATEGORY.' AS c', 'c.id = s.category_id', 'LEFT');

		$this->db->join(TBL_BRANDS.' AS b', 'b.id = c.brand_id', 'LEFT');

		$this->db->where('s.is_delete',0);

		$this->db->where('c.is_delete',0);

		$this->db->where('b.is_delete',0);

		$this->db->where('p.is_delete',0);

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

	

	var $marchant_pending_order_column = array("id", "id", "b.name","c.name","s.name","product_name","price","quantity","discount_price"); 



	public function get_fetch_marchant_pending_product_data()

	{

		$sSearch = $this->input->post('search');

		$order=$this->input->post('order')['0'];

		$iDisplayStart=$this->input->post('start');

		$iDisplayLength=$this->input->post('length');

		

		$this->db->select('p.id,s.name as sub_category_name,c.name as category_name,p.is_active,b.name as brand_name,p.product_name,p.price,p.quantity,p.discount_price,p.discount_start_date,p.discount_end_date,p.admin_approved');

		//filtering data by search value

		if(isset($sSearch['value']))  

	    {  

			$where_like= "(s.name LIKE '%".$sSearch['value']."%' OR c.name LIKE '%".$sSearch['value']."%' OR b.name LIKE '%".$sSearch['value']."%' OR p.product_name LIKE '%".$sSearch['value']."%' OR p.price LIKE '%".$sSearch['value']."%' OR p.quantity LIKE '%".$sSearch['value']."%' OR p.discount_price LIKE '%".$sSearch['value']."%')";

			$this->db->where($where_like); 

	    } 

		//ordering column

		(isset($_POST["order"]))?$this->db->order_by($this->marchant_pending_order_column[$order['column']], $order['dir']):$this->db->order_by('id', 'DESC');	

		

		//get the count of records after filtering 

		$this->db->from(TBL_PRODUCT.' AS p');

		$this->db->join(TBL_SUB_CATEGORY.' AS s', 's.id = p.sub_category_id', 'LEFT');

		$this->db->join(TBL_CATEGORY.' AS c', 'c.id = s.category_id', 'LEFT');

		$this->db->join(TBL_BRANDS.' AS b', 'b.id = c.brand_id', 'LEFT');

		$this->db->where('s.is_delete',0);

		$this->db->where('c.is_delete',0);

		$this->db->where('b.is_delete',0);

		$this->db->where('p.is_delete',0);

		$this->db->where('p.admin_approved !=',1);

		$recordfilter_query = $this->db->get();  

		$recordsFiltered=$recordfilter_query->num_rows();

		

		//fetch all record

		$this->db->select('p.id,s.name as sub_category_name,c.name as category_name,p.is_active,b.name as brand_name,p.product_name,p.price,p.quantity,p.discount_price,p.discount_start_date,p.discount_end_date,p.admin_approved');

		//filtering data by search value

		if(isset($sSearch['value']))  

	    {  

			$where_like= "(s.name LIKE '%".$sSearch['value']."%' OR c.name LIKE '%".$sSearch['value']."%' OR b.name LIKE '%".$sSearch['value']."%' OR p.product_name LIKE '%".$sSearch['value']."%' OR p.price LIKE '%".$sSearch['value']."%' OR p.quantity LIKE '%".$sSearch['value']."%' OR p.discount_price LIKE '%".$sSearch['value']."%')";

			$this->db->where($where_like); 

	    } 

		//ordering column

		(isset($_POST["order"]))?$this->db->order_by($this->marchant_pending_order_column[$order['column']], $order['dir']):$this->db->order_by('id', 'DESC');	

		

		//get the count of records after filtering 

		$this->db->from(TBL_PRODUCT.' AS p');

		$this->db->join(TBL_SUB_CATEGORY.' AS s', 's.id = p.sub_category_id', 'LEFT');

		$this->db->join(TBL_CATEGORY.' AS c', 'c.id = s.category_id', 'LEFT');

		$this->db->join(TBL_BRANDS.' AS b', 'b.id = c.brand_id', 'LEFT');

		$this->db->where('s.is_delete',0);

		$this->db->where('c.is_delete',0);

		$this->db->where('b.is_delete',0);

		$this->db->where('p.is_delete',0);

		$this->db->where('p.admin_approved !=',1);

		if (isset($iDisplayStart) && $iDisplayLength != '-1') {

			$this->db->limit($iDisplayLength, $iDisplayStart);

        }

		$recordfilter_query = $this->db->get();  

		$fetch_data=$recordfilter_query->result();  

		$data = array();

		$cnt=$this->input->post('start')+1;		   

	    foreach($fetch_data as $row)  

	    {  

			if($row->discount_price)

			{

				$discount_status=$row->discount_price.' ['.$row->discount_start_date.' To '.$row->discount_end_date.']';

			}

			else

			{

				$discount_status='NA';

			}

			

			$sub_array = array();  

			$sub_array[] =$cnt;

			$sub_array[] = $row->brand_name;

			$sub_array[] = $row->category_name;  

			$sub_array[] = $row->sub_category_name;  

			$sub_array[] = $row->product_name;  

			$sub_array[] = $row->price;  

			$sub_array[] = $row->quantity;  

			$sub_array[] = $discount_status;

			

			$sub_array[] = '<td><select name="admin_approve_status" id="admin_approve_status" onchange="update_approved_status(this.value,'.$row->id.')"><option value="0">Pending</option><option value="1" '.(($row->admin_approved==1)?'selected="selected"':'').'>Approved</option><option value="2" '.(($row->admin_approved==2)?'selected="selected"':'').'>Rejected</option></select></td>';

			

		    $data[] = $sub_array;  

			$cnt++;

	    }

 

		//get the total record count

		$this->db->select('id');

		$this->db->from(TBL_PRODUCT.' AS p');

		$this->db->join(TBL_SUB_CATEGORY.' AS s', 's.id = p.sub_category_id', 'LEFT');

		$this->db->join(TBL_CATEGORY.' AS c', 'c.id = s.category_id', 'LEFT');

		$this->db->join(TBL_BRANDS.' AS b', 'b.id = c.brand_id', 'LEFT');

		$this->db->where('s.is_delete',0);

		$this->db->where('c.is_delete',0);

		$this->db->where('b.is_delete',0);

		$this->db->where('p.is_delete',0);

		$this->db->where('p.admin_approved !=',1);

		$recordsTotal=$this->db->count_all_results();

		

		$output = array(  

			"draw"            => intval($this->input->post("draw")),  

			"recordsTotal"    => $recordsTotal,  

			"recordsFiltered" => $recordsFiltered,  

			"data"            => $data  

	    );  

		return $output;  

	}

	

	public function update_approved_status($data,$id)

	{

		$result=array();

		$this->db->where('id',$id);

		if($this->db->update(TBL_PRODUCT, $data))

		{

			$result['message']="Product status updated successfully.";

			$result['status']="success";

		}

		else

		{

			$result['message']="Error! while update product status.";

			$result['status']="error";

		}	

		return $result;

	}

}

?>