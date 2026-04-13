<?

function sectionalContent($ruleNo = 1,$ruleName = "",$ruleStatus = "danger",$contentResults = "",$notice = "", $securityDomains = "")
{
	
	$badge_icon = "thumbs-up";$color = "4ea747";
	
	$badge_class = $ruleStatus;
	
	if($ruleStatus=="danger"){$badge_icon = "thumbs-down";$color = "dc4245";}
	else if($ruleStatus=="warning"){$badge_icon = "exclamation-triangle";$color = "f9c132";}
	else if($ruleStatus=="informational"){$badge_icon = "hand-point-right";$color = "6c757d";$badge_class = "secondary";}
	else{$badge_icon = "thumbs-up";$color = "4ea747";}
	
	return
	'
		<div id="branding-title-tag" style="padding:0% 1% 0% 1%;margin:0%;background-color:#fff;border-bottom:0px solid #ddd;">
			<table class="table table-borderless" style="margin:0%;font-size:95%;padding:0%;" border=0>
				<tr style="border-bottomx:1px solid #ededed;">
					<td style="width:22.5%;padding-left:0%;">
						<button type="button" class="btn btn-outline-secondary btn-sm" style="border-width:0px;font-weight:bold;"><span class="badge badge-'.$badge_class.'"><i class="fa fa-'.$badge_icon.'" style="font-size:130%;"></i></span>&nbsp;&nbsp;'.$ruleName.'</button>
						'.$securityDomains.'
					</td>
					<td id="'.$ruleNo.'-collapse_tigger" class="text-secondary -collapse_tigger" style="font-size:92.5%;line-height:175%;cursor:pointer;" data-toggle="collapse" data-target="#'.$ruleNo.'-collapse">
						'.$contentResults.'
					</td>
					<td id="'.$ruleNo.'-collapse_tigger-arrow" style="width:1%;font-size:95%;cursor:pointer;" class="text-secondary"><a class="text-reset fw-bold" data-toggle="collapse" data-target="#'.$ruleNo.'-collapse"><i class="fa fa-chevron-down" style="font-size:100%;"></i></a></td>
				</tr>
				<tr id="'.$ruleNo.'-collapse" class="collapse active">
					<td style="width:100%;font-size:87.5%;padding:1%;" class="text-secondary -collapse_tigger" colspan="3">
						<p class="col-md-12 img-thumbnail"  style="background-color:#f9f9f9;padding:1%;line-height:175%;border-left:4px solid #'.$color.';">
							'.$notice.'
						</p>
					</td>
				</tr>
			</table>
		</div>
		<script>
			jQuery("#'.$ruleNo.'-collapse_tigger").off("click");jQuery("#'.$ruleNo.'-collapse_tigger").unbind("click");
			jQuery(document).on("click","#'.$ruleNo.'-collapse_tigger",function(){jQuery("#'.$ruleNo.'-collapse").toggle();});
			jQuery(document).on("click","#'.$ruleNo.'-collapse_tigger-arrow",function(){jQuery("#'.$ruleNo.'-collapse").toggle();});
		</script>
	';
}

?>