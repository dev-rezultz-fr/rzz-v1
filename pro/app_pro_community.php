<?php
	// screen selection
	$curr_tab = 'rankings';
	if (isset($_GET['s'])){
		switch ($_GET['s']){
			//case 'create':
			case 'subscribe':
			case 'rankings':
			//case 'comments':
				$curr_tab = $_GET['s'];
			break;
		}
	}
	// changing current tournament
	if (isset($_GET['t'])){
		app_sel_trn($_GET['t'],$app_screen);
	}
	
	if (isset($_POST['action'])){
		switch ($_POST['action']){
			case 'cmn_sub':
				if (isset($_POST['sub_incr']))	{
					app_bdd_conn($_SESSION['user_is_admin']);
					for($i=0;$i<$_POST['sub_incr'];$i++){
						if(app_box('sub_chk_'.$i,$_POST) AND !cmn_usr_connected_to_trn($_SESSION['id_user'],$_POST['sub_trn_'.$i])){
							cmn_connect_usr_to_trn($_SESSION['id_user'],$_POST['sub_trn_'.$i]);
						}
					}
				}
				else {
					$app_message .= "Une erreur est survenue,veuillez renouveler votre requ&ecirc;te. ";
				}
			break;
			case 'frc_post':
				if (isset($_POST['frc_inc_nb'])){
					//frc_update_forecast($_POST,$_POST['frc_inc_nb']);
				}
				else {
					$app_message .= "Une erreur est survenue,veuillez renouveler votre requ&ecirc;te. ";
				}
			break;
		}
	}
?>