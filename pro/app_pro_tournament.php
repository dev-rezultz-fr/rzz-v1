<?php
	// screen selection
	$curr_tab = 'group';
	if (isset($_GET['s'])){
		switch ($_GET['s']){
			case 'group':
			case 'final':
			case 'stats':
				$curr_tab = $_GET['s'];
			break;
		}
	}
	$sel_phase = 'A';
	if (isset($_GET['p'])){
		switch ($_GET['p']){
			case 'A':
			case 'B':
			case 'C':
			case 'D':
			case 'E':
			case 'F':
			case 'G':
			case 'H':
				$sel_phase = $_GET['p'];
			break;
		}
	}
	// changing current tournament
	if (isset($_GET['t'])){
		app_sel_trn($_GET['t'],$app_screen);
	}
	
	if (isset($_POST['action'])){
		switch ($_POST['action']){
			case 'xxx':
				if (isset($_POST['xxx_incr']))	{
					app_bdd_conn($_SESSION['user_is_admin']);
					for($i=0;$i<$_POST['xxx_incr'];$i++){
						
					}
				}
			break;
		}
	}
?>