<?php
//session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Super_admin extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$type = $this->session->userdata('type');
		if ($type == NULL || $type == 0) {
			redirect('accounts', 'refresh');	
		}
	}

	public function index()
	{
		$data['sheets'] = $this->Super_admin_model->get_all_sheets();
		
		$sheets = $data['sheets'];

		$data['details'] = array();

		foreach($sheets as $sheet)
		{
			$d['id'] = $sheet->id;
			$d['name'] = $sheet->name;
			$d['balance'] = $this->get_sheet_balance($sheet->id);
			array_push($data['details'], $d);
		}

		$this->load->view('admin/super_admin_dashboard', $data);	
	}

	protected function get_sheet_balance($sheet_id)
	{
		$totalCredit = $this->Super_admin_model->totalCreditOrDebitBySheetId($sheet_id, 1);
		$totalDebit = $this->Super_admin_model->totalCreditOrDebitBySheetId($sheet_id, 0);
		$balance = $totalCredit - $totalDebit;

		return number_format($balance, 2);
	}

	public function logout(){
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('type');
		$sdata = array();
		$sdata['message'] = '<div class="alert alert-success">You are successfuly logged out!</div>';
		$this->session->set_userdata($sdata);
		redirect('accounts', 'refresh');

	}



/**
 *
 * Sheets
 *
 */

	public function sheets()
	{
		$data['sheets'] = $this->Super_admin_model->get_all_sheets();
		$this->load->view('admin/sheets/sheets', $data);
	}


	public function ajax_sheets()
	{
		$fetch_data = $this->Super_admin_model->make_datatable_for_sheets();  

           $data = array();  

           foreach($fetch_data as $row)  
           {  
                $sub_array = array(); 
                 
                $sub_array[] = $row->name;    

                $sub_array[] = '<a href="#" class="btn btn-default btn-sm edit" id="'.$row->id.'"><i class="glyphicon glyphicon-edit"></i></a>
                <a href="#" class="btn btn-danger btn-sm delete" id="'.$row->id.'"><i class="glyphicon glyphicon-trash"></i></a>';  
  
                $data[] = $sub_array;                
           }  
           $output = array(  
                "draw"                  =>     intval($_POST["draw"]),  
                "recordsTotal"          =>     $this->Super_admin_model->count_all_sheets(),  
                "recordsFiltered"       =>     $this->Super_admin_model->get_filtered_data_for_sheets(),  
                "data"                  =>     $data  
           );  
           echo json_encode($output); 		
	}

	public function add_or_update_sheet()
	{	
		$data['name'] = $this->input->post('name', true);	
		$action = $this->input->post('button_action', true);

		if($action == 'insert')
		{
			$this->Super_admin_model->add_sheet($data);
			$message = '<div class="alert alert-success">Added successfully!</div>';
		}	
		else if($action == 'update')
		{
			$id = $this->input->post('id', true);
			$this->Super_admin_model->update_sheet_by_id($id, $data);
			$message = '<div class="alert alert-success">Updated successfully!</div>';
		}


       $output = array(
            'success'   =>  $message
        );

       echo json_encode($output);
	}

	public function get_sheet_name()
	{
		$id = $this->input->post('id', true);
		$data = $this->Super_admin_model->get_sheet_by_id($id);
		echo json_encode($data);
	}

	public function delete_sheet($id)
	{

		$this->Super_admin_model->delete_sheet_and_its_entries($id);
		$message = '<div class="alert alert-success">Deleted successfully!</div>';
		$output = array(
            'success'   =>  $message
        );

       echo json_encode($output);
	}


