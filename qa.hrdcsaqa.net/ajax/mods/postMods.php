<?
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

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
	$_TD_SQLS = "";
	global $db;$db->SetFetchMode(ADODB_FETCH_ASSOC);
	//$_POST['_p'] = $_GET['_p'];$_POST['_pfx'] = $_GET['_pfx'];$_POST['_terms']=$_GET['_terms'];$_POST['isnew']=$_GET['isnew'];
	if(!(isset($_POST['_p']) && ($_POST['_p']>=1))){echo json_encode(array("status"=>0,"desc"=>"nomodule","data"=>0,"data_v"=>array(0,0,0)));exit;}
	//else if(!(isset($_POST['_pfx']) && (!empty($_POST['_pfx'])))){echo json_encode(array("status"=>0,"desc"=>"nomoduleprefix","data"=>0,"data_v"=>array(0,0,0)));exit;}
	
	if(isset($_POST['_terms']) && ($_POST['_terms']==1)) // Send terms and conditions to email
	{
		if(!(isset($_POST['isnew']) && ($_POST['isnew']>=1))){echo json_encode(array("status"=>0,"desc"=>"noitem","data"=>0,"data_v"=>array(0,0,0)));exit;}
		
		$_INSERT_SELECT_FIELDS = "";$_INSERT_SELECT_FIELDS_ARR = array();
		$_rs = $db->Execute("SHOW COLUMNS FROM `_mod_".$_POST['_p']."_draft`");
		if(($_rs) && ($_rs->_numOfRows >= 1))
		{
			if(isset($_POST['_has_items']) && ($_POST['_has_items']==1)) // --- UPDATE ITEMS MODULE INTO INVENTORY 
			{
				$_rs_inventory_link = $db->Execute("SELECT `is_inventory_linked` FROM `pages` WHERE `id`=".$_POST['_p']." LIMIT 1;");
				if(($_rs_inventory_link) && ($_rs_inventory_link->_numOfRows >= 1))
				{
					$_INVENTORY_MODULE = $_rs_inventory_link->fields['is_inventory_linked'];$_INVENTORY_FIELD_ARR = array();$_INVENTORY_FIELD_TEMP_STR = "";
					$_ORDER_FIELD_ARR = array();$_ORDER_FIELD_TEMP_STR = "";
					
					$_rs_inventory_fields_temp = $db->Execute("SHOW COLUMNS FROM `_mod_".$_INVENTORY_MODULE."`");
					if(($_rs_inventory_fields_temp) && ($_rs_inventory_fields_temp->_numOfRows >= 1))
					{
						while(!$_rs_inventory_fields_temp->EOF){if(isset($_rs_inventory_fields_temp->fields['Field']) && (is_numeric($_rs_inventory_fields_temp->fields['Field']))){$_INVENTORY_FIELD_TEMP_STR .= $_rs_inventory_fields_temp->fields['Field'].",";}$_rs_inventory_fields_temp->MoveNext();}
						$_INVENTORY_FIELD_TEMP_STR = substr($_INVENTORY_FIELD_TEMP_STR, 0, -1);
						
						$_rs_inventory_fields = $db->Execute("SELECT `id`,`is_barcode`,`is_barcode_qty`,`is_barcode_cost` FROM `pages_fields` WHERE `id` IN (".$_INVENTORY_FIELD_TEMP_STR.") AND (`is_barcode`=1 OR `is_barcode_qty`=1 OR `is_barcode_cost`=1);");
						if(($_rs_inventory_fields) && ($_rs_inventory_fields->_numOfRows >= 1))
						{
							while(!$_rs_inventory_fields->EOF)
							{
								if(isset($_rs_inventory_fields->fields['is_barcode']) && ($_rs_inventory_fields->fields['is_barcode']==1)){$_INVENTORY_FIELD_ARR['is_barcode'] = $_rs_inventory_fields->fields['id'];}
								if(isset($_rs_inventory_fields->fields['is_barcode_qty']) && ($_rs_inventory_fields->fields['is_barcode_qty']==1)){$_INVENTORY_FIELD_ARR['is_barcode_qty'] = $_rs_inventory_fields->fields['id'];}
								if(isset($_rs_inventory_fields->fields['is_barcode_cost']) && ($_rs_inventory_fields->fields['is_barcode_cost']==1)){$_INVENTORY_FIELD_ARR['is_barcode_cost'] = $_rs_inventory_fields->fields['id'];}
								$_rs_inventory_fields->MoveNext();
							}
						}
					}
					
					$_rs_order_fields_temp = $db->Execute("SHOW COLUMNS FROM `_mod_".$_POST['_p']."`");
					if(($_rs_order_fields_temp) && ($_rs_order_fields_temp->_numOfRows >= 1))
					{
						while(!$_rs_order_fields_temp->EOF){if(isset($_rs_order_fields_temp->fields['Field']) && (is_numeric($_rs_order_fields_temp->fields['Field']))){$_ORDER_FIELD_TEMP_STR .= $_rs_order_fields_temp->fields['Field'].",";}$_rs_order_fields_temp->MoveNext();}
						$_ORDER_FIELD_TEMP_STR = substr($_ORDER_FIELD_TEMP_STR, 0, -1);
						
						$_rs_order_fields = $db->Execute("SELECT `id`,`has_barcode`,`has_barcode_qty`,`has_barcode_price` FROM `pages_fields` WHERE `id` IN (".$_ORDER_FIELD_TEMP_STR.") AND (`has_barcode`=1 OR `has_barcode_qty`=1 OR `has_barcode_price`=1);");
						if(($_rs_order_fields) && ($_rs_order_fields->_numOfRows >= 1))
						{
							while(!$_rs_order_fields->EOF)
							{
								if(isset($_rs_order_fields->fields['has_barcode']) && ($_rs_order_fields->fields['has_barcode']==1)){$_ORDER_FIELD_ARR['has_barcode'] = $_rs_order_fields->fields['id'];}
								if(isset($_rs_order_fields->fields['has_barcode_qty']) && ($_rs_order_fields->fields['has_barcode_qty']==1)){$_ORDER_FIELD_ARR['has_barcode_qty'] = $_rs_order_fields->fields['id'];}
								if(isset($_rs_order_fields->fields['has_barcode_price']) && ($_rs_order_fields->fields['has_barcode_price']==1)){$_ORDER_FIELD_ARR['has_barcode_price'] = $_rs_order_fields->fields['id'];}
								
								$_rs_order_fields->MoveNext();
							}
						}
					}
					
					$_rs_order_placement = $db->Execute("SELECT `".$_ORDER_FIELD_ARR['has_barcode']."`,`".$_ORDER_FIELD_ARR['has_barcode_qty']."`,`".$_ORDER_FIELD_ARR['has_barcode_price']."` FROM `_mod_".$_POST['_p']."_draft` WHERE `id`='".$_POST['isnew']."' LIMIT 1;");
					if(($_rs_order_placement) && ($_rs_order_placement->_numOfRows >= 1))
					{
						$_OP_ARR['barcode'] = ((isset($_rs_order_placement->fields[$_ORDER_FIELD_ARR['has_barcode']])) && (!empty($_rs_order_placement->fields[$_ORDER_FIELD_ARR['has_barcode']]))) ? json_decode($_rs_order_placement->fields[$_ORDER_FIELD_ARR['has_barcode']], true) : array();
						$_OP_ARR['barcode_qty'] = ((isset($_rs_order_placement->fields[$_ORDER_FIELD_ARR['has_barcode_qty']])) && (!empty($_rs_order_placement->fields[$_ORDER_FIELD_ARR['has_barcode_qty']]))) ? json_decode($_rs_order_placement->fields[$_ORDER_FIELD_ARR['has_barcode_qty']], true) : array();
						$_OP_ARR['barcode_price'] = ((isset($_rs_order_placement->fields[$_ORDER_FIELD_ARR['has_barcode_price']])) && (!empty($_rs_order_placement->fields[$_ORDER_FIELD_ARR['has_barcode_price']]))) ? json_decode($_rs_order_placement->fields[$_ORDER_FIELD_ARR['has_barcode_price']], true) : array();
						
						if(isset($_OP_ARR['barcode']) && is_array($_OP_ARR['barcode']))
						{
							$_BARCODE_ID = (isset($_INVENTORY_FIELD_ARR['is_barcode']) && ($_INVENTORY_FIELD_ARR['is_barcode'] > 0)) ? $_INVENTORY_FIELD_ARR['is_barcode'] : 0 ;
							foreach($_OP_ARR['barcode'] as $key=>$val)
							{
								$_QTY = (isset($_OP_ARR['barcode_qty'][$key]) && ($_OP_ARR['barcode_qty'][$key] > 0)) ? $_OP_ARR['barcode_qty'][$key] : 0 ;
								
								@$db->Execute("UPDATE `_mod_".$_INVENTORY_MODULE."` SET `_mod_".$_INVENTORY_MODULE."`.`".$_INVENTORY_FIELD_ARR['is_barcode_qty']."`=`_mod_".$_INVENTORY_MODULE."`.`".$_INVENTORY_FIELD_ARR['is_barcode_qty']."`-".$_QTY.",`progression_count`=".$_QTY.",`progression`='-1',`date_update`=".$db->qstr(NOW())." WHERE `_mod_".$_INVENTORY_MODULE."`.`".$_BARCODE_ID."`='".$val."' LIMIT 1;");
							}
						}
					}
				}
			}
			
			$_isNotId = false;
			while(!$_rs->EOF)
			{
				if($_rs->fields['Field'] != "id")
				{
					$_INSERT_SELECT_FIELDS .= "`".$_rs->fields['Field']."`";$_isNotId=true;
					
					if(!in_array($_rs->fields['Field'],array("id","code","user","parent","external","_terms","date_update","date")))
					{$_INSERT_SELECT_FIELDS_ARR[] = $_rs->fields['Field'];}
				}
				
				$_rs->MoveNext();
				if((!$_rs->EOF) && ($_isNotId)){$_INSERT_SELECT_FIELDS .= ",";}
			}
		}
		
		if((!empty($_INSERT_SELECT_FIELDS)) && ($db->Execute("INSERT INTO `_mod_".$_POST['_p']."` ($_INSERT_SELECT_FIELDS) SELECT $_INSERT_SELECT_FIELDS FROM `_mod_".$_POST['_p']."_draft` WHERE `_mod_".$_POST['_p']."_draft`.id='".$_POST['isnew']."'")))
		{
			$_rs_new = $db->Execute("SELECT COUNT(id) AS `total` FROM `_mod_".$_POST['_p']."`");
			if(($_rs_new) && ($_rs_new->_numOfRows >= 1))
			{
				$_id = $_rs_new->fields['total'];
				if($db->Execute("UPDATE `_mod_".$_POST['_p']."` SET code=CONCAT('".autoBuildRefNo($_POST['_p'])."','-',".$_id."),`parent`=".$_POST['isnew'].",`date_update`=".$db->qstr(NOW()).",`date`=".$db->qstr(NOW())." WHERE id='".$_id."' LIMIT 1"))
				{
					$old = umask(0);
					
					if(!is_dir(CONSUMER_ROOTPATH."docs/modules/".$_POST['_p'])) // if module directory does not exist
					{
						if(mkdir(CONSUMER_ROOTPATH."docs/modules/".$_POST['_p'],0777) && mkdir(CONSUMER_ROOTPATH."docs/modules/".$_POST['_p']."/".$_id,0777))
						{
							$db->Execute("UPDATE `_mod_".$_POST['_p']."_draft` SET `pub`=0,`date_update`=".$db->qstr(NOW())." WHERE id='".$_POST['isnew']."' LIMIT 1");
						}
					}
					else
					{
						if(mkdir(CONSUMER_ROOTPATH."docs/modules/".$_POST['_p']."/".$_id,0777))
						{
							if($db->Execute("UPDATE `_mod_".$_POST['_p']."_draft` SET `pub`=0,`date_update`=".$db->qstr(NOW())." WHERE id='".$_POST['isnew']."' LIMIT 1"))
							{
								$rs_w =$db->Execute("SELECT `B`.`id` FROM `workflows_page_workflows` AS `A` INNER JOIN `workflows_groups` AS `B` ON `A`.workflow=`B`.id WHERE `B`.`page`='".$_POST['_p']."' AND `A`.pub=1 AND `A`.del=1 ORDER BY `B`.rank ASC");
								if(($rs_w) && ($rs_w->numRows() >= 1))
								{
									$_wf_i = $_wf_status = 1;$_wf_total=$rs_w->numRows();
									while(!$rs_w->EOF)
									{
										$_insert_cols = $_insert_vals = "";
										if($_wf_i==1)
										{
											$_wf_status = 1; // -- not used yet
											$_insert_cols = ",`user`,`role`,`status`,`date_update`";
											
											$_LOGGER_ID = $_SESSION['accesses']->_login['id'];$_LOGGER_TYPE = $_SESSION['accesses']->_login['type'][0];
											if(isset($_POST['_herculesvb_usr']) && (is_numeric($_POST['_herculesvb_usr'])) && ($_POST['_herculesvb_usr']>=1))
											{
												$_LOGGER_ID = $_POST['_herculesvb_usr'];
												$loggerInfoArr = userInfo($_LOGGER_ID);
												$_LOGGER_TYPE = (is_array($loggerInfoArr) && (sizeof($loggerInfoArr) >= 1) && isset($loggerInfoArr[6]) && ($loggerInfoArr[6] >= 1)) ? $loggerInfoArr[6]:0;
												
											}
											
											$_insert_vals = ",".$db->qstr($_LOGGER_ID).",".$_LOGGER_TYPE.",1,".$db->qstr(NOW());
											
										}
										else if($_wf_i==$rs_w->numRows())
										{
											$_wf_status = 0; // -- not used yet
										}
										else
										{
											$_wf_status = 0; // -- not used yet
										}
										
										if($db->Execute("INSERT INTO `_mod_".$_POST['_p']."_workflow` (`parent`,`stage`,`date`$_insert_cols) VALUES (".$_id.",".$rs_w->fields['id'].",".$db->qstr(NOW()).$_insert_vals.")"))
										{
											if($_wf_i==1)
											{
												@$db->Execute("UPDATE `_mod_".$_POST['_p']."` SET `status`=1,`stage`=".$rs_w->fields['id'].",`date_update`=".$db->qstr(NOW())." WHERE id='".$_id."' LIMIT 1");
											}
											else if($_wf_i==$rs_w->numRows())
											{
												$workflowStatuArr = workflowStatuArr($_POST['_p'],$_id);
												if(isset($workflowStatuArr[0]) && isset($workflowStatuArr[1]))
												{
													@$db->Execute("UPDATE `_mod_".$_POST['_p']."` SET `progress`=".$workflowStatuArr[1].",`date_update`=".$db->qstr(NOW())." WHERE id='".$_id."' LIMIT 1");
													
													// --- Copying data from an old ref number
													$old = umask(0);
													@exec("cp -a ".CONSUMER_ROOTPATH."docs_drafts/modules/".$_POST['_p']."/".$_POST['isnew']."/. ".CONSUMER_ROOTPATH."docs/modules/".$_POST['_p']."/".$_id, $out_, $status_);
													umask($old);
													// --- Module Notifications
													
													$_rs_page = $db->Execute("SELECT `name`,`description`,`has_sms`,`has_attachment_pdf`,`has_attachment_excel` FROM `pages` WHERE `id`='".$_POST['_p']."' LIMIT 1");
													if(($_rs_page) && ($_rs_page->_numOfRows >= 1))
													{
														$_notificationHasAttachmenteArr = array($_rs_page->fields['name'],$_rs_page->fields['description'],$_rs_page->fields['name'],0,0,0);
														if(isset($_rs_page->fields['has_sms']) && ($_rs_page->fields['has_sms']>=1))
														{
															$_notificationHasAttachmenteArr[3] = 1;
														}
														
														if(isset($_rs_page->fields['has_attachment_pdf']) && ($_rs_page->fields['has_attachment_pdf']>=1))
														{
															$_notificationHasAttachmenteArr[4] = 1;
															//@file(WWWROOT."exports/pdf/_pdaPDF.php?id=".$_id."&_file=1");
														}
														
														if(isset($_rs_page->fields['has_attachment_excel']) && ($_rs_page->fields['has_attachment_excel']>=1))
														{
															$_notificationHasAttachmenteArr[5] = 1;
															//@file(WWWROOT."exports/excel/_pdaImportData.php?_id=".$_id."&_file=1");
														}
														
														$FILTERING="`id`,`code`,`date`";$VALSARRAY = array('id'=>array('id',1),'code'=>array('code',1),'date'=>array('date',1));					
														list($FILTERING,$VALSARRAY) = buildAutoMailBodyFields($_POST['_p'],$_id,$FILTERING,$VALSARRAY);
														
														include("../../inc/notifications_mods.php");
														 
														$userInfoArr = array($_SESSION['accesses']->_login['id'],$_SESSION['accesses']->_login['cname'],$_SESSION['accesses']->_login['email'],$_SESSION['accesses']->_login['cell'],$_SESSION['accesses']->_login['image']);
														
														if(isset($_POST['_herculesvb_usr']) && (is_numeric($_POST['_herculesvb_usr'])) && ($_POST['_herculesvb_usr']>=1)){$userInfoArr = userInfo($_POST['_herculesvb_usr']);}
														
														if(is_array($userInfoArr) && (sizeof($userInfoArr) >= 1) && is_array($VALSARRAY) && (sizeof($VALSARRAY) >= 1))
														{
															$pageNotityFieldsArr = pageFieldsArr(" AND `C`.page='".$_POST['_p']."' AND `A`.isnotifyme=1 ");
															
															$_NOTIFYMEARR = array();
															if(is_array($pageNotityFieldsArr) && (sizeof($pageNotityFieldsArr) >= 1))
															{
																foreach($pageNotityFieldsArr as $k_nme => $val_nme)
																{
																	foreach ($val_nme as $____k => $____v)
																	{
																		$rs_nme = $db->Execute("SELECT `".$____k."` FROM `_mod_".$_POST['_p']."` WHERE id='".$_id."' LIMIT 1");
																		if(($rs_nme) && ($rs_nme->_numOfRows >= 1))
																		{
																			$_NOTIFYMEARR[] = $rs_nme->fields[$____k];
																		}
																	}
																}
															}
															//print_r($_NOTIFYMEARR);
															itemLoggedNotification($userInfoArr,$VALSARRAY,$_notificationHasAttachmenteArr,$_NOTIFYMEARR);
															//itemLoggedNotification($userInfoArr,$userInfoArr,$VALSARRAY,$_notificationHasAttachmenteArr);
														}
													}
												}
											}
										}
										
										$_wf_i++;
										$rs_w->MoveNext();
									}
								}
							}
						}
					}
					
					$_rs_td = $db->Execute("SELECT * FROM `_mod_".$_POST['_p']."` WHERE id='".$_id."' LIMIT 1");
					if(($_rs_td) && ($_rs_td->_numOfRows >= 1))
					{
						if(isset($_INSERT_SELECT_FIELDS_ARR) && is_array($_INSERT_SELECT_FIELDS_ARR) && (sizeof($_INSERT_SELECT_FIELDS_ARR)>=1))
						{
							//while(list($k,$v) = each($_INSERT_SELECT_FIELDS_ARR))
							foreach($_INSERT_SELECT_FIELDS_ARR as $k=>$v)
							{
								if(isset($_rs_td->fields[$v]) && (!empty($_rs_td->fields[$v])) && (!is_numeric($_rs_td->fields[$v])) && (!is_date($_rs_td->fields[$v])) && (!is_time($_rs_td->fields[$v])))
								{
									$strArr = td_sanitize($_rs_td->fields[$v]);
									
									if(isset($strArr) && is_array($strArr) && (sizeof($strArr)>=1))
									{
										//while(list($_k,$_v) = each($strArr))
										foreach($strArr as $_k=>$_v)
										{
											$_TD_SQLS.= "(".$db->qstr($_v).",".$db->qstr(NOW()).",".$db->qstr(NOW())."),";
										}
									}
								}
							}
							
							if(isset($_TD_SQLS) && (!empty($_TD_SQLS)))
							{
								@$db->Execute("INSERT INTO `_mod_".$_POST['_p']."_td` (`term`,`date_update`,`date`) VALUES ".rtrim($_TD_SQLS,',')." ON DUPLICATE KEY UPDATE `count`=`count`+1,`date_update`=".$db->qstr(NOW()).";");
							}
						}
					}
					
					umask($old);echo json_encode(array("status"=>1,"desc"=>"Successful Saved: ".autoBuildRefNo($_POST['_p'])."-".$_id,"data"=>$_id,"data_v"=>array(0,0,0)));exit;
				}else{echo json_encode(array("status"=>0,"desc"=>"noitemadded","data"=>3,"data_v"=>array(0,0,0)));exit;}
				
			}else{echo json_encode(array("status"=>0,"desc"=>"noitemadded","data"=>2,"data_v"=>array(0,0,0)));exit;}
			
		}
		else{echo json_encode(array("status"=>0,"desc"=>"noitemadded","data"=>1,"data_v"=>array(0,0,0)));exit;}
	}
	
	$array = array("status"=>0,"desc"=>"","data"=>0,"data_v"=>array(0,0,0));$_countArr = array();
	$_updateFields=$_insertFields=$_insertFieldsVals=$_TD_SQLS="";$fieldNonVALID=$progressbarTOTAL=0;$_selectFields="-100";$status_v=1;
	//echo "SHOW COLUMNS FROM `_mod_".$_POST['_p']."_draft`";
	$_rs = $db->Execute("SHOW COLUMNS FROM `_mod_".$_POST['_p']."_draft`");
	if(($_rs) && ($_rs->_numOfRows >= 1))
	{
		$dbsArr = array();
		while (!$_rs->EOF)
		{
			$dbsArr[]=$_rs->fields['Field'];
			$_rs->MoveNext();
		}
		
		//print_r($dbsArr);
		$tmpArr = $_POST;
		//while (list($k,$v) = each($tmpArr))
		foreach($tmpArr as $k=>$v)
		{
			if(isset($k) && (is_numeric($k)))
			{
				if(isset($_POST[$k]))
				{
					if(true) // --- IF VALID = true
					{
						$progressbarTOTAL += 1;
					}
					else
					{
						$fieldNonVALID = $k;
					}
					
					$_updateFields .=",`".$k."`=".$db->qstr(urldecode($_POST[$k])); // Get Update field values
					$_insertFields .=",`".$k."`"; // Get Drop Down values
					$_insertFieldsVals .=",".$db->qstr(urldecode($_POST[$k])); // Get Insert field values
					$_selectFields .=",".$k; // Get Drop Down values
					
					/*if(!is_numeric($_POST[$k]))
					{
						$strArr = td_sanitize($_POST[$k]);
						
						if(isset($strArr) && is_array($strArr) && (sizeof($strArr)>=1))
						{
							while(list($k,$v) = each($strArr))
							{
								$_TD_SQLS.= "INSERT INTO `_mod_".$_POST['_p']."_td` (`term`,`date_update`,`date`) VALUES (".$db->qstr($v).",".$db->qstr(NOW()).",".$db->qstr(NOW()).") ON DUPLICATE KEY UPDATE `count`=`count`+1,`date_update`=".$db->qstr(NOW()).";";
							}
						}
					}*/
				}
				
				if(is_array($dbsArr) && (!in_array($k,$dbsArr)))
				{
					@$db->Execute("ALTER TABLE `_mod_".$_POST['_p']."_draft` ADD COLUMN `".$k."` blob AFTER `date`");
					@$db->Execute("ALTER TABLE `_mod_".$_POST['_p']."` ADD COLUMN `".$k."` blob AFTER `date`");
				}
			}
		}
		
		if(isset($_POST['_hasfromfile']) && ($_POST['_hasfromfile']==1)) // has from file fields type
		{
			if(isset($_POST['_fromfiles']) && is_array($_POST['_fromfiles']))
			{
				//while(list($__k,$__v) = each($_POST['_fromfiles']))
				foreach($_POST['_fromfiles'] as $__k=>$__v)
				{
					$_updateFields .=",`file_".$__k."`=".$db->qstr(urldecode($__v)); // Get Update field values
					$_insertFields .=",`file_".$__k."`"; // Get Drop Down values
					$_insertFieldsVals .=",".$db->qstr(urldecode($__v)); // Get Insert field values
					//$_selectFields .=",".$k; // Get Drop Down values
				}
			}
		}
		
		if(isset($_POST['_haslookup']) && ($_POST['_haslookup']==1)) // has lookup fields type
		{
			if(isset($_POST['_lookups']) && is_array($_POST['_lookups']))
			{
				//while(list($__k,$__v) = each($_POST['_lookups']))
				foreach($_POST['_lookups'] as $__k=>$__v)
				{
					$_updateFields .=",`lookup_".$__k."`=".$db->qstr(urldecode($__v)); // Get Update field values
					$_insertFields .=",`lookup_".$__k."`"; // Get Drop Down values
					$_insertFieldsVals .=",".$db->qstr(urldecode($__v)); // Get Insert field values
					//$_selectFields .=",".$k; // Get Drop Down values
				}
			}
		}
		
		if(isset($_POST['__items']) && is_array($_POST['__items'])) // has lookup items
		{
			//while(list($__k,$__v) = each($_POST['__items']))
			foreach($_POST['__items'] as $__k=>$__v)
			{
				$__items = (isset($__v) && is_array($__v) && (sizeof($__v)>0)) ? json_encode($__v) : "";
				$_updateFields .=",`".$__k."`=".$db->qstr(urldecode($__items)); // Get Update field values
				$_insertFields .=",`".$__k."`"; // Get Drop Down values
				$_insertFieldsVals .=",".$db->qstr(urldecode($__items)); // Get Insert field values
				//$_selectFields .=",".$k; // Get Drop Down values
			}
		}
		
		if(isset($_POST['__aggregates']) && is_array($_POST['__aggregates'])) // has lookup items
		{
			//while(list($__k,$__v) = each($_POST['__aggregates']))
			foreach($_POST['__aggregates'] as $__k=>$__v)
			{
				$_updateFields .=",`aggregate_".$__k."`=".$db->qstr(urldecode($__v)); // Get Update field values
				$_insertFields .=",`aggregate_".$__k."`"; // Get Drop Down values
				$_insertFieldsVals .=",".$db->qstr(urldecode($__v)); // Get Insert field values
			}//echo $_updateFields;
		}
	}
	else // --- This functionality is not live yet
	{
		echo json_encode(array("status"=>0,"desc"=>"isdemo","data"=>0,"data_v"=>array(0,0,0)));exit;
	}
	
	$isNotXML = true;if(isset($_POST['_herculesvb_usr']) && (is_numeric($_POST['_herculesvb_usr'])) && ($_POST['_herculesvb_usr']>=1)){$isNotXML = false;}

	$rs_v = $db->Execute("SELECT
			     `A`.name AS vname,`A`.parameters AS vparam,`A`.inverse AS ival,`A`.hasvalue AS hval,`A`.elsejumpto AS ejmpto,`B`.id,`B`.name as label,`C`.name AS tname,
			     `B`.validateif_field AS viff,`B`.validateif_value AS vifv,`B`.validateif_isinverse AS vifi
			 FROM `pages_fields_validations` AS `A`
			 INNER JOIN `pages_fields` AS `B` ON `B`.id=`A`.field
			 INNER JOIN `cf_types` AS `C` ON `C`.id=`B`.type
			 INNER JOIN `pages_tabs` AS `D` ON `D`.id=`B`.tab
			 WHERE `A`.field IN ($_selectFields) AND `B`.ismandatory='1' AND `D`.pub='1' AND `D`.del='1' AND `B`.pub='1' AND `B`.del='1' AND `A`.pub='1' AND `A`.del='1' ORDER BY `D`.rank ASC,`B`.rank ASC,`A`.rank ASC");
	if(($rs_v) && ($rs_v->_numOfRows >= 1)) // --- VALIDATIONS
	{
		while (!$rs_v->EOF)
		{
			if(isset($rs_v->fields['viff']) && ($rs_v->fields['viff']>=1))
			{
				$fieldInfo = fieldInfo($rs_v->fields['viff']);//print_r($fieldInfo);echo $_POST[$rs_v->fields['viff']];
				if(isset($fieldInfo[2]) && ($fieldInfo[2]==3) && isset($fieldInfo[4]) && ($fieldInfo[4]==3)) // If Type = SELECT (3) & Select Type = LIST (3)
				{
					$field_val = (isset($_POST[$rs_v->fields['viff']]) && ($_POST[$rs_v->fields['viff']]>=1)) ? $_POST[$rs_v->fields['viff']] : 0;
					$rs_list = $db->Execute("SELECT name FROM `lists` WHERE `lists`.id='".$field_val."' AND pub='1' AND del='1' LIMIT 1");
					if(($rs_list) && ($rs_list->numRows() >= 1)){$_POST[$rs_v->fields['viff']] = $rs_list->fields['name'];}
					//echo $_POST[$rs_v->fields['viff']];
				}
				
				if(isset($_POST[$rs_v->fields['viff']]))
				{
					$isDependant = false;
					
					if(isset($rs_v->fields['vifv']) && (!empty($rs_v->fields['vifv'])))
					{
						$rangeArr = explode(";",$rs_v->fields['vifv']);
						if(isset($rangeArr) && is_array($rangeArr) && (sizeof($rangeArr) >= 1))
						{//print_r($rangeArr);echo $_POST[$rs_v->fields['viff']];
							if(in_array("NULL",$rangeArr) && ((!isset($_POST[$rs_v->fields['viff']])) || empty($_POST[$rs_v->fields['viff']])))
							{
								if((!isset($_POST[$rs_v->fields['viff']])) || empty($_POST[$rs_v->fields['viff']]))
								{
									$isDependant = true;
								}
							}
							else if(in_array($_POST[$rs_v->fields['viff']],$rangeArr) && (!empty($_POST[$rs_v->fields['viff']])))
							{//echo $_POST[$rs_v->fields['viff']];
								$isDependant = true;
							}
							
							if(isset($rs_v->fields['vifi']) && ($rs_v->fields['vifi']==1)){$isDependant=$isDependant?false:true;}
						}
					}
					
					if($isDependant)
					{
						if($isNotXML){list($status_v,$msg_v) = killValidation(array(array($rs_v->fields['label'],$rs_v->fields['id'],$rs_v->fields['tname'],$rs_v->fields['vname'],$rs_v->fields['vparam'],$rs_v->fields['hval'],$rs_v->fields['ival'])));}
						if($status_v==0){echo json_encode(array("status"=>$status_v,"desc"=>$msg_v,"data"=>$rs_v->fields['id'],"data_v"=>array($fieldNonVALID,$progressbarTOTAL,sizeof($_countArr))));exit;}
						else{$_countArr[$rs_v->fields['id']]=1;}
					}else{$_countArr[$rs_v->fields['id']]=1;}
				}
			}
			else
			{
				if($isNotXML){list($status_v,$msg_v) = killValidation(array(array($rs_v->fields['label'],$rs_v->fields['id'],$rs_v->fields['tname'],$rs_v->fields['vname'],$rs_v->fields['vparam'],$rs_v->fields['hval'],$rs_v->fields['ival'])));}
				// --- LATER LATER remove this to below code so drafting can work.
				if($status_v==0){echo json_encode(array("status"=>$status_v,"desc"=>$msg_v,"data"=>$rs_v->fields['id'],"data_v"=>array($fieldNonVALID,$progressbarTOTAL,sizeof($_countArr))));exit;}
				else{$_countArr[$rs_v->fields['id']]=1;}
			}
			
			$rs_v->MoveNext();
		}
	}
	
	if(isset($status_v) && ($status_v==1))
	{
		$_external = (isset($_POST['_external']) && ($_POST['_external']>=1)) ? ",`external`=".$_POST['_external'] : "";
		
		if(isset($_POST['isnew']) && ($_POST['isnew']>=1))
		{
			if($db->Execute("UPDATE `_mod_".$_POST['_p']."_draft` SET `date_update`=".$db->qstr(NOW()).$_updateFields.$_external." WHERE `_mod_".$_POST['_p']."_draft`.id='".$_POST['isnew']."'"))
			{
				$msg = "Successfully updated the following record: ".autoBuildRefNo($_POST['_p'])."-D-".$_POST['isnew']."..";
				
				$array = array("status"=>1,"desc"=>$msg,"data"=>$_POST['isnew'],"data_v"=>array($fieldNonVALID,$progressbarTOTAL,sizeof($_countArr)));
			}
		}
		else
		{
			$_LOGGER_ID = (isset($_POST['_herculesvb_usr']) && (is_numeric($_POST['_herculesvb_usr'])) && ($_POST['_herculesvb_usr']>=1)) ? $_POST['_herculesvb_usr'] : $_SESSION['accesses']->_login['id'];
			
			if($db->Execute("INSERT INTO `_mod_".$_POST['_p']."_draft` (`user`,`date`,`date_update`$_insertFields) VALUES (".$db->qstr($_LOGGER_ID).",".$db->qstr(NOW()).",".$db->qstr(NOW()).$_insertFieldsVals.");"))
			{
				$_id = $db->Insert_ID();$code = autoBuildRefNo($_POST['_p'])."-D-".$_id;//$code = $_POST['_pfx']."-D-".$_id;
				$old = umask(0);
				if(!is_dir(CONSUMER_ROOTPATH."docs_drafts/modules/".$_POST['_p']))
				{
					if(
					   mkdir(CONSUMER_ROOTPATH."docs_drafts/modules/".$_POST['_p'],0777)
					   &&
					   mkdir(CONSUMER_ROOTPATH."docs_drafts/modules/".$_POST['_p']."/".$_id,0777)
					   &&
					   mkdir(CONSUMER_ROOTPATH."docs_drafts/modules/".$_POST['_p']."/".$_id."/xml",0777)
					   ){}
				}
				else
				{
					if(mkdir(CONSUMER_ROOTPATH."docs_drafts/modules/".$_POST['_p']."/".$_id,0777) && mkdir(CONSUMER_ROOTPATH."docs_drafts/modules/".$_POST['_p']."/".$_id."/xml",0777)){}
				}
				umask($old);
				
				@$db->Execute("UPDATE `_mod_".$_POST['_p']."_draft` SET code=".$db->qstr($code).$_external." WHERE `_mod_".$_POST['_p']."_draft`.id='".$_id."' LIMIT 1");
				
				$msg = "Successfully added the following record: ".$code."..";
				
				$array = array("status"=>1,"desc"=>$msg,"data"=>$_id,"data_v"=>array($fieldNonVALID,$progressbarTOTAL,sizeof($_countArr)));
			}
		}
	}
	
	echo json_encode($array);
}
else {echo json_encode(array("status"=>0,"desc"=>"islogout","data"=>0,"data_v"=>array(0,0,0)));exit;}

?>