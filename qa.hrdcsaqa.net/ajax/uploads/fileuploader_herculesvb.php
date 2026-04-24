<?php

require_once('../../inc/connection.php');

$data = array();$_id=$_GET['isnew'];$code="";

if(isset($_GET['_p']) && ($_GET['_p']>=1))
{
	$error = false;$msg="";$files = array();$uploaddir = CONSUMER_ROOTPATH."docs_drafts/modules/".$_GET['_p']."/herculesvb/";
	
	$old = umask(0);
	if((!is_dir(CONSUMER_ROOTPATH."docs_drafts/modules/".$_GET['_p'])) || (!is_dir(CONSUMER_ROOTPATH."docs_drafts/modules/".$_GET['_p']."/herculesvb")))
	{
		if(mkdir(CONSUMER_ROOTPATH."docs_drafts/modules/".$_GET['_p'],0777) && mkdir(CONSUMER_ROOTPATH."docs_drafts/modules/".$_GET['_p']."/herculesvb",0777))
		{
			foreach($_FILES as $file)
			{
				$file_name = time()."-".$file['name'];if(move_uploaded_file($file['tmp_name'],$uploaddir.basename($file_name))){$files[] = $file_name;}//else{$error=true;$msg="";}
			}
		}else{$error=true;$msg="nofileupload";}
	}
	else
	{
		foreach($_FILES as $file)
		{
			$file_name = time()."-".$file['name'];if(move_uploaded_file($file['tmp_name'],$uploaddir.basename($file_name))){$files[] = $file_name;}//else{$error=true;$msg="";}
		}
	}
	umask($old);
	
    
    /*
    if(isset($_GET['_herculesvbdir']) && ($_GET['_herculesvbdir']>=1) && isset($_GET['_herculesvbfile']) && (!empty($_GET['_herculesvbfile'])))
    {
	if(file_exists(CONSUMER_ROOTPATH."docs_drafts/modules/".$_GET['_herculesvbdir']."/herculesvb/".$_GET['_herculesvbfile']))
	{
	    if(copy(CONSUMER_ROOTPATH."docs_drafts/modules/".$_GET['_herculesvbdir']."/herculesvb/".$_GET['_herculesvbfile'] , $uploaddir.$_GET['_herculesvbfile']))
	    {echo json_encode(array('isnew'=>$_id,'files'=>$_GET['_herculesvbfile']));exit;}
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
    }*/
    
    $data = ($error) ? array('error'=>$msg) : array('success'=>'Successfuly uploaded files','files'=>$files);
}
else{$data = array('error'=>'nomodule');}

echo json_encode($data);

?>