/**
 *
 * Amounts
 *
 */

	public function new_entry()
	{
		$sheetID = $this->input->post('sheet_id', true);

		if(!isset($sheetID))
		{
			redirect('super_admin/');
		}

		$data['sheet_id'] = $sheetID;
		$data['sheetName'] = $this->Super_admin_model->get_sheet_name($sheetID);
		$data['sheets'] = $this->Super_admin_model->get_all_sheets();

		$this->load->view('admin/report/add_new', $data);
	}

	public function saving_new_entry()
	{
		$data = array();
		date_default_timezone_set("Asia/Dhaka");
		$date = date("yy-m-d H:i:s");

		$data['sheet_id'] = $this->input->post('sheet_id', true);
		$data['description'] = $this->input->post('description', true);
		$data['amount'] = $this->input->post('amount', true);
		$data['type'] = $this->input->post('type', true);
		$data['date'] = $date;

		$this->Super_admin_model->add_new_entry($data);

		$sdata['message'] = "Added successfully!";
		$this->session->set_userdata($sdata);
		redirect('super_admin/get_amounts/'.$data['sheet_id']);
	}

	public function edit_entry($id = null)
	{
		if(!isset($id))
		{
			redirect('Super_admin/');
		}

		$data['entry'] = $this->Super_admin_model->show_entry_by_id($id);
		$data['sheetName'] = $this->Super_admin_model->get_sheet_name($data['entry']->sheet_id);
		$data['sheets'] = $this->Super_admin_model->get_all_sheets();
		$this->load->view('admin/report/edit', $data);
	}

	public function saving_edited_entry()
	{
		$data = array();

		$id = $this->input->post('id', true);
		$sheetID = $this->input->post('sheet_id', true);

		$data['description'] = $this->input->post('description', true);
		$data['amount'] = $this->input->post('amount', true);
		$data['type'] = $this->input->post('type', true);

		$this->Super_admin_model->save_edited_entry($id, $data);

		$sdata['message'] = "Updated successfully!";
		$this->session->set_userdata($sdata);
		redirect('super_admin/get_amounts/'.$sheetID);		
	}

	public function delete_entry()
	{
		$id = $this->input->post('id', true);
		$sheetID = $this->input->post('sheet_id', true);

		$this->Super_admin_model->delete_entry($id);

		$sdata['message'] = "Deleted successfully!";
		$this->session->set_userdata($sdata);
		redirect('super_admin/get_amounts/'.$sheetID);	
	}

	public function get_amounts($id = null)
	{
		if(!isset($id))
		{
			redirect('Super_admin/');
		}

		$data['sheetID'] = $id;
		$data['sheets'] = $this->Super_admin_model->get_all_sheets();
		$data['sheetName'] = $this->Super_admin_model->get_sheet_name($id);

		date_default_timezone_set("Asia/Dhaka");
		$data['dateForHeading'] = date("M");

		$fromDate = date("yy-m-01");
		$startingDate =  date_format(date_create($fromDate),"Y-m-d H:i:s");

		$data['fromDate'] = $fromDate;

		$data['entries'] = $this->Super_admin_model->get_amounts_by_sheet_id($id, $startingDate);

		$data['initial_amount'] = $this->getInitialAmount($id, $startingDate);

		$this->load->view('admin/report/report', $data);
	}

	public function get_filtered_amounts()
	{
		$sheetID = $this->input->post('sheet_id', true);
		$fromDate = $this->input->post('from_date', true);
		$toDate = $this->input->post('to_date', true);

		if($fromDate == null || $toDate == null)
		{
			redirect('super_admin/get_amounts/'.$sheetID);
		}

		$data['sheetID'] = $sheetID;
		$data['sheets'] = $this->Super_admin_model->get_all_sheets();
		$data['sheetName'] = $this->Super_admin_model->get_sheet_name($sheetID);

		$data['dateForHeading'] = date_format(date_create($fromDate), "d M Y" ).' - '.date_format(date_create($toDate), "d M Y" );

		$startingDate =  date_format(date_create($fromDate),"Y-m-d H:i:s");

		$data['fromDate'] = $fromDate;
		$data['toDate'] = $toDate;

		$endingDate = date_format(date_create($toDate),"Y-m-d 23:59:59");	


		$data['entries'] = $this->Super_admin_model->get_amounts_by_sheet_id($sheetID, $startingDate, $endingDate);

		$data['initial_amount'] = $this->getInitialAmount($sheetID, $startingDate);


		$this->load->view('admin/report/report', $data);
	}

	protected function getInitialAmount($id, $startingDate)
	{
		$debitTillStartingDate = $this->Super_admin_model->total_where_between_debit($id, $startingDate);
		$creditTillStartingDate = $this->Super_admin_model->total_where_between_credit($id, $startingDate);

		$initialAmountTillStartingDate = $creditTillStartingDate - $debitTillStartingDate;

		return $initialAmountTillStartingDate;
	}


