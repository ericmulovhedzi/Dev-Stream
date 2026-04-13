<?
	
	if(isset($_POST['submit']))
	{
		
		//print_r($_POST);
		
	}
?>
<style>	 
	
</style>
<div class="" style='padding-bottom:0%;padding-top:0%;padding-bottom:1%;'>
	<div class="container">
		
		<div class="row" style='width:100%;padding:0% 0% 2.5% 0%;margin:0%;'>
			<div class="page-header" style="width:100%;padding:0%;text-align:center;">
				<h1 style="margin-top:4%;">Contact</h1>
				<p>We are always looking forward to hearing from you.</p>
			</div>
		</div>
		
		<div class="row" style='background:transparent;padding:0% 0% 2% 0%;'><!-- Beggining of container -->
			
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<div class="img-thumbnail">
					<div class="caption" style=";margin:0% 5% 0% 5%;text-align:center;">
						<h5 style='text-align:center;margin:7.5% 0% 10% 0%;'>Johannesburg</h5>
						<p style='font-size:100%;text-align:left;'>
							<strong>Email:</strong> oranky@spiderblackonline.co.za<br /><br />
							<strong>Tel:</strong> +27.87 701 5384<br /><br />
							<strong>Address:</strong> Unit A3 Halfway Gardens Office Park, 1 Asparagus Road, Halfway House, Midrand, 1685<br />
						</p>
					</div>
					<div class="caption" style="text-align:justify;margin:0%;text-align:center;">
						<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14344.100981583015!2d28.11604926999856!3d-25.99999039329365!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1e956e033aa0cbfb%3A0xc4471b38c305b8cb!2s1%20Asparagus%20Rd%2C%20Halfway%20Gardens%2C%20Midrand%2C%201686!5e0!3m2!1sen!2sza!4v1634569407556!5m2!1sen!2sza" style="border:0;width:100%;height:300px;" allowfullscreen="" loading="lazy"></iframe>
					</div>
				</div>
			</div>
			<div class="col-md-2"></div>
			
		</div><!-- End of container -->
		
	</div>
	
