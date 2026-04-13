<style>	 
	.navbar-nav{float:right;text-align:right;border:0px solid #f00;}
</style>
<header style="background:#e2cc00;border:0px solid #0f0;padding:0%;margin:0%;">
	
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="xmargin:24px 0;">
	<a class="navbar-brand" href="javascript:void(0)"><img class="logo_image" src="<? echo WWWROOT; ?>images/ovhcar-logo.png" srcset="<? echo WWWROOT; ?>images/ovhcar-logo.png 3x" alt="iMbobo App Logo"></a>
	<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navb">
		<span class="navbar-toggler-icon"></span>
	</button>
      
	<div class="collapse navbar-collapse" id="navb">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item">
				<a class="nav-link text-light" href="javascript:void(0)"><i class="fa fa-home" style="font-size:140%;"></i></a>
			</li>
			<li class="nav-item">
				<a class="nav-link text-light" href="javascript:void(0)">About</a>
			</li>
			<li class="nav-item">
				<a class="nav-link text-light" href="javascript:void(0)">Contacts</a>
			</li>
		</ul>
		
		<form class="form-inline my-2 my-lg-0">
			<!--
			<input class="form-control mr-sm-2" type="text" placeholder="Search">
			<button class="btn btn-success my-2 my-sm-0" type="button">Search</button>-->
			<ul class="navbar-nav mr-auto">
				 <!-- Dropdown -->
				<li class="nav-item dropdown">
					<a class="nav-link text-light dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
						Support
					</a>
					<div class="dropdown-menu">
					      <a class="dropdown-item" href="#"><i class="fa fa-life-ring" style="font-size:100%;"></i> &nbsp;&nbsp;Live Assistance</a>
					      <a class="dropdown-item" href="#"><i class="fa fa-question" style="font-size:100%;"></i> &nbsp;&nbsp;Help Center</a>
					      <a class="dropdown-item" style="border-top:1px solid #ddd;font-weight:bold;">Resources</a>
					      <a class="dropdown-item" href="#"><i class="fa fa-folder" style="font-size:100%;"></i> &nbsp;&nbsp;Guides</a>
					      <a class="dropdown-item" href="#"><i class="fa fa-blog" style="font-size:100%;"></i> &nbsp;&nbsp;FAQs</a>
					</div>
				</li>
				<li class="nav-item">
					<a class="nav-link text-light" href="javascript:void(0)"><i class="fa fa-user-circle" style="font-size:100%;"></i> &nbsp;&nbsp;My Account</a>
				</li>
			</ul>
		</form>
	</div>
</nav>
</header>