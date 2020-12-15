<?php
	
	$tab_menu = array();
	$tab_menu['ndm'] = "Pronos Nostradamus";
	$tab_menu['frc'] = "Pronos sur les matches";
	app_dsp_menu($tab_menu,$app_screen,$curr_tab);
	
	// Import des librairies de fonctions d'affichage
	include('dsp/frc_frm.php');
	
	switch($curr_tab){
		case 'ndm':
			if(get_trn_field($_SESSION['tournament'],'begin_date')>DATE('Y-m-d H:i:s')){
				frc_ndm_frm();
			}
			else{
				frc_ndm_frm_ls($frc_user);
			}
		break;
		case 'frc':
			frc_frc_frm($_SESSION['tournament']);
		break;
		}
?>