</div>
<style>	 
	@media (max-width:767px){.contact-clean {padding: 20px 0;}}
	.contact-clean form{width: 90%;margin: 0 auto;background-color: #ffffff;padding: 40px;border-radius: 4px;color: #505e6c;box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.1);}
	@media (max-width:767px){.contact-clean form {padding: 30px;}}
	.contact-clean h2 {margin-top: 5px;font-weight: bold;font-size: 28px;margin-bottom:36px;color:inherit;}
	.contact-clean .form-group:last-child {margin-bottom: 5px;}
	.contact-clean form .form-control {background: #fff;border-radius: 2px;box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.05);outline: none;color: inherit;padding-left: 12px;height: 42px;}
	.contact-clean form .form-control:focus {border: 1px solid #b2b2b2;}
	.contact-clean form textarea.form-control {min-height: 100px;max-height: 260px;padding-top: 10px;resize: vertical;resize: none;}
	.contact-clean form .btn {padding: 16px 32px;border: none;background: none;box-shadow: none;text-shadow: none;opacity: 0.9;text-transform: uppercase;font-weight: bold;font-size: 13px;letter-spacing: 0.4px;line-height: 1;outline: none !important;}
	.contact-clean form .btn:hover {opacity: 0.8;}
	.contact-clean form .btn:active {transform: translateY(1px);}
	.contact-clean form .btn-primary {background-color: #000000;margin-top: 15px;color: #fff;}
	
	select:not(.esg-sorting-select):not([class*="trx_addons_attrib_"]){visibility:visible;}
</style>
<!--<script src="https://www.google.com/recaptcha/api.js?render=6Lc-3twcAAAAAEfD1f0-1BxIVMgXc60ZgpVmfdq9"></script>
<script>
      function onClick(e) {
        e.preventDefault();
        grecaptcha.ready(function() {
          grecaptcha.execute('6Lc-3twcAAAAAEfD1f0-1BxIVMgXc60ZgpVmfdq9', {action: 'submit'}).then(function(token) {
              // Add your logic to submit to your backend server here.
          });
        });
      }
</script>-->
<div class="" style='background-color:#282935;padding-top:0%;padding-bottom:5%;'>
	<div class="container">
		
		<div class="row" style='width:100%;padding:0% 0% 3% 0%;margin:0%;color:#f0f0f0;'>
			<div class="page-header" style="width:100%;padding:0%;text-align:center;">
				<h2 style="margin-top:4%;">We would really like to here from you.</h2>
				<p>Please, send us a note and we will get back to you.</p>
			</div>
		</div>
		<div class="row" style='width:100%;padding:0% 0% 0% 0%;margin:0%;color:#f0f0f0;'>
			<div class="col-md-6">
				<center><img src="<? echo WWWROOT; ?>images/icons/email-icon.png" style="width:50%;margin-top:15.5%;" /></center>
			</div>
			<div class="col-md-6">
				<div class="contact-clean">
					<form id="contact-form" method="POST" action="<? echo WWWROOT; ?>?p=contacts" class="" >
						<h2 class="text-center">Please, drop us a note!</h2>
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-text"><i class="fa fa-user" style="color:#555;"></i></span>
								<input class="form-control" type="text" name="68" placeholder="Name" required>
							</div>
						</div>
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-text"><i class="fa fa-phone-alt" style="color:#555;"></i></span>
								<input class="form-control" type="tel" name="69" placeholder="Cell/Tel" required>
								<span class="input-group-text" style="color:#555;"><strong>@</strong></span>
								<input class="form-control" type="email" name="70" placeholder="Email" >
							</div>
						</div>
						<div class="form-group">
							<div class="input-group">
								<label for="sel1"><span class="input-group-text" style="height:112%;">Enquiry Type</span></label>
								<select class="form-control classic" name="71" id="sel1" style="">
									<option value="">Please Select..</option>
									<option value="152">I can't log a pothole</option>
									<option value="153">I want help with iMbobo App</option>
									<option value="157">I have a question about my membership</option>
									<option value="172">I want to setup a branch</option>
									<option value="173">I would like to be a sectional director</option>
									<option value="171">I want help with Legal</option>
									<option value="154">I want help with Records Management</option>
									<option value="155">I need help accessing the portal</option>
									<option value="156">I am a councillor, need training</option>
									<option value="158">I have a question about AARTO</option>
									<option value="159">I am a developer, have API integration problems</option>
									<option value="160">Other</option>
								</select>
							</div>
						</div>
						<div class="form-group"><textarea class="form-control" name="72" placeholder="Message" rows="14" required></textarea></div>
						<div class="form-group"><button id="btn-submit"  data-toggle="tooltip" title="Popover Header" data-placement="right" class="btn btn-primary g-recaptcha" data-sitekey="6Lc-3twcAAAAAEfD1f0-1BxIVMgXc60ZgpVmfdq9" data-callback='onSubmit' data-action='submit' name="submit" type="submit">submit</button></div>
					</form>
				</div>
			</div>
		</div>
		
		
  
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
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-modal">Close</button>
      </div>
    </div>

  </div>
</div>

<script>
	
	var OVH_CERTIFICATE = "TVRzN01URTdPekV4";
	
	$("#contact-form").submit(function( event )
	{
		var datastring = $("#contact-form").serialize();
		
		if(datastring != "")
		{
			datastring = "_c="+encodeURI(OVH_CERTIFICATE)+"&_d="+encodeURI(btoa(datastring))+"&_type=1";
			
			$("#btn-submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;&nbsp;Loading...');
			$.ajax(
			{
				type: "POST",
				url: "<? echo WWWROOT; ?>ajax/webforms.php",
				data: datastring,
				success: function(data)
				{
					data = JSON.parse(data);
					
					if(data.status == 1)
					{
						$(".modal-title").html("Success:");$(".modal-body").html('<div class="alert alert-success">'+data.description+'</div>');$("#myModal").modal();$("#btn-submit").html('submit');
						$('#contact-form')[0].reset();setTimeout(function(){$('html, body').animate({scrollTop:100},4000);},5000);
					}
					else
					{
						$(".modal-title").html("Error:");$(".modal-body").html('<div class="alert alert-danger">'+data.description+'</div>');$("#myModal").modal();$("#btn-submit").html('submit');
					}
				},
				error: function(jqXHR, textStatus, errorThrown)
				{
					$(".modal-title").html("Error:");$(".modal-body").html('<div class="alert alert-danger">Failed to load API.</div>');$("#myModal").modal();$("#btn-submit").html('submit');
				}
			});
			
			event.preventDefault();
		}
		else
		{
			$(".modal-title").html("Error:");$(".modal-body").html('<div class="alert alert-danger">No form data available.</div>');$("#myModal").modal();$("#btn-submit").html('submit');
			event.preventDefault();
		}
	});
	
</script>