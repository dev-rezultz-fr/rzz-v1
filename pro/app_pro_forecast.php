<?php
	$frc_user = $_SESSION['id_user'];
	
	// screen selection
	$curr_tab = 'frc';
	if (isset($_GET['s'])){
		switch ($_GET['s']){
			case 'ndm':
				$curr_tab = $_GET['s'];
			break;
		}
	}
	// changing current tournament
	if (isset($_GET['t'])){
		app_sel_trn($_GET['t'],$app_screen);
	}
	
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
			case "ndm":
				if (isset($_POST['ndm_incr'])){
					app_bdd_conn($_SESSION['user_is_admin']);
					$incr = 0;
					for($i=0;$i<=$_POST['ndm_incr'];$i++){
						if ($_POST['id_ent_'.$i] != -1 AND $_POST['change_'.$i] == 1){
							$incr ++;
							$requete="";
							$type_frc = 'NDM';
							$id_phase = $_POST['id_ph_'.$i];
							$id_pm = $_POST['id_pm_'.$i];
							$rank_pm = $_POST['rank_'.$i];
							$id_ent = $_POST['id_ent_'.$i];
							$code_ent = $_POST['code_'.$i];
							
							$frc_id = $_POST['id_frc_'.$i];
							if($frc_id != -1){
								$requete = "DELETE FROM frc_nostradamus WHERE id=".$frc_id;
								//echo $requete;
								$req = mysql_query($requete);
							}
							$requete = "DELETE FROM frc_nostradamus WHERE id_user=".$_SESSION['id_user']." AND id_ph=".$id_phase." AND rnk_pm=".$rank_pm;
							$req = mysql_query($requete);
							$requete = "INSERT INTO frc_nostradamus VALUES(NULL,".$_SESSION['tournament'].",".$_SESSION['id_user'].",'".$type_frc."',".$id_phase.",".$id_pm.",".$rank_pm.",".$id_ent.",'".$code_ent."',NOW(),0,0)";
							$req = mysql_query($requete);
							//echo $requete;
						}
					}
				}
			break;
			case "visu_ndm":
				// user selection for nostradamus
				$frc_user = $_SESSION['id_user'];
				if (isset($_POST['visu_ndm_user']) AND $curr_tab='ndm'){
					$frc_user = $_POST['visu_ndm_user'];
				}
			break;
		}
	}
?>