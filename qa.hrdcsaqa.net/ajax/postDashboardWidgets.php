<?
require_once('../inc/connection.php');

global $db;
if(isset($_SESSION['accesses']->_login['id']) && (count($_SESSION['accesses']->_login) >= 1) && isset($_REQUEST['fid']) && ($_REQUEST['fid']>=1) && isset($_REQUEST['_h']) && ($_REQUEST['_h']>=1) && isset($_REQUEST['_w']) && ($_REQUEST['_w']>=1) && isset($_REQUEST['_l']) && isset($_REQUEST['_t']))
{
	$isInsert = false;$sql="";
	$rs_check =$db->Execute("SELECT * FROM `settings_dashboard_widgets` WHERE `user`='".$_SESSION['accesses']->_login['id']."' AND `stage`=".$db->qstr($_REQUEST['fid'])." AND `pub`=1 AND `del`=1 LIMIT 1");
	if($rs_check && ($rs_check->numRows() >= 1))
	{
		/*if(isset($_REQUEST['_d']) && ($_REQUEST['_d']==1)) // --- Delete Workflow, Also delete sub or actual workflow item
		{
			$db->Execute("UPDATE `".$jointsTableArr[0]."` SET `del`=".$db->qstr(0)." WHERE `".$jointsTableArr[1]."`=".$db->qstr($_REQUEST['_pg'])." AND `".$jointsTableArr[2]."`=".$db->qstr($_REQUEST['_val'])." AND `del`=1 LIMIT 1;");
		}*/
		if(isset($_REQUEST['ftype']) && ($_REQUEST['ftype']=="filter"))
		{
			$sql = "UPDATE `settings_dashboard_widgets` SET `type`=".$db->qstr($_REQUEST['_type'])
				.",`_groupby`=".$db->qstr((isset($_REQUEST['_groupby']) && (!empty($_REQUEST['_groupby']))) ? $_REQUEST['_groupby']:"")
				.",`_groupbyval`=".$db->qstr((isset($_REQUEST['_groupbyval']) && ($_REQUEST['_groupbyval']>=1)) ? $_REQUEST['_groupbyval']:0)
				.",`_filterby`=".$db->qstr((isset($_REQUEST['_filterby']) && (!empty($_REQUEST['_filterby']))) ? $_REQUEST['_filterby']:"")
				.",`_filterbyval`=".$db->qstr((isset($_REQUEST['_filterbyval']) && ($_REQUEST['_filterbyval']>=1)) ? $_REQUEST['_filterbyval']:0)
				.",`_orderby`=".$db->qstr((isset($_REQUEST['_orderby']) && (!empty($_REQUEST['_orderby']))) ? $_REQUEST['_orderby']:"")
				.",`_orderbyasc`=".$db->qstr((isset($_REQUEST['_orderbyasc']) && (!empty($_REQUEST['_orderbyasc']))) ? $_REQUEST['_orderbyasc']:"")
				.",`_limit`=".$db->qstr((isset($_REQUEST['_limit']) && ($_REQUEST['_limit']>=1)) ? $_REQUEST['_limit']:0)
				.",`date_update`=".$db->qstr(NOW())." WHERE `user`='".$_SESSION['accesses']->_login['id']."' AND `stage`='".$_REQUEST['fid']."' LIMIT 1";
		}
		else
		{
			$sql = "UPDATE `settings_dashboard_widgets` SET `width`=".$db->qstr($_REQUEST['_w']).",`height`=".$db->qstr($_REQUEST['_h']).",`left`=".$db->qstr($_REQUEST['_l']).",`top`=".$db->qstr($_REQUEST['_t']).",`date_update`=".$db->qstr(NOW())." WHERE `user`='".$_SESSION['accesses']->_login['id']."' AND `stage`='".$_REQUEST['fid']."' LIMIT 1";
		}
	}
	else
	{
		if(isset($_REQUEST['ftype']) && ($_REQUEST['ftype']=="filter"))
		{
			$sql = "INSERT INTO `settings_dashboard_widgets`
					(`user`,`stage`,`type`,`_groupby`,`_groupbyval`,`_filterby`,`_filterbyval`,`_orderby`,`_orderbyasc`,`_limit`,`date`)
					VALUES
					(".$db->qstr($_SESSION['accesses']->_login['id']).",".$db->qstr($_REQUEST['fid']).",".$db->qstr($_REQUEST['_type']).","
					.$db->qstr((isset($_REQUEST['_groupby']) && (!empty($_REQUEST['_groupby']))) ? $_REQUEST['_groupby']:"").","
					.$db->qstr((isset($_REQUEST['_groupbyval']) && ($_REQUEST['_groupbyval']>=1)) ? $_REQUEST['_groupbyval']:0).","
					.$db->qstr((isset($_REQUEST['_filterby']) && (!empty($_REQUEST['_filterby']))) ? $_REQUEST['_filterby']:"").","
					.$db->qstr((isset($_REQUEST['_filterbyval']) && ($_REQUEST['_filterbyval']>=1)) ? $_REQUEST['_filterbyval']:0).","
					.$db->qstr((isset($_REQUEST['_orderby']) && (!empty($_REQUEST['_orderby']))) ? $_REQUEST['_orderby']:"").","
					.$db->qstr((isset($_REQUEST['_orderbyasc']) && (!empty($_REQUEST['_orderbyasc']))) ? $_REQUEST['_orderbyasc']:"").","
					.$db->qstr((isset($_REQUEST['_limit']) && ($_REQUEST['_limit']>=1)) ? $_REQUEST['_limit']:0).","
					.$db->qstr(NOW()).")";
		}
		else
		{
			$sql = "INSERT INTO `settings_dashboard_widgets`
					(`user`,`stage`,`width`,`height`,`left`,`top`,`date`)
					VALUES
					(".$db->qstr($_SESSION['accesses']->_login['id']).",".$db->qstr($_REQUEST['fid']).",".$db->qstr($_REQUEST['_w']).",".$db->qstr($_REQUEST['_h']).",".$db->qstr($_REQUEST['_l']).",".$db->qstr($_REQUEST['_t']).",".$db->qstr(NOW()).")";
		}
		$isInsert = true;
	}
	
	if($db->Execute($sql))
	{
		$_id = $db->Insert_ID();
		if($isInsert){$db->Execute("UPDATE `settings_dashboard_widgets` SET `rank`=".$db->qstr($_id).",`date_update`=".$db->qstr(NOW())." WHERE id='".$_id."' LIMIT 1");}
		//echo "---".$_REQUEST['_type'].":".$_REQUEST['fid'];
		$_REQUEST['_groupby'] = (isset($_REQUEST['_groupby']) && (!empty($_REQUEST['_groupby']))) ? $_REQUEST['_groupby']:"";
		$_REQUEST['_groupbyval'] = (isset($_REQUEST['_groupbyval']) && ($_REQUEST['_groupbyval']>=1)) ? $_REQUEST['_groupbyval']:0;
		$_REQUEST['_filterby'] = (isset($_REQUEST['_filterby']) && (!empty($_REQUEST['_filterby']))) ? $_REQUEST['_filterby']:"";
		$_REQUEST['_filterbyval'] = (isset($_REQUEST['_filterbyval']) && ($_REQUEST['_filterbyval']>=1)) ? $_REQUEST['_filterbyval']:0;
		$_REQUEST['_orderby'] = (isset($_REQUEST['_orderby']) && (!empty($_REQUEST['_orderby']))) ? $_REQUEST['_orderby']:"";
		$_REQUEST['_orderbyasc'] = (isset($_REQUEST['_orderbyasc']) && (!empty($_REQUEST['_orderbyasc']))) ? $_REQUEST['_orderbyasc']:"";
		$_REQUEST['_limit'] = (isset($_REQUEST['_limit']) && ($_REQUEST['_limit']>=1)) ? $_REQUEST['_limit']:0;
		
		if(isset($_REQUEST['ftype']) && ($_REQUEST['ftype']=="size"))
		{
			if($rs_check && ($rs_check->numRows() >= 1))
			{
				list($action,$desc,$view_total,$view_body) = drawWidgets($_REQUEST['fid'],$rs_check->fields['type'],$rs_check->fields['_groupby'],$rs_check->fields['_groupbyval'],$rs_check->fields['_filterby'],$rs_check->fields['_filterbyval'],$rs_check->fields['_orderby'],$rs_check->fields['_orderbyasc'],$rs_check->fields['_limit']);
			}
		}
		else{list($action,$desc,$view_total,$view_body) = drawWidgets($_REQUEST['fid'],$_REQUEST['_type'],$_REQUEST['_groupby'],$_REQUEST['_groupbyval'],$_REQUEST['_filterby'],$_REQUEST['_filterbyval'],$_REQUEST['_orderby'],$_REQUEST['_orderbyasc'],$_REQUEST['_limit']);}
		echo json_encode(array("status"=>$action,"desc"=>$desc,"total"=>$view_total,"data"=>$view_body));exit;
	}
}


?>