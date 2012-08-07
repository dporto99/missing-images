<?php
const STGR_XML = "../stgr2-excursion.xml";
const PROD_XML = "../mia-prodd-excursion.xml";
const EXPORT_CSV = "../excursions.csv";
const EXPORT_HTML = "../bge-img-diff-prod-stg.htm";

require_once ('Implementation.php');
session_start();
$_SESSION['data'];

$task = '';
if ( isset($_POST['task'])){
	$task = $_POST['task'];
}

if (empty($_SESSION['data'])){
	$data = new Implementation();
	$_SESSION['data'] = $data->compare_data(STGR_XML,PROD_XML);
}
switch($task){
	case "load_excursions":
		if(count($_SESSION['data']) == 0 ){
			echo '({"total":"0", "results":""})';
		}
		else{
			$jsonresult = json_encode($_SESSION['data']);
			echo '({"total":"'.count($_SESSION['data']).'","results":'.$jsonresult.'})';
		}
	break;

	case "export_to_csv":
		if(count($_SESSION['data']) == 0){
			echo '0';
		}
		else {
			Implementation::export_to_csv($_SESSION['data'],EXPORT_CSV);
			echo '1';
		}
		break;

	case "export_to_html":
		if(count($_SESSION['data']) == 0){
			echo '0';
		}
		else {
			Implementation::export_to_html($_SESSION['data'],EXPORT_HTML);
			echo '1';
		}
		break;
	
	default:
		echo "{failure:false}";
	break;
}


?>