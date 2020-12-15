<?php
	// screen selection
	$curr_tab = 'trn';
	if (isset($_GET['s'])){
		switch ($_GET['s']){
			case 'trn':
			case 'club':
			case 'user':
				$curr_tab = $_GET['s'];
			break;
		}
	}
	// changing current tournament
	if (isset($_GET['t'])){
		app_sel_trn($_GET['t'],$app_screen);
	}

	// check if action has been sent by POST
	if(isset($_POST['action'])){
		//echo $_POST['action'];
		switch($_POST['action']){
	
	// User administration
			case "app_adm":
				if (isset($_POST['app_adm_incr'])){
					app_bdd_conn($_SESSION['user_is_admin']);
					for($i=0;$i<$_POST['app_adm_incr'];$i++){
						$requete="";
						if(isset($_POST['app_adm_pwd_'.$i]) AND $_POST['app_adm_pwd_'.$i]!=""){
						}
						if(app_box('app_adm_isadm_'.$i,$_POST)){
						}
						if(app_box('app_adm_active_'.$i,$_POST)){
						}
						if(app_box('app_adm_istrn_'.$i,$_POST)){
						}
					}
				}
			break;
	
	// Promote action
			case "promote":
				if (isset($_POST['status']) AND $_SESSION['tournament']!= -1){
					trn_promote_trn($_SESSION['tournament'],$_POST['status']);
					if ($_POST['status'] == 3 AND get_first_trn_level($_SESSION['tournament']) != -1){
						app_bdd_conn($_SESSION['user_is_admin']);
						$requete = 'UPDATE trn_tournaments SET current_level='.get_first_trn_level($_SESSION['tournament']).' WHERE id='.$_SESSION['tournament'];
						$req = mysql_query($requete);
					}
					$app_message .= "Donnees mises � jour";
				}
				else {
					$app_message .= "Erreur de promotion du tournoi. Renouvelez l'op&eacute;ration";
				}
			break;
	// Update tournament ranking action
			case "upd_trn_rnk":
				if ($_SESSION['tournament']!= -1){
					$trn_level = get_trn_field($_SESSION['tournament'],'current_level');
					app_bdd_conn($_SESSION['user_is_admin']);
					$requete = 'SELECT id FROM trn_phases WHERE id_trn='.$_SESSION['tournament'].' AND trn_level='.$trn_level;
					$req = mysql_query($requete);
					while ($ligne_req = mysql_fetch_array($req)){
						if($trn_level == 99){
							trn_ranking_populate_group($_SESSION['tournament'],$ligne_req['id']);
							trn_ranking_sort_group($_SESSION['tournament'],$ligne_req['id']);
						}
						else{
							trn_ranking_populate_final($_SESSION['tournament'],$ligne_req['id']);
							trn_ranking_sort_final($_SESSION['tournament'],$ligne_req['id']);
						}
					}
				}
				else {
					$app_message .= "Erreur de mise &agrave; jour du tournoi. Renouvelez l'op&eacute;ration";
				}
			break;
	// Update community ranking action
			case "upd_cmn_rnk":
				if ($_SESSION['tournament']!= -1){
					app_bdd_conn($_SESSION['user_is_admin']);
					$requete = 'SELECT id,rnk_frc_good,rnk_frc_bonus,rnk_frc_ndm FROM cmn_clubs WHERE id_trn='.$_SESSION['tournament'];
					$req = mysql_query($requete);
					while ($ligne_req = mysql_fetch_array($req)){
						cmn_ranking_populate_user($_SESSION['tournament'],$ligne_req);
						cmn_ranking_sort_user($_SESSION['tournament'],$ligne_req['id']);
					}
				}
				else {
					$app_message .= "Erreur de mise &agrave; jour des utilisateurs. Renouvelez l'op&eacute;ration";
				}
			break;
	// Creation action
			case "new":
				if(app_box('flag_is_template',$_POST)){
					$array_f = $_POST;
					$array_f['id_template'] = 'NULL';
					$array_f['edition'] = 'NULL';
					$array_f['begin_date'] = 'NULL';
					trn_insert_trn(1,$array_f); // creating a template
					if (app_box('flag_instance',$_POST)){
						$array_f['id_template'] = $_SESSION['last_id'];
						$array_f['edition'] = $_POST['edition'];
						$array_f['begin_date'] = $_POST['begin_date'];
						trn_insert_trn(0,$array_f); // creating an instance
						$_SESSION['tournament'] = $_SESSION['last_id'];
						app_update_user('last_tournament',$_SESSION['tournament']);
					}
				}
				elseif ($_POST['id_template'] != 0){
				// template selection and tournament population
					$requete = 'SELECT * from trn_tournaments WHERE id='.(int)$_POST['id_template'];
					//echo $requete;
					app_bdd_conn($_SESSION['user_is_admin']);
					$req = mysql_query($requete);
					$array_f = mysql_fetch_array($req);
					$array_f['id_template'] = $_POST['id_template'];
					$array_f['edition'] = $_POST['edition'];
					$array_f['begin_date'] = app_form_date($_POST['begin_date'],"00:00");
					trn_insert_trn(0,$array_f); // creating an instance
					$_SESSION['tournament'] = $_SESSION['last_id'];
					app_update_user('last_tournament',$_SESSION['tournament']);
				}
				else{
					$app_message .= "Merci de s&eacute;lectionner un template ou d'en cr&eacute;er un nouveau. ";
					$_POST['flag_instance'] = 0;
				}
	
				if (app_box('flag_instance',$_POST)){
				// Subscription in a specific public club
				cmn_create_club($_SESSION['id_user'],$_SESSION['tournament'],"",$array_f['label']."-".$array_f['edition']."-Public",1);
				
				// Tournament initialization (phases and groups)
				// creating a phase by groups and a phase member by group member
					$array_f['id_trn'] = $_SESSION['tournament'];
					if (app_box('flag_groups',$array_f)){
						$array_f['trn_level'] = 99;
						$array_f['flag_rt'] = app_box('flag_groups_rt',$array_f);
						$array_f['nb_members'] = $array_f['nb_qual_ent'] / max($array_f['nb_groups'],1);
						$code_poule = $array_f['label_groups'];
						for ($i=0;$i<$array_f['nb_groups'];$i++){
							$array_f['code'] = $code_poule;
							$array_f['label'] = 'Groupe '.$code_poule;
							$code_poule = ++$code_poule;
							trn_insert_phase($array_f,1,0,0);
							$array_f['id_phase'] = $_SESSION['last_id'];
							for ($j=0;$j<$array_f['nb_members'];$j++){
								trn_insert_phase_member($array_f,$j+1);
							}
						}
					}
				
				// Creating a phase by final table match (no final) and 2 phase members for each
					if (app_box('flag_final_table',$array_f)){
						if (app_box('flag_groups',$array_f)){
							$array_f['trn_level'] = $array_f['nb_groups']*$array_f['nb_qual_after_gr']/2;
						}
						else{
							$array_f['trn_level'] = $array_f['nb_qual_ent'];
						}
						$array_f['flag_rt'] = app_box('flag_final_table_rt',$array_f);
						$array_f['nb_members'] = 2;
						while ($array_f['trn_level'] > 1){
							$array_f['label'] = app_level_to_str($array_f['trn_level']);
							for ($i=1;$i<$array_f['trn_level']+1;$i++){
								$array_f['code'] = '1/'.$array_f['trn_level'].'-'.$i;
								trn_insert_phase($array_f,0,0,0);
								$array_f['id_phase'] = $_SESSION['last_id'];
								trn_insert_phase_member($array_f,1);
								trn_insert_phase_member($array_f,2);
							}
							$array_f['trn_level'] = $array_f['trn_level'] / 2;
						}
			
					// Creating a phase for the final and small final and their phase members
						$array_f['trn_level'] = 1;
						$array_f['flag_rt'] = app_box('flag_finals_rt',$array_f);
						// small final if needed
						if (app_box('flag_small_final',$array_f) == 1){
							$array_f['code'] = 'PF';
							$array_f['label'] = 'Petite '.app_level_to_str($array_f['trn_level']);
							trn_insert_phase($array_f,0,0,1);
							$array_f['id_phase'] = $_SESSION['last_id'];
							trn_insert_phase_member($array_f,1);
							trn_insert_phase_member($array_f,2);
						}
						$array_f['code'] = 'F';
						$array_f['label'] = app_level_to_str($array_f['trn_level']);
						trn_insert_phase($array_f,0,1,0);
						$array_f['id_phase'] = $_SESSION['last_id'];
						trn_insert_phase_member($array_f,1);
						trn_insert_phase_member($array_f,2);
					}
				
				// Building x-phases by default
				// From the level specified in level_final_table_det, determine x-phases "by building"
				// This level is considered as the last one which is determined by tirage
					$i_from = 0;
					$i_to = 0;
					$seq_to = 0;
					$curr_lev = 0;
					$curr_from = 0;
					$curr_f_ok = false;
					$requete = '
					SELECT p.id id_from, p.trn_level lev_from, q.id id_to
					FROM trn_phases p, trn_phases q
					WHERE q.id_trn = p.id_trn
					  AND p.id_trn = '.$_SESSION['tournament'].'
					  AND q.trn_level = p.trn_level/2
					  AND q.flag_small_final = 0
					  AND p.trn_level <= '.$array_f['level_final_table_det'].'
					  AND p.trn_level > 1
					ORDER BY lev_from DESC,id_from ASC,id_to ASC
					';
					app_bdd_conn($_SESSION['user_is_admin']);
					$req = mysql_query($requete);
					while ($ligne_req = mysql_fetch_array($req)){
						if($curr_lev != $ligne_req['lev_from']){$i_from=1;$i_to = 1;$curr_f_ok = false;}
						elseif($curr_from != $ligne_req['id_from']){$i_from++;$i_to = 1;$curr_f_ok = false;}
						else{$i_to++;}
						$curr_lev = $ligne_req['lev_from'];
						$curr_from = $ligne_req['id_from'];
						
						if(!$curr_f_ok){							
							if (($i_from/2<=$i_to) AND ($i_from/2>$i_to-1)){
								$seq_to = 2-$i_from%2;
								trn_insert_x_ph($_SESSION['tournament'],$ligne_req['id_from'],1,$_SESSION['tournament'],$ligne_req['id_to'],$seq_to);
								$curr_f_ok = true;
							}
						}
					}
					unset($ligne_req);
					
					// If needed, creating x-phases for semi-finals losers (small final)
					if(app_box('flag_small_final',$array_f)==1){
						$seq_to = 0;
						$requete = '
						SELECT p.id id_from, p.trn_level lev_from, q.id id_to
						FROM trn_phases p, trn_phases q
						WHERE q.id_trn = p.id_trn
						  AND p.id_trn = '.$_SESSION['tournament'].'
						  AND q.trn_level = 1
						  AND q.flag_small_final = 1
						  AND p.trn_level = 2
						ORDER BY lev_from DESC,id_from ASC,id_to ASC
						';
						app_bdd_conn($_SESSION['user_is_admin']);
						$req = mysql_query($requete);
						while($ligne_req = mysql_fetch_array($req)){
							$seq_to = $seq_to +1;
							trn_insert_x_ph($_SESSION['tournament'],$ligne_req['id_from'],2,$_SESSION['tournament'],$ligne_req['id_to'],$seq_to);
						}
					}
					unset($array_f);
				
				// Initializing matches
				// For each phase creating all combinations of possible matches (with rt if needed)
					app_bdd_conn($_SESSION['user_is_admin']);
					$requete = 'SELECT * from trn_phases a WHERE id_trn='.$_SESSION['tournament'];
					$req = mysql_query($requete);
					while ($ligne_req = mysql_fetch_array($req)){
						// Combination with group members
						$requete2 = 'SELECT dom.id dom_id, ext.id ext_id from trn_phase_members dom, trn_phase_members ext WHERE ext.id<>dom.id AND ext.seq_entity>dom.seq_entity AND ext.id_trn='.$_SESSION['tournament'].' AND ext.id_phase='.$ligne_req['id'].' AND dom.id_trn='.$_SESSION['tournament'].' AND dom.id_phase='.$ligne_req['id'];
						$req2 =  mysql_query($requete2);
						while ($ligne_req2 = mysql_fetch_array($req2)){
							trn_insert_match($_SESSION['tournament'],$ligne_req['id'],$ligne_req['trn_level'],$ligne_req['flag_rt'],$ligne_req2['dom_id'],$ligne_req2['ext_id']);
						}			
					}
					trn_promote_trn($_SESSION['tournament'],1);
				}
			break;
		
		
			case "rgl":
	// RGL : Validation / mise � jour des r�gles du tournoi
			break;
			
			case "xph":
	// XPH : Cr�ation des chemins entre phases
				if (isset($_POST['xph_incr'])){
					app_bdd_conn($_SESSION['user_is_admin']);
					for($i=0;$i<$_POST['xph_incr'];$i++){
						$requete="";
						if ($_POST['xph_ph_from_'.$i] <> "0"){
							//$id_trn_from = (int)$_POST['xph_id_trn_from_'.$i];
							list($id_phase_from,$ranking_from) = explode("_",$_POST['xph_ph_from_'.$i]);
							//$classement_equipe_from = (int)$_POST['xph_classement_equipe_from_'.$i];
							//$id_trn_to = (int)$_POST['xph_id_trn_to_'.$i];
							$id_phase_to = (int)$_POST['xph_ph_to_'.$i];
							$seq_ent_to = (int)$_POST['xph_sq_'.$i];
							//$update_flag = $_POST['update_xph_'.$i];
							if($_POST['xph_xid_'.$i] != 0){
								$requete = 'UPDATE trn_x_phases SET id_trn_from='.$_SESSION['tournament'].', id_phase_from='.$id_phase_from.', rank_ent_from='.$ranking_from.' WHERE id='.$_POST['xph_xid_'.$i];
							}
							else{
								$requete = 'INSERT INTO trn_x_phases VALUES (NULL,'.$_SESSION['tournament'].','.$id_phase_from.','.$ranking_from.','.$_SESSION['tournament'].','.$id_phase_to.','.$seq_ent_to.')';
							}
						}
						else{
							if($_POST['xph_xid_'.$i] != 0){
								$requete = 'DELETE FROM trn_x_phases WHERE id='.$_POST['xph_xid_'.$i];
							}
						}
						if($requete <> ""){
							$req = mysql_query($requete);
							//echo $requete;
						}
					}
				}
			break;
		
			case "qlf":
	// QLF : S�lection des qualifi�s parmi les �quipes existantes
				if (isset($_POST['qlf_incr'])){
					app_bdd_conn($_SESSION['user_is_admin']);
					for($i=0;$i<$_POST['qlf_incr'];$i++){
						$update_flag = $_POST['update_qlf_'.$i];
						$id_eq = (int)$_POST['qlf_id_eq_'.$i];
						// Gestion de l'ajout d'un qualifi�
						if ($update_flag == 'insert' AND isset($_POST['qlf_'.$i]) AND $_POST['qlf_'.$i] == 'on'){
							$requete = 'INSERT INTO trn_qual_entities VALUES (NULL,'.$_SESSION['tournament'].','.$id_eq.',NULL)';
							$req = mysql_query($requete);
						}
						// Gestion de la suppression d'un qualifi�
						if($update_flag =='update' AND !isset($_POST['qlf_'.$i])){
							$id_qual = (int)$_POST['qlf_id_qual_'.$i];
							$requete = 'DELETE FROM trn_qual_entities WHERE id='.$id_qual;
							$req = mysql_query($requete);
						}
					}
				}
			break;
		
			case "cha":
	// CHA : Constitution des chapeaux
			break;
		
			case "tir":
	// TIR : Effectuer le tirage d'un level
				if (isset($_POST['tir_incr'])){
					app_bdd_conn($_SESSION['user_is_admin']);
					for($i=0;$i<$_POST['tir_incr'];$i++){
						$id_gr = $_POST['tir_id_gr_'.$i];
						$id_eq = $_POST['tir_id_eq_'.$i];
						if ($id_eq != 0){
							$requete = 'UPDATE trn_phase_members SET id_entity='.$id_eq.' WHERE id='.$id_gr;
							//echo $requete;
							$req = mysql_query($requete);
						}
					}
				}
			break;
		
			case "cal":
	// CAL : Mettre � jour les dates de chaque match
				if (isset($_POST['cal_incr'])){
					app_bdd_conn($_SESSION['user_is_admin']);
					for($i=0;$i<$_POST['cal_incr'];$i++){
						// Match aller
						$id_aller = $_POST['cal_id_aller_'.$i];
						if (isset($_POST['cal_jr_aller_'.$i])){
							$jr_aller = $_POST['cal_jr_aller_'.$i];
						}
						else{
							$jr_aller = 'NULL';
						}
						$date_aller = $_POST['cal_date_aller_'.$i];
						$heure_aller = $_POST['cal_hr_aller_'.$i];
						$date_aller = app_form_date($date_aller,$heure_aller);
						$requete = 'UPDATE trn_matches SET mt_date="'.$date_aller.'", mt_day='.$jr_aller.' WHERE id='.$id_aller;
						$req = mysql_query($requete);
													
						if (isset($_POST['cal_switch_'.$i]) AND $_POST['cal_switch_'.$i] == 'on'){
							$requeteb = 'UPDATE trn_matches SET id_ph_mb_home='.$_POST['cal_id_away_'.$i].', id_ph_mb_away='.$_POST['cal_id_home_'.$i].' WHERE id='.$id_aller;
							$reqb = mysql_query($requeteb);
						}
						
						// Match retour
						if (isset($_POST['cal_id_retour_'.$i])){
							$id_retour = $_POST['cal_id_retour_'.$i];
							if (isset($_POST['cal_jr_retour_'.$i])){
								$jr_retour = $_POST['cal_jr_retour_'.$i];
							}
							else{
								$jr_retour = 'NULL';
							}
							$date_retour = $_POST['cal_date_retour_'.$i];
							$heure_retour = $_POST['cal_hr_retour_'.$i];
							$date_retour = format_date($date_retour,$heure_retour);
							$requete2 = 'UPDATE trn_matches SET mt_date="'.$date_retour.'", mt_day='.$jr_retour.' WHERE id='.$id_retour;
							$req2 = mysql_query($requete2);
							
							if (isset($_POST['cal_switch_ar_'.$i]) AND $_POST['cal_switch_ar_'.$i] == 'on'){
								$requete3 = 'UPDATE trn_matches SET id_fst_leg='.$id_retour.' WHERE id='.$id_aller;
								$req3 = mysql_query($requete3);
								$requete4 = 'UPDATE trn_matches SET id_fst_leg=NULL WHERE id='.$id_retour;
								$req4 = mysql_query($requete4);
							}
						}
					}
				}
			break;
	
			case "sel":
	// SEL : Renseigner les s�lections (joueurs retenus pour le tournoi par chaque �quipe)
			break;
		
			case "res":
	// RES : Renseigner les r�sultats des phases en cours
				if (isset($_POST['res_incr'])){
					app_bdd_conn($_SESSION['user_is_admin']);
					for($i=0;$i<$_POST['res_incr'];$i++){
						$flag_ok = false;
						if ($_POST['res_bdom_'.$i] != "" OR $_POST['res_bext_'.$i] != ""){
							$flag_ok = true;
							$requete="";
							$id_match = $_POST['res_id_'.$i];
							$is_group = $_POST['res_group_'.$i];
							$bdom = (int)$_POST['res_bdom_'.$i];
							$bext = (int)$_POST['res_bext_'.$i];
							$res = "";
							$res_int = "";
							// Final result
							if($bdom > $bext){$res = '1';}
							elseif($bdom < $bext){$res = '2';}
							elseif($bdom == $bext){$res = 'N';}
							if ($is_group == 1){ // Group match
								// intermediate result and final result are the same
								$idom = $bdom;
								$iext = $bext;
								$res_int = $res;
							}
							else{ // Direct elimination match
								// intermediate result and final result could be different
								$idom = (int)$_POST['res_idom_'.$i];
								$iext = (int)$_POST['res_iext_'.$i];
								// Intermediate result
								if($idom > $iext){$res_int = '1';}
								elseif($idom < $iext){$res_int = '2';}
								elseif($idom == $iext){$res_int = 'N';}
								// AP flag
								$flap = 0;
								if($idom == $iext){$flap=1;}
								// Penalties
								if($res == 'N' AND ((isset($_POST['res_pdom_'.$i]) AND $_POST['res_pdom_'.$i]!=0) OR (isset($_POST['res_pext_'.$i]) AND $_POST['res_pext_'.$i]!=0))){
									$pdom = (int)$_POST['res_pdom_'.$i];
									$pext = (int)$_POST['res_pext_'.$i];
									if($pdom > $pext){$res = '1';}
									elseif($pdom < $pext){$res = '2';}
									elseif($pdom == $pext){$flag_ok = false;}
									else{$flag_ok = false;}
									$requete="pen_home=".$pdom.", pen_away=".$pext.", ";
								}
							}
							$requete = "UPDATE trn_matches SET ".$requete."int_mks_home=".$idom.", int_mks_away=".$iext.",nb_mks_home=".$bdom.", nb_mks_away=".$bext.", int_result='".$res_int."', final_result='".$res."', flag_ap=".$flap." WHERE id=".$id_match;
							if ($flag_ok){
								$req = mysql_query($requete);
								//echo $requete;
							
								// Updating individual forecast on this result
								// For lines which are good (proper result was forecast)
								// Default : comparison with intermediate result
								$requete = "UPDATE frc_matches SET frc_is_good=1 WHERE frc_int_result='".$res_int."' AND id_match=".$id_match;
								$req = mysql_query($requete);
								// For lines which are bonused (proper score was forecast)
								// Default : comparison with intermediate result
								$requete = "UPDATE frc_matches SET frc_is_bonused=1 WHERE nb_mks_home='".$idom."' AND nb_mks_away='".$iext."' AND id_match=".$id_match;
								$req = mysql_query($requete);
								if ($is_group != 1){ // Direct elimination match
									// For lines which are bonused (proper entity was forecast to be qualified whereas not proper result was forecast)
									$requete = "UPDATE frc_matches SET frc_is_bonused=1 WHERE frc_final_result='".$res."' AND frc_is_good<>1 AND id_match=".$id_match;
									$req = mysql_query($requete);
								}
								// Flag lines on this match
								$requete = "UPDATE frc_matches SET frc_is_checked=1 WHERE id_match=".$id_match;
								$req = mysql_query($requete);
							}
						}
					}
				}
			break;
		
			case "val_phs":
	// UPD_PHS : Update and validate a phase with final ranking of its entites
				if (isset($_POST['rnk_incr'])){
					app_bdd_conn($_SESSION['user_is_admin']);
					$requete = "SELECT t.current_level,t.level_final_table_det,t.nb_qual_after_gr FROM trn_tournaments t WHERE id=".$_SESSION['tournament'];
					//echo $requete;
					$req = mysql_query($requete);
					$trn_dt = mysql_fetch_array($req);
					if ($trn_dt['current_level'] == 99){$tg_qlf = $trn_dt['nb_qual_after_gr'];}else{$tg_qlf = 1;}
					
				// update qualified entities during the phase
					for($i=0;$i<$_POST['rnk_incr'];$i++){
						$requete = "";
						if (app_box('rnk_qlf_'.$i,$_POST)){
							$id_ph_mb = (int)$_POST['rnk_id_'.$i];
							if (isset($_POST['rnk_typ_qlf_'.$i])){$typ_q = $_POST['rnk_typ_qlf_'.$i];}else{$typ_q = "NULL";}
							$requete = "UPDATE trn_phase_members SET flag_qualified=1, type_qlf='".$typ_q."' WHERE id=".$id_ph_mb;
							//echo $requete;
							$req = mysql_query($requete);
						}
					}
				// a phase can be validated if no match are to be played for it and if proper number of qualified entities is defined
				// finally, the flag of validation is put on it
					for($i=0;$i<$_POST['rnk_incr'];$i++){
						$requete = "";
						$nb_mat = -1;
						$nb_qlf = 0;
						if (app_box('rnk_val_'.$i,$_POST)){
							$id_ph = (int)$_POST['rnk_id_ph_'.$i];
							$requete = "SELECT count(*) FROM trn_matches WHERE final_result IS NULL AND id_phase=".$id_ph;
							//echo $requete;
							$req = mysql_query($requete);
							$dt_mat = mysql_fetch_array($req);
							$nb_mat = $dt_mat['0'];
							$requete = "SELECT count(*) FROM trn_phase_members WHERE flag_qualified=1 AND id_phase=".$id_ph;
							//echo $requete;
							$req = mysql_query($requete);
							$dt_qlf = mysql_fetch_array($req);
							$nb_qlf = $dt_qlf['0'];
							
							if ($nb_qlf == $tg_qlf AND $nb_mat == 0){
								$requete = "UPDATE trn_phases SET flag_validated=1 WHERE id=".$id_ph;
								//echo $requete;
								$req = mysql_query($requete);
							}
							else {
								$app_message .= "Phase non validable en l'�tat ".$id_ph;
							}
						}
					}
				// then, final ranking are reported to its members and qualified ones are affected to the proper next phase (thanks to x_phases)
					for($i=0;$i<$_POST['rnk_incr'];$i++){
						if (isset($_POST['rnk_id_'.$i])){
							$requete = "";
							$id_ph_mb = (int)$_POST['rnk_id_'.$i];
							if(isset($_POST['rnk_frnk_'.$i])){ // group phases
								$final_rk = (int)$_POST['rnk_frnk_'.$i];
							}
							else{ //final table phases
								$final_rk = 2-app_box('rnk_qlf_'.$i,$_POST);
							}
							$requete = "SELECT p.flag_validated FROM trn_phases p, trn_phase_members g WHERE g.id_phase=p.id AND g.id=".$id_ph_mb;
							//echo $requete;
							$req = mysql_query($requete);
							$phs_dt = mysql_fetch_array($req);
							if ($phs_dt['flag_validated'] == 1){
								$requete = "UPDATE trn_phase_members SET final_ranking=".$final_rk." WHERE id=".$id_ph_mb;
								//echo $requete;
								$req = mysql_query($requete);
							
								$requete = "SELECT xp.*,g.id_entity FROM trn_x_phases xp,trn_phase_members g WHERE xp.id_phase_from=g.id_phase AND g.id=".$id_ph_mb." AND xp.rank_ent_from=".$final_rk." AND xp.id_trn_from=".$_SESSION['tournament'];
								$req = mysql_query($requete);
								$nb_rows = mysql_num_rows($req);
								if ($nb_rows == 1){
									$xph_dt = mysql_fetch_array($req);
									$requete1 = "UPDATE trn_phase_members SET id_entity=".$xph_dt['id_entity']." WHERE id_trn=".$xph_dt['id_trn_to']." AND id_phase=".$xph_dt['id_phase_to']." AND seq_entity=".$xph_dt['seq_ent_to'];
									$req1 = mysql_query($requete1);
								}
							}
						}
					}
				}
			break;
		
			case "val_lev":
	// UPD_PHS : Validate a level with qualified entities for next level
			// a level can be validated if no phase of this level are to be validated
			// finally, the level of the tournament is increased
				if ($_SESSION['tournament']!= -1){
					app_bdd_conn($_SESSION['user_is_admin']);
					$trn_level = get_trn_field($_SESSION['tournament'],'current_level');
					$requete = "SELECT count(*) FROM trn_phases WHERE flag_validated=0 AND id_trn=".$_SESSION['tournament']." AND trn_level=".$trn_level;
					//echo $requete;
					$req = mysql_query($requete);
					$dt_lev = mysql_fetch_array($req);
					$nb_ph = $dt_lev['0'];
					//echo'-';
					//echo $nb_ph;
					if ($nb_ph == 0 AND $trn_level != 1){
						$requete = "UPDATE trn_tournaments SET current_level=".get_next_trn_level($trn_level,$_SESSION['tournament'])." WHERE id=".$_SESSION['tournament'];
						//echo $requete;
						$req = mysql_query($requete);
					}
					elseif($trn_level == 1){
						trn_promote_trn($_SESSION['tournament'],4);
						$app_message .= "Le Tournoi est termin�.";
					}
					else {
						$app_message .= "Niveau du tournoi non validable en l'�tat.";
					}
					// Validating nostradamus forecasts for the level we just reached
					// Checking the list of entities qualified in this lew level and entities users have forecast to be in this new level
						$requete = "
						UPDATE frc_nostradamus SET frc_is_good=1
						WHERE id_trn=".$_SESSION['tournament']."
						  AND id_ph IN (SELECT id FROM trn_phases WHERE id_trn=".$_SESSION['tournament']." AND trn_level=".$trn_level.")
						  AND frc_value IN (SELECT pm.id_entity FROM trn_phase_members pm,trn_phases p WHERE p.id=pm.id_phase AND pm.id_trn=".$_SESSION['tournament']." AND p.trn_level=".get_next_trn_level($trn_level,$_SESSION['tournament']).")
						";
						$req = mysql_query($requete);
						$requete = "
						UPDATE frc_nostradamus SET frc_is_checked=1
						WHERE id_trn=".$_SESSION['tournament']."
						  AND id_ph IN (SELECT id FROM trn_phases WHERE id_trn=".$_SESSION['tournament']." AND trn_level=".$trn_level.")
						";
						$req = mysql_query($requete);
				}
			break;

// Phase 99 : Tournoi d�sactiv�

		} // fin switch
	} // fin traitement d'une action envoy�e par POST
		
	

?>