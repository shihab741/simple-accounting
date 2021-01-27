<?php
//session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;

class Admin extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$type = $this->session->userdata('type');
		if ($type == NULL) {
			redirect('accounts', 'refresh');
		}
	}

	public function index()
	{
		date_default_timezone_set("Asia/Dhaka");
		$fromDate = date("Y-m-01");

		$table_name = 'daily_data';
		$initialAmountId = 1;

		$data = $this->filtered_report($table_name, $initialAmountId, $fromDate);

		$this->load->view('admin/shopping/report', $data);
	}

	public function logout(){
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('type');
		$sdata = array();
		$sdata['message'] = 'You are successfuly logged out!';
		$this->session->set_userdata($sdata);
		redirect('accounts', 'refresh');
	}





	public function filtered_report_for_shopping()
	{
		$fromDate = $this->input->post('from_date', true);
		$toDate = $this->input->post('to_date', true);

		if($fromDate == null || $toDate == null)
		{
			redirect('admin');
		}

		$table_name = 'daily_data';
		$initialAmountId = 1;

		$data = $this->filtered_report($table_name, $initialAmountId, $fromDate, $toDate);

		$this->load->view('admin/shopping/report', $data);	
	}


	public function previous_months()
	{
		$data = array();
		$data['type'] = $this->session->userdata('type');
		$data['all_months_list'] = $this->Super_admin_model->get_all_months_list();
		$this->load->view('admin/shopping/all_months_list', $data);		
	}

	public function monthly_data_by_id($id)
	{
		$data = array();
		$data['type'] = $this->session->userdata('type');
		$data['monthly_data'] = $this->Super_admin_model->get_monthly_data($id);
		$this->load->view('admin/shopping/montlhy_data_by_id', $data);
	}


	/**
	 *
	 * To buy list
	 *
	 */

	public function to_buy_list()
	{
		$data = array();
		$data['type'] = $this->session->userdata('type');
		$data['products_to_buy'] = $this->Super_admin_model->products_to_buy();
		$this->load->view('admin/to-buy-list/list', $data);
	}

	public function add_item_on_to_buy_list()
	{
		$data = array();
		$data['item_name'] = $this->input->post('item_name', true);
		$data['entered_by'] = $this->session->userdata('type');

		$this->Super_admin_model->add_item_on_to_buy_list($data);

		$sdata['message'] = "<div class='alert alert-success'>Added successfully!</div>";
		$this->session->set_userdata($sdata);
		redirect('admin/to_buy_list');		
	}

	public function delete_to_buy_item($id)
	{
		$type = $this->session->userdata('type');

		if($type == 1)
		{
			$this->Super_admin_model->delete_item_from_to_buy_list($id);

			$sdata['message'] = "<div class='alert alert-success'>Deleted successfully!</div>";
			$this->session->set_userdata($sdata);
			redirect('admin/to_buy_list');
		}
		else
		{
			$by = $this->Super_admin_model->entered_by($id);
			$entered_by = $by->entered_by;

			if ($entered_by == $type) 
			{
				$this->Super_admin_model->delete_item_from_to_buy_list($id);

				$sdata['message'] = "<div class='alert alert-success'>Deleted successfully!</div>";
				$this->session->set_userdata($sdata);
				redirect('admin/to_buy_list');
			}
			else
			{
				$sdata['message'] = "<div class='alert alert-danger'>You do not have permission to delete this!</div>";
				$this->session->set_userdata($sdata);
				redirect('admin/to_buy_list');				
			}
		}
	}

/**
 *
 * PDF
 *
 */
 
	public function plain_text(){
		$products_to_buy = $this->Super_admin_model->products_to_buy();

		$html = '<h3>To buy list</h3><ul>';

		foreach($products_to_buy as $product_to_buy)
		{
			$html .='<li>'.$product_to_buy->item_name.'</li>';

		}

		$html .= '</ul>';
		echo $html;		
	}
 
 /**
 *
 * Function for filtered report
 *
 */

	protected function filtered_report($table_name, $initialAmountId, $fromDate, $toDate = null)
	{

		$data = array();
		$data['type'] = $this->session->userdata('type');
		
		date_default_timezone_set("Asia/Dhaka");
		
		$id = $initialAmountId; 

		$initialAmountObject = $this->Super_admin_model->initial_amount_query($id);
		$initialAmount = $initialAmountObject->amount;		

		$startingDate =  date_format(date_create($fromDate),"Y-m-d H:i:s");

		$data['fromDate'] = $fromDate;

		if($toDate == null)
		{
			$data['date'] = date("M");

			$data['all_entries'] = $this->Super_admin_model->get_filtered_entries($table_name, $startingDate);
		}
		else
		{
			$data['date'] = date_format(date_create($fromDate), "d M Y" ).' - '.date_format(date_create($toDate), "d M Y" );	
			$endingDate = date_format(date_create($toDate),"Y-m-d 23:59:59");	

			$data['all_entries'] = $this->Super_admin_model->get_filtered_entries($table_name, $startingDate, $endingDate);	

			$data['toDate'] = $toDate;
		}
	

		$debitTillStartingDate = $this->Super_admin_model->total_where_between_debit($table_name, $startingDate);
		$creditTillStartingDate = $this->Super_admin_model->total_where_between_credit($table_name, $startingDate);

		$initialAmountTillStartingDate = $initialAmount + $creditTillStartingDate - $debitTillStartingDate;

		$data['initial_amount'] = $initialAmountTillStartingDate;


		return $data;		
	}

