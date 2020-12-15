<?php
	error_reporting(0);
	function app_box($box,$tab){
		if(!isset($tab[$box])){return 0;}
		elseif($tab[$box] =='on'){
			return 1;
		}
		else{
			return $tab[$box];
		}
	}
	
	function app_exist_user($login){
		app_bdd_conn(0);
		$requete = "SELECT id FROM app_users WHERE login='".$login."'";
		$req = mysql_query($requete);
		if(mysql_num_rows($req)>0){return true;}
		else{return false;}
	}
	
	function app_create_user($array_f){
		$f_req  = "INSERT INTO app_users VALUES (NULL,";
		$f_req .= "'".$array_f['dsp_name']."',";
		$f_req .= "'".$array_f['mail']."',";
		$f_req .= "'".$array_f['login']."',";
		$f_req .= "'".MD5($array_f['psw'])."',";
		$f_req .= "0,"; // last_tournament
		$f_req .= "0,"; // is admin
		$f_req .= "1)"; // flag active
		app_bdd_conn(0);
		//echo $f_req;
		$req = mysql_query($f_req);
	}
	
	function app_update_user($field,$data,$type){
		if($type=='text'){$sep="'";}else{$sep="";}
		app_bdd_conn($_SESSION['user_is_admin']);
		$requete = "UPDATE app_users SET ".$field."=".$sep."".$data."".$sep." WHERE id=".$_SESSION['id_user']."";
		$req = mysql_query($requete);
	}
	
	function app_admin_user($field,$data,$id_user){
		app_bdd_conn($_SESSION['user_is_admin']);
		$requete = "UPDATE app_users SET ".$field."=".$sep."".$data."".$sep." WHERE id=".$id_user;
		$req = mysql_query($requete);
	}
		
	function app_max_void($p){
		if ($p<0 OR $p==""){return "";}
		else{return $p;}
	}
		
	function app_form_date($date,$heure){
		// Format de date
		$date_formatee = "20".implode('-',array_reverse(explode('/',$date)));
		$date_formatee = $date_formatee." ".$heure;
		return $date_formatee;
	}
		
	function app_int_to_str($in){
		if ($in<1){return 0;}
		elseif ($in==1){return '1er';}
		else {return $in.' &egrave;me';}
	}
		
	function app_sports_sel($sport){
		$xpos = array();
		$ypos = array();
		
		$xpos['FOOT'] = -32+18+2;
		$xpos['TENNIS'] = -97+18+2;
		$xpos['RUGBY'] = -227+18+2;
		
		$ypos['FOOT'] = -26+18+2;
		$ypos['TENNIS'] = -94+18+2;
		$ypos['RUGBY'] = -159+18+2;
		
		$app_bg = array();
		$app_bg['FOOT'] = "background-position: ".$xpos['FOOT']."px ".$ypos['FOOT']."px;";
		$app_bg['TENNIS'] = "background-position: ".$xpos['TENNIS']."px ".$ypos['TENNIS']."px;";
		$app_bg['RUGBY'] = "background-position: ".$xpos['RUGBY']."px ".$ypos['RUGBY']."px;";
		
		return $app_bg[$sport];
	}
	
	function app_level_to_str($level){
		switch ($level){
			case 99:
				return 'Poules';
				break;
			case 64:
				return '64&egrave;mes';
				break;
			case 32:
				return '32&egrave;mes';
				break;
			case 16:
				return 'Seizi&#232;mes';
				break;
			case 8:
				return 'Huiti&egrave;mes';
				break;
			case 4:
				return 'Quarts';
				break;
			case 2:
				return 'Demies';
				break;
			case 1:
				return 'Finale';
				break;		
		}
	}
	
	function prod($is_admin){
		$conn_server = "localhost";
		$conn_user = ($is_admin)?"root":"root";//"client";
		$conn_pword = ($is_admin)?"":"";//"BWHJrEszdQ7MSpjt";
		$conn_bdd = "rzz_prod";
		$conn = mysql_connect($conn_server, $conn_user, $conn_pword);
		mysql_select_db($conn_bdd);
	}
	
	
	function app_bdd_conn($is_admin){
		$conn_server = "localhost";
		$conn_user = ($is_admin)?"root":"root";//"client";
		$conn_pword = ($is_admin)?"":"";//"BWHJrEszdQ7MSpjt";
		$conn_bdd = "rzz_1";
		$conn = mysql_connect($conn_server, $conn_user, $conn_pword);
		mysql_select_db($conn_bdd);
		//$conn_server = "rezultzfbj_prod.mysql.db";
		//$conn_user = ($is_admin)?"rezultzfbj_prod":"rezultzfbj_prod";//"client";
		//$conn_pword = ($is_admin)?"KpmC9NhYKSAv":"KpmC9NhYKSAv";//"BWHJrEszdQ7MSpjt";
		//$conn_bdd = "rezultzfbj_prod";
		//$conn = mysql_connect($conn_server, $conn_user, $conn_pword);
		//mysql_select_db($conn_bdd);
	}
	
	function app_dsp_menu($tab,$page,$current){
		$width = 100/max(count($tab),1);
?>
	<div id="divmenu">
		<ul id="menu" style="width:100%;">
<?php
		foreach ($tab as $cle => $menu){
			echo '<li style="width:'.$width.'%;">';
				echo '<a';
					if($cle == $current){echo ' class="current"';}
					echo ' href="'.$page.'.php?s='.$cle.'" style="width:100%;">';
					echo $menu;
				echo '</a>';
			echo '</li>';
		}
?>
		</ul>
	</div>
<?php
		unset($cle);
		unset($menu);
		unset($width);
	}
	
	function app_dsp_menu_js($tab,$current,$section){
		$width = 100/max(count($tab),1);
?>
	<div id="divmenu">
		<ul id="menu" style="width:100%;">
	<?php
		foreach ($tab as $cle => $menu){
	?>
			<li style="width:<?php echo $width;?>%;">
				<a	id="lien_<?php echo $cle;?>" <?php if($cle == $current){echo 'class="current"';}?> href="javascript:display_tabs('<?php echo $cle;?>','<?php echo $section;?>')" style="width:100%;"><?php echo $menu;?></a>
			</li>
	<?php
		}
	?>
		</ul>
	</div>
<?php
		unset($cle);
		unset($menu);
		unset($width);
	}
	
	function app_sel_trn($id_trn,$screen){
		if ($id_trn == "new"){
			$_SESSION['tournament'] = -1;
			header('Location:administration.php');
		}
		elseif (trn_exist_trn($id_trn)){
			$_SESSION['tournament'] = $id_trn;
			app_update_user('last_tournament',$_SESSION['tournament']);
			header('Location:'.$screen.'.php');
		}
		else {
			$app_message .= "Le tournoi demand&eacute; n'existe pas. Veuillez renouveler votre requ&ecirc;te. ";
		}
	}
?>