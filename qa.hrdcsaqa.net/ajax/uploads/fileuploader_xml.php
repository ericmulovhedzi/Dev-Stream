<?php

require_once('../../inc/connection.php');

$data = array();$_id=$_GET['isnew'];$code="";

if(isset($_GET['_p']) && ($_GET['_p']>=1))
{
    if(!(isset($_GET['isnew']) && ($_GET['isnew']>=1)))
    {
	$_LOGGER_ID = (isset($_GET['_herculesvb_usr']) && (is_numeric($_GET['_herculesvb_usr'])) && ($_GET['_herculesvb_usr']>=1)) ? $_GET['_herculesvb_usr'] : $_SESSION['accesses']->_login['id'];
	
	if($db->Execute("INSERT INTO `_mod_".$_GET['_p']."_draft` (`user`,`date`,`date_update`$_insertFields) VALUES (".$db->qstr($_LOGGER_ID).",".$db->qstr(NOW()).",".$db->qstr(NOW()).$_insertFieldsVals.");"))
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
	
    $error = false;$files = array();$uploaddir = CONSUMER_ROOTPATH."docs_drafts/modules/".$_GET['_p']."/".$_id."/xml/";
    
    if(isset($_GET['_herculesvbdir']) && ($_GET['_herculesvbdir']>=1) && isset($_GET['_herculesvbfile']) && (!empty($_GET['_herculesvbfile'])))
    {
	if(file_exists(CONSUMER_ROOTPATH."docs_drafts/modules/".$_GET['_herculesvbdir']."/herculesvb/".$_GET['_herculesvbfile']))
	{
	    if(copy(CONSUMER_ROOTPATH."docs_drafts/modules/".$_GET['_herculesvbdir']."/herculesvb/".$_GET['_herculesvbfile'],$uploaddir.$_GET['_herculesvbfile']))
	    {system("rm -r ".CONSUMER_ROOTPATH."docs_drafts/modules/".$_GET['_herculesvbdir']."/herculesvb/".$_GET['_herculesvbfile'],$unlinkoutput);echo json_encode(array('isnew'=>$_id,'files'=>$_GET['_herculesvbfile']));exit;}
	    else{echo json_encode(array('error'=>'filecopyerror','desc'=>'File copy error..'));exit;}
	}
	else{echo json_encode(array('error'=>'filedoesnotexist','desc'=>'File does not exist..'));exit;}
    }
    else
    {
	foreach($_FILES as $file)
	{
	    $file_name = time()."-".$file['name'];
	    if(move_uploaded_file($file['tmp_name'],$uploaddir.basename($file_name)))
	    {$files[] = $file_name;$data = array('success'=>'Successfuly uploaded files','formData'=>$_POST);}
	    else{$error = true;}
	}
    }
    
    $data = ($error) ? array('error'=>'nofileupload','desc'=>'File upload failed..!') : array('isnew'=>$_id,'files'=>$files[0]);
}
else{$data = array('error'=>'nomodule','desc'=>'No Module Available..!');}

echo json_encode($data);

?>