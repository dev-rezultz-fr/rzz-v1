<?php
	
	$tab_menu = array();
	//app_bdd_conn($_SESSION['user_is_admin']);
	//$sql_requete = 'SELECT * FROM trn_phases p,trn_tournaments t WHERE t.id='.$_SESSION['tournament'].' ORDER BY trn_level';
	//$req = mysql_query($sql_requete);
	//while($ligne_req = mysql_fetch_array($req)){
	//	$tab_menu['group'] = "Phase de Groupe";
	//}
	$tab_menu['group'] = "Phase de Groupe";
	$tab_menu['final'] = "Tableau Final";
	$tab_menu['stats'] = "Statistiques";
	app_dsp_menu($tab_menu,$app_screen,$curr_tab);
	
	// Import des librairies de fonctions d'affichage
	include('dsp/trn_frm.php');
	
	if ($_SESSION['tournament']!=-1){
		switch($curr_tab){
			case 'group':
				trn_phs_frm(99,$sel_phase);
			break;
			break;
			case 'final':
				trn_fin_frm();
			break;
			case 'stats':
				//trn_sts_frm();
			break;
		}
	}
	else{
?>
	<h3 style="text-align:center;">Aucun tournoi n'est s&eacute;lectionn&eacute; ci-dessus !</h2>
<?php
	}
?>