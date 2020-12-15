<?php	
	// Import des librairies de fonctions d'affichage
	include('dsp/trn_frm_administration.php');
		
	// if no defined tournament, display trn selection table
	if ($_SESSION['tournament'] == -1){
		// Display new tournament form and selection of existing tournaments form
		trn_new_frm();
	}
	else{
		// Get the status of the defined trn and display adapted options
		if (get_trn_status($_SESSION['tournament']) !=-1){
			switch(get_trn_status($_SESSION['tournament'])){
				case 1:
					trn_init_frm($_SESSION['tournament']);
					break;
				case 2:
					frm_adm_2($_SESSION['tournament']);
					break;
				case 3:
					frm_adm_3($_SESSION['tournament']);
					break;
				case 4:
					frm_adm_4($_SESSION['tournament']);
					break;
			}
		}
	}
?>