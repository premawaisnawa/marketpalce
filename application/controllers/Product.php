<?php

class Product extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->library(array('form_validation','pagination'));
		$this->load->helper(array('form', 'url'));
		$this->load->model(array('M_product','M_product_category','M_product_sub_category','M_pagination','M_quotation','M_quotation_detail'));
	}

	function index(){
		redirect('Product/public_product_list_view');
	}
	/* function public_product_list_view() digunakan untuk menampilkan product list
	kepada public (non member, member) 	*/
	function public_product_list_view(){
		$data_nav = "";
		$product_rules['join']['other_table_columns'] = " ,user_tb.*, productpic_tb.*, productcategory_tb.*, productsubcategory_tb.* ";
		$product_rules['join']['join_table'] = " INNER JOIN user_tb INNER JOIN productpic_tb INNER JOIN productcategory_tb INNER JOIN productsubcategory_tb
		ON product_tb.Id = productpic_tb.ProductId
		AND user_tb.Id = product_tb.SupplierId
		AND product_tb.ProductSubCategoryCode = productsubcategory_tb.Code
		AND productcategory_tb.Code = productsubcategory_tb.ProductCategoryCode";
		$product_rules['group_by'] = ' productpic_tb.ProductId ';

		//mengambil nilai page dari url
		$page = $this->input->get('per_page');
		$this->M_pagination->set_config("",12,"","","","","");
		/* mengecek apakah nilai dari form pencarian ada atau tidak jika ada maka
		Product list akan menampilkan product berdasarkan nama product atau kategori
		produk */
		if ( (!empty($this->input->get('search_value')) OR !empty($this->input->get('product_category_code'))) OR !empty($this->input->get('product_sub_category_code')) ) {
			if (!empty($this->input->get('search_value'))) {
				$search_value = $this->input->get('search_value');
				$data_nav['search_value'] = $search_value;
				$product_rules['filter_value'] =  array('is_published' => 1, 'search_value'=>$search_value, 'is_verified_supplier' =>1);
				$this->M_product->set_search_product($product_rules);
				$get_product = $this->M_product->get_product();
				$this->M_pagination->set_config(
					"","","","","","","","Product/public_product_list_view?search_value=".$search_value,
					$get_product->num_rows()
				);
				$config = $this->M_pagination->get_config();
				$offset = $this->M_pagination->get_offset($page);
				$product_rules['limit'] = $config["per_page"];
				$product_rules['offset'] = $offset;
				$this->M_product->set_search_product($product_rules);
				$get_product = $this->M_product->get_product();
				$data['breadcrumb'] = "<li>"."<a href='".site_url('Home/home_view/')."'>Home</a>"."</li>";
				$data['breadcrumb'] .= "<li class='active'>"."Search for '".$search_value."''</li>";
			}
			elseif ( !empty($this->input->get('product_category_code'))) {
				$product_category_code = $this->input->get('product_category_code');
				$product_rules['filter_value'] =  array('is_published' => 1, 'product_category_code'=>$product_category_code, 'is_verified_supplier' =>1);
				$this->M_product->set_search_product($product_rules);
				$get_product = $this->M_product->get_product();
				$this->M_pagination->set_config(
					"","","","","","","","Product/public_product_list_view?product_category_code=".$product_category_code,
					$get_product->num_rows()
				);
				$config = $this->M_pagination->get_config();
				$offset = $this->M_pagination->get_offset($page);
				$product_rules['limit'] = $config["per_page"];
				$product_rules['offset'] = $offset;
				$this->M_product->set_search_product($product_rules);
				$get_product = $this->M_product->get_product();
				$get_product_category = $this->M_product_category->get_product_category($product_category_code);
				$baris = $get_product_category->row();
				$data['breadcrumb'] = "<li>"."<a href='".site_url('Home/home_view/')."'>Home</a>"."</li>";
				$data['breadcrumb'] .= "<li class='active'>".$baris->ProductCategory."</li>";
				$head_data['page_title'] = $baris->ProductCategory;
			}
			else {

				$product_sub_category_code = $this->input->get('product_sub_category_code');
				$product_rules['filter_value'] =  array('is_published' => 1, 'product_sub_category_code'=>$product_sub_category_code, 'is_verified_supplier' =>1);
				$this->M_product->set_search_product($product_rules);
				$get_product = $this->M_product->get_product();
				$this->M_pagination->set_config(
					"","","","","","","","Product/public_product_list_view?product_sub_category_code=".$product_sub_category_code,
					$get_product->num_rows()
				);
				$config = $this->M_pagination->get_config();
				$offset = $this->M_pagination->get_offset($page);
				$product_rules['limit'] = $config["per_page"];
				$product_rules['offset'] = $offset;
				$this->M_product->set_search_product($product_rules);
				$get_product = $this->M_product->get_product();

				$product_sub_category_rules['join']['other_table_columns'] = " ,productcategory_tb.* ";
				$product_sub_category_rules['join']['join_table'] = " INNER JOIN productcategory_tb
				ON productcategory_tb.Code = productsubcategory_tb.ProductCategoryCode ";
				$product_sub_category_rules['filter_value'] =  array('product_sub_category_code'=>$product_sub_category_code);
				$this->M_product_sub_category->set_search_product_sub_category($product_sub_category_rules);
				$get_product_sub_category = $this->M_product_sub_category->get_product_sub_category();
				$baris = $get_product_sub_category->row();
				$data['breadcrumb'] = "<li>"."<a href='".site_url('Home/home_view/')."'>Home</a>"."</li>";
				$data['breadcrumb'] .= "<li>"."<a href='".site_url('Product/public_product_list_view?')."product_category_code=".
				$baris->ProductCategoryCode."'>".$baris->ProductCategory."</a>"."</li>";
				$data['breadcrumb'] .= "<li class='active'>".$baris->ProductSubCategory."</li>";
					// $data['breadcrumb'] .= "<li class='active'>"."<a  href='".site_url('Product/public_product_list_view?')."product_sub_category_code=".$product_sub_category_code."'>".$baris->ProductSubCategory."</a>"."</li>";
				$head_data['page_title'] = $baris->ProductSubCategory;

			}
		}
		/*menampilkan semua product secara acak*/
		else {
			$product_rules['filter_value'] =  array('is_published' => 1, 'is_verified_supplier' =>1);

			$this->M_product->set_search_product($product_rules);

			$get_product = $this->M_product->get_product();
			$this->M_pagination->set_config(
				"","","","","","","","index.php/product/public_product_list_view",
				$get_product->num_rows()
			);
			$config = $this->M_pagination->get_config();
			$offset = $this->M_pagination->get_offset($page);
			$product_rules['limit'] = $config["per_page"];
			$product_rules['offset'] = $offset;
			$this->M_product->set_search_product($product_rules);
			$get_product = $this->M_product->get_product();
			$data['breadcrumb'] = "<li>"."<a href='".site_url('Home/home_view/')."'>Home</a>"."</li>";
			$data['breadcrumb'] .= "<li class='active'>All Product</li>";
		}
		$this->pagination->initialize($config);
		$data['product'] = $get_product->result();
		$str_links = $this->pagination->create_links();
		$data["links"] = explode('&nbsp;',$str_links );
		$this->M_product_category->set_search_product_category();
		$get_product_category = $this->M_product_category->get_product_category();

		$this->M_product_sub_category->set_search_product_sub_category();
		$get_product_sub_category = $this->M_product_sub_category->get_product_sub_category();

		$data_nav['product_category'] = $get_product_category->result();
		$data_nav['product_sub_category'] = $get_product_sub_category->result();
		// if ($this->session->userdata('user_id')) {
		// 	$buyer_id = $this->session->userdata('user_id');
		// 	$get_unread_qutation_detail = $this->M_quotation_detail->get_unread_qutation_detail("",$buyer_id);
		// 	$data_nav['unread_quotation_detail'] = $get_unread_qutation_detail->result();
		// 	$data_nav['unread_quotation_detail_num_rows'] = $get_unread_qutation_detail->num_rows();
		// }
		$head_data['page_title'] = isset($head_data['page_title']) ? $head_data['page_title'] : "Dinilaku" ;
		//print_r($str_links);exit();
		$this->load->view('template/front/head_front',$head_data);
		$this->load->view('template/front/navigation',$data_nav);
		$this->load->view('public/product/product_list',$data);
		$this->load->view('template/front/foot_front');
	}
	function public_product_detail_view($product_id){
		//$product_rules['limit'] = 8;
		$product_rules['join']['other_table_columns'] = " ,user_tb.*, productpic_tb.*, productcategory_tb.*, productsubcategory_tb.* ";
		$product_rules['join']['join_table'] = " INNER JOIN user_tb INNER JOIN productpic_tb INNER JOIN productcategory_tb INNER JOIN productsubcategory_tb
		ON product_tb.Id = productpic_tb.ProductId
		AND user_tb.Id = product_tb.SupplierId
		AND product_tb.ProductSubCategoryCode = productsubcategory_tb.Code
		AND productcategory_tb.Code = productsubcategory_tb.ProductCategoryCode";
		$product_rules['filter_value'] =  array('product_id' => $product_id);
		//$product_rules['group_by'] = ' productpic_tb.ProductId ';
		$this->M_product->set_search_product($product_rules);



		$get_product = $this->M_product->get_product();
		$data['product'] = $get_product->result();
		$this->M_product_category->set_search_product_category();
		$get_product_category = $this->M_product_category->get_product_category();

		$this->M_product_sub_category->set_search_product_sub_category();
		$get_product_sub_category = $this->M_product_sub_category->get_product_sub_category();

		$data_nav['product_category'] = $get_product_category->result();
		$data_nav['product_sub_category'] = $get_product_sub_category->result();

		$baris = $get_product->row();
		$head_data['page_title'] = $baris->Name;
		$data['breadcrumb'] = "<li>"."<a href='".site_url('Home/home_view/')."'>Home</a>"."</li>";
		$data['breadcrumb'] .= "<li>"."<a href='".site_url('Product/public_product_list_view?')."product_category_code=".$baris->ProductCategoryCode."'>".$baris->ProductCategory."</a>"."</li>";
		$data['breadcrumb'] .= "<li >"."<a  href='".site_url('Product/public_product_list_view?')."product_sub_category_code=".$baris->ProductSubCategoryCode."'>".$baris->ProductSubCategory."</a>"."</li>";
		$data['breadcrumb'] .= "<li class='active'>".$baris->Name."</li>";
		$this->load->view('template/front/head_front',$head_data);
		$this->load->view('template/front/navigation',$data_nav);
		$this->load->view('public/product/product_detail',$data);
		$this->load->view('template/front/foot_front');
	}
	function product_view(){
		$user_id = $this->session->userdata('user_id');
		$user_level = $this->session->userdata('user_level');
		if (empty($user_id) || ($user_level != 1 && $user_level != 3)) {
			$this->session->sess_destroy();
			redirect('Home/home_view');
		}
		$product_rules['join']['other_table_columns'] = " ,user_tb.*, productpic_tb.*, productcategory_tb.*, productsubcategory_tb.* ";
		$product_rules['join']['join_table'] = " INNER JOIN user_tb INNER JOIN productpic_tb INNER JOIN productcategory_tb INNER JOIN productsubcategory_tb
		ON product_tb.Id = productpic_tb.ProductId
		AND user_tb.Id = product_tb.SupplierId
		AND product_tb.ProductSubCategoryCode = productsubcategory_tb.Code
		AND productcategory_tb.Code = productsubcategory_tb.ProductCategoryCode";
		$product_rules['group_by'] = ' productpic_tb.ProductId ';
		$product_rules['filter_value'] =  array('supplier_id'=>$user_id);
		$this->M_product->set_search_product($product_rules);
		$get_product = $this->M_product->get_product();
		$data['product'] = $get_product->result();
		// echo "<pre>";
		// print_r($data['product']);
		// echo "</pre>";
		// $get_quotation = $this->M_quotation->get_quotation("",$supplier_id,"",0);
		// $data_notification['unread_quotation'] = $get_quotation->result();
		// $data_notification['unread_quotation_num_rows'] = $get_quotation->num_rows();
		// $get_unread_qutation_detail = $this->M_quotation_detail->get_unread_qutation_detail($supplier_id);
		// $data_notification['unread_quotation_detail'] = $get_unread_qutation_detail->result();
		// $data_notification['unread_quotation_detail_num_rows'] = $get_unread_qutation_detail->num_rows();
		$this->load->view('template/back/head_back');
		$this->load->view('template/back/sidebar_back');
		$this->load->view('private/product/product',$data);
		$this->load->view('template/back/foot_back');
	}
	function product_edit_view($product_id){
		$user_id = $this->session->userdata('user_id');
		$user_level = $this->session->userdata('user_level');
		//mencegah hak akses selain supplier masuk ke dalam method ini
		if (empty($user_id) || ($user_level != 1 && $user_level != 3)) {
			$this->session->sess_destroy();
			redirect('Home/home_view');
		}

		$product_rules['join']['other_table_columns'] = " ,product_tb.Id AS ProductId ,user_tb.*, productcategory_tb.*, productsubcategory_tb.* ";
		$product_rules['join']['join_table'] = " INNER JOIN user_tb  INNER JOIN productcategory_tb INNER JOIN productsubcategory_tb
		ON user_tb.Id = product_tb.SupplierId
		AND product_tb.ProductSubCategoryCode = productsubcategory_tb.Code
		AND productcategory_tb.Code = productsubcategory_tb.ProductCategoryCode";
		$product_rules['filter_value'] =  array('product_id'=>$product_id, 'supplier_id'=>$user_id);
		$this->M_product->set_search_product($product_rules);
		$get_product = $this->M_product->get_product();
		// mencegah supplier lain mengakses halaman edit product supplier lain
		if ($get_product->num_rows() == 0) {
			redirect('Product/product_view');
		}

		$product_pic_rules['filter_value'] =  array('product_id'=>$product_id);
		$this->M_product->set_search_product_pic($product_pic_rules);
		$get_product_pic = $this->M_product->get_product_pic();

		$this->M_product_category->set_search_product_category();
		$get_product_category = $this->M_product_category->get_product_category();

		$row = $get_product->row();
		// echo $row->ProductCategoryCode;exit();
		$product_sub_category_rules['filter_value'] =  array('product_category_code'=>$row->ProductCategoryCode);
		$this->M_product_sub_category->set_search_product_sub_category($product_sub_category_rules);
		$get_product_sub_category = $this->M_product_sub_category->get_product_sub_category();

		$data['product'] = $get_product->result();
		$data['product_pic'] = $get_product_pic->result();
		$data['product_category'] = $get_product_category->result();
		$data['product_sub_category'] = $get_product_sub_category->result();

		$this->load->view('template/back/head_back');
		$this->load->view('template/back/sidebar_back');
		$this->load->view('private/product/edit_product',$data);
		$this->load->view('template/back/foot_back');
	}

	function update_product(){
		$supplier_id = $this->session->userdata('user_id');
		$product_id = $this->input->post('product_id');
		$data = array(
			'Name' => $this->input->post('product_name'),
			'Unit' => $this->input->post('unit'),
			'MinPrice' => $this->input->post('min_price'),
			'MaxPrice' => $this->input->post('max_price'),
			'SupplyAbility' => $this->input->post('supply_ability'),
			'PeriodSupplyAbility' => $this->input->post('period_supply_ability'),
			'ProductSubCategoryCode' => $this->input->post('product_sub_category_code'),
			'ProductDescription' => $this->input->post('product_description'),
			'PkgDelivery' => $this->input->post('pkg_delivery'),
			'IsPublished' => $this->input->post('status')
		);
		//echo $product_id;exit();

		$product_pictures = $this->input->post('file');
		$deleted_image = $this->input->post('deleted_image');
		if (isset($deleted_image)) {
			foreach ($deleted_image as $key => $value) {
				$product_pic_id = $value;
				$product_pic_rules['filter_value'] =  array('product_pic_id'=>$product_pic_id);
				$this->M_product->set_search_product_pic($product_pic_rules);
				$get_product_pic = $this->M_product->get_product_pic();
				$get_product_pic_row = $get_product_pic->row();

				$this->db->where('Id', $product_pic_id);
				$this->db->delete('productpic_tb');

				if(file_exists($file='./assets/supplier_upload/'.str_replace(' ', '_', $get_product_pic_row->FileName))){
					unlink($file);
				}
				echo "{}";
			}
		}

		// print_r($this->input->post('file'));
		// $deleted_image = $this->input->post('deleted_image');
		// print_r($deleted_image);
		// foreach ($deleted_image as $key => $value) {
		// 	echo $value."||";
		// }
		//
		 //exit();
		$this->M_product->update_product($product_id,$data,$product_pictures);

		$this->session->set_flashdata('msg', $this->input->post('product_name').' successfully updated  ');
		redirect('Product/product_view');
	}
	function product_add_view(){
		$supplier_id = $this->session->userdata('user_id');
		if (empty($supplier_id)) {
			redirect('Home/home_view');
		}

		$this->load->view('template/back/head_back');
		$this->load->view('template/back/sidebar_back');
		$this->load->view('private/product/add_product');
		$this->load->view('template/back/foot_back');
	}
	function generate_product_sub_category(){
		$product_category_code=$this->input->post('product_category_code');
		$product_sub_category = $this->M_product_sub_category->get_product_sub_category($product_category_code,1);
		echo $product_sub_category;
	}
	function add_product(){
		$supplier_id = $this->session->userdata('user_id');
		$data = array(
			'Name' => $this->input->post('product_name'),
			'Unit' => $this->input->post('unit'),
			'MinPrice' => $this->input->post('min_price'),
			'MaxPrice' => $this->input->post('max_price'),
			'SupplyAbility' => $this->input->post('supply_ability'),
			'PeriodSupplyAbility' => $this->input->post('period_supply_ability'),
			'ProductSubCategoryCode' => $this->input->post('product_sub_category_code'),
			'ProductDescription' => $this->input->post('product_description'),
			'PkgDelivery' => $this->input->post('pkg_delivery'),
			'IsPublished' => $this->input->post('status'),
			'SupplierId' => $supplier_id
		);
		// $this->form_validation->set_rules('product_name', "ProductName", 'required');
		// $this->form_validation->run();
		$product_pictures = $this->input->post('file');
		// if (empty($product_pictures) || !isset($product_pictures)) {
		// 	redirect('Product/product_add_view');
		// }
		$product_id = $this->M_product->add_product($data,$product_pictures);
		$this->session->set_flashdata('msg', $this->input->post('product_name').' successfully added  ');
		redirect('Product/product_view');
	}

	function add_product_picture(){
		$config['upload_path']   = './assets/supplier_upload';
		$config['allowed_types'] = 'gif|jpg|jpeg|png|ico|pdf|docx';
		$config['max_size']             = 2000;
		//mengganti nama asli file menjadi cstom

		//!!!!harus tambah id user!!!!!!!!!!!!!!!!!!!!!!!!!!!
		$new_name = time().$_FILES["userfiles"]['name'];
		$config['file_name'] = $new_name;
		$this->load->library('upload',$config);
		if($this->upload->do_upload('userfiles')){
			$token=$this->input->post('token_foto');
			$nama=$this->upload->data('file_name');
		}
		$data = $nama.",".$token;
		echo json_encode($data);
	}
	function remove_product_picture_edit(){
		$product_id_pic=$this->input->post('product_id_pic');
		$this->db->where('Id', $product_id_pic);
		$this->db->delete('productpic_tb');
	}
	function show_product_detail_modal()
	{
		$supplier_id = $this->session->userdata('user_id');
		$product_id = $this->input->post('product_id');
		$product_rules['join']['other_table_columns'] = " ,user_tb.*, productpic_tb.*, productcategory_tb.*, productsubcategory_tb.* ";
		$product_rules['join']['join_table'] = " INNER JOIN user_tb INNER JOIN productpic_tb INNER JOIN productcategory_tb INNER JOIN productsubcategory_tb
		ON product_tb.Id = productpic_tb.ProductId
		AND user_tb.Id = product_tb.SupplierId
		AND product_tb.ProductSubCategoryCode = productsubcategory_tb.Code
		AND productcategory_tb.Code = productsubcategory_tb.ProductCategoryCode";
		$product_rules['filter_value'] =  array('product_id' => $product_id, 'supplier_id' => $supplier_id);
		// /$product_rules['group_by'] = ' productpic_tb.ProductId ';
		$this->M_product->set_search_product($product_rules);
		$get_product = $this->M_product->get_product();

		$product = $get_product->result();
		$row = $get_product->row();
		echo "<div class'text-right'><a href='".site_url('Product/public_product_detail_view/').$row->ProductId."' class=' btn btn-info' target='_blank'><span class='glyphicon glyphicon-eye-open'></span> Preview Product Detail in Published</a><div><br>";
		// print_r($row);exit();
		foreach ($product as $key ) {
			// echo $row->Name;
			echo "<img style='margin-right: 3px' src=".base_url()."assets/supplier_upload/".$key->FileName." alt='' width='110'>";

		}
		//

		$status = ($row->IsPublished==1) ? "published" : "no published" ;
		echo "
		<br>
		<h4 class=''><b>Product Name</b></h4>
		<p class=''>".$row->Name."</p>".
		"
		<h4 class=''><b>Product Unit</b></h4>
		<p class=''>".$row->Unit."</p>".
		"
		<h4 class=''><b>Product Category</b></h4>
		<p class=''>".$row->ProductCategory."</p>".
		"
		<h4 class=''><b>Product Sub Category</b></h4>
		<p class=''>".$row->ProductSubCategory."</p>".
		"
		<h4 class=''><b>Min Price</b></h4>
		<p class=''>US $".number_format($row->MinPrice, 2, '.', ',')."</p>".
		"
		<h4 class=''><b>Max Price</b></h4>
		<p class=''>US $".number_format($row->MaxPrice, 2, '.', ',')."</p>".
		"
		<h4 class=''><b>Supply Ability</b></h4>
		<p class=''>".number_format($product[0]->SupplyAbility, 0, '.', ',')."</p>".
		"
		<h4 class=''><b>Period Supply Ability</b></h4>
		<p class=''>".$row->PeriodSupplyAbility."</p>".
		"
		<h4 class=''><b>Product Description</b></h4>
		<p class=''>".$row->ProductDescription."</p>".
		"
		<h4 class=''><b>Package Delivery</b></h4>
		<p class=''>".$row->PkgDelivery."</p>".
		"
		<h4 class=''><b>Product Status</b></h4>
		<p class=''>".$status."</p>";

	}
	function remove_product_picture(){
		$nama=$this->input->post('nama');
		if(file_exists($file='./assets/supplier_upload/'.str_replace(' ', '_', $nama))){
			unlink($file);
		}
		echo "{}";
	}


	}
?>
