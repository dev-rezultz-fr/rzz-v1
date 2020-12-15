<?php
	$curr_tab = 'profile';
	$tab_menu = array();
	$tab_menu['profile'] = "Mettre &agrave; jour mes infos";
	$tab_menu['rules'] = "Les r&egrave;gles du jeu";
	app_dsp_menu($tab_menu,$app_screen,$curr_tab);
	
	// Import des librairies de fonctions d'affichage
	include('dsp/app_frm.php');
	
	switch($curr_tab){
		case 'profile':
			app_profile_frm();
		break;
		case 'rules':
			app_rules_frm();
		break;
		}
?>