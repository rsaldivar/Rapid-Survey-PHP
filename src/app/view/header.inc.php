<!DOCTYPE html>
<html lang="en">
<head>
<!-- Meta, title, CSS, favicons, etc. -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<title>RAPID SURVEY</title>
<!-- Bootstrap core CSS -->
<link href="<?php echo SITE_URL;?>app/view/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
<!-- Documentation extras -->
<link href="<?php echo SITE_URL;?>app/view/bootstrap/assets/css/docs.css" rel="stylesheet">
<link href="<?php echo SITE_URL;?>app/view/bootstrap/assets/css/pygments-manni.css" rel="stylesheet">
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="<?php echo SITE_URL;?>app/view/bootstrap/assets/js/html5shiv.js"></script>
  <script src="<?php echo SITE_URL;?>app/view/bootstrap/assets/js/respond.min.js"></script>
<![endif]-->
<script>
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-146052-10']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL;?>app/view/js/source/jquery-1.8.2.min.js"></script>
<script src="<?php echo SITE_URL;?>app/view/bootstrap/assets/js/respond.min.js"></script>
<script src="<?php echo SITE_URL;?>app/view/bootstrap/js/tab.js"></script>
<script src="<?php echo SITE_URL;?>app/view/js/jquery.validate.js"></script>
<script src="<?php echo SITE_URL;?>app/view/js/jquery.tablednd_0_5.js"></script>
<link href="<?php echo SITE_URL;?>app/view/css/style.css" rel="stylesheet">
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script> $(function() {   $( ".datepicker" ).datepicker({ dateFormat: "yy-mm-dd"});  });</script>


<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL;?>app/view/js/source/jquery.fancybox.css?v=2.1.2" media="screen" />
</head>
<body>
		
<a class="sr-only" href="#content">Skip navigation</a>
<!-- Docs master nav -->
<header class="navbar navbar-inverse navbar-fixed-top bs-docs-nav" role="banner">
<div class="container">
  <div class="row">
    <div class="navbar-header">
      <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a href="<?php echo SITE_URL;?>" class="navbar-brand logo"><img src="<?php echo SITE_URL;?>app/view/img/logo.png" title="Showcase Client Management" alt="Showcase Client Management" height="45px"></a> </div>
    <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
    <?php if($_SESSION["SESS_USER_USERNAME"] !='') { ?>
    <ul class="nav navbar-nav pull-right">
		<?php 
		if($_SESSION["SESS_USER_ROLE"] == 'administrador')
		{
		?>
		<li> <a href="<?php echo $this->buildUrl('customer'); ?>"><span class="glyphicon glyphicon-credit-card"></span> Customer Management</a> </li>
		<?php
		}
		if($_SESSION["SESS_USER_ROLE"] == 'cliente' )
		{
		?>
		<li> <a href="<?php echo $this->buildUrl('customer'); ?>"><span class="glyphicon glyphicon-user"></span> My Account</a> </li>
		<?php
		}
		
		if($_SESSION["SESS_USER_ROLE"] == 'cliente' || $_SESSION["SESS_USER_ROLE"] == 'Project Manager' || $_SESSION["SESS_USER_ROLE"] == 'administrador')
		{
		?>
		<!--li> <a href="<?php echo $this->buildUrl('pending/surveys'); ?>"><span class="glyphicon glyphicon-list"></span> Pending surveys</a> </li-->
		<li> <a href="<?php echo $this->buildUrl('customer/surveys'); ?>"><span class="glyphicon glyphicon-list"></span> Survey Management</a> </li>
		<?php
		}
		if($_SESSION["SESS_USER_ROLE"] == 'administrador')
		{
		?>
		<li> <a href="<?php echo $this->buildUrl('user'); ?>"><span class="glyphicon glyphicon-user"></span> User Management</a> </li>
		<?php
		}
		?>
		<li> <a href="<?php echo $this->buildUrl('home/change-password'); ?>"><span class="glyphicon glyphicon-lock" ></span> Change Password</a> </li>
		<li> <a href="<?php echo $this->buildUrl('home/logout'); ?>"><span class="glyphicon glyphicon-home"></span> Logout</a> </li>
    </ul>
    <?php } ?>
    </nav> </div>
</div>
</header>