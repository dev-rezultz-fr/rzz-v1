<?php
	
	$tab_menu = array();
	//$tab_menu['create'] = "Cr&eacute;er un club d'amis";
	$tab_menu['subscribe'] = "Se connecter &agrave; un tournoi";
	$tab_menu['rankings'] = "Mes classements";
	//$tab_menu['comments'] = "Mes commentaires";
	app_dsp_menu($tab_menu,$app_screen,$curr_tab);
	
	// Import des librairies de fonctions d'affichage
	include('dsp/cmn_frm.php');
	
	switch($curr_tab){
		case 'subscribe':
			cmn_sub_frm();
		break;
		case 'rankings':
			cmn_rnk_frm();
		break;
		case 'comments':
			//cmn_com_frm();
		break;
		}
?>