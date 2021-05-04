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