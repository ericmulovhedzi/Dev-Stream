<?php
require_once('../../inc/connection.php');

$array = array();

if(isset($_GET['_key']) && ((!empty($_GET['_key'])) || ($_GET['_key']>=0)) && isset($_GET['_col']) && ((!empty($_GET['_col'])) || ($_GET['_col']>=0)) && isset($_GET['_value']) && ((!empty($_GET['_value'])) || ($_GET['_value']>=0)))
{
	$rs = $db->Execute("SELECT `id`,`dynamicdefault_value` FROM `pages_fields` WHERE `id`=".$_GET['_key']." AND `isdynamicdefault`=1 AND pub='1' AND del='1' LIMIT 1");
	if(($rs) && ($rs->_numOfRows >= 1))
	{
		if(isset($rs->fields['dynamicdefault_value']) && (!empty($rs->fields['dynamicdefault_value'])))
		{
			$rangeArr = explode(";",$rs->fields['dynamicdefault_value']);
			if(isset($rangeArr) && is_array($rangeArr) && (sizeof($rangeArr) >= 1))
			{
				$foundmatch=false;
				//while(list($k,$v) = each($rangeArr))
				foreach($rangeArr as $k=>$v)
				{
					if(isset($v) && (!empty($v)))
					{
						$v_rangeArr = explode(":",$v);
						if(isset($v_rangeArr) && is_array($v_rangeArr) && (sizeof($v_rangeArr) == 2))
						{//print_r($v_rangeArr);
							if(isset($v_rangeArr[0]) && ((!empty($v_rangeArr[0])) || ($v_rangeArr[0]>=0)))
							{
								if($v_rangeArr[0] == $_GET['_value'])
								{
									$array = array("status"=>1,"desc"=>"","data"=>(isset($v_rangeArr[1])?$v_rangeArr[1]:""));$foundmatch=true;
								}
							}//else{$array = array("status"=>0,"desc"=>"No rule attribute available.","data"=>"");}
						}//else{$array = array("status"=>0,"desc"=>"Incorrect rule specified.","data"=>"");}
					}//else{$array = array("status"=>0,"desc"=>"No rule available.","data"=>"");}
				}
				
				if(!$foundmatch){$array = array("status"=>0,"desc"=>"No mach found","data"=>"");}
			}else{$array = array("status"=>0,"desc"=>"Incorrect default parameters specified","data"=>"");}
		}else{$array = array("status"=>0,"desc"=>"No default parameters available","data"=>"");}
	}else{$array = array("status"=>0,"desc"=>"Field is not available","data"=>"");}
}else{$array = array("status"=>0,"desc"=>"No parameters specified","data"=>"");}
echo json_encode($array);exit;
?>