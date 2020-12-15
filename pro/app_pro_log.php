<?php
	// Test si aucune session en cours
	if (!isset($_SESSION['user_is_logged']) OR !$_SESSION['user_is_logged']){
		// Test si session requise
		if (isset($_POST['login']) AND isset($_POST['psw'])){
			$login = $_POST['login'];
			$psw = md5($_POST['psw']);
			app_bdd_conn(0);
			$user = mysql_query("SELECT * FROM app_users WHERE login='".$login."'"); // Requ�te SQL
			if($data_user = mysql_fetch_array($user)){
				if ($psw == $data_user['psw'] AND $data_user['active'] == 1){
					$_SESSION['user_is_logged'] = true;
					$_SESSION['id_user'] = $data_user['id'];
					$_SESSION['login'] = $data_user['login'];
					$_SESSION['dsp_name'] = $data_user['dsp_name'];
					$_SESSION['user_is_admin'] = ($data_user['is_app_admin']==1)?true:false;
					$_SESSION['tournament'] = $data_user['last_tournament'];
					$_SESSION['mail'] = $data_user['mail'];
					$app_message = "Bonjour ".$_SESSION['dsp_name'];
				}
				else{
					$app_message .= "Erreur de connexion : mot de passe erron&eacute; ou compte d&eacute;sactiv&eacute;";
					$app_screen = "connection";
				}
			}
			else{
				$app_message = $app_message."Erreur de connexion : login incorrect";
				$app_screen = "connection";
			}
		}
		else{
			$app_screen = "connection";
		}
	}
?>