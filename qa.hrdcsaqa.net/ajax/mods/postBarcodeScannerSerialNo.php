<?
require_once('../../inc/connection.php');
require_once(CONSUMER_ROOTPATH.'inc/validator_.php');

/*function autoBuildRefNo($p)
{
	global $db;
	$rs =$db->Execute("SELECT `id`,`name`,`parent` FROM `pages` WHERE id='$p' AND `parent`>0 LIMIT 1");
	if(isset($rs) && ($rs->numRows() >= 1))
	{
		if(isset($rs->fields['parent']) && ($rs->fields['parent']==1)) return $rs->fields['id'];
	        else return autoBuildRefNo($rs->fields['parent']).'-'.$rs->fields['id'];
	}
	return;
}*/

//if(isset($_SESSION['accesses']->_login['id']) && (count($_SESSION['accesses']->_login) >= 1))
if(true)
{
	$array=array("status"=>0,"desc"=>"System Error..","data"=>"0");$arr=array();
	
	if( (isset($_GET['barcode']) && (!empty($_GET['barcode']))) && (isset($_GET['scan_mode']) && (!empty($_GET['scan_mode']))) )
	{
		//if(is_numeric($_GET['barcode']) == 1)
		if(true)
		{
			$array = array("status"=>0,"desc"=>"System Error...","data"=>0,"data_v"=>array(0,0,0));$_countArr = array();
			
			$_MOD = (isset($_GET['_mod']) && ($_GET['_mod']>=1)) ? $_GET['_mod'] : 0;
			$_MOD_INV = (isset($_GET['_mod_inv']) && ($_GET['_mod_inv']>=1)) ? $_GET['_mod_inv'] : 0;
			$_PO = (isset($_GET['_id']) && (!empty($_GET['_id']))) ? $_GET['_id'] : 0;
			$_BATCH = (isset($_GET['_batch']) && (!empty($_GET['_batch']))) ? $_GET['_batch'] : 0;
			$_SKU = (isset($_GET['sku']) && (!empty($_GET['sku']))) ? $_GET['sku'] : "-1";
			$_BARCODE = (isset($_GET['barcode']) && (!empty($_GET['barcode']))) ? $_GET['barcode'] : "-1";
			$_BIN = (isset($_GET['_bin']) && (!empty($_GET['_bin']))) ? $_GET['_bin'] : 0;
			$_ID = (isset($_GET['_id']) && ($_GET['_id']>=1)) ? $_GET['_id'] : 1;
			$_QTY = (isset($_GET['_qty']) && ($_GET['_qty']>=1) && is_numeric($_GET['_qty'])) ? $_GET['_qty'] : 1;
			
			
			$_SQL_INVENTORY_SELECT = $_SQL_INVENTORY_WHERE = $_SQL_INVENTORY_SET = "";
			$_IVENTORY_MODULE = $_MOD_INV;
			
			$rs__ = $db->Execute("SELECT
						`A`.id,`A`.name,
						`A`.`is_barcode`,`A`.`is_barcode_qty`,`A`.`is_barcode_cost`,`A`.`is_item`,`A`.`is_desc`,`A`.`is_bin`,`A`.`is_category`,
						`A`.`has_barcode`,`A`.`has_barcode_qty`,`A`.`has_barcode_price`,`A`.`has_item`,`A`.`has_desc`,
						`D`.is_inventory_linked
						FROM `pages_fields` AS `A`
						LEFT JOIN `pages_tabs` AS `B` ON `B`.id=`A`.tab LEFT JOIN `pages_sections` AS `C` ON `C`.id=`B`.section LEFT JOIN `pages` AS `D` ON `D`.id=`C`.page
						WHERE `C`.page=".$_MOD_INV." AND `D`.`is_inventory_barcode`='1' AND `A`.`pub`='1' AND `A`.`del`='1' AND `B`.`pub`='1' AND `B`.`del`='1' AND `C`.`pub`='1' AND `C`.`del`='1' ORDER BY `A`.id ASC");
				
			if(($rs__) && ($rs__->numRows() >= 1))
			{
				//echo $_IVENTORY_MODULE = $rs__->fields['is_inventory_linked'];
				
				while(!$rs__->EOF)
				{
					if($rs__->fields['is_barcode'] == 1){$_SQL_INVENTORY_SELECT .= ",`A`.`".$rs__->fields['id']."` AS `_barcode`";$_SQL_INVENTORY_WHERE = "`A`.`".$rs__->fields['id']."`='".$_SKU."'";}
					else if($rs__->fields['is_barcode_qty'] == 1){$_SQL_INVENTORY_SELECT .= ",`A`.`".$rs__->fields['id']."` AS `_qty`";$_SQL_INVENTORY_SET = "`_mod_$_IVENTORY_MODULE`.`".$rs__->fields['id']."`=`_mod_$_IVENTORY_MODULE`.`".$rs__->fields['id']."`+".$_QTY;}
					else if($rs__->fields['is_item'] == 1){$_SQL_INVENTORY_SELECT .= ",`A`.`".$rs__->fields['id']."` AS `_item`";}
					else if($rs__->fields['is_desc'] == 1){$_SQL_INVENTORY_SELECT .= ",`A`.`".$rs__->fields['id']."` AS `_desc`";}
					else if($rs__->fields['is_bin'] == 1){$_SQL_INVENTORY_SELECT .= ",`A`.`".$rs__->fields['id']."` AS `_bin`";}
					
					$rs__->MoveNext();	
				}
			}
			//echo "SELECT `A`.`id`,`A`.`code`".$_SQL_INVENTORY_SELECT." FROM `_mod_$_IVENTORY_MODULE` AS `A` WHERE ".$_SQL_INVENTORY_WHERE." AND `A`.status<>2 LIMIT 1";
			$rs_w =$db->Execute("SELECT `A`.`id`,`A`.`code`".$_SQL_INVENTORY_SELECT." FROM `_mod_$_IVENTORY_MODULE` AS `A` WHERE ".$_SQL_INVENTORY_WHERE." AND `A`.status<>2 LIMIT 1");
			if(($rs_w) && ($rs_w->numRows() >= 1))
			{//echo "INSERT INTO `_mod_".$_MOD."_grv_product_life_cycle` (`user`,`po_id`,`sku_id`,`serial_no`,`batch_no`,`bin_location`,`d_received`,`date_update`,`date`) VALUES (".$_SESSION['accesses']->_login['id'].",".$db->qstr($_PO).",".$rs_w->fields['id'].",".$db->qstr($_BARCODE).",".$db->qstr($_BATCH).",".$db->qstr($_BIN).",".$db->qstr(NOW()).",".$db->qstr(NOW()).",".$db->qstr(NOW()).");";
				if($db->Execute("INSERT INTO `_mod_".$_MOD."_grv_product_life_cycle` (`user`,`po_id`,`sku_id`,`serial_no`,`batch_no`,`bin_location`,`d_received`,`date_update`,`date`) VALUES (".$_SESSION['accesses']->_login['id'].",".$db->qstr($_PO).",".$rs_w->fields['id'].",".$db->qstr($_BARCODE).",".$db->qstr($_BATCH).",".$db->qstr($_BIN).",".$db->qstr(NOW()).",".$db->qstr(NOW()).",".$db->qstr(NOW()).");"))
				{//echo "UPDATE `_mod_$_IVENTORY_MODULE` SET ".$_SQL_INVENTORY_SET.",`progression_count`=".$_QTY.",`progression`=1,`date_update`=".$db->qstr(NOW())." WHERE `_mod_$_IVENTORY_MODULE`.`id`=".$rs_w->fields['id']." LIMIT 1;";
					@$db->Execute("UPDATE `_mod_$_IVENTORY_MODULE` SET ".$_SQL_INVENTORY_SET.",`progression_count`=".$_QTY.",`progression`=1,`date_update`=".$db->qstr(NOW())." WHERE `_mod_$_IVENTORY_MODULE`.`id`=".$rs_w->fields['id']." LIMIT 1;");
					if($db->affected_rows() != 0)
					{
						$array = array("status"=>1,"desc"=>"Stock '".$rs_w->fields['_item']."' with barcode '".$rs_w->fields['_barcode']."' successully listed into inventory of <strong>".($rs_w->fields['_qty']+$_QTY)."</strong> items.","data"=>($rs_w->fields['_qty']+$_QTY));
					}
					else{$array = array("status"=>0,"desc"=>"Failed to list stock '".$rs_w->fields['_item']."' with SKU '".$rs_w->fields['_barcode']."' for barcode '".$_BARCODE."'.","data"=>array($arr));}
					
					$__WHERE_FILTER = "";
				}else{$arr=array();$array = array("data"=>"0","status"=>0,"desc"=>"Error '".$db->errorMsg()."'.");}
			}
			else{$arr=array();$array = array("data"=>"0","status"=>0,"desc"=>"Item with SKU '".$_SKU."' does not exisit.");}
		}
		else{$array = array("data"=>"0","status"=>0,"desc"=>"Error occured, barcode number is not numeric.");}
	}
	else{$array = array("data"=>"0","status"=>0,"desc"=>"Error occured, please specify barcode number.");}
	
	echo json_encode($array);
}
else {echo json_encode(array("data"=>"0","status"=>0,"desc"=>"Error occured, please specify barcode number."));exit;}

?>