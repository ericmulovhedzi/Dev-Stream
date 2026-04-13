<?

header('Access-Control-Allow-Origin: *');

?>
<style>	 
	a:hover{text-decoration:none;}.card{border-width:0px;margin-top:1px;border-left-width:1px;border-right-width:1px;}.card-link{color:#333;font-weightx:bold;}
	
	.circular{height:100px;width: 100px;position: relative;transform:scale(2);}
	.circular .inner{position: absolute;z-index: 6;top: 50%;left: 50%;height: 80px;width: 80px;margin: -40px 0 0 -40px;background: #f9f9f9;border-radius: 100%;}
	.circular .number{position: absolute;top:50%;left:50%;transform: translate(-50%, -50%);z-index:10;font-size:38px;font-weight:800;color:#dc4245;}
	.circular .bar{position: absolute;height: 100%;width: 100%;background: #fff;-webkit-border-radius: 100%;clip: rect(0px, 100px, 100px, 50px);}
	.circle .bar .progress{position: absolute;height: 100%;width: 100%;-webkit-border-radius: 100%;clip: rect(0px, 50px, 100px, 0px);background: #5f5f5f;}
	.circle .left .progress{z-index:1;animation: left 4s linear both;}
	@keyframes left{100%{transform: rotate(180deg);}}
	.circle .right {transform: rotate(180deg);z-index:3;}
	.circle .right .progress{animation: right 4s linear both;animation-delay:4s;}
	@keyframes right{100%{transform: rotate(180deg);}}
	

</style>

<div class="" style='padding-bottom:0%;padding-top:0%;padding-bottom:1%;margin:0px;border:0px solid #f00;'>
	<div class="">
		
		<div class="row" style='background:transparent;padding:0% 0% 5% 0%;'><!-- Beggining of container -->
		
			<div class="col-md-2" style="background-color:#fafafa;border-right:1px solid #eee;padding:0%;height:100vh;">
				<div class="img-thumbnailx" style="padding:10% 0% 0% 0%;width:100%;">
					<h6 style='width:100%;color:#333;margin:0%;padding:0% 0% 4% 0%;border-bottom:1px solid #ddd;font-size:100%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="text-reset fw-bold" href="#"><span style='color:#555;'><i class="fa fa-house-user" style=""></i>&nbsp;&nbsp;&nbsp;Dashoboard</span></a></h6>
					<h6 style='width:100%;color:#333;margin:0%;padding:6% 0% 3% 0%;border-bottom:0px solid #ddd;font-size:90%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="text-reset fw-bold" href="#"><span style='color:#555;'><i class="fa fa-globe-africa" style="font-size:120%;"></i>&nbsp;&nbsp;Optimize a Site</span></a></h6>
					<h6 style='width:100%;color:#333;margin:0%;padding:3% 0% 3% 0%;border-bottom:0px solid #ddd;font-size:90%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="text-reset fw-bold" href="#"><span style='color:#555;'><i class="fa fa-poll" style="font-size:120%;"></i>&nbsp;&nbsp;Market Analysis</span></a></h6>
					<h6 style='width:100%;color:#333;margin:0%;padding:3% 0% 3% 0%;border-bottom:0px solid #ddd;font-size:90%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="text-reset fw-bold" href="#"><span style='color:#555;'><i class="fa fa-bookmark" style="font-size:120%;"></i>&nbsp;&nbsp;Keyword Tool</span></a></h6>
				</div>
			</div>
			<div class="col-md-8" style="float:left;background-color:#f9f9f9;">
				<div style="padding:3% 0% 1% 1%;">
					
					<p>
						
						<div class="input-group mb-3">
							<input id="_url" type="text" class="form-control" autocomplete="on" placeholder="Search">
							<div class="input-group-append">
								<button class="btn btn-light" type="button" style="width:50px;background-color:#eee;border:1px solid #d0d0d0;"><img src="<? echo WWWROOT; ?>images/loading_1.gif" id="img-search" style="float:right;display:none;margin-top:0px;margin-right:6px;" /></button>
								<button id="_search" class="btn btn-success" type="submit">Go</button>
							</div>
						</div>
					</p>
					<p id="site-overview" style="background-color:#fff;">
					
						<div class="col-md-4 img-thumbnailx" style="border:0px solid #ccc;float:left;padding:1%;background-color:#fff;">
							<center><img style="width:100%;" src="<? echo WWWROOT; ?>images/generic/coding-ide.jpg" /></center>
							<!--<center><embed type="text/html" src="http://spiderblackonline.co.za/" width="100%" height="100%" allowfullscreen /></center>-->
						</div>
						<div class="col-md-4 img-thumbnailx" style="border:0px solid #ccc;float:left;padding:0.5% 1% 1% 1%;background-color:#fff;">
							<h6 style="font-size:130%;text-align:left;"><a id="website_url" target="_blank" class="text-seconday" href="#">Website URL</a></h6>
							<p style="text-align:center;">
								<!-- Red -->
								<!--<span class="input-group-text" style="font-size:90%;width:40%;"><i class="fa fa-user" style="color:#555;">&nbsp;&nbsp;</i>Errors:</span>-->
								<div class="progress" style="width:100%;height:24px;float:left;margin-top:2.5%;">
									<div id="id-progress-bar-bg-danger" class="progress-bar bg-danger" style="width:25%;height:24px;">0%</div>
								</div>
								<div class="progress" style="width:100%;height:24px;float:left;margin-top:2.5%;">
									<div  id="id-progress-bar-bg-warning"class="progress-bar bg-warning" style="width:25%;height:24px;">0%</div>
								</div>
								<div class="progress" style="width:100%;height:24px;float:left;margin-top:2.5%;">
									<div id="id-progress-bar-bg-success" class="progress-bar bg-success" style="width:25%;height:24px;">0%</div>
								</div>
							</p>
						</div>
						<div class="col-md-4 img-thumbnail" style="border:0px solid #ccc;float:left;background-color:#f7f7f7;padding:6% 1% 1% 1%;">
							<center>
								<div class="circular">
									<div class="inner"></div>
									<div class="number"></div>
									<div class="circle">
										<div class="bar left">
											<div class="progress progress-bar"></div>
										</div>
										<div class="bar right">
											<div class="progress progress-bar"></div>
										</div>
									</div>
								</div>
							</center>
						</div>
					</p>
					<p style="clear:both;display:block;text-align:right;border-top:1px solid #ccc;padding:0%;">
						<img src="<? echo WWWROOT; ?>images/loading_1.gif" id="img-search" style="float:right;display:none;margin-top:0px;margin-right:6px;" />
						<form name="download-search" class="form-inline my-2 my-lg-0" style="float:right;margin:0%;padding-bottom:1%;padding-top:0%;">
							<button id="_download_btn" class="btn btn-light  btn-sm" style="float:right;border:1px solid #ccc;margin-top:0%;" type="button"><i class="fa fa-file-pdf" style="font-size:100%;"></i> &nbsp;&nbsp;Download PDF</button>
						</form>
					</p>
					<p style="clear:both;display:block;text-align:left;border-top:1px solid #ccc;padding-top:1%;">
						<button type="button" class="btn btn-outline-secondary btn-sm" style="border:1px solid #ccc;"><span id="id-badge-badge-danger" class="badge badge-danger" style="font-size:12px;">0</span> Errors</button>
						<button type="button" class="btn btn-outline-secondary btn-sm" style="border:1px solid #ccc;"><span id="id-badge-badge-warning" class="badge badge-warning" style="font-size:12px;">0</span> Require Improvement</button>
						<button type="button" class="btn btn-outline-secondary btn-sm" style="border:1px solid #ccc;"><span id="id-badge-badge-success" class="badge badge-success" style="font-size:12px;">0</span> Passed</button>
						<button type="button" class="btn btn-outline-secondary btn-sm" style="border:1px solid #ccc;"><span id="id-badge-badge-informational" class="badge badge-secondary" style="font-size:12px;">0</span> Informational</button>
					</p>
				</div>
	
	
	
				<div style="padding:1% 0% 1% 1%;"><h3 style="text-align:left;font-weight:normal;">Branding</h3></div> <!-- Branging -->
				<div id="branding_url"style="padding:1%;background-color:#fff;border-bottom:1px solid #eee;"></div>
				<div id="branding_favicon"style="padding:1%;background-color:#fff;border-bottom:1px solid #eee;"></div>
				<div id="branding_custom_404_page"style="padding:1%;background-color:#fff;border-bottom:1px solid #eee;"></div>
				
				<div style="padding:1% 0% 1% 1%;"><h3 style="text-align:left;font-weight:normal;">Key meta tags</h3></div> <!-- Key Meta Tags -->
				<div id="key_meta_tag_title_tag"style="padding:1%;background-color:#fff;border-bottom:1px solid #eee;"></div>
				<div id="key_meta_tag_meta_description_tag"style="padding:1%;background-color:#fff;border-bottom:1px solid #eee;"></div>
				
				<div style="padding:1% 0% 1% 1%;"><h3 style="text-align:left;font-weight:normal;">Content Analysis</h3></div> <!-- Content Analysis -->
				<div id="content_analysis_language"style="padding:1%;background-color:#fff;border-bottom:1px solid #eee;"></div>
				<div id="content_html_headings"style="padding:1%;background-color:#fff;border-bottom:1px solid #eee;"></div>
				<div id="content_html_in_page_links"style="padding:1%;background-color:#fff;border-bottom:1px solid #eee;"></div>
				<div id="content_nlp"style="padding:1%;background-color:#fff;border-bottom:1px solid #eee;"></div>
			
				<div style="padding:1% 0% 1% 1%;"><h3 style="text-align:left;font-weight:normal;">Indexing</h3></div> <!-- Indexing -->
				<div id="indexing_url_resolve"style="padding:1%;background-color:#fff;border-bottom:1px solid #eee;"></div>
				<div id="indexing_url_underscores"style="padding:1%;background-color:#fff;border-bottom:1px solid #eee;"></div>
				<div id="indexing_robots_txt"style="padding:1%;background-color:#fff;border-bottom:1px solid #eee;"></div>
			
				<div style="padding:1% 0% 1% 1%;"><h3 style="text-align:left;font-weight:normal;">Technologies</h3></div> <!-- Technologies-->
				<div id="technologies_server_uptime"style="padding:1%;background-color:#fff;border-bottom:1px solid #eee;"></div>
				<div id="technologies_server_ip_address"style="padding:1%;background-color:#fff;border-bottom:1px solid #eee;"></div>
				<div id="technologies_doctype"style="padding:1%;background-color:#fff;border-bottom:1px solid #eee;"></div>
				<div id="technologies_encoding"style="padding:1%;background-color:#fff;border-bottom:1px solid #eee;"></div>
			
				<div style="padding:1% 0% 1% 1%;"><h3 style="text-align:left;font-weight:normal;">Security</h3></div> <!-- Security -->
				<div id="security_ssl_security"style="padding:1%;background-color:#fff;border-bottom:1px solid #eee;"></div>
				<div id="security_mixed_content"style="padding:1%;background-color:#fff;border-bottom:1px solid #eee;"></div>
				<div id="security_relative_urls"style="padding:1%;background-color:#fff;border-bottom:1px solid #eee;"></div>
				<div id="security_post_covid19_cyber_security"style="padding:1%;background-color:#fff;border-bottom:1px solid #eee;"></div>
				<div id="security_information_security_policy"style="padding:1%;background-color:#fff;border-bottom:1px solid #eee;"></div>
				
				<div style="padding:1% 0% 1% 1%;"><h3 style="text-align:left;font-weight:normal;">Traffic</h3></div> <!-- Traffic -->
				<div id="traffic_traffic_rank"style="padding:1%;background-color:#fff;border-bottom:1px solid #eee;"></div>
				
				
				
				
			</div>
			<div class="col-md-2" style="background-colorx:#eee;border-right:1px solid #eee;">
				<div class="img-thumbnailx" style="padding:7.5% 1% 1% 1%;">
					<h6 style='width:100%;color:#333;margin:0%;padding:15% 0% 4% 0%;border-bottom:1px solid #ddd;font-size:90%;font-weight:bold;color:#666;'>&nbsp;&nbsp;&nbsp;PAGE SECTIONS</h6>
					
					<h6 style='width:100%;color:#333;margin:0%;padding:6% 0% 3% 0%;border-bottom:0px solid #ddd;font-size:82.5%;'>&nbsp;&nbsp;&nbsp;<span style='color:#bbb;'>Optimization</span></h6>
					<h6 style='width:100%;color:#333;margin:0%;padding:3% 0% 3% 0%;border-bottom:0px solid #ddd;font-size:82.5%;'>&nbsp;&nbsp;&nbsp;<a class="text-reset fw-bold" href="#"><span style='color:#888;'><i class="fa fa-angle-right"></i>&nbsp;&nbsp;Branding</span></a></h6>
					<h6 style='width:100%;color:#333;margin:0%;padding:3% 0% 3% 0%;border-bottom:0px solid #ddd;font-size:82.5%;'>&nbsp;&nbsp;&nbsp;<a class="text-reset fw-bold" href="#"><span style='color:#888;'><i class="fa fa-angle-right"></i>&nbsp;&nbsp;Key Meta Tags</span></a></h6>
					<h6 style='width:100%;color:#333;margin:0%;padding:3% 0% 3% 0%;border-bottom:0px solid #ddd;font-size:82.5%;'>&nbsp;&nbsp;&nbsp;<a class="text-reset fw-bold" href="#"><span style='color:#888;'><i class="fa fa-angle-right"></i>&nbsp;&nbsp;Content Analysis</span></a></h6>
					<h6 style='width:100%;color:#333;margin:0%;padding:3% 0% 3% 0%;border-bottom:0px solid #ddd;font-size:82.5%;'>&nbsp;&nbsp;&nbsp;<a class="text-reset fw-bold" href="#"><span style='color:#888;'><i class="fa fa-angle-right"></i>&nbsp;&nbsp;Indexing</span></a></h6>
					<h6 style='width:100%;color:#333;margin:0%;padding:3% 0% 3% 0%;border-bottom:0px solid #ddd;font-size:82.5%;'>&nbsp;&nbsp;&nbsp;<a class="text-reset fw-bold" href="#"><span style='color:#888;'><i class="fa fa-angle-right"></i>&nbsp;&nbsp;Mobile Friendly</span></a></h6>
					<h6 style='width:100%;color:#333;margin:0%;padding:6% 0% 3% 0%;border-bottom:0px solid #ddd;font-size:82.5%;'>&nbsp;&nbsp;&nbsp;<span style='color:#bbb;'>Technical</span></h6>
					<h6 style='width:100%;color:#333;margin:0%;padding:3% 0% 3% 0%;border-bottom:0px solid #ddd;font-size:82.5%;'>&nbsp;&nbsp;&nbsp;<a class="text-reset fw-bold" href="#"><span style='color:#888;'><i class="fa fa-angle-right"></i>&nbsp;&nbsp;Security</span></a></h6>
					<h6 style='width:100%;color:#333;margin:0%;padding:3% 0% 3% 0%;border-bottom:0px solid #ddd;font-size:82.5%;'>&nbsp;&nbsp;&nbsp;<a class="text-reset fw-bold" href="#"><span style='color:#888;'><i class="fa fa-angle-right"></i>&nbsp;&nbsp;Performance</span></a></h6>
					<h6 style='width:100%;color:#333;margin:0%;padding:3% 0% 3% 0%;border-bottom:0px solid #ddd;font-size:82.5%;'>&nbsp;&nbsp;&nbsp;<a class="text-reset fw-bold" href="#"><span style='color:#888;'><i class="fa fa-angle-right"></i>&nbsp;&nbsp;Technologies</span></a></h6>
					<h6 style='width:100%;color:#333;margin:0%;padding:3% 0% 3% 0%;border-bottom:0px solid #ddd;font-size:82.5%;'>&nbsp;&nbsp;&nbsp;<a class="text-reset fw-bold" href="#"><span style='color:#888;'><i class="fa fa-angle-right"></i>&nbsp;&nbsp;Domain</span></a></h6>
					
					<h6 style='width:100%;color:#333;margin:0%;padding:6% 0% 3% 0%;border-bottom:0px solid #ddd;font-size:82.5%;'>&nbsp;&nbsp;&nbsp;<span style='color:#bbb;'>Organizational Context</span></h6>
					<h6 style='width:100%;color:#333;margin:0%;padding:3% 0% 3% 0%;border-bottom:0px solid #ddd;font-size:82.5%;'>&nbsp;&nbsp;&nbsp;<a class="text-reset fw-bold" href="#"><span style='color:#888;'><i class="fa fa-angle-right"></i>&nbsp;&nbsp;Mission or Mandate</span></a></h6>
					<h6 style='width:100%;color:#333;margin:0%;padding:3% 0% 3% 0%;border-bottom:0px solid #ddd;font-size:82.5%;'>&nbsp;&nbsp;&nbsp;<a class="text-reset fw-bold" href="#"><span style='color:#888;'><i class="fa fa-angle-right"></i>&nbsp;&nbsp;Strategy</span></a></h6>
					<h6 style='width:100%;color:#333;margin:0%;padding:3% 0% 3% 0%;border-bottom:0px solid #ddd;font-size:82.5%;'>&nbsp;&nbsp;&nbsp;<a class="text-reset fw-bold" href="#"><span style='color:#888;'><i class="fa fa-angle-right"></i>&nbsp;&nbsp;SDGs</span></a></h6>
					
					<h6 style='width:100%;color:#333;margin:0%;padding:6% 0% 3% 0%;border-bottom:0px solid #ddd;font-size:82.5%;'>&nbsp;&nbsp;&nbsp;<span style='color:#bbb;'>External</span></h6>
					<h6 style='width:100%;color:#333;margin:0%;padding:3% 0% 3% 0%;border-bottom:0px solid #ddd;font-size:82.5%;'>&nbsp;&nbsp;&nbsp;<a class="text-reset fw-bold" href="#"><span style='color:#888;'><i class="fa fa-angle-right"></i>&nbsp;&nbsp;Traffic</span></a></h6>
					<h6 style='width:100%;color:#333;margin:0%;padding:3% 0% 3% 0%;border-bottom:0px solid #ddd;font-size:82.5%;'>&nbsp;&nbsp;&nbsp;<a class="text-reset fw-bold" href="#"><span style='color:#888;'><i class="fa fa-angle-right"></i>&nbsp;&nbsp;Backlinks</span></a></h6>
					<h6 style='width:100%;color:#333;margin:0%;padding:3% 0% 3% 0%;border-bottom:0px solid #ddd;font-size:82.5%;'>&nbsp;&nbsp;&nbsp;<a class="text-reset fw-bold" href="#"><span style='color:#888;'><i class="fa fa-angle-right"></i>&nbsp;&nbsp;Social Media</span></a></h6>
					
				</div>
			</div>
			
		</div><!-- End of container -->
	</div>	
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" style="margin-top:0%;">Notice:</h6>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-modal">Close</button>
      </div>
    </div>

  </div>
</div>

<script>
const numb = document.querySelector(".number");
let counter = 0;
//setInterval(() => {if(counter == 10 ){clearInterval();}else{counter+=1;numb.textContent = counter + "%";}}, 80);
//numb.textContent = 45 + "";//progress
numb.textContent  = 0 +"";
var searchImage = "<div class='img-thumbnail' style='padding:1% 2.5% 2.5% 2.5%;'><img src='<? echo WWWROOT; ?>images/loading_1.gif' style='float:left;margin-top:0px;' /></div>";
function clearSections()
{
	$('#website_url').html(searchImage);
	$('#branding_url').html(searchImage);
	$('#branding_favicon').html(searchImage);
	$('#branding_custom_404_page').html(searchImage);
	
	$('#key_meta_tag_title_tag').html(searchImage);
	$('#key_meta_tag_meta_description_tag').html(searchImage);
	
	$('#traffic_traffic_rank').html(searchImage);
	
	$('#content_analysis_language').html(searchImage);$('#content_html_headings').html(searchImage);$('#content_nlp').html(searchImage);$('#content_html_in_page_links').html(searchImage);
	
	$('#indexing_url_resolve').html(searchImage);$('#indexing_url_underscores').html(searchImage);$('#indexing_robots_txt').html(searchImage);
	
	$('#technologies_server_uptime').html(searchImage);$('#technologies_server_ip_address').html(searchImage);$('#technologies_doctype').html(searchImage);$('#technologies_encoding').html(searchImage);
	
	$('#security_ssl_security').html(searchImage);$('#security_mixed_content').html(searchImage);$('#security_relative_urls').html(searchImage);$('#security_post_covid19_cyber_security').html(searchImage);$('#security_information_security_policy').html(searchImage);

	// --- Badges
	
	$('#id-badge-badge-success').html("0");$('#id-badge-badge-danger').html("0");$('#id-badge-badge-warning').html("0");$('#id-badge-badge-informational').html("0");
	
	// --- Progress Bars
	
	$('#id-progress-bar-bg-success').html("0%").css({"width": "10%"});$('#id-progress-bar-bg-warning').html("0%").css({"width": "10%"});$('#id-progress-bar-bg-danger').html("0%").css({"width": "10%"});
	
	numb.textContent =  "%";
}

function retriever()
{
	$("#webviewer").css({'width':'0px','height':'0px'});$('#_url').css({'background-color':'#fefefe'});
	if((typeof $('#_url') === 'undefined') || ($('#_url').val()==""))
	{
		$('#_url').css({'background-color':'#FBE3E4'});
	}
	else
	{
		$('#img-search').show();clearSections();$('#_url').css({'background-color':'#fefefe'});
		
		var _url = ($.trim($('#_url').val()).replace("view-source:https://","").replace("view-source:http://","").replace("http://","")).replace("https://","").toLowerCase();
		//var _url = $.trim($('#_url').val()).replace("view-source:https://","").replace("view-source:http://","");
		
		//$('#rs-header').html("RESULTS: <span style='color:#DB0000;'>"+_url+"</span>");
		//$("#webviewer").attr('src',"");
		
		/*$.ajax({url:"http://"+$.trim($('#_url').val()),data:"p=1",success: function(data)*/
		
		// --- Stage 1: Assign URL name
			
			$('#website_url').html(_url);
			$('#website_url').attr('href','http://'+_url);
		
		// --- Stage X: Assign URL name
		
		$('#img-import').hide();$('#img-search').show();numb.textContent = "oo";
		$.ajax({url:"<? echo WWWROOT; ?>engine/searchEngine.php",timeout: 60000,data:"_page="+encodeURI(_url),
		       success: function(data)
			{
				data = JSON.parse(data);
				
				if(data.rules.time_out > 0)
				{
					//$(".modal-title").html("Error:");
					$('#img-search').hide();numb.textContent = "0";
					$(".modal-body").html('<div class="alert alert-danger">Your website is either not reachable or it\'s session has timed out.</div>');$("#myModal").modal();/*$("#btn-submit").html('submit');*/	
				}
				else
				{
					$('#branding_url').html(data.rules.branding_url);
					$('#branding_favicon').html(data.rules.branding_favicon);
					$('#branding_custom_404_page').html(data.rules.branding_custom_404_page);
					
					$('#key_meta_tag_title_tag').html(data.rules.key_meta_tag_title_tag);
					$('#key_meta_tag_meta_description_tag').html(data.rules.key_meta_tag_meta_description_tag);
					
					$('#content_analysis_language').html(data.rules.content_analysis_language);
					$('#content_html_headings').html(data.rules.content_html_headings);
					$('#content_html_in_page_links').html(data.rules.content_html_in_page_links);
					$('#content_nlp').html(data.rules.content_nlp);
					
					$('#indexing_url_resolve').html(data.rules.indexing_url_resolve);
					$('#indexing_url_underscores').html(data.rules.indexing_url_underscores);
					$('#indexing_robots_txt').html(data.rules.indexing_robots_txt);
					
					$('#technologies_server_uptime').html(data.rules.technologies_server_uptime);
					$('#technologies_server_ip_address').html(data.rules.technologies_server_ip_address);
					$('#technologies_doctype').html(data.rules.technologies_doctype);
					$('#technologies_encoding').html(data.rules.technologies_encoding);
					
					$('#security_ssl_security').html(data.rules.security_ssl_security);
					$('#security_mixed_content').html(data.rules.security_mixed_content);
					$('#security_relative_urls').html(data.rules.security_relative_urls);
					$('#security_post_covid19_cyber_security').html(data.rules.security_post_covid19_cyber_security);
					$('#security_information_security_policy').html(data.rules.security_information_security_policy);
					
					$('#traffic_traffic_rank').html(data.rules.traffic_traffic_rank);
					
					$('#id-badge-badge-success').html(data.progress.success);
					$('#id-badge-badge-danger').html(data.progress.danger);
					$('#id-badge-badge-warning').html(data.progress.warning);
					$('#id-badge-badge-informational').html(data.progress.informational);
					
					var _TOTAL = data.progress.success + data.progress.danger + data.progress.warning;
					
					$('#id-progress-bar-bg-success').html(((100*data.progress.success)/_TOTAL).toFixed(2)+"%").css({"width": ((100*data.progress.success)/_TOTAL).toFixed(2)+"%"});
					$('#id-progress-bar-bg-warning').html(((100*data.progress.warning)/_TOTAL).toFixed(2)+"%").css({"width": ((100*data.progress.warning)/_TOTAL).toFixed(2)+"%"});
					$('#id-progress-bar-bg-danger').html(((100*data.progress.danger)/_TOTAL).toFixed(2)+"%").css({"width": ((100*data.progress.danger)/_TOTAL).toFixed(2)+"%"});
					
					passRate = ((100*data.progress.success)/_TOTAL).toFixed(0);
					counter = 0;
					setInterval(() =>
					{
						if(passRate >= 65) // -- SUCCESS = 2
						{
							$('.progress-bar').css({"background-color":"#4fa747"});
							$('.number').css({"color":"#4fa747"});
						}
						else if(passRate >= 47.50) // -- WARNING = 1
						{
							$('.progress-bar').css({"background-color":"#f9c132"});
							$('.number').css({"color":"#f9c132"});
						}
						else // -- DANGER = 0
						{
							$('.progress-bar').css({"background-color":"#dc4245"});
							$('.number').css({"color":"#dc4245"});
						}
						
						if(counter == passRate )
						{clearInterval();}
						else
						{
							counter+=1;numb.textContent = counter + "";
						}
					}, 95);
				}	
				$('#img-search').hide();
			},
			error: function(xhr, status, error)
			{
				$('#img-search').hide();numb.textContent = "0%";
			}
		});
	}
	$(".tr-results" ).remove();
}

$('#_search').click(function(){retriever();});

$('#_download_btn').click(function()
{
	if((typeof $('#_url') === 'undefined') || ($('#_url').val()==""))
	{
		$('#_url').css({'background-color':'#FBE3E4'});
	}
	else
	{
		//window.open("http://oranky.com/exports/pdf/dataobjects/_seo_report.php?_page="+$('#_url').val());
		window.open("https://hrdcsaqa.net/oranky/exports/pdf/dataobjects/_seo_report.php?_page="+$('#_url').val());
	}
});

</script>