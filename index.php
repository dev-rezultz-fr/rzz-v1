<?php 
	session_start();
	// Defining screen variables
		$app_message = '';
		$app_screen = 'index';
		if (!isset($_SESSION['tournament'])){$_SESSION['tournament'] = -1;}
	// Including library files
		include('lib/app_lib.php');
		include('lib/trn_lib.php');
		include('lib/frc_lib.php');
	// Including processing files
		include('pro/app_pro_log.php');
		include('pro/app_pro_index.php');
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
					if (!isset($_SESSION['user_is_logged']) OR !$_SESSION['user_is_logged']){
						include('dsp/app_dsp_connection.php');
					}
					else{
						//include('dsp/app_dsp_index.php');
				?>
				<div class="shortcut" style="margin-top:0px;width:80%;float:none;height:120px;">
					<a style="background-image:url('img/whistle_on.png');padding-top:10px;">
						<span>
							<ul>
								<li style="list-style:none;margin-left:-20px;">Attention ! D&eacute;cision arbitrale (donc constestable...) - Nouveau mode de jeu retroactif sur les phase finales</li>
								<li>Pronostiquez sur le r&eacute;sultat/score au bout de 90 min et sur l'&eacute;quipe qui passe si vous mettez match nul</li>
								<li>Toujours 10pts pour le bon r&eacute;sultat et +5pts pour le score parfait. En revanche, si vous vous &ecirc;tes tromp&eacute;s sur ce prono mais avez juste sur le vainqueur final : + 5pts</li>
								<li>C'est ce nouveau bonus qui est retroactif sur le 8&egrave;mes de finales, avec don de 5pts pour tous les matches nuls pronostiqu&eacute;s</li>
							</ul>
						</span>
					</a>
				</div>
				<?php 
						// Import des librairies de fonctions d'affichage
						include('dsp/frc_frm.php');
						frc_frc_frm_next($_SESSION['tournament'],5);
					}
				?>
			</div>
			<div id="footer">
				<?php
					if (isset($_SESSION['user_is_logged']) AND $_SESSION['user_is_logged']){
						include('dsp/app_footer.php');
					}
				?>
			</div>
		</div>
	</body>
	<script type="text/javascript" src="script/app_common.js"></script>
	<script type="text/javascript" src="script/frc_common.js"></script>
	<script type="text/javascript" src="script/app_drag_drop.js"></script>
</html>