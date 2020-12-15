<?php
	
	function get_trn_nb_points($id_trn,$res){
		app_bdd_conn($_SESSION['user_is_admin']);
		$requete = 'SELECT rnk_trn_vc,rnk_trn_df,rnk_trn_dw FROM trn_tournaments WHERE id='.$id_trn;
		$req = mysql_query($requete);
		$ligne_req = mysql_fetch_array($req);
		switch ($res){
			case 'wn': return $ligne_req['rnk_trn_vc'];break;
			case 'df': return $ligne_req['rnk_trn_df'];break;
			case 'dr': return $ligne_req['rnk_trn_dw'];break;
		}
	}
	
	function get_trn_field($id_trn,$field){
		app_bdd_conn($_SESSION['user_is_admin']);
		$requete = 'SELECT '.$field.' FROM trn_tournaments WHERE id='.$id_trn;
		$req = mysql_query($requete);
		$ligne_req = mysql_fetch_array($req);
		if(isset($ligne_req[$field])){
			return $ligne_req[$field];
		}
		else{return -1;}
	}
	
	function trn_exist_trn($id_trn){
		app_bdd_conn($_SESSION['user_is_admin']);
		$requete = 'SELECT id FROM trn_tournaments WHERE id_template IS NOT NULL AND id='.$id_trn;
		$req = mysql_query($requete);
		$ligne_req = mysql_fetch_array($req);
		if(isset($ligne_req['id'])){return true;}
		else{return false;}
	}
	
	function get_trn_status($id_trn){
		app_bdd_conn($_SESSION['user_is_admin']);
		$requete = 'SELECT status FROM trn_tournaments WHERE id='.$id_trn;
		$req = mysql_query($requete);
		$ligne_req = mysql_fetch_array($req);
		if(isset($ligne_req['status'])){
			return $ligne_req['status'];
		}
		else{return -1;}
	}
	
	function get_trn_level($id_trn){
		app_bdd_conn($_SESSION['user_is_admin']);
		$requete = 'SELECT current_level FROM trn_tournaments WHERE id='.$id_trn;
		$req = mysql_query($requete);
		$ligne_req = mysql_fetch_array($req);
		if(isset($ligne_req['current_level'])){
			return $ligne_req['current_level'];
		}
		else{return -1;}
	}
	
	function get_first_trn_level($id_trn){
		app_bdd_conn($_SESSION['user_is_admin']);
		$requete = 'SELECT max(trn_level) first_level FROM trn_phases WHERE id_trn='.$id_trn;
		$req = mysql_query($requete);
		$ligne_req = mysql_fetch_array($req);
		if(isset($ligne_req['first_level'])){
			return $ligne_req['first_level'];
		}
		else{return -1;}
	}
	
	function get_prev_trn_level($level,$id_trn){
		app_bdd_conn($_SESSION['user_is_admin']);
		$requete = 'SELECT min(trn_level) next_level FROM trn_phases WHERE trn_level>'.$level.' AND id_trn='.$id_trn;
		$req = mysql_query($requete);
		$ligne_req = mysql_fetch_array($req);
		if(isset($ligne_req['next_level'])){
			return $ligne_req['next_level'];
		}
		else{return -1;}
	}
	
	function get_next_trn_level($level,$id_trn){
		app_bdd_conn($_SESSION['user_is_admin']);
		$requete = 'SELECT max(trn_level) next_level FROM trn_phases WHERE trn_level<'.$level.' AND id_trn='.$id_trn;
		$req = mysql_query($requete);
		$ligne_req = mysql_fetch_array($req);
		if(isset($ligne_req['next_level'])){
			return $ligne_req['next_level'];
		}
		else{return -1;}
	}
	
	function trn_tab_is_det($id_trn){
		app_bdd_conn($_SESSION['user_is_admin']);
		$requete = 'SELECT level_final_table_det FROM trn_tournaments WHERE id='.$id_trn;
		$req = mysql_query($requete);
		$ligne_req = mysql_fetch_array($req);
		if($ligne_req['level_final_table_det'] == 99){return true;}
		else{return false;}
	}
?>