/**
 *
 * Inventory
 *
 */

	public function inventory()
	{
		$data = array();

		$data['type'] = $this->session->userdata('type');

		$this->load->view('admin/item/report', $data);
	}

	public function newItem()
	{
		$data = array();
		$data['type'] = $this->session->userdata('type');
		$this->load->view('admin/item/add_new', $data);
	}

	public function saving_new_item()
	{
		$data = array();
		$data['name'] = $this->input->post('name', true);
		$data['stock'] = $this->input->post('stock', true);
		$data['per_day'] = $this->input->post('per_day', true);

		$this->Super_admin_model->add_inventory_item($data);

		$sdata['message'] = "<div class='alert alert-success'>Added successfully!</div>";
		$this->session->set_userdata($sdata);
		redirect('admin/inventory');			
	}

	public function editItem($id)
	{
		$data = array();
		$data['type'] = $this->session->userdata('type');
		
		$data['item_data'] = $this->Super_admin_model->get_inventory_item_by_id($id);

		$this->load->view('admin/item/edit_item', $data);
	}

	public function save_updated_inventory_item()
	{
		$data = array();
		$id = $this->input->post('id', true);
		$data['name'] = $this->input->post('name', true);
		$data['stock'] = $this->input->post('stock', true);
		$data['per_day'] = $this->input->post('per_day', true);

		$this->Super_admin_model->save_updated_inventory_item($id, $data);

		$sdata['message'] = "<div class='alert alert-success'>Updated successfully!</div>";
		$this->session->set_userdata($sdata);
		redirect('admin/inventory');	
	}

	public function delete_inventory_item($id)
	{
		$this->Super_admin_model->delete_inventory_item($id);
		$sdata['message'] = "<div class='alert alert-success'>Deleted successfully!</div>";
		$this->session->set_userdata($sdata);
		redirect('admin/inventory');		
	}

	public function getInventoryItem()
	{
		$id = $this->input->post('id', true);
		$data = $this->Super_admin_model->get_inventory_item_by_id($id);
		echo json_encode($data);
	}

	// public function testingForm($id)
	// {
	// 	$data = $this->Super_admin_model->get_inventory_item_by_id($id);
	// 	$this->load->view('admin/item/testing', $data);
	// }

	public function addOrTakeFromInventory()
	{
		$id = $this->input->post('id', true);
		$button_action = $this->input->post('button_action', true);
		$addedOrTaken = $this->input->post('addOrTake', true);
		$stock = $this->input->post('stock', true);


		if($button_action == 'add')
		{
			$stock = $stock + $addedOrTaken;
			$message = '<div class="alert alert-success">Added successfully!</div>';
		}
		if($button_action == 'take')
		{
			$stock = $stock - $addedOrTaken;
			$message = '<div class="alert alert-success">Taken successfully!</div>';
		}

		$this->Super_admin_model->updateInventoryStock($id, $stock);

       $output = array(
            'success'   =>  $message
        );

       echo json_encode($output);
	}

	public function ajax_inventory_items()
	{

		$fetch_data = $this->Super_admin_model->make_datatables_for_inventory();  

           $data = array();  

           foreach($fetch_data as $row)  
           {  
                $sub_array = array(); 
                 
                $sub_array[] = '<a href="'.base_url().'admin/editItem/'.$row->id.'">'.$row->name.'</a>';  

                $sub_array[] = $row->stock; 

                $sub_array[] = $row->per_day == 0 ? '---' : $row->per_day; 

                $sub_array[] = $row->per_day == 0 ? '---' : number_format($row->stock / $row->per_day, 1);  

                $sub_array[] = '<a href="#" class="btn btn-default btn-sm add_to_stock" id="'.$row->id.'"><i class="glyphicon glyphicon-plus"></i></a>
                <a href="#" class="btn btn-default btn-sm take_from_stock" id="'.$row->id.'"><i class="glyphicon glyphicon-minus"></i></a>';  
  
                $data[] = $sub_array;                
           }  
           $output = array(  
                "draw"                  =>     intval($_POST["draw"]),  
                "recordsTotal"          =>     $this->Super_admin_model->count_all_inventory_items(),  
                "recordsFiltered"       =>     $this->Super_admin_model->get_filtered_data_for_inventory_items(),  
                "data"                  =>     $data  
           );  
           echo json_encode($output); 
	}











	// public function pdf()
	// {
	// 	$products_to_buy = $this->Super_admin_model->products_to_buy();

	// 	$html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body><h3>To buy list</h3><ul style="font-family: SolaimanLipi, sans-serif;">';

	// 	foreach($products_to_buy as $product_to_buy)
	// 	{
	// 		$html .='<li>'.$product_to_buy->item_name.'</li>';

	// 	}

	// 	$html .= '</ul></body></html>';
	// 	echo $html;
	// 	die();

	// 	$dompdf = new Dompdf();
	// 	// $dompdf->set_option('defaultFont', 'Courier');
	// 	$dompdf->loadHtml($html, 'UTF-8');
	// 	$dompdf->render();
	// 	$dompdf->stream();
	// }

			
}