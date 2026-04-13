<?php
# FileName="ss_intlz.php"
# Type="MYSQL"

// ------------------------------- Sesesions intialization -----------------------------------

if (!isset($_SESSION))
{
  session_start();    
}

if (!isset($_SESSION['lgn']))
{
 $_SESSION['lgn'] = "N";  
}

if (!isset($_GET['p'])) {
		$_GET['p'] = 'home';				
	}
if (!isset($_GET['id'])) {
		$_GET['id'] = 0;				
	}	
if (!isset($_GET['numBegin'])) {
		$_GET['numBegin'] = 1;
	}
	
if (!isset($_GET['begin'])) {
		$_GET['begin'] = 0;
	}
	
if (!isset($_GET['num'])) {
		$_GET['num'] = 1;
	}
if (!isset($_GET['totalrows'])) {
		$_GET['totalrows'] = 0;
	}
if (!isset($_GET['delete'])) {
		$_GET['delete'] = 0;
	}
if (!isset($_GET['publish'])) {
		$_GET['publish'] = 0;
	}
if (!isset($_GET['content_id'])) {
		$_GET['content_id'] = 0;
	}
if (!isset($_GET['user_id'])) {
		$_GET['user_id'] = 0;
	}
if (!isset($_GET['publish'])) {
		$_GET['publish'] = 2;
	}
if (!isset($_GET['delete'])) {
		$_GET['delete'] = 0;
	}
if (!isset($_GET['action'])) {
		$_GET['action'] = 0;
	}
if (!isset($_GET['msg_id'])) {
		$_GET['msg_id'] = 0;
	}
if (!isset($_GET['sent_id'])) {
		$_GET['sent_id'] = 0;
	}
if (!isset($_GET['sent_name'])) {
		$_GET['sent_name'] = 0;
	}
if (!isset($_GET['mailto_id'])) {
		$_GET['mailto_id'] = 0;
	}
if (!isset($_GET['topic_id'])) {
		$_GET['topic_id'] = 0;
	}
if (!isset($_GET['topic_name'])) {
		$_GET['topic_name'] = 0;
	}	
if (!isset($_GET['artist_id'])) {
		$_GET['artist_id'] = 0;
	}
if (!isset($_GET['edit_id'])) {
		$_GET['edit_id'] = 0;
	}
if (!isset($_GET['table_name'])) {
		$_GET['table_name'] = "";
	}
if (!isset($_GET['item_id'])) {
		$_GET['item_id'] = 0;
	}
if (!isset($_GET['mn_id'])) {
		$_GET['mn_id'] = 0;
	}
if (!isset($_GET['bnnr_id'])) {
		$_GET['bnnr_id'] = 0;
	}
if (!isset($_GET['back_page'])) {
		$_GET['back_page'] = "";
	}
if (!isset($_GET['set_limit'])) {
		$_GET['set_limit'] = "";
	}	
if (!isset($_GET['keyword'])) {
		$_GET['keyword'] = "";
	}
if (!isset($_GET['view'])) {
		$_GET['view'] = "";
	}
if (!isset($_GET['web'])) {
		$_GET['web'] = 'public';				
	}
if (!isset($_GET['toid'])) {
		$_GET['toid'] = 0;
	}
if (!isset($_GET['fromid'])) {
		$_GET['fromid'] = 0;
	}
if (!isset($_GET['invite'])) {
		$_GET['invite'] = 0;
	}
if (!isset($_GET['plays'])) {
		$_GET['plays'] = 0;
	}
if (!isset($_GET['order_type'])) {
		$_GET['order_type'] = 1;
	}
if (!isset($_GET['view_order_by'])) {
		$_GET['view_order_by'] = 'date';
	}	
?>