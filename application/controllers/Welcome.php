<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	
	function __construct() {
        parent::__construct();
    }
	
	//primero tenemos que configurar en config/config.php      $config['composer_autoload'] = 'vendor/autoload.php';
	public function index(){
		$this->load->library('excel');
		$out = [];
		if(!empty($_FILES)){
			$column_name = ['name','departament'];//Para codeigniter definimos el nombre de la columna de la tabla que guardaremos el valor
			$num = 2;//Definimos la cantidad de columnas a obtener del excel
			$file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			if(isset($_FILES['excel']['name']) && in_array($_FILES['excel']['type'], $file_mimes)) {
				$sheetData = $this->excel->load_fsc($_FILES['excel']['tmp_name']);
				if(count($sheetData) > 0){
					$num--;//decrementamos porque la clave de un array comienza en 0, 1, 2, 3, si se desea hasta la columna 2 solo necesitamos el 0, 1
					foreach($sheetData as $row){
						if(count($row) > 0){
							$arr_row = [];
							$iter = 0;
							$is_null = 0;
							foreach($row as $key => $value){
								if($iter > $num){
									break;
								}
								if(empty($value)){
									$is_null++;
								}
								$arr_row[$column_name[$iter++]] = $value;
							}
							if($is_null < $num){
								array_push($out,$arr_row);
							}
						}
					}
				}
				
				var_dump($out);exit();
			}
			//SI DESEAMOS GUARDAR EL EXCEL
			/*$this->load->library('upload');
			if(!empty($_FILES['excel']['name'])) {
				$upload_path = 'uploads/';
				if (!is_dir($upload_path)) {
					mkdir($upload_path, 0777, true);
				}
				$config['upload_path'] = $upload_path;
				$config['allowed_types'] = '*';
				$config['max_size']	= '10240';
				$config['overwrite'] = TRUE;
				$config['file_name'] = uniqid();
				$this->upload->initialize($config);
				if (!$this->upload->do_upload('excel')) {
					$error_foto = $this->upload->display_errors();
				} else {
					$data = $this->upload->data('excel');
					//$file_name = $this->upload->data('file_name');
					//$file_path = $this->upload->data('file_path');
					//$inputFileName = $file_path . $file_name;
				}
			}*/
		}
		
		$this->load->view('form');
	}
}
