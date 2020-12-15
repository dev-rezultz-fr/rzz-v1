<?php
	
	function cmn_connect_usr_to_trn($id_user,$id_trn){
		if (trn_exist_trn($id_trn)){
			app_bdd_conn($_SESSION['user_is_admin']);
			// Connection to the public club for the seeded tournament
			$requete = "SELECT id FROM cmn_clubs WHERE flag_is_public=1 AND id_trn=".$id_trn;
			$req = mysql_query($requete);
			$ligne_req = mysql_fetch_array($req);
			$id_club = $ligne_req['id'];
			cmn_sub_usr_club($id_club,$id_user);
		}
		else {
			$app_message .= "Le tournoi demand&eacute; n'existe pas. Veuillez renouveler votre requ&ecirc;te. ";
		}
	}
	
	function cmn_usr_connected_to_trn($id_user,$id_trn){
		app_bdd_conn($_SESSION['user_is_admin']);
		$requete = "SELECT count(cu.id) is_conn FROM cmn_clubs c, cmn_club_users cu WHERE cu.id_club=c.id AND cu.id_user=".$id_user." AND c.id_trn=".$id_trn." GROUP BY c.id_trn";
		$req = mysql_query($requete);
		$ligne_req = mysql_fetch_array($req);
		if ($ligne_req['0'] == 1){return true;}
		else{return false;}
	}
	
	function cmn_sub_usr_club($id_club,$id_user){
		$f_req  = "INSERT INTO cmn_club_users VALUES (NULL,";
		$f_req .= (int)$id_club.",";
		$f_req .= (int)$id_user.")";
		
		app_bdd_conn($_SESSION['user_is_admin']);
		$req = mysql_query($f_req);
		$id_club_user = mysql_insert_id();
		// Population of the ranking line for this club
		$requete = "INSERT INTO cmn_rankings VALUES(";
		$requete .= "NULL,"; // id
		$requete .= $id_club.","; // id_club
		$requete .= $id_club_user.","; // id_club_user
		$requete .= "NULL,"; // dsp_rank
		$requete .= "0,"; // calc_rank
		$requete .= "0,"; // pts
		$requete .= "0,"; // good
		$requete .= "0,"; // bonus
		$requete .= "0)"; // ndm
		$req = mysql_query($requete);
	}
?>