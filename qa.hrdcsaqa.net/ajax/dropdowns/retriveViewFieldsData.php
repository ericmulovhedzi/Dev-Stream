<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../inc/connection.php');

$array = array();
$_err=1;$msg="";

if((!isset($_GET['_val'])) || (empty($_GET['_val'])))
{
	$_err=0;$msg="Search value is empty.";
}
else if((!isset($_GET['_records'])) || (empty($_GET['_records'])))
{
	$_err=0;$msg="No sources specified.";
}
else if((!isset($_GET['_records_src_types'])) || (empty($_GET['_records_src_types']) && ( $_GET['_records_src_types']!=0) ))
{
	$_err=0;$msg="No source types specified.";
}
else if((!isset($_GET['_records_srch_cols'])) || (empty($_GET['_records_srch_cols']) && ( $_GET['_records_srch_cols']!=0) ))
{
	$_err=0;$msg="No search field specified.";
}

$array = array("status"=>$_err,"desc"=>$msg);

if($_err == 1)
{
	$row = 1; $col = 0;$isRecordFound = false;$new_data = array();
	$dir = CONSUMER_ROOTPATH."SYSTEM_FILES/MANUAL_UPLOADS/MASTER_DATA/";
	
	$_recordsArr = explode(";",$_GET['_records']);
	$_records_srch_colsArr = explode(";",$_GET['_records_srch_cols']);
	$_records_src_typesArr = explode(";",$_GET['_records_src_types']);
	//print_r($_GET);exit;
	$fieldTypesArr = genericItemsArr("pages_fields","",array("id","type"));
	$listsArr = genericItemsArr("lists");
	//print_r($fieldTypesArr);
	//print_r($listsArr);
	//while(list($k,$v) = each($_recordsArr))
	foreach($_recordsArr as $k=>$v)
	{
		if(isset($_records_src_typesArr[$k]) && ($_records_src_typesArr[$k]==2)) // --- source_db (2)
		{
			if(isset($_GET[$v]) && ($_GET[$v] >= 2))
			{
				$_records_srch_colsArr[$k] = is_numeric(trim($_records_srch_colsArr[$k])) ? (int)trim($_records_srch_colsArr[$k]) : trim($_records_srch_colsArr[$k]);
				$_GET['_val'] = is_numeric(trim($_GET['_val'])) ? (int)trim($_GET['_val']) : trim($_GET['_val']);
							
				$_tempFileArr = $_GET[$v];$returncols="";
				//while (list($k_,$v_) = each($_tempFileArr))
				foreach($_tempFileArr as $k_=>$v_)
				{
					$returncols .=",`$v_`";
				}
				global $db;
				$rs = $db->Execute("SELECT `id`$returncols FROM `".$v."` WHERE `".$_records_srch_colsArr[$k]."`='".$_GET['_val']."' AND `status` IN (3) AND pub='1' AND del='1' LIMIT 1");
				if(($rs) && ($rs->_numOfRows >= 1))
				{
					while(!$rs->EOF)
					{
						$_tempFileArr = $_GET[$v];
						//while (list($k_,$v_) = each($_tempFileArr))
						foreach($_tempFileArr as $k_=>$v_) 
						{
							if(isset($fieldTypesArr[$v_]) && ($fieldTypesArr[$v_] == 3)) // --- Field of type select type {Default = select from list}
							{
								$_value = (isset($listsArr[$rs->fields[$v_]]) && (!empty($listsArr[$rs->fields[$v_]]))) ? $listsArr[$rs->fields[$v_]] : 'N/A';
								$new_data[$k_] = trim($_value);
							}
							else
							{
								$new_data[$k_] = trim($rs->fields[$v_]);
							}
						}
						$isRecordFound = true;break 1;
						$rs->MoveNext();
					}
				}
			}
		}
		else if(isset($_records_src_typesArr[$k]) && ($_records_src_typesArr[$k]==1)) // --- source_csv (1)
		{//print_r($v."<br />");print_r($_GET[$v]);
			if(isset($_GET[$v]) && ($_GET[$v] >= 2))
			{
				$_file = $v.".csv";
				if(file_exists($dir.$_file))
				{//echo $dir.$_file."<br />";
					if(($handle = fopen($dir.$_file, "r")) !== FALSE)
					{//echo $dir.$_file."<br />";
						// --- $_records_srch_colsArr[$k] = array_flip(explode(',',$_records_srch_colsArr[$k]));
						// --- print_r($_records_srch_colsArr[$k]);exit;
						while(($data = fgetcsv($handle, 10000, "|")) !== FALSE)
						{
							$data[$_records_srch_colsArr[$k]] = (is_numeric(trim($data[$_records_srch_colsArr[$k]])) ? (int)trim($data[$_records_srch_colsArr[$k]]) : trim($data[$_records_srch_colsArr[$k]]));
							$_GET['_val'] = (is_numeric(trim($_GET['_val'])) ? (int)trim($_GET['_val']) : trim($_GET['_val']));
							//echo trim($_records_srch_colsArr[$k])." : ".trim($data[$_records_srch_colsArr[$k]])." : ".trim($_GET['_val'])."<br />";
							//if(isset($data[0]) && ((int)trim($data[0]) == (int)trim($_GET['_outlet'])))
							if(
								isset($data[$_records_srch_colsArr[$k]]) &&
								(
									(($data[$_records_srch_colsArr[$k]] != 0) && ($data[$_records_srch_colsArr[$k]] == $_GET['_val']))
									||
									(strcmp(strtolower($data[$_records_srch_colsArr[$k]]),strtolower($_GET['_val']))==0)
								)
							  )
							{
								//echo strtolower($data[$_records_srch_colsArr[$k]])."----".strtolower($_GET['_val']);
								$_tempFileArr = $_GET[$v];
								//while (list($k_,$v_) = each($_tempFileArr))
								foreach($_tempFileArr as $k_=>$v_) 
								{
									$new_data[$k_] = trim($data[$v_]);
								}
								
								$isRecordFound = true;break 1;
							}
						}
						if(!$isRecordFound)$array = array("status"=>0,"desc"=>"No record was found..","_rs"=>$_GET['_val'],"data"=>".");
						fclose($handle);
					}
					else{$array = array("status"=>0,"desc"=>"Could not open file: ".$_file);}
				}
				else{$array = array("status"=>0,"desc"=>"File does not exists: ".$_file);}
			}
			else{$array = array("status"=>0,"desc"=>"No fields specified.");}
		}
	}
	
	if($isRecordFound)$array = array("status"=>1,"desc"=>"Successful..","_rs"=>$_GET['_val'],"data"=>$new_data);
	//else $array = array("status"=>0,"desc"=>"No record was found..","outlet"=>$_GET['_outlet'],"data"=>".");
}

echo json_encode($array);
?>