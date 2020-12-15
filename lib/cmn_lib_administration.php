<?php
	function cmn_create_club($id_creator,$id_trn,$password,$label,$is_pub){
		$f_req  = "INSERT INTO cmn_clubs VALUES (NULL,";
		$f_req .= (int)$id_creator.",";
		$f_req .= (int)$id_trn.",";
		$f_req .= "'".$password."',";
		$f_req .= "'".$label."',";
		$f_req .= (int)$is_pub.")";
		
		app_bdd_conn($_SESSION['user_is_admin']);
		$req = mysql_query($f_req);
		cmn_sub_usr_club(mysql_insert_id(),$id_creator);
	}
	
	function cmn_ranking_populate_user($id_trn,$tab){
	// Process which update ranking information for phase members --> groups phases
		// Reaching phase members for current phase in current tournament
		$gd = $tab['rnk_frc_good'];
		$bn = $tab['rnk_frc_bonus'];
		$nd = $tab['rnk_frc_ndm'];
		$id_club = $tab['id'];
		app_bdd_conn($_SESSION['user_is_admin']);
		$req ="
		SELECT au.id id_user,cu.id id_club_user
		FROM cmn_rankings r,
			 cmn_club_users cu,
			 app_users au
		WHERE r.id_club = ".$id_club."
		  AND cu.id_club = r.id_club
		  AND au.id = cu.id_user
		";
		$req_user = mysql_query($req);
		while($data_user = mysql_fetch_array($req_user)){
			// id_user
			$id_user = $data_user['id_user'];
			$id_club_user = $data_user['id_club_user'];
			// number of good
			$req ="SELECT count(*) FROM frc_matches fm WHERE fm.frc_is_checked=1 AND fm.frc_is_good=1 AND fm.id_trn=".$id_trn." AND fm.id_user=".$id_user;
			//echo $req;
			$req_good = mysql_query($req);
			$good = mysql_fetch_array($req_good);
			$good_d = $good['0'];
			// number of bonus
			$req ="SELECT count(*) FROM frc_matches fm WHERE fm.frc_is_checked=1 AND fm.frc_is_bonused=1 AND fm.id_trn=".$id_trn." AND fm.id_user=".$id_user;
			$req_bonus = mysql_query($req);
			$bonus = mysql_fetch_array($req_bonus);
			$bonus_d = $bonus['0'];
			// number of nostradamus
			$req ="SELECT count(*) FROM frc_nostradamus fn WHERE fn.frc_is_checked=1 AND fn.frc_is_good=1 AND fn.id_trn=".$id_trn." AND fn.id_user=".$id_user;
			$req_ndm = mysql_query($req);
			$ndm = mysql_fetch_array($req_ndm);
			$ndm_d = $ndm['0'];
			// ==> number of points
			$pts = 0;
			//echo $gd.'-'.$good.'-'.$bn.'-'.$bonus.'-'.$nd.'-'.$ndm;
			$pts = $gd*$good_d + $bn*$bonus_d + $nd*$ndm_d;
			// Update of rankings for the current user
			$req = "UPDATE cmn_rankings SET pts=".$pts.",good=".$good_d.",bonus=".$bonus_d.",nostradamus=".$ndm_d;
			$req = $req." WHERE id_club=".$id_club." AND id_club_user=".$id_club_user;
			$req_update = mysql_query($req);
		}
	}
	
	function cmn_ranking_sort_user($id_trn,$id_club){
	// Process which update ranking sort for one phase --> group phases
		app_bdd_conn($_SESSION['user_is_admin']);
		$rang = 0;
		$disp_rang = "";
		$temp_comp = "-|-|-";
		$req0 ="
		SELECT * 
		FROM cmn_rankings
		WHERE id_club = ".$id_club."
		ORDER BY pts DESC, bonus DESC, nostradamus DESC";

		$req_club = mysql_query($req0);
		while($data_class = mysql_fetch_array($req_club)){
			$id = $data_class['id'];
			$curr_comp = $data_class['pts']."|".$data_class['bonus']."|".$data_class['nostradamus'];
			$rang = $rang + 1;
			if ($curr_comp == $temp_comp){$disp_rang = "-";}
			else {$disp_rang = "".$rang;}
			$temp_comp = $curr_comp;
			
			// Update of rankings for the current phase member
			$req = "UPDATE cmn_rankings SET dsp_rank='".$disp_rang."', calc_rank=".$rang." WHERE id=".$id;
			$req_update = mysql_query($req);
		}
	}
	
?>