/**
 *
 * Excel report
 *
 */
	public function excel_report()
	{
		$sheetID = $this->input->post('sheet_id', true);
		$fromDate = $this->input->post('from_date', true);
		$toDate = $this->input->post('to_date', true);
		$fileNamePrefix = $this->input->post('fileNamePrefix', true);	

		$startingDate =  date_format(date_create($fromDate),"Y-m-d H:i:s");

		if($toDate == null)
		{
			$all_entries = $this->Super_admin_model->get_amounts_by_sheet_id($sheetID, $startingDate);		
		}
		else
		{
			$endingDate = date_format(date_create($toDate),"Y-m-d 23:59:59");
			$all_entries = $this->Super_admin_model->get_amounts_by_sheet_id($sheetID, $startingDate, $endingDate);
		}		

		$initialAmount = $this->getInitialAmount($sheetID, $startingDate);

		$this->generate_excel_file($initialAmount, $all_entries, $fileNamePrefix);
	}

	protected function generate_excel_file($initialAmount, $all_entries, $fileNamePrefix)
	{
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $presentBalance = $initialAmount;
        $totalDebit = 0;
        $totalCredit = 0;

        $sheet->setCellValue('A1', 'Date');
        $sheet->setCellValue('B1', 'Description');
        $sheet->setCellValue('C1', 'Debit');
        $sheet->setCellValue('D1', 'Credit');
        $sheet->setCellValue('E1', 'Balance');

        $rows = 2;
        $sheet->setCellValue('A' . $rows, '...');
        $sheet->setCellValue('B' . $rows, 'Brought forward (B/F)');
        $sheet->setCellValue('C' . $rows, '');
        $sheet->setCellValue('D' . $rows, '');
        $sheet->setCellValue('E' . $rows, $presentBalance);
		$rows++;

        foreach ($all_entries as $v_all_entries){
  
            $date = date_create($v_all_entries->date);
            $formatedDate = date_format($date, "d, M, Y");

            $sheet->setCellValue('A' . $rows, $formatedDate);
            $sheet->setCellValue('B' . $rows, $v_all_entries->description);
            $sheet->setCellValue('C' . $rows, $v_all_entries->type == 0 ? $v_all_entries->amount: '');
            $sheet->setCellValue('D' . $rows, $v_all_entries->type == 1 ? $v_all_entries->amount: '');
 
            if($v_all_entries->type == 0){
            	$totalDebit = $totalDebit + $v_all_entries->amount;
            	$presentBalance = $presentBalance - $v_all_entries->amount;
            }
            else{
            	$totalCredit = $totalCredit + $v_all_entries->amount;
            	$presentBalance = $presentBalance + $v_all_entries->amount;
            }

            $sheet->setCellValue('E' . $rows, $presentBalance);
            $rows++;
        } 

        $sheet->setCellValue('A' . $rows, '');
        $sheet->setCellValue('B' . $rows, '');
        $sheet->setCellValue('C' . $rows, 'Total debit');
        $sheet->setCellValue('D' . $rows, 'Total credit');
        $sheet->setCellValue('E' . $rows, 'Present balance');
		$rows++;

        $sheet->setCellValue('A' . $rows, '');
        $sheet->setCellValue('B' . $rows, '');
        $sheet->setCellValue('C' . $rows, $totalDebit);
        $sheet->setCellValue('D' . $rows, $totalCredit);
        $sheet->setCellValue('E' . $rows, $presentBalance);
		$rows++;

		date_default_timezone_set("Asia/Dhaka");
		$date = date("d M Y");
		$fileName = $fileNamePrefix.$date.'.xlsx';

        $writer = new Xlsx($spreadsheet);

        header("Content-Type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename="' .$fileName. '"');
        $writer->save('php://output');   		
	}
}