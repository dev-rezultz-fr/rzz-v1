<?php
	
	// Menu displaying
	$tab_menu = array();
	$tab_menu['trn'] = 'Tournois';
	$tab_menu['club'] = 'Clubs';
	$tab_menu['user'] = 'Utilisateurs';
	app_dsp_menu($tab_menu,$app_screen,$curr_tab);
	include('dsp/app_frm_administration.php');
	
	switch($curr_tab){
		// trn : tournament administration
		case 'trn':
			include('dsp/trn_dsp_administration.php');
			break;
			
		// club : club administration
		case 'club':
			//include('dsp/cmn_dsp_administration.php');
			break;
		
		// user : user administration
		case 'user':
			app_users_frm();
			break;
	}
?>