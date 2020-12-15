<?php 
	session_start();
	if (!isset($_SESSION['user_is_logged']) OR !$_SESSION['user_is_logged']){
		header('Location:index.php');
	}
	if (!isset($_SESSION['user_is_admin']) OR !$_SESSION['user_is_admin']){
		header('Location:index.php');
	}
	// Defining screen variables
		$app_message = '';
		$app_screen = 'administration';
		if (!isset($_SESSION['tournament'])){$_SESSION['tournament'] = -1;}
	// Including library files
		include('lib/app_lib.php');
		//include('lib/app_lib_administration.php');
		include('lib/cmn_lib.php');
		include('lib/cmn_lib_administration.php');
		include('lib/trn_lib.php');
		include('lib/trn_lib_administration.php');
	// Including processing files
		include('pro/app_pro_administration.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
        <meta charset="utf-8" />
		<!--[if lt IE 9]>
			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<title>Rezultz | Les Bons Pronos entre Amis</title>
		<link rel="stylesheet" media="screen" type="text/css" title="Design" href="style/app_body.css"/>
		<link rel="stylesheet" media="screen" type="text/css" title="Design" href="style/app_top_nav.css"/>
		<link rel="stylesheet" media="screen" type="text/css" title="Design" href="style/app_navigators.css"/>
		<link rel="stylesheet" media="screen" type="text/css" title="Design" href="style/app_forms.css"/>
		<link rel="stylesheet" media="screen" type="text/css" title="Design" href="style/trn_contents.css"/>
		<link rel="stylesheet" media="screen" type="text/css" title="Design" href="style/app_tables.css"/>
		<link rel="stylesheet" media="screen" type="text/css" title="Design" href="style/app_links.css"/>
		<link rel="icon" href="favicon.png"/>
		</script>
	</head>
	<body>
		<div id="main">
			<?php
				include('dsp/app_navigator.php');
			?>
			<div id="top">
			<?php
				include('dsp/app_top.php');
			?>
			</div>
			<div id="content">
				<?php
						include('dsp/app_dsp_administration.php');
				?>
			</div>
			<div id="footer">
			</div>
		</div>
	</body>
	<script type="text/javascript" src="script/app_common.js"></script>
	<script type="text/javascript" src="script/app_drag_drop.js"></script>
</html>