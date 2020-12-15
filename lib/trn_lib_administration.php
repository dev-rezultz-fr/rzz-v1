<?php	
	function trn_insert_trn($is_template,$array_f){
		$f_req  = "INSERT INTO trn_tournaments VALUES (NULL,";
		$f_req .= (int)$is_template.",".(int)$array_f['id_template'].",";
		$f_req .= "'".$array_f['code']."',";
		$f_req .= "'".$array_f['label']."',";
		$f_req .= "'".$array_f['sport']."',";
		$f_req .= "'".$array_f['type_qual_ent']."',";
		$f_req .= (int)$array_f['nb_qual_ent'].",";
		$f_req .= app_box('flag_quals',$array_f).",";
		$f_req .= app_box('flag_sels',$array_f).",";
		$f_req .= app_box('flag_groups',$array_f).",";
		$f_req .= (int)$array_f['nb_groups'].",";
		$f_req .= "'".$array_f['label_groups']."',";
		$f_req .= app_box('flag_groups_rt',$array_f).",";
		$f_req .= (int)$array_f['nb_qual_after_gr'].",";
		$f_req .= app_box('flag_final_table',$array_f).",";
		$f_req .= (int)$array_f['level_final_table_det'].",";
		$f_req .= app_box('flag_final_table_rt',$array_f).",";
		$f_req .= app_box('flag_finals_rt',$array_f).",";
		$f_req .= app_box('flag_small_final',$array_f).",";
		$f_req .= (int)$array_f['edition'].",";
		$f_req .= "999,"; // current_level --> defaulting
		$f_req .= "0,"; // status --> defaulting
		$f_req .= (int)$array_f['begin_date'].",";
		$f_req .= (int)$array_f['rnk_trn_vc'].",";
		$f_req .= (int)$array_f['rnk_trn_df'].",";
		$f_req .= (int)$array_f['rnk_trn_dw'].",";
		$f_req .= (int)$array_f['rnk_frc_good'].",";
		$f_req .= (int)$array_f['rnk_frc_bonus'].",";
		$f_req .= (int)$array_f['rnk_frc_ndm'].")";
		$_SESSION['request'] = $f_req;
		app_bdd_conn($_SESSION['user_is_admin']);
		$req = mysql_query($f_req);
		$_SESSION['last_id'] = mysql_insert_id();
	}
	
	function trn_insert_phase($array_f,$flag_group,$flag_final,$flag_small_finale){
		$f_req  = "INSERT INTO trn_phases VALUES (NULL,";
		$f_req .= $array_f['id_trn'].",";
		$f_req .= $array_f['trn_level'].",";
		$f_req .= "'".$array_f['code']."',";
		$f_req .= "'".$array_f['label']."',";
		$f_req .= $array_f['nb_members'].",";
		$f_req .= $flag_group.",";
		$f_req .= app_box('flag_rt',$array_f).",";
		$f_req .= $flag_final.",";
		$f_req .= $flag_small_finale.",";
		$f_req .= "0)"; // flag validated
		
		app_bdd_conn($_SESSION['user_is_admin']);
		$req = mysql_query($f_req);
		$_SESSION['last_id'] = mysql_insert_id();
	}
	
	function trn_insert_phase_member($array_f,$seq){
		$f_req  = "INSERT INTO trn_phase_members VALUES (NULL,";
		$f_req .= $array_f['id_trn'].",";
		$f_req .= $array_f['id_phase'].",";
		$f_req .= "NULL,"; // id entity
		$f_req .= $seq.",";
		$f_req .= "0,"; // final ranking
		$f_req .= "0,"; // flag qualified
		$f_req .= "NULL)"; // type qlf
		
		app_bdd_conn($_SESSION['user_is_admin']);
		$req = mysql_query($f_req);
		$_SESSION['last_id'] = mysql_insert_id();
		
		// insert initialized ranking for phase member
		$f_req  = "INSERT INTO trn_rankings VALUES (NULL,";
		$f_req .= $array_f['id_trn'].",";
		$f_req .= $array_f['id_phase'].",";
		$f_req .= $_SESSION['last_id'].",";
		$f_req .= "'-',"; // dsp rank
		$f_req .= "0,"; // calc rank
		$f_req .= "0,"; // pts
		$f_req .= "'-',"; // dsp diff
		$f_req .= "0,"; // diff
		$f_req .= "0,"; // pl
		$f_req .= "0,"; // wn
		$f_req .= "0,"; // dr
		$f_req .= "0,"; // df
		$f_req .= "0,"; // mf
		$f_req .= "0,"; // ma
		$f_req .= "0,"; // pf
		$f_req .= "0,"; // pa
		$f_req .= "0,"; // mf_aw
		$f_req .= "0)"; // ma_aw
		
		$req = mysql_query($f_req);
	}
	
	function trn_insert_match($id_trn,$id_phase,$trn_level,$flag_rt,$home,$away){
		// First leg
		$f_req  = "INSERT INTO trn_matches VALUES (NULL,";
		$f_req .= $id_trn.",";
		$f_req .= $id_phase.",";
		$f_req .= $trn_level.",";
		$f_req .= $home.","; // phase memeber home
		$f_req .= $away.","; // phase memeber away
		$f_req .= "NULL,"; // id first leg
		$f_req .= "NULL,"; // match date
		$f_req .= "NULL,"; // match day
		$f_req .= "NULL,"; // nb marks home
		$f_req .= "NULL,"; // nb marks away
		$f_req .= "NULL,"; // nb pen home
		$f_req .= "NULL,"; // nb pen away
		$f_req .= "0,"; // flag ap
		$f_req .= "NULL,"; // int result
		$f_req .= "NULL)"; // final result
		
		app_bdd_conn($_SESSION['user_is_admin']);
		$req = mysql_query($f_req);
		$_SESSION['last_id'] = mysql_insert_id();
		// Second leg if needed
		if ($flag_rt){
			$f_req  = "INSERT INTO trn_matches VALUES (NULL,";
			$f_req .= $id_trn.",";
			$f_req .= $id_phase.",";
			$f_req .= $trn_level.",";
			$f_req .= $away.","; // phase member home
			$f_req .= $home.","; // phase member away
			$f_req .= $_SESSION['last_id'].","; // id first leg
			$f_req .= "NULL,"; // match date
			$f_req .= "NULL,"; // match day
			$f_req .= "NULL,"; // nb marks home
			$f_req .= "NULL,"; // nb marks away
			$f_req .= "NULL,"; // nb pen home
			$f_req .= "NULL,"; // nb pen away
			$f_req .= "0,"; // flag ap
			$f_req .= "NULL,"; // int result
			$f_req .= "NULL)"; // final result
			
			$req = mysql_query($f_req);
			$_SESSION['last_id'] = mysql_insert_id();
		}
	}
	
	function trn_insert_x_ph($id_trn_f,$id_ph_f,$rnk_f,$id_trn_t,$id_ph_t,$seq_t){
		$f_req  = "INSERT INTO trn_x_phases VALUES (NULL,";
		$f_req .= $id_trn_f.",";
		$f_req .= $id_ph_f.",";
		$f_req .= $rnk_f.",";
		$f_req .= $id_trn_t.",";
		$f_req .= $id_ph_t.",";
		$f_req .= $seq_t.")";
		
		app_bdd_conn($_SESSION['user_is_admin']);
		$req = mysql_query($f_req);
	}
	
	function trn_promote_trn($id_trn,$status){
		app_bdd_conn($_SESSION['user_is_admin']);
		$requete = 'UPDATE trn_tournaments SET status='.$status.' WHERE id='.$id_trn;
		$req = mysql_query($requete);
	}
	
	function trn_ranking_populate_group($id_trn,$id_phase){
	// Process which update ranking information for phase members --> groups phases
		// Reaching phase members for current phase in current tournament
		app_bdd_conn($_SESSION['user_is_admin']);
		$req ="
		SELECT g.id 
		FROM trn_phase_members g 
		WHERE g.id_trn = ".$id_trn."
		  AND g.id_phase = ".$id_phase;
		$req_ph_mb = mysql_query($req);
		while($data_ph_mb = mysql_fetch_array($req_ph_mb)){
			// id_phase_mb
			$id_ph_mb = $data_ph_mb['id'];
			// number of victories
			$req ="SELECT count(*) FROM trn_matches m, trn_phase_members g WHERE m.final_result = '1' AND g.id = m.id_ph_mb_home AND g.id_trn = '".$id_trn."' AND m.id_trn = '".$id_trn."' AND m.id_phase = '".$id_phase."' AND m.id_ph_mb_home = '".$id_ph_mb."'";
			$req_dom = mysql_query($req);
			$v_dom = mysql_fetch_array($req_dom);
			$req ="SELECT count(*) FROM trn_matches m, trn_phase_members g WHERE m.final_result = '2' AND g.id = m.id_ph_mb_away AND g.id_trn = '".$id_trn."' AND m.id_trn = '".$id_trn."' AND m.id_phase = '".$id_phase."' AND m.id_ph_mb_away = '".$id_ph_mb."'";
			$req_ext = mysql_query($req);
			$v_ext = mysql_fetch_array($req_ext);
			$v = $v_dom['0'] + $v_ext['0'];
			// number of draws 
			$req ="SELECT count(*) FROM trn_matches m, trn_phase_members g WHERE m.final_result = 'N' AND g.id = m.id_ph_mb_home AND g.id_trn = '".$id_trn."' AND m.id_trn = '".$id_trn."' AND m.id_phase = '".$id_phase."' AND m.id_ph_mb_home = '".$id_ph_mb."'";
			$req_dom = mysql_query($req);
			$n_dom = mysql_fetch_array($req_dom);
			$req ="SELECT count(*) FROM trn_matches m, trn_phase_members g WHERE m.final_result = 'N' AND g.id = m.id_ph_mb_away AND g.id_trn = '".$id_trn."' AND m.id_trn = '".$id_trn."' AND m.id_phase = '".$id_phase."' AND m.id_ph_mb_away = '".$id_ph_mb."'";
			$req_ext = mysql_query($req);
			$n_ext = mysql_fetch_array($req_ext);
			$n = $n_dom['0'] + $n_ext['0'];
			// number of defeats
			$req ="SELECT count(*) FROM trn_matches m, trn_phase_members g WHERE m.final_result = '2' AND g.id = m.id_ph_mb_home AND g.id_trn = '".$id_trn."' AND m.id_trn = '".$id_trn."' AND m.id_phase = '".$id_phase."' AND m.id_ph_mb_home = '".$id_ph_mb."'";
			$req_dom = mysql_query($req);
			$d_dom = mysql_fetch_array($req_dom);
			$req ="SELECT count(*) FROM trn_matches m, trn_phase_members g WHERE m.final_result = '1' AND g.id = m.id_ph_mb_away AND g.id_trn = '".$id_trn."' AND m.id_trn = '".$id_trn."' AND m.id_phase = '".$id_phase."' AND m.id_ph_mb_away = '".$id_ph_mb."'";
			$req_ext = mysql_query($req);
			$d_ext = mysql_fetch_array($req_ext);
			$d = $d_dom['0'] + $d_ext['0'];
			// ==> number of played matches
			$mj = 0;
			$mj = $v + $n + $d;
			// ==> number of points
			$pts = 0;
			$vic = get_trn_nb_points($id_trn,'wn');
			$nul = get_trn_nb_points($id_trn,'dr');
			$def = get_trn_nb_points($id_trn,'df');
			$pts = $vic*$v + $nul*$n + $def*$d;
			// numbers of marks for
			$req ="SELECT sum(nb_mks_home) FROM trn_matches m, trn_phase_members g WHERE g.id = m.id_ph_mb_home AND g.id_trn = '".$id_trn."' AND m.id_trn = '".$id_trn."' AND m.id_phase = '".$id_phase."' AND m.id_ph_mb_home = '".$id_ph_mb."'";
			$req_dom = mysql_query($req);
			$bp_dom = mysql_fetch_array($req_dom);
			$req ="SELECT sum(nb_mks_away) FROM trn_matches m, trn_phase_members g WHERE g.id = m.id_ph_mb_away AND g.id_trn = '".$id_trn."' AND m.id_trn = '".$id_trn."' AND m.id_phase = '".$id_phase."' AND m.id_ph_mb_away = '".$id_ph_mb."'";
			$req_ext = mysql_query($req);
			$bp_ext = mysql_fetch_array($req_ext);
			$bp = $bp_dom['0'] + $bp_ext['0'];
			// number of marks against
			$req ="SELECT sum(nb_mks_away) FROM trn_matches m, trn_phase_members g WHERE g.id = m.id_ph_mb_home AND g.id_trn = '".$id_trn."' AND m.id_trn = '".$id_trn."' AND m.id_phase = '".$id_phase."' AND m.id_ph_mb_home = '".$id_ph_mb."'";
			$req_dom = mysql_query($req);
			$bc_dom = mysql_fetch_array($req_dom);
			$req ="SELECT sum(nb_mks_home) FROM trn_matches m, trn_phase_members g WHERE g.id = m.id_ph_mb_away AND g.id_trn = '".$id_trn."' AND m.id_trn = '".$id_trn."' AND m.id_phase = '".$id_phase."' AND m.id_ph_mb_away = '".$id_ph_mb."'";
			$req_ext = mysql_query($req);
			$bc_ext = mysql_fetch_array($req_ext);
			$bc = $bc_dom['0'] + $bc_ext['0'];
			// ==> mark average
			$diff = 0;
			$dsp_diff = "";
			$diff = $bp - $bc;
			if ($diff > 0){
				$dsp_diff = "+".$diff;
			}
			else{
				$dsp_diff = "".$diff;
			}
			// Update of rankings for the current phase member
			$req = "UPDATE trn_rankings SET pts='".$pts."', dsp_diff='".$dsp_diff."', diff='".$diff."', pl='".$mj."', wn='".$v."', dr='".$n."', df='".$d."', mf='".$bp."', ma='".$bc."'";
			$req = $req." WHERE id_trn='".$id_trn."' AND id_phase='".$id_phase."' AND id_ph_mb='".$id_ph_mb."'";
			$req_update = mysql_query($req);
		}
	}
	
	function trn_ranking_populate_final($id_trn,$id_phase){
	// Process which update ranking information for phase members --> final table phases
		// Reaching phase members for current phase in current tournament
		app_bdd_conn($_SESSION['user_is_admin']);
		$req ="
		SELECT g.id 
		FROM trn_phase_members g 
		WHERE g.id_trn = ".$id_trn."
		  AND g.id_phase = ".$id_phase;
		$req_ph_mb = mysql_query($req);
		while($data_ph_mb = mysql_fetch_array($req_ph_mb)){
			// id_phase_mb
			$id_ph_mb = $data_ph_mb['id'];
			// numbers of marks for
			$req ="SELECT sum(nb_mks_home) FROM trn_matches m, trn_phase_members g WHERE g.id = m.id_ph_mb_home AND g.id_trn = '".$id_trn."' AND m.id_trn = '".$id_trn."' AND m.id_phase = '".$id_phase."' AND m.id_ph_mb_home = '".$id_ph_mb."'";
			$req_dom = mysql_query($req);
			$bp_dom = mysql_fetch_array($req_dom);
			$req ="SELECT sum(nb_mks_away) FROM trn_matches m, trn_phase_members g WHERE g.id = m.id_ph_mb_away AND g.id_trn = '".$id_trn."' AND m.id_trn = '".$id_trn."' AND m.id_phase = '".$id_phase."' AND m.id_ph_mb_away = '".$id_ph_mb."'";
			$req_ext = mysql_query($req);
			$bp_ext = mysql_fetch_array($req_ext);
			$bp = $bp_dom['0'] + $bp_ext['0'];
			// number of marks against
			$req ="SELECT sum(nb_mks_away) FROM trn_matches m, trn_phase_members g WHERE g.id = m.id_ph_mb_home AND g.id_trn = '".$id_trn."' AND m.id_trn = '".$id_trn."' AND m.id_phase = '".$id_phase."' AND m.id_ph_mb_home = '".$id_ph_mb."'";
			$req_dom = mysql_query($req);
			$bc_dom = mysql_fetch_array($req_dom);
			$req ="SELECT sum(nb_mks_home) FROM trn_matches m, trn_phase_members g WHERE g.id = m.id_ph_mb_away AND g.id_trn = '".$id_trn."' AND m.id_trn = '".$id_trn."' AND m.id_phase = '".$id_phase."' AND m.id_ph_mb_away = '".$id_ph_mb."'";
			$req_ext = mysql_query($req);
			$bc_ext = mysql_fetch_array($req_ext);
			$bc = $bc_dom['0'] + $bc_ext['0'];
			// numbers of pen for
			$req ="SELECT sum(pen_home) FROM trn_matches m, trn_phase_members g WHERE g.id = m.id_ph_mb_home AND g.id_trn = '".$id_trn."' AND m.id_trn = '".$id_trn."' AND m.id_phase = '".$id_phase."' AND m.id_ph_mb_home = '".$id_ph_mb."'";
			$req_dom = mysql_query($req);
			$pp_dom = mysql_fetch_array($req_dom);
			$req ="SELECT sum(pen_away) FROM trn_matches m, trn_phase_members g WHERE g.id = m.id_ph_mb_away AND g.id_trn = '".$id_trn."' AND m.id_trn = '".$id_trn."' AND m.id_phase = '".$id_phase."' AND m.id_ph_mb_away = '".$id_ph_mb."'";
			$req_ext = mysql_query($req);
			$pp_ext = mysql_fetch_array($req_ext);
			$pp = $pp_dom['0'] + $pp_ext['0'];
			// number of pen against
			$req ="SELECT sum(pen_away) FROM trn_matches m, trn_phase_members g WHERE g.id = m.id_ph_mb_home AND g.id_trn = '".$id_trn."' AND m.id_trn = '".$id_trn."' AND m.id_phase = '".$id_phase."' AND m.id_ph_mb_home = '".$id_ph_mb."'";
			$req_dom = mysql_query($req);
			$pc_dom = mysql_fetch_array($req_dom);
			$req ="SELECT sum(pen_home) FROM trn_matches m, trn_phase_members g WHERE g.id = m.id_ph_mb_away AND g.id_trn = '".$id_trn."' AND m.id_trn = '".$id_trn."' AND m.id_phase = '".$id_phase."' AND m.id_ph_mb_away = '".$id_ph_mb."'";
			$req_ext = mysql_query($req);
			$pc_ext = mysql_fetch_array($req_ext);
			$pc = $pc_dom['0'] + $pc_ext['0'];
			
			// Update of rankings for the current phase member
			$req = "UPDATE trn_rankings SET mf=".$bp.", ma=".$bc.", pf=".$pp.", pa=".$pc;
			$req = $req." WHERE id_trn='".$id_trn."' AND id_phase='".$id_phase."' AND id_ph_mb='".$id_ph_mb."'";
			//echo $req;
			$req_update = mysql_query($req);
		}
	}
	
	function trn_ranking_sort_group($id_trn,$id_phase){
	// Process which update ranking sort for one phase --> group phases
		app_bdd_conn($_SESSION['user_is_admin']);
		$rang = 0;
		$disp_rang = "";
		$temp_comp = "-|-|-";
		$req0 ="
		SELECT * 
		FROM trn_rankings
		WHERE id_trn = ".$id_trn."
		  AND id_phase = ".$id_phase."
		ORDER BY pts DESC, diff DESC, mf DESC";

		$req_ph_mb = mysql_query($req0);
		while($data_class = mysql_fetch_array($req_ph_mb)){
			$curr_comp = $data_class['pts']."|".$data_class['diff']."|".$data_class['mf'];
			$rang = $rang + 1;
			if ($curr_comp == $temp_comp){$disp_rang = "-";}
			else {$disp_rang = "".$rang;}
			$temp_comp = $curr_comp;
			
			// Update of rankings for the current phase member
			$req = "UPDATE trn_rankings SET dsp_rank='".$disp_rang."', calc_rank=".$rang." WHERE id_trn=".$id_trn." AND id_phase=".$id_phase." AND id_ph_mb=".$data_class['id_ph_mb'];
			$req_update = mysql_query($req);
		}
	}
	
	function trn_ranking_sort_final($id_trn,$id_phase){
	// Process which update ranking sort for one phase --> final table phases
		app_bdd_conn($_SESSION['user_is_admin']);
		$rang = 0;
		$disp_rang = "";
		$temp_comp = "-|-";
		$req0 ="
		SELECT r.mf,r.pf,r.id_ph_mb 
		FROM trn_rankings r
		WHERE id_trn = ".$id_trn."
		  AND id_phase = ".$id_phase."
		ORDER BY mf DESC, pf DESC";
		//echo $req0;
		$req_ph_mb = mysql_query($req0);
		while($data_class = mysql_fetch_array($req_ph_mb)){
			$curr_comp = $data_class['mf']."|".$data_class['pf'];
			$rang = $rang + 1;
			if ($curr_comp == $temp_comp){
				$disp_rang = "-";
			}
			else {
				$disp_rang = "".$rang;
			}
			$temp_comp = $curr_comp;
			
			// Update of rankings for the current phase member
			$req = "UPDATE trn_rankings SET dsp_rank='".$disp_rang."', calc_rank=".$rang." WHERE id_trn=".$id_trn." AND id_phase=".$id_phase." AND id_ph_mb=".$data_class['id_ph_mb'];
			$req_update = mysql_query($req);
		}
	}
?>