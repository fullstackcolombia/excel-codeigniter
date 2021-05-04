# excel-codeigniter
Ejemplo de importar excel en codeigniter v3.x
Es una simple forma de importar los datos de un excel en un proyecto de codeigniter usando la libreria Phpspreadsheet

---------------------------------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------------------------------

El primer paso es en el explorador de carpetas ubicarse en la carpeta del proyecto de codeigniter

---------------------------------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------------------------------

Seguidamente abrir el CMD y ejecutar el comando:
composer require phpoffice/phpspreadsheet

---------------------------------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------------------------------

El la carpeta application/libraries creamos un archivo Excel.php

<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\IOFactory;

class Excel {

    protected $CI;

    public function __construct(){
        $this->CI =& get_instance();
    }

	public function load_fsc($t){
		$spreadsheet = IOFactory::load($t);
		$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
		return $sheetData;
	}
}
?>

---------------------------------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------------------------------

En el archivo config/config.php cambiamos el valor de la variable composer_autoload
$config['composer_autoload'] = 'vendor/autoload.php';

---------------------------------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------------------------------

En el controlador solo debemos importar la libreria
$this->load->library('excel');
Obtenemos los datos del excel
$sheetData = $this->excel->load_fsc($_FILES['excel']['tmp_name']);

---------------------------------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------------------------------

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
							foreach($row as $key => $value){
								if($iter > $num){
									break;
								}
								$arr_row[$column_name[$iter++]] = $value;
							}
							array_push($out,$arr_row);
						}
					}
				}
				
				var_dump($out);exit();
			}
		}
		
		$this->load->view('form', $data);
	}
