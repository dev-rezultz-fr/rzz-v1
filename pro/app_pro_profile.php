<?php
		// changement de tournoi courant
	if (isset($_GET['t'])){
		app_sel_trn($_GET['t'],$app_screen);
	}
	
	// Test si variable POST envoyée
		// envoi de pronostics (matches présents sur page d'accueil)
	if (isset($_POST['action'])){
		switch ($_POST['action']){
			case 'pro':
			// check if both passwords are the same, if not reject
				if($_POST['prof_psw'] != $_POST['prof_conf_psw']){
					$app_message .= "Le mot de passe confirm&eacute; est diff&eacute;rent du premier. Ils doivent &ecirc;tre identiques ";
				}
			// if both passwords are the same and not empty
				elseif($_POST['prof_psw'] != ''){
					$pwd = $_POST['prof_psw'];
					app_update_user('psw',MD5($pwd),'text');
				}
			// if dsp_name is not empty
				if($_POST['prof_dspn'] != ''){
					$dsp_name = $_POST['prof_dspn'];
					app_update_user('dsp_name',$dsp_name,'text');
					$_SESSION['dsp_name'] = $dsp_name;
				}
			// if mail is not empty
				if($_POST['prof_mail'] != ''){
					$mail = $_POST['prof_mail'];
					app_update_user('mail',$mail,'text');
					$_SESSION['mail'] = $mail;
				}
			break;
		}
	}
?>