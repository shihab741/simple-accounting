<?php
	class Super_admin_model extends CI_Model
	{

/**
 *
 * Sheet
 *
 */


		public function get_all_sheets()
		{
			return $this->db->select('*')->from('sheets')->order_by('name', 'ASC')->get()->result();
		}

		public function add_sheet($data)
		{
			$this->db->insert('sheets', $data);
		}

		public function get_sheet_by_id($id)
		{
			return $this->db->select('*')->from('sheets')->where('id', $id)->get()->row();				
		}

		public function update_sheet_by_id($id, $data)
		{
			$this->db->where('id', $id)->update('sheets', $data);
		}

		public function delete_sheet_and_its_entries($id)
		{
			$this->db->where('id', $id)->delete('sheets');				
			$this->db->where_in('sheet_id', $id)->delete('amounts');				
		}

		public function make_ajax_query_for_sheets()
		{
	      	$order_column = array("name", "stock", "per_day", null, null); 

	           $this->db->select('*');  
	           $this->db->from('sheets');  
	           if(isset($_POST["search"]["value"]))  
	           {  
	                $this->db->like("name", $_POST["search"]["value"]);  
	           }  
	           if(isset($_POST["order"]))  
	           {  
	                $this->db->order_by($order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
	           }  
	           else  
	           {  
	                $this->db->order_by('name', 'ASC');  
	           } 			
		}

		public function make_datatable_for_sheets()
		{
           $this->make_ajax_query_for_sheets();  
           if($_POST["length"] != -1)  
           {  
                $this->db->limit($_POST['length'], $_POST['start']);  
           }  
           $query = $this->db->get();  
           return $query->result();  			
		}

      function get_filtered_data_for_sheets()
      {  
           $this->make_ajax_query_for_sheets();  
           $query = $this->db->get();  
           return $query->num_rows();  
      }       

      function count_all_sheets()  
      {  
           $this->db->select("*");  
           $this->db->from('sheets');  
           return $this->db->count_all_results();  
      } 

/**
 *
 * Amount
 *
 */

		public function get_amounts_by_sheet_id($id, $startingDate, $endingDate = null)
		{		
			$this->db->select('*')->from('amounts')->where('sheet_id', $id)->order_by('date', 'ASC');

			$this->db->where('date >=' , $startingDate);
			
			if($endingDate != null)
			{
				$this->db->where('date <=' , $endingDate);			
			}

			return $this->db->get()->result();
		}

		public function get_sheet_name($id)
		{
			return $this->db->select('*')->from('sheets')->where('id', $id)->get()->row();
		}


/**
 *
 * Where between total debit and credit
 *
 */
		public function total_where_between_debit($id, $startingDate)
		{
			$this->db->select_sum('amount');
			$this->db->from('amounts');
			$this->db->where('type', 0);
			$this->db->where('sheet_id', $id);
			$this->db->where('date <' , $startingDate);
			$query_result = $this->db->get();
			$result = $query_result->row();
			return $result->amount;				
		}

		public function total_where_between_credit($id, $startingDate)
		{
			$this->db->select_sum('amount');
			$this->db->from('amounts');
			$this->db->where('type', 1);
			$this->db->where('sheet_id', $id);
			$this->db->where('date <' , $startingDate);
			$query_result = $this->db->get();
			$result = $query_result->row();
			return $result->amount;				
		}

		public function add_new_entry($data)
		{
			$this->db->insert('amounts', $data);
		}

		public function show_entry_by_id($id)
		{
			return $this->db->select('*')->from('amounts')->where('id', $id)->get()->row();	
		}

		public function save_edited_entry($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('amounts', $data);				
		}

		public function delete_entry($id)
		{
			$this->db->where('id', $id);
			$this->db->delete('amounts');			
		}

		public function totalCreditOrDebitBySheetId($sheet_id, $type)
		{
			$result = $this->db->select_sum('amount')->from('amounts')
			                  ->where('type', $type)
			                  ->where('sheet_id', $sheet_id)
			                  ->get()
			                  ->row();
			return $result->amount;	
		}
}