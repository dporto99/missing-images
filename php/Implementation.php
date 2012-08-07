<?php

class Implementation {
	function __construct() {}

	private function load_from_xml($path) {
	  $xml = simplexml_load_file($path);
		$result = array();
		foreach($xml->children() as $record){
			$item = array();
			foreach($record->children() as $prop){
				foreach ($prop->children() as $pval){
					$item[(string)$prop->attributes()] = (string)$pval;
				}
			}
			if ($item)
			$result[] = $item;
		}
		return $result;
	}

	private function bge_images() {}

	private function brochure_images() {}

	private function compare($prod, $stgr){
		$fields = array ('small_image', 'large_images', 'extra_large_image', 'excursion_image');
		foreach ($prod as $in_prod){
			$match = true;
			foreach ($stgr as $in_stgr){
				if ($in_prod['code'] == $in_stgr['code']){
					if (serialize($in_prod) !== serialize($in_stgr)){
						foreach ($fields as $f){
							if ($in_prod[$f]	!= $in_stgr[$f]){
								$in_prod['stg_'.$f] = $in_stgr[$f];
								$match = false;
							}
						}
					}
					break;
				}
			}
			if (!$match) $result[] = $in_prod;
		}

		return $this->sort($result);
	}

	private function sort($data) {
		foreach($data as $key=>$value) {
			$code[] = $value['code'];
		}

		array_multisort($code, SORT_STRING, $data);
		return $data;
	}

	public static function export_to_csv($data, $path) {
		$csvfile = fopen($path, 'w+');
		$header = "Code,Type,Small Image,Large Image,Extra Large Image,Staging Small Image,Staging Large Image,Staging Extra Large Image\n\n";
		fputs($csvfile, $header);
		foreach ($data as $row) {
			$line = implode(",", $row) . "\n";
			fputs($csvfile, $line);
		}
		fclose($csvfile);
	}

	public static function export_to_html($data, $path) {
		$htmlfile = fopen($path, 'w+');
		$header = <<<HEADER
<html>
<head>
  <style>
    td, th {
      padding:3px;
      font-family:Arial, sans-serif;
      border:solid 1;
      text-align: center;
      vertical-align: center; }
    .code, .type { width:100px; background-color:#E9E9BD; }
    img { width:150px; border: 1px solid black;}
    .prod { background-color:#BDBDBD; width: 200px; }
    .stg { background-color:#F5EDED; width: 200px; }
    .code td { font-size:16px; }
    p {width: 200px;word-wrap:break-word; font-size: 12px;}
  </style>
</head>
<body>
<table align="center" border=0 cellpadding=0 cellspacing=0 style="border-collapse:collapse;table-layout:fixed" >
  <col class="code">
  <col class="type">
  <col class="prod">
  <col class="stg">
  <col class="prod">
  <col class="stg">
  <col class="prod">
  <col class="stg">
  <thead height=12>
    <th>Code</th>
    <th>Type</th>
    <th>Prod Small Image</th>
		<th>Staging Small Image</th>
		<th>Prod Large Image</th>
    <th>Staging Large Image</th>
		<th>Prod Excursion Image</th>
		<th>Staging Excursion Image</th>
</thead>
HEADER;

		fputs($htmlfile, $header);
		$fields = array (
			'small_image' 				=> 'mia-www1.prodd',
			'stg_small_image' 		=> 'stgr2.nclmiami',
			'large_images' 				=> 'mia-www1.prodd',
			'stg_large_images' 		=> 'stgr2.nclmiami',
			'excursion_image' 		=> 'mia-www1.prodd',
			'stg_excursion_image' => 'stgr2.nclmiami',
		);
		foreach ($data as $row) {
			$line = '<tr height=12><td height=12 >%s</td><td >%s</td>';
			foreach( $fields as $key => $value){
				$record = ($row[$key])
					?	'<td ><p>%s</p><a target="_blank" href="http://%s.ncl.com/%s"><img src="http://%s.ncl.com/%s"></a></td>'
					: '<td ><br/></td>';
				$line .= sprintf($record,$row[$key],$value,$row[$key],$value,$row[$key]);
			}
			fputs($htmlfile, sprintf($line,$row['code'],$row['type']). '</tr>');
		}
		fputs($htmlfile, '</table></body></html>');
		fclose($htmlfile);
	}

	public function compare_data($stgr_xml, $prod_xml) {
		$stgr = $this->load_from_xml($stgr_xml);
		$prod = $this->load_from_xml($prod_xml);
		return $this->compare($prod, $stgr);
	}
}

//const STGR_XML = "../stgr2-excursion.xml";
//const PROD_XML = "../mia-prodd-excursion.xml";
//const EXPORT_HTML = "../bge-img-diff-prod-stg.htm";
//$test = new Implementation();
//$data = $test->compare_data(STGR_XML, PROD_XML);
//Implementation::export_to_html($data,EXPORT_HTML);
