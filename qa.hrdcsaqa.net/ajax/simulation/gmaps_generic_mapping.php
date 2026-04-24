<?php
require_once('../../inc/connection.php');

$array = array(array());

$body = "";

//if(isset($_GET['_tbl']) && (!empty($_GET['_tbl'])) && isset($_GET['_fld']) && ((!empty($_GET['_fld'])) || ($_GET['_col']>=0)) && isset($_GET['_col']) && ((!empty($_GET['_col'])) || ($_GET['_col']>=0)) && isset($_GET['_value']) && (!empty($_GET['_value'])))
if(true)
{
	$_SQL_FILTER = "-1";
	$rs = $db->Execute("SELECT 
			    (
				SELECT `B`.`id`
				FROM `localities` AS `B`
				WHERE `B`.device=`A`.device
				ORDER BY `B`.`date` DESC
				LIMIT 1
			    ) AS `nid` 
			     FROM `localities` AS `A` 
			     WHERE 1=1 GROUP BY `A`.device LIMIT 3");
	
	if(($rs) && ($rs->_numOfRows >= 1))
	{
	 while(!$rs->EOF){$_SQL_FILTER .= ",".$rs->fields['nid'];$rs->MoveNext();}   
	}
	$_SQL = "SELECT `A`.id, `A`.gps_a AS `g_lat` , `A`.gps_l AS `g_lon` , `A`.device, `A`.street_address, `A`.city_municipality,`A`.featured_name,`A`.suburb, `A`.street_address, 'Ward' AS `ward`, `A`.date
			     FROM `localities` AS `A` 
			     WHERE `A`.id IN ($_SQL_FILTER) GROUP BY `A`.device ORDER BY `A`.date DESC LIMIT 3";
	
	$rs = $db->Execute($_SQL);
	
	
	if(($rs) && ($rs->_numOfRows >= 1))
	{
		$rs_totals = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
		$rs_totals_master = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
		$rs_abi_regionsArr = array();
		
		$i = 0 ;
		$_rid_prev = 0;
		$regionDisplay = true;
		
		$array = array();
		
		while(!$rs->EOF)
		{
		    if(is_numeric(trim($rs->fields['g_lat'])) && is_numeric(trim($rs->fields['g_lon'])))
		    {
			if($i == 0){$G_LAT = $rs->fields['g_lat'];$G_LON = $rs->fields['g_lon'];}
			    $_id = $rs->fields['id'];
			    
			    //$icon = (isset($rs->fields['ward']) && ($rs->fields['ward']>=1)) ? WWWROOT."images/icons/car-icon30x70-imbobo.png" : WWWROOT."images/icons/car-icon30x70-imbobo-gray.png";
			    //$icon = (isset($rs->fields['ward']) && ($rs->fields['ward']>=1)) ? "images/icons/car-icon30x70-imbobo.png" : "images/icons/car-icon30x70-imbobo-gray.png";
			    $icon = "images/icons/if_map-marker_24.png";
			    
			    $store_format = "";
			    $district = "";
			    $date = $rs->fields['date'];
			    
			   // $body .="var t =  new Object();t.name=\"<div style='width:300px;font-weight:bold;text-decoration:underline;'>".strtoupper("Vehicle: ".preg_replace('~[^A-Za-z1-9-;\'"&<>\/]+~u','',$rs->fields['device']))."</div><span class='save' style='clear:both;'>Street: ".$rs->fields['featured_name']." ".$rs->fields['street_address']."</span> <br /><span class='cancel'>GPS: </span> { ".$rs->fields['g_lat']." : ".$rs->fields['g_lon']."} <br />Date: ".substr($date,0,11)." @ ".substr($date,11,25)."<br /><span style='color:#dfa702;'> Suburb: ".$rs->fields['suburb']."</span> <br /><span class='save'>".$rs->fields['city_municipality']."</span><br /<span class='save'> Ward: ".$rs->fields['ward']."</span><br /><span class='save'> Region:</span>\";t.lat=".$rs->fields['g_lat'].";t.lng=".$rs->fields['g_lon'].";t.icon='".$icon."';a[".$i."]= t;";
			    
			    $name = "<div style='width:300px;font-weight:bold;text-decoration:underline;'>".strtoupper("Vehicle: ".preg_replace('~[^A-Za-z1-9-;\'"&<>\/]+~u','',$rs->fields['device']))."</div><span class='save' style='clear:both;'>Street: ".$rs->fields['featured_name']." ".$rs->fields['street_address']."</span> <br /><span class='cancel'>GPS: </span> { ".$rs->fields['g_lat']." : ".$rs->fields['g_lon']."} <br />Date: ".substr($date,0,11)." @ ".substr($date,11,25)."<br /><span style='color:#dfa702;'> Suburb: ".$rs->fields['suburb']."</span> <br /><span class='save'>".$rs->fields['city_municipality']."</span><br /<span class='save'> Ward: ".$rs->fields['ward']."</span><br /><span class='save'> Region:</span>";
			    $array[$i] = array($rs->fields['g_lat'], $rs->fields['g_lon'],$icon,$name);	
			    $i++;
		    }
		    
		    $rs->MoveNext(); 
		}
	    
		$TOTAL = $i;
	}
}
echo json_encode($array);
?>