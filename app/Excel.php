<?php

require_once 'PHPExcel-develop/Classes/PHPExcel.php';
require_once 'PHPExcel-develop/Classes/PHPExcel/IOFactory.php';

class Excel extends PHPExcel
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	//must be called by $obj = Excel::read($file);
	public function read($inputFileName) {
		echo "creating reader"."\n";
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$reader = PHPExcel_IOFactory::createReader($inputFileType);
		return $reader->load($inputFileName);
	}

	public function write($acctObj, $outputFileName) 
	{
		echo "creating writer"."\n";
		$outputFileType = PHPExcel_IOFactory::identify($outputFileName);
		$writer = PHPExcel_IOFactory::createWriter($acctObj, $outputFileType);
		$writer->save($outputFileName);
		echo "file has been written"."\n";
	}
	
	
}

?>