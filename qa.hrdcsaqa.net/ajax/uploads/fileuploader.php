<?php
// Ignore user aborts and allow the script
// to run forever
ignore_user_abort(true);
set_time_limit(0);
ini_set('post_max_size','100M');
ini_set('upload_max_filesize','100M');

require_once('../../inc/connection.php');

$data = array();$_id=$_GET['isnew'];$code="";

if(isset($_GET['_p']) && ($_GET['_p']>=1))
{
    if(!(isset($_GET['isnew']) && ($_GET['isnew']>=1)))
    {
	if($db->Execute("INSERT INTO `_mod_".$_GET['_p']."_draft` (`user`,`date`,`date_update`$_insertFields) VALUES (".$db->qstr($_SESSION['accesses']->_login['id']).",".$db->qstr(NOW()).",".$db->qstr(NOW()).$_insertFieldsVals.");"))
	{
		$_id = $db->Insert_ID();/*$code = $_GET['_pfx']."-D-".$_id;*/$code = autoBuildRefNo($_GET['_p'])."-D-".$_id;
		$old = umask(0);
		if(!is_dir(CONSUMER_ROOTPATH."docs_drafts/modules/".$_GET['_p']))
		{
			if(
			   mkdir(CONSUMER_ROOTPATH."docs_drafts/modules/".$_GET['_p'],0777)
			   &&
			   mkdir(CONSUMER_ROOTPATH."docs_drafts/modules/".$_GET['_p']."/".$_id,0777)
			   &&
			   mkdir(CONSUMER_ROOTPATH."docs_drafts/modules/".$_GET['_p']."/".$_id."/xml",0777)
			   ){}
		}
		else
		{
			if(mkdir(CONSUMER_ROOTPATH."docs_drafts/modules/".$_GET['_p']."/".$_id,0777) && mkdir(CONSUMER_ROOTPATH."docs_drafts/modules/".$_GET['_p']."/".$_id."/xml",0777)){}
		}
		
		umask($old);
		@$db->Execute("UPDATE `_mod_".$_GET['_p']."_draft` SET code=".$db->qstr($code)." WHERE `_mod_".$_GET['_p']."_draft`.id='".$_id."' LIMIT 1");
	}
    }
	
    $error = false;$files = array();$uploaddir = CONSUMER_ROOTPATH."docs_drafts/modules/".$_GET['_p']."/".$_id."/";
    //print_r($_FILES);print_r($_POST);print_r($_GET);
    foreach($_FILES as $file)
    {
        if(move_uploaded_file($file['tmp_name'],$uploaddir.basename($file['name'])))
        {
	    if($db->Execute("UPDATE `_mod_".$_GET['_p']."_draft` SET `".$_GET['_fid']."`=".$db->qstr($file['name'])." WHERE `_mod_".$_GET['_p']."_draft`.id='".$_id."' LIMIT 1"))
	    {
		$rs_f = $db->Execute("SELECT `A`.`copyfilepath` FROM `pages_fields` AS `A` WHERE `A`.id='".$_GET['_fid']."' AND `A`.`copyfilepath` IS NOT NULL AND `A`.pub='1' AND `A`.del='1' LIMIT 1");
		if(($rs_f) && ($rs_f->numRows() >= 1))
		{
		    if(isset($rs_f->fields['copyfilepath']) && (!empty($rs_f->fields['copyfilepath'])) && (file_exists($rs_f->fields['copyfilepath'])))
		    {
			if(file_exists($uploaddir.basename($file['name'])))
			{
			    @copy($uploaddir.basename($file['name']),$rs_f->fields['copyfilepath'].basename($file['name']));
			}
		    }
		}
	    }
	    
	    $files[] = $file['name'];$data = array('success'=>'Successfuly uploaded files','formData'=>$_POST);
	}
        else{$error = true;}
    }
    
    $data = ($error) ? array('error'=>'nofileupload','desc'=>'File upload failed..!') : array('isnew'=>$_id,'files'=>$files[0]);
}
else{$data = array('error'=>'nomodule','desc'=>'No Module Available..!');}

echo json_encode($data);

?>