<?
require_once('../inc/connection.php');

global $db;
if(isset($_SESSION['accesses']->_login['id']) && (count($_SESSION['accesses']->_login) >= 1) && isset($_REQUEST['fid']) && ($_REQUEST['fid']>=1))
{
	$sql= "";
	if(isset($_REQUEST['ftype']) && ($_REQUEST['ftype']=="size"))
	{
		if(isset($_REQUEST['_h']) && ($_REQUEST['_h']>=1) && isset($_REQUEST['_w']) && ($_REQUEST['_w']>=1) && isset($_REQUEST['_l']) && isset($_REQUEST['_t']))
		{
			$sql = "UPDATE `pages_tabs` SET `width`=".$db->qstr($_REQUEST['_w']).",`height`=".$db->qstr($_REQUEST['_h']).",`left`=".$db->qstr($_REQUEST['_l']).",`top`=".$db->qstr($_REQUEST['_t'])." WHERE `id`='".$_REQUEST['fid']."' LIMIT 1";
		}
	}
	else if(isset($_REQUEST['ftype']) && ($_REQUEST['ftype']=="archive"))
	{
		if(isset($_REQUEST['fname']) && ($_REQUEST['fname']=="color"))
		{$sql = "UPDATE `_mods_archives` SET `bgcolor`=".$db->qstr($_REQUEST['_bgcolor']).",`color`=".$db->qstr($_REQUEST['_color'])." WHERE `mod`='".$_REQUEST['fid']."' AND `occurrence`='".$_REQUEST['_arc']."' LIMIT 1";}
	}
	
	if($db->Execute($sql))
	{
		echo json_encode(array("status"=>1,"desc"=>'',"total"=>'',"data"=>''));exit;
	}
}
?>