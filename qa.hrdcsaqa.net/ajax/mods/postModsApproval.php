<?
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require_once('../../inc/connection.php');
require_once(CONSUMER_ROOTPATH.'inc/validator_.php');

if(isset($_SESSION['accesses']->_login['id']) && (count($_SESSION['accesses']->_login) >= 1))
{
	/*$_POST['_p']=$_POST['_p'];$_POST['_val']=$_GET['_val'];$_WF_ROLES = $_WF_USERS =array();
	$rs_wf = $db->Execute("SELECT `A`.`stage`,`B`.`item`,`B`.`type`,`C`.`name`
				FROM `_mod_".$_POST['_p']."_workflow` AS `A` LEFT JOIN `workflows_links` AS `B` ON `B`.`stage`=`A`.`stage` LEFT JOIN `workflows_groups` AS `C` ON `C`.`id`=`A`.`stage`
				WHERE `A`.`parent`='".$_POST['_val']."' AND `B`.del=1 ORDER BY `A`.`id` ASC");
	if(($rs_wf) && ($rs_wf->numRows() >= 1))
	{
		$userRolesArr = genericItemsArr("roles");
		
		while(!$rs_wf->EOF)
		{
			$_WF_ROLES[$rs_wf->fields['stage']]['name'] = $rs_wf->fields['name'];
			$_WF_ROLES[$rs_wf->fields['stage']][$rs_wf->fields['type']][$rs_wf->fields['item']] = $rs_wf->fields['item'];
			
			//echo $rs_wf->fields['stage']."-".$rs_wf->fields['item']."-".$rs_wf->fields['type']."-".$rs_wf->fields['name']."<br />";
			$rs_wf->MoveNext();
		}
		
		while(list($k,$v) = each($_WF_ROLES))
		{
			echo "&nbsp;".$v['name']."<br />";unset($v['name']);
			while(list($k_,$v_) = each($v))
			{
				echo "&nbsp;&nbsp;&nbsp;".$k_.":<br />";
				if($k_=="role")
				{
					while(list($k__,$v__) = each($v_))
					{
						$role = isset($userRolesArr[$k__]) ? $userRolesArr[$k__] : "N/A";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$k__."-".$role."<br />";
						
						$_rs_users = $db->Execute("SELECT `A`.id,CONCAT(`A`.name,' ',`A`.surname) AS cname,`A`.cell,`A`.email,`A`.image FROM `users` AS `A` WHERE `A`.`type`='".$k__."'");
						if(($_rs_users) && ($_rs_users->_numOfRows >= 1))
						{
							while(!$_rs_users->EOF)
							{
								$_WF_USERS[$_rs_users->fields['id']] = array($_rs_users->fields['email'],$_rs_users->fields['cell'],$_rs_users->fields['cname']);
								echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$_rs_users->fields['email']."-".$_rs_users->fields['cname']."<br />";
								$_rs_users->MoveNext();
							}
						}
					}
				}
				else if($k_=="user")
				{
					while(list($k__,$v__) = each($v_))
					{
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$k__."-".$v__."<br />";
						$_rs_users = $db->Execute("SELECT `A`.id,CONCAT(`A`.name,' ',`A`.surname) AS cname,`A`.cell,`A`.email,`A`.image FROM `users` AS `A` WHERE `A`.`id`='".$k__."'");
						if(($_rs_users) && ($_rs_users->_numOfRows >= 1)){$_WF_USERS[$_rs_users->fields['id']] = array($_rs_users->fields['email'],$_rs_users->fields['cell'],$_rs_users->fields['cname']);}
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$_rs_users->fields['email']."-".$_rs_users->fields['cname']."<br />";
					}
				}
			}
		}
	}
	print_r($_WF_USERS);
	exit;*/
	
	$array = array("status"=>0,"desc"=>"","data"=>0,"data_v"=>array(0,0,0));
	if(!(isset($_POST['_p']) && ($_POST['_p']>=1))){echo json_encode(array("status"=>0,"desc"=>"nomodule","data"=>'No module available..!',"data_v"=>array(0,0,0)));exit;}
	else if(!(isset($_POST['_val']) && ($_POST['_val']>=1))){echo json_encode(array("status"=>0,"desc"=>"noparent","data"=>'No item to be apprroved..!',"data_v"=>array(0,0,0)));exit;}
	else if(!(isset($_POST['_wf']) && ($_POST['_wf']>=1))){echo json_encode(array("status"=>0,"desc"=>"nostage","data"=>"No stage selected..!","data_v"=>array(0,0,0)));exit;}
	if(!(isset($_POST['_approval']) && ($_POST['_approval']>=0))){echo json_encode(array("status"=>0,"desc"=>"noaction","data"=>"No action taken..!","data_v"=>array(0,0,0)));exit;}
	else if(!(isset($_POST['_comments']) && (!empty($_POST['_comments'])))){echo json_encode(array("status"=>0,"desc"=>"nocomments","data"=>"No comments added..!","data_v"=>array(0,0,0)));exit;}
	else
	{
		global $db;
		if($db->Execute("UPDATE `_mod_".$_POST['_p']."_workflow` SET `user`=".$db->qstr($_SESSION['accesses']->_login['id']).",`role`=".$db->qstr($_SESSION['accesses']->_login['type'][0]).",`status`=".$db->qstr($_POST['_approval']).",`reason`=".$db->qstr($_POST['_reason']).",`comments`=".$db->qstr($_POST['_comments']).",`date_update`=".$db->qstr(NOW())." WHERE `parent`=".$db->qstr($_POST['_val'])." AND `stage`=".$db->qstr($_POST['_wf'])." AND `status`=".$db->qstr(0)." AND (TO_DAYS(`date_update`)=0 OR TO_DAYS(`date_update`) IS NULL) LIMIT 1"))
		{
			$workflowStatuArr = workflowStatuArr($_POST['_p'],$_POST['_val']);
			if(isset($workflowStatuArr[0]) && isset($workflowStatuArr[1]))
			{
				$_update_editables = "";
				$_rs_check_editables = $db->Execute("SELECT `iseditable` FROM `pages` WHERE id='".$_POST['_p']."' AND `iseditable`=1 LIMIT 1");
				if(($_rs_check_editables) && ($_rs_check_editables->_numOfRows >= 1)){$_update_editables = ",`iseditable`=2";}
				
				@$db->Execute("UPDATE `_mod_".$_POST['_p']."` SET `status`=".$workflowStatuArr[0].",`progress`=".$workflowStatuArr[1].",`date_update`=".$db->qstr(NOW()).$_update_editables." WHERE id='".$_POST['_val']."' LIMIT 1");
				$array = array("status"=>1,"desc"=>"Record successfully updated..","data"=>$_POST['_val'],"data_v"=>array(1,1,1));
				
				// --- Module Notifications
					
				$_rs_page = $db->Execute("SELECT `name`,`description`,`has_sms`,`has_attachment_pdf`,`has_attachment_excel` FROM `pages` WHERE `id`='".$_POST['_p']."' LIMIT 1");
				if(($_rs_page) && ($_rs_page->_numOfRows >= 1))
				{
					$rs_wf = $db->Execute("SELECT `A`.`user`,`A`.`stage`,`A`.`status`,IF(TO_DAYS(`A`.`date_update`)>0,1,0) AS `isactioned`,`B`.`item`,`B`.`type`,`C`.`name`,`D`.`filter_user_object`,`D`.`filter_field`,
							      `D`.nonotification, IF( `D`.nonotification =1 AND `B`.type = 'role', 1, 0 ) AS `isnonotification`
						FROM `_mod_".$_POST['_p']."_workflow` AS `A` LEFT JOIN `workflows_links` AS `B` ON `B`.`stage`=`A`.`stage`
						LEFT JOIN `workflows_groups` AS `C` ON `C`.`id`=`A`.`stage` LEFT JOIN `workflows_page_workflows` AS `D` ON `D`.`workflow`=`C`.`id`
						WHERE `A`.`parent`='".$_POST['_val']."' AND `B`.del=1
						HAVING `isnonotification` <>1
						ORDER BY `A`.`id` ASC");
					if(($rs_wf) && ($rs_wf->numRows() >= 1))
					{
						$stage_index=$rs_wf->fields['stage'];$userRolesArr = genericItemsArr("roles");
						while(!$rs_wf->EOF)
						{
							$_color = "#555";$_WF_ROLES[$rs_wf->fields['stage']]['status'] = " <i style='color:$_color'>(NOT APPROVED YET)</i>";
							
							if(isset($rs_wf->fields['status']) && ($rs_wf->fields['isactioned']==1))
							{
								if($rs_wf->fields['status']==0){$_color = "#e61e2b";$_WF_ROLES[$rs_wf->fields['stage']]['status'] = " <i style='color:$_color'>(REJECTED)</i>";} // --- Status: REJECTED
								else if($rs_wf->fields['status']==1){$_color = "#1fe61e";$_WF_ROLES[$rs_wf->fields['stage']]['status'] = " <i style='color:$_color'>(APPROVED)</i>";} // --- Status: APPROVED
							}
							
							if($stage_index==$rs_wf->fields['stage'])
							{
								$_color = "#222";$_WF_ROLES[$rs_wf->fields['stage']]['status'] = " <i style='color:$_color'>(LOGGED)</i>";
								$rs_wf->fields['type'] = "user";$rs_wf->fields['item'] = $rs_wf->fields['user'];
							}
							
							$_WF_ROLES[$rs_wf->fields['stage']]['name'] = $rs_wf->fields['name'];
							$_WF_ROLES[$rs_wf->fields['stage']][$rs_wf->fields['type']][$rs_wf->fields['item']] = $rs_wf->fields['item'];
							if($rs_wf->fields['type']=="role")$_WF_ROLES[$rs_wf->fields['stage']]['filterobject'] = array($rs_wf->fields['filter_user_object'],$rs_wf->fields['filter_field']);
							//echo $rs_wf->fields['stage']."-".$rs_wf->fields['item']."-".$rs_wf->fields['type']."-".$rs_wf->fields['name']."<br />";
							$rs_wf->MoveNext();
						}
						//print_r($_WF_ROLES);break;
						//while(list($k,$v) = each($_WF_ROLES))
						foreach($_WF_ROLES as $k=>$v)
						{
							//echo "&nbsp;".$v['name']."<br />";unset($v['name']);
							//while(list($k_,$v_) = each($v))
							foreach($v as $k_=>$v_)
							{
								//echo "&nbsp;&nbsp;&nbsp;".$k_.":<br />";
								if($k_=="role")
								{//echo "&nbsp;&nbsp;&nbsp;".$v['filterobject'][0].":<br />";
									$_SQL_INNER_JOIN = $_SQL_WHERE = "";
									if(isset($v['filterobject'][0]) && ($v['filterobject'][0]>=1))
									{
										if(array_key_exists($v['filterobject'][0],$userSpecificObjectsArr['role'])) // --- Role
										{
											if(isset($_SESSION['accesses']->_login['role']) && ($_SESSION['accesses']->_login['role'][$v['filterobject'][0]]) && isset($v['filterobject'][1]) && ($v['filterobject'][1]>=1))
											{
												$_rs_mods = $db->Execute("SELECT `".$v['filterobject'][1]."` FROM `_mod_".$_POST['_p']."` WHERE `id`='".$_POST['_val']."' LIMIT 1");
												if(($_rs_mods) && ($_rs_mods->_numOfRows >= 1))
												{
													$_SQL_INNER_JOIN = " INNER JOIN `roles` AS `B` ON `B`.id=`A`.type ";
													$_SQL_WHERE = " AND `B`.`".$userSpecificObjectsArr['role'][$v['filterobject'][0]]."`=".$db->qstr($_rs_mods->fields[$v['filterobject'][1]]);
													$_WF_ROLES[$k]['filterobject'][2] = "ROLE - ".$userSpecificObjectsArr['role'][$v['filterobject'][0]]." <b style='color:#DB0000;'><i>IS</i></b> ".$_rs_mods->fields[$v['filterobject'][1]];
												}
											}
										}
										
										if(array_key_exists($v['filterobject'][0],$userSpecificObjectsArr['district'])) // --- District
										{
											if(isset($_SESSION['accesses']->_login['district']) && ($_SESSION['accesses']->_login['district'][$v['filterobject'][0]]) && isset($v['filterobject'][1]) && ($v['filterobject'][1]>=1))
											{//echo "SELECT `".$v['filterobject'][1]."` FROM `_mod_".$_POST['_p']."` WHERE `id`='".$_POST['_val']."' LIMIT 1";
												$_rs_mods = $db->Execute("SELECT `".$v['filterobject'][1]."` FROM `_mod_".$_POST['_p']."` WHERE `id`='".$_POST['_val']."' LIMIT 1");
												if(($_rs_mods) && ($_rs_mods->_numOfRows >= 1))
												{
													//echo $userSpecificObjectsArr['district'][$v['filterobject'][0]]." : ".$_SESSION['accesses']->_login['district'][$v['filterobject'][0]]." : ".$v['filterobject'][1];
													$_SQL_INNER_JOIN = " INNER JOIN `districts` AS `C` ON `C`.id=`A`.district ";
													$_SQL_WHERE = " AND `C`.`".$userSpecificObjectsArr['district'][$v['filterobject'][0]]."`=".$db->qstr($_rs_mods->fields[$v['filterobject'][1]]);
													$_WF_ROLES[$k]['filterobject'][2] = "DISTRICT - ".$userSpecificObjectsArr['district'][$v['filterobject'][0]]." <b style='color:#DB0000;'><i>IS</i></b> ".$_rs_mods->fields[$v['filterobject'][1]];
													//echo $_SQL_WHERE = " AND `C`.`".$userSpecificObjectsArr['district'][$v['filterobject'][0]]."`=".$db->qstr($_SESSION['accesses']->_login['district'][$v['filterobject'][0]])." AND `C`.`".$userSpecificObjectsArr['district'][$v['filterobject'][0]]."`=".$db->qstr($_rs_mods->fields[$v['filterobject'][1]]);
												}
											}
										}
										
										if(array_key_exists($v['filterobject'][0],$userSpecificObjectsArr['region'])) // --- Region
										{
											if(isset($_SESSION['accesses']->_login['region']) && ($_SESSION['accesses']->_login['region'][$v['filterobject'][0]]) && isset($v['filterobject'][1]) && ($v['filterobject'][1]>=1))
											{
												$_rs_mods = $db->Execute("SELECT `".$v['filterobject'][1]."` FROM `_mod_".$_POST['_p']."` WHERE `id`='".$_POST['_val']."' LIMIT 1");
												if(($_rs_mods) && ($_rs_mods->_numOfRows >= 1))
												{
													$_SQL_INNER_JOIN = " INNER JOIN `districts` AS `C` ON `C`.id=`A`.district INNER JOIN `regions` AS `D` ON `D`.id=`C`.region ";
													$_SQL_WHERE = " AND `D`.`".$userSpecificObjectsArr['region'][$v['filterobject'][0]]."`=".$db->qstr($_rs_mods->fields[$v['filterobject'][1]]);
													$_WF_ROLES[$k]['filterobject'][2] = "REGION - ".$userSpecificObjectsArr['region'][$v['filterobject'][0]]." <b style='color:#DB0000;'><i>IS</i></b> ".$_rs_mods->fields[$v['filterobject'][1]];
												}
											}
										}
										
										if(array_key_exists($v['filterobject'][0],$userSpecificObjectsArr['group'])) // --- Group
										{
											if(isset($_SESSION['accesses']->_login['group']) && ($_SESSION['accesses']->_login['group'][$v['filterobject'][0]]) && isset($v['filterobject'][1]) && ($v['filterobject'][1]>=1))
											{
												$_rs_mods = $db->Execute("SELECT `".$v['filterobject'][1]."` FROM `_mod_".$_POST['_p']."` WHERE `id`='".$_POST['_val']."' LIMIT 1");
												if(($_rs_mods) && ($_rs_mods->_numOfRows >= 1))
												{
													$_SQL_INNER_JOIN = " INNER JOIN `roles` AS `B` ON `B`.id=`A`.type INNER JOIN `groups` AS `E` ON `E`.id=`B`.group ";
													$_SQL_WHERE = " AND `E`.`".$userSpecificObjectsArr['group'][$v['filterobject'][0]]."`=".$db->qstr($_rs_mods->fields[$v['filterobject'][1]]);
													$_WF_ROLES[$k]['filterobject'][2] = "GROUP - ".$userSpecificObjectsArr['group'][$v['filterobject'][0]]." <b style='color:#DB0000;'><i>IS</i></b> ".$_rs_mods->fields[$v['filterobject'][1]];
												}
											}
										}
									}
									
									//while(list($k__,$v__) = each($v_))
									foreach($v_ as $k__=>$v__)
									{
										$role = isset($userRolesArr[$k__]) ? $userRolesArr[$k__] : "N/A";
										$_WF_ROLES[$k][$k_][$k__]=$role;
										//echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$k__."-".$role."<br />";
										//echo "SELECT `A`.id,CONCAT(`A`.name,' ',`A`.surname) AS cname,`A`.cell,`A`.email,`A`.image FROM `users` AS `A` $_SQL_INNER_JOIN WHERE `A`.`type`='".$k__."' $_SQL_WHERE";
										$_rs_users = $db->Execute("SELECT `A`.id,CONCAT(`A`.name,' ',`A`.surname) AS cname,`A`.cell,`A`.email,`A`.image FROM `users` AS `A` $_SQL_INNER_JOIN WHERE `A`.`type`='".$k__."' $_SQL_WHERE");
										if(($_rs_users) && ($_rs_users->_numOfRows >= 1))
										{
											while(!$_rs_users->EOF)
											{
												$_WF_USERS[$_rs_users->fields['id']] = array($_rs_users->fields['email'],$_rs_users->fields['cell'],$_rs_users->fields['cname']);
												//echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$_rs_users->fields['email']."-".$_rs_users->fields['cname']."<br />";
												$_rs_users->MoveNext();
											}
										}
									}
								}
								else if($k_=="user")
								{
									//while(list($k__,$v__) = each($v_))
									foreach($v_ as $k__=>$v__)
									{
										//echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$k__."-".$v__."<br />";
										$_rs_users = $db->Execute("SELECT `A`.id,CONCAT(`A`.name,' ',`A`.surname) AS cname,`A`.cell,`A`.email,`A`.image FROM `users` AS `A` WHERE `A`.`id`='".$k__."'");
										if(($_rs_users) && ($_rs_users->_numOfRows >= 1))
										{
											$_WF_USERS[$_rs_users->fields['id']] = array($_rs_users->fields['email'],$_rs_users->fields['cell'],$_rs_users->fields['cname']);
											$_WF_ROLES[$k][$k_][$k__]=$_rs_users->fields['cname'];
										}
										//echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$_rs_users->fields['email']."-".$_rs_users->fields['cname']."<br />";
									}
								}
							}
						}
					}
					//print_r($_WF_USERS);
					//break;
					$_notificationHasAttachmenteArr = array($_rs_page->fields['name'],$_rs_page->fields['description'],$_rs_page->fields['name'],0,0,0);
					if(isset($_rs_page->fields['has_sms']) && ($_rs_page->fields['has_sms']>=1))
					{
						$_notificationHasAttachmenteArr[3] = 1;
					}
					
					if(isset($_rs_page->fields['has_attachment_pdf']) && ($_rs_page->fields['has_attachment_pdf']>=1))
					{
						$_notificationHasAttachmenteArr[4] = 1;
						//@file(WWWROOT."exports/pdf/_pdaPDF.php?id=".$_POST['_val']."&_file=1");
					}
					
					if(isset($_rs_page->fields['has_attachment_excel']) && ($_rs_page->fields['has_attachment_excel']>=1))
					{
						$_notificationHasAttachmenteArr[5] = 1;
						//@file(WWWROOT."exports/excel/_pdaImportData.php?_id=".$_POST['_val']."&_file=1");
					}
					
					$FILTERING="`id`,`code`,`user`,`date`";$VALSARRAY = array('id'=>array('id',1),'code'=>array('code',1),'user'=>array('user',1),'date'=>array('date',1));					
					list($FILTERING,$VALSARRAY) = buildAutoMailBodyFields($_POST['_p'],$_POST['_val'],$FILTERING,$VALSARRAY);
					
					$loggerInfoArr = array();
					if(isset($VALSARRAY['user'][2]) && ($VALSARRAY['user'][2]>=1))
					{
						$loggerInfoArr = userInfo($VALSARRAY['user'][2]);
					}
					
					//include(ROOTPATH."inc/notifications_mods.php");
					include("../../inc/notifications_mods.php");
					
					$userInfoArr = array($_SESSION['accesses']->_login['id'],$_SESSION['accesses']->_login['cname'],$_SESSION['accesses']->_login['email'],$_SESSION['accesses']->_login['cell'],$_SESSION['accesses']->_login['image']);
					//print_r($_WF_ROLES);print_r($_WF_USERS);
					if(is_array($loggerInfoArr) && (sizeof($loggerInfoArr) >= 1) && is_array($VALSARRAY) && (sizeof($VALSARRAY) >= 1))
					{
						$pageNotityFieldsArr = pageFieldsArr(" AND `C`.page='".$_POST['_p']."' AND `A`.isnotifyme=1 ");
						
						$_NOTIFYMEARR = array();
						if(is_array($pageNotityFieldsArr) && (sizeof($pageNotityFieldsArr) >= 1))
						{
							foreach($pageNotityFieldsArr as $k_nme => $val_nme)
							{
								foreach ($val_nme as $____k => $____v)
								{
									$rs_nme = $db->Execute("SELECT `".$____k."` FROM `_mod_".$_POST['_p']."` WHERE id='".$_POST['_val']."' LIMIT 1");
									if(($rs_nme) && ($rs_nme->_numOfRows >= 1))
									{
										$_NOTIFYMEARR[] = $rs_nme->fields[$____k];
									}
								}
							}
						}
						
						itemApprovalNotification($userInfoArr,$loggerInfoArr,$VALSARRAY,$_notificationHasAttachmenteArr,$workflowStatuArr[0],$workflowStatuArr[1],$_POST['_comments'],NOW(),$_WF_ROLES,$_WF_USERS,$_NOTIFYMEARR);
					}
				}
				
				if($_POST['_approval']==0) // --- If item rejected
				{
					$_rs_mods = $db->Execute("SELECT `external` FROM `_mod_".$_POST['_p']."` WHERE `id`='".$_POST['_val']."' AND `external`>0 LIMIT 1");
					if(($_rs_mods) && ($_rs_mods->_numOfRows >= 1))
					{
						$rs_integration = $db->Execute("SELECT `id` FROM `security_integration_technology_partners` WHERE `id`=".$_rs_mods->fields['external']." AND `hasrevivalpill`=1 AND `pub`='1' AND `del`='1' LIMIT 1");
						if(($rs_integration) && ($rs_integration->numRows() >= 1))
						{
							$rs_root = $db->Execute("SELECT `root`,`file` FROM `audit_integration_partners_transactions` WHERE `external`=".$_rs_mods->fields['external']." AND `module`=".$_POST['_p']." AND `data`=".$_POST['_val']." AND `status`=1 LIMIT 1");
							if(($rs_root) && ($rs_root->numRows() >= 1))
							{
								if(file_exists(CONSUMER_ROOTPATH."docs/modules/".$_POST['_p']."/".$_POST['_val']."/xml/".$rs_root->fields['file']))
								{
									if(copy(CONSUMER_ROOTPATH."docs/modules/".$_POST['_p']."/".$_POST['_val']."/xml/".$rs_root->fields['file'],CONSUMER_ROOTPATH."docs_drafts/modules/".$rs_root->fields['root']."/herculesvb/".$rs_root->fields['file']))
									{
										@$db->Execute("UPDATE `_mod_".$_POST['_p']."_draft` SET code=".$db->qstr($code).$_external." WHERE `_mod_".$_POST['_p']."_draft`.id='".$_id."' LIMIT 1");
									}
								}
							}
						}
					}
				}
			}
			else{echo json_encode(array("status"=>0,"desc"=>"norecordupdated","data"=>"No record updated","data_v"=>array(0,0,0)));exit;}
		}
		else{echo json_encode(array("status"=>0,"desc"=>"noworkflowupdated","data"=>"No workflow updated","data_v"=>array(0,0,0)));exit;}
	}
	
	echo json_encode($array);exit;
}
else {echo json_encode(array("status"=>0,"desc"=>"islogout","data"=>"logout","data_v"=>array(0,0,0)));exit;}

?>