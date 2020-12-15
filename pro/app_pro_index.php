<?php
	// Test si variable GET envoyée
		// screen selection
	$curr_tab = 'connection';
	if (isset($_GET['s'])){
		switch ($_GET['s']){
			case 'subscribe':
			case 'connection':
				$curr_tab = $_GET['s'];
			break;
		}
	}
		// déconnexion
	if (isset($_GET['action'])){
		switch ($_GET['action']){
			case 'disconnect':
				app_update_user('last_tournament',$_SESSION['tournament']);
				$_SESSION = array();
				session_destroy();
				$_SESSION['user_is_logged'] = false;
				header('Location:index.php');
			break;
		}
	}
		// changement de tournoi courant
	if (isset($_GET['t'])){
		app_sel_trn($_GET['t'],$app_screen);
	}
	
	// Test si variable POST envoyée
		// envoi de pronostics (matches présents sur page d'accueil)
	if (isset($_POST['action'])){
		switch ($_POST['action']){
			case "frc":
				if (isset($_POST['frc_incr'])){
					app_bdd_conn($_SESSION['user_is_admin']);
					$incr = 0;
					for($i=0;$i<$_POST['frc_incr'];$i++){
						$mtdate = $_POST['frc_mtdate_'.$i];
						if ($_POST['frc_change_'.$i] == "1" AND $mtdate>DATE('Y-m-d H:i:s')){
							$incr = $incr + 1;
							$requete="";
							$id_match = $_POST['frc_id_'.$i];
							if ($_POST['frc_bdom_'.$i] !='' OR $_POST['frc_bext_'.$i] !=''){
								$bdom = (int)$_POST['frc_bdom_'.$i];
								$bext = (int)$_POST['frc_bext_'.$i];
							}
							else{
								$bdom = 'NULL';
								$bext = 'NULL';
							}
							$flag_ap = app_box('frc_ap_'.$i,$_POST);
							$int_result = $_POST['frc_int_result_'.$i];
							if (isset($_POST['frc_fin_result_'.$i])){
								$final_result = $_POST['frc_fin_result_'.$i];
							}
							else{
								$final_result = $_POST['frc_final_result_'.$i];
							}
							if($final_result == '0' AND $int_result == 'N'){$final_result = 'NULL';}
							elseif($final_result == '0' AND $int_result != 'N'){$final_result = $int_result;}
							$frc_id = $_POST['frc_id_fm_'.$i];
							if ($mtdate<DATE('Y-m-d H:i:s')){
								$app_message = "Au moins un de vos pronostics a &eacute;t&eacute; fait apr&egrave;s le d&eacute;but du match. ";
							}
							elseif($frc_id == -1){
								$requete = "INSERT INTO frc_matches VALUES(NULL,".$_SESSION['tournament'].",".$_SESSION['id_user'].",".$id_match.",NOW(),".$bdom.",".$bext.",".$flag_ap.",'".$int_result."','".$final_result."',0,0,0)";
							}
							else{
								$requete = "UPDATE frc_matches SET frc_date=NOW(),nb_mks_home=".$bdom.",nb_mks_away=".$bext.",flag_ap=".$flag_ap.",frc_int_result='".$int_result."',frc_final_result='".$final_result."' WHERE id=".$frc_id;
							}
							$req = mysql_query($requete);
						}
					}
					if ($incr == 1){$app_message.=$incr." pronostic a &eacute;t&eacute; enregistr&eacute;. ";}
					elseif ($incr > 1){$app_message.=$incr." pronostics ont &eacute;t&eacute; enregistr&eacute;s. ";}
				}
			break;
			
			case 'subscribe':
			// check if login and psw are filled, if not reject
				if($_POST['sub_login'] == "" OR $_POST['sub_psw'] == ""){
					$app_message .= "Merci de renseigner Login et mot de passe pour cr&eacute;er un compte. ";
				}
			// check unicity of the login asked for creation, if already exists reject
				elseif(app_exist_user($_POST['sub_login'])){
					$app_message .= "Le login propos&eacute; existe d&eacute;j&agrave;. Merci d'en choisir un autre. ";
				}
			// check if both passwords are the same, if not reject
				elseif($_POST['sub_psw'] != $_POST['conf_psw']){
					$app_message .= "Le mot de passe confirm&eacute; est diff&eacute;rent du premier. Ils doivent &ecirc;tre identiques ";
				}
			// if all is correct create the user and connect it
				else{
					if($_POST['dsp_name'] == ""){$_POST['dsp_name'] = $_POST['sub_login'];}
					$_POST['login'] = $_POST['sub_login'];
					$_POST['psw'] = $_POST['sub_psw'];
					app_create_user($_POST);
					include('app_pro_log.php');
				}
			break;
		}
	}
?>