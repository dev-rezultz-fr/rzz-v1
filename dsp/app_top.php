<?php
	if (isset($_SESSION['user_is_logged']) AND $_SESSION['user_is_logged']){
?>
	<a href="index.php?action=disconnect" class="top_nav" title="Se d&eacute;connecter">
		<img src="img/disconnect.png"/>
	</a>
	<ul id="trnsel">
		<li>
<?php
		// Displaying information for tournament user is currently connected to
		if (trn_exist_trn($_SESSION['tournament'])){
			app_bdd_conn($_SESSION['user_is_admin']);
			$requete = '
			SELECT t.id, t.label, t.edition, t.sport 
			FROM trn_tournaments t, cmn_clubs c, cmn_club_users cu
			WHERE cu.id_user='.$_SESSION['id_user'].' AND cu.id_club=c.id AND c.id_trn=t.id AND t.id='.$_SESSION['tournament'];
			$req = mysql_query($requete);
			$ligne_req = mysql_fetch_array($req);
?>
			<a><span class="sports" style="<?php echo app_sports_sel($ligne_req['sport']);?>"></span><?php echo $ligne_req['label'].' '.$ligne_req['edition'];?></a>
<?php
			unset($ligne_req);
		}
		else{
?>
			<a style="padding-left:40px;width:340px;">[S&eacute;lectionner une comp&eacute;tition]</a>
<?php
		}
?>
			<ul>
<?php
		// Displaying all tournaments (the user has already subscribed to)
		app_bdd_conn($_SESSION['user_is_admin']);
		$requete = '
		SELECT t.id, t.label, t.edition, t.sport 
		FROM trn_tournaments t, cmn_clubs c, cmn_club_users cu 
		WHERE cu.id_user='.$_SESSION['id_user'].' AND cu.id_club=c.id AND c.id_trn=t.id AND t.id<>'.$_SESSION['tournament'].' AND t.flag_is_template=0';
		$req = mysql_query($requete);
		while($ligne_req = mysql_fetch_array($req)){
?>
				<li>
					<a href="<?php echo $app_screen;?>.php?<?php if (isset($curr_tab)){echo 's='.$curr_tab.'&amp;';}?>t=<?php echo $ligne_req['id'];?>"><span class="sports" style="<?php echo app_sports_sel($ligne_req['sport']);?>"></span><?php echo $ligne_req['label'].' '.$ligne_req['edition'];?></a>
				</li>
<?php
		}
		unset($ligne_req);
		// (Test it exists tournaments the user is not connected to)
		app_bdd_conn($_SESSION['user_is_admin']);
		$requete = '
		SELECT count(*) 
		FROM trn_tournaments t
		WHERE t.id<>'.$_SESSION['tournament'].' AND t.flag_is_template=0
		  AND '.$_SESSION['id_user'].' NOT IN (SELECT cu.id_user FROM cmn_clubs c, cmn_club_users cu WHERE cu.id_club=c.id AND c.id_trn=t.id)';
		$req = mysql_query($requete);
		$ligne_req = mysql_fetch_array($req);
		if ($ligne_req['0'] != 0){
?>
				<li>
					<a href="community.php?s=subscribe" style="padding-left:40px;width:340px;">[S'inscrire à une comp&eacute;tition]</a>
				</li>
<?php
		}
		unset($ligne_req);
		// Test if admin
		if ($_SESSION['user_is_admin']){
?>
				<li>
					<a href="administration.php?t=new" style="padding-left:40px;width:340px;">[Cr&eacute;er un tournoi]</a>
				</li>
<?php
		}
?>
			</ul>
		</li>
	</ul>
<?php
	}
	if($app_message != ''){
?>
	<p id="message"><?php echo $app_message; ?></p>
<?php
	}
?>