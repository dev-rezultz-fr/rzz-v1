<?php

	function cmn_new_frm(){
	?>
	<div id="cmn_crt" class="bloc">
		<form action="#" method="post" name="cmn_crt_frm">
		<input class="submit" style="float:right;" type="submit" name="val_crt" value="Valider"/>
		<h2 class="commut">Cr&eacute;er un club d'amis</h2>
		<table class="tacorps" width="90%">
			<tr class="ttitle">
				<td width="50%" style="text-align:center;">Tournoi</td>
				<td width="50%" style="text-align:center;">Mot de passe &agrave; envoyer &agrave; mes amis</td>
			</tr>
			<tr height="30px">
				<td style="text-align:center;">
					<select size="1">
						<option value="" selected='selected'>-S&eacute;lectionner un tournoi-</option>
	<?php
		app_bdd_conn($_SESSION['user_is_admin']);
		$sql_requete = '
		SELECT t.id, t.label, t.code, t.edition
		FROM trn_tournaments t  
		WHERE t.id_template IS NOT NULL 
		ORDER BY t.begin_date ASC';
		$req = mysql_query($sql_requete);
		while($ligne_req = mysql_fetch_array($req)){
	?>
						<option value="<?php echo $ligne_req['id']; ?>" <?php if(isset($_GET['t']) AND $_GET['t']==$ligne_req['id']){echo "selected='selected'";}?>><?php echo $ligne_req['label']." ".$ligne_req['edition'];?></option>
	<?php
				$incr ++;
		}
	?>		
					</select>
				</td>
				<td style="text-align:center;"><input type="text" name="cmn_sub_<?php echo $incr;?>" size="8" style="text-align:center;"value=""/></td>
			</tr>
		</table>
		<input type="hidden" name="action" value="crt" />
		<input type="hidden" name="crt_incr" value="<?php echo $incr; ?>" />
		</form>
	</div>
	<?php
		unset($ligne_req);
	}

	function cmn_sub_frm(){
?>
	<div id="cmn_sub" class="bloc">
		<form action="#" method="post" name="cmn_sub_frm">
		<input class="submit" style="float:right;" type="submit" name="val_sub" value="Valider"/>
		<h2 class="commut">Liste des tournois disponibles</h2>
		<table class="tacorps" width="90%">
			<tr class="ttitle">
				<td width="20%" style="text-align:center;">Tournoi</td>
				<td width="20%" style="text-align:center;">Date de d&eacute;but</td>
				<td width="20%" style="text-align:center;">Inscrit sur ce tournoi ?</td>
				<td width="20%" style="text-align:center;">Nombre d'inscrits</td>
			</tr>
	<?php
		$incr = 0;
		app_bdd_conn($_SESSION['user_is_admin']);
		$sql_requete = '
		SELECT t.id, t.label, t.code, t.edition, DATE_FORMAT(t.begin_date,"%d/%m/%y") begin, t.begin_date, IFNULL(cs.card,-1) card, count(cu.id) count_users 
		FROM trn_tournaments t 
			left join (SELECT c1.id_trn, cu1.id_user, count(c1.id) card 
						FROM cmn_club_users cu1, cmn_clubs c1 
						WHERE c1.id = cu1.id_club 
						  AND id_user='.$_SESSION['id_user'].' 
						GROUP BY cu1.id)cs 
						on cs.id_trn=t.id,
			 cmn_club_users cu, cmn_clubs c
		WHERE t.flag_is_template=0
		  AND c.id = cu.id_club
		  AND c.id_trn = t.id
		GROUP BY t.id
		ORDER BY t.begin_date ASC';
		//echo $sql_requete;
		$req = mysql_query($sql_requete);
		while($ligne_req = mysql_fetch_array($req)){
	?>
			<tr height="30px">						
				<td style="text-align:center;"><?php echo $ligne_req['label']." ".$ligne_req['edition'];?></td>
				<td style="text-align:center;"><?php echo $ligne_req['begin'];?></td>
				<td style="text-align:center;">
					<input type="checkbox" name="sub_chk_<?php echo $incr;?><?php if($ligne_req['card']<>-1){echo '_dis';}?>" <?php if($ligne_req['card']<>-1){echo 'checked disabled';}?>/>
				</td>
				<td style="text-align:center;"><?php echo $ligne_req['count_users']; ?></td>
				<input type="hidden" name="sub_trn_<?php echo $incr;?>" value="<?php echo $ligne_req['id']; ?>" />
			</tr>
	<?php
			$incr ++;
		}
	?>
		</table>
			<input type="hidden" name="action" value="cmn_sub" />
			<input type="hidden" name="sub_incr" value="<?php echo $incr; ?>" />
		</form>
	</div>
	<?php
		unset($ligne_req);
	}

	function cmn_rnk_frm(){
?>
	<div id="cmn_rnk" class="bloc">
		<h2 class="commut">Consulter le classement des pronostiqueurs</h2>
	<?php
		if ($_SESSION['tournament']<=0){
	?>
			<h3 style="text-align:center;">Aucun tournoi n'est s&eacute;lectionn&eacute; ci-dessus !</h2>
	<?php
		}
		else{
	?>

		<table class="tacorps" width="90%">
			<tr class="ttitle">
				<td width="15%" style="text-align:center;">#</td>
				<td width="25%" style="text-align:left;">Joueur</td>
				<td width="15%" style="text-align:center;background-color:#ddd;">Total (points)</td>
				<td width="15%" style="text-align:center;">Nb de pronos justes</td>
				<td width="15%" style="text-align:center;">Nb de bonus gagn&eacute;s</td>
				<td width="15%" style="text-align:center;">Nb de Nostradamus justes</td>
			</tr>
	<?php
			$sql_requete = '
			SELECT u.id id_user ,u.dsp_name, r.*, c.*
			FROM cmn_rankings r,cmn_club_users cu, cmn_clubs c, app_users u
			WHERE u.id = cu.id_user
			  AND c.id_trn = '.$_SESSION['tournament'].'
			  AND c.id = cu.id_club
			  AND c.flag_is_public=1
			  AND r.id_club_user = cu.id
			ORDER BY calc_rank ASC';
			$req = mysql_query($sql_requete);
			//echo $sql_requete;
			$first_line = true;
			while($ligne_req = mysql_fetch_array($req)){
				if($first_line){
	?>
			<ul class="match" style="display:block">
				<li class="m_rnk_b" style="display:block;width:99%;line-height:22px;">
					<span class="comment">Bar&egrave;me : Prono juste (<?php echo $ligne_req['rnk_frc_good']; ?> pts), Bonus (<?php echo $ligne_req['rnk_frc_bonus']; ?> pts), Nostradamus (<?php echo $ligne_req['rnk_frc_ndm']; ?> pts)</span>
				</li>
			</ul>				
	<?php
					$first_line = false;
				}
	?>
			<tr height="30px" class="<?php if ($_SESSION['id_user']==$ligne_req['id_user']){echo 'thandled';}else{echo 'tcorps';}?>">
				<td style="text-align:center;"><?php echo $ligne_req['dsp_rank']; ?></td>
				<td style="text-align:left;"><?php echo ucfirst($ligne_req['dsp_name']); ?></td>
				<td style="text-align:center;background-color:#ddd;"><?php echo $ligne_req['pts']; ?></td>
				<td style="text-align:center;"><?php echo $ligne_req['good']; ?></td>
				<td style="text-align:center;"><?php echo $ligne_req['bonus']; ?></td>
				<td style="text-align:center;"><?php echo $ligne_req['nostradamus']; ?></td>
			</tr>
	<?php
			}
	?>
		</table>
	<?php
		}
	?>
	</div>
	<?php
	}

	function cmn_com_frm(){	
?>
	<div id="cmn_rnk" class="bloc">
		<h2 class="commut">Partager mes impressions</h2>
		<select size="1" style="height:30px;width:25%;font-size:13px;margin:5px;">
	<?php
		if ($_SESSION['tournament']==-1){
	?>
			<option value="" selected='selected'>- S&eacute;lectionner une discussion -</option>
	<?php
		}
	?>
			<option value="improve">Suggestions sur le site</option>
	<?php
		app_bdd_conn($_SESSION['user_is_admin']);
		$sql_requete = '
		SELECT t.id, t.label, t.code, t.edition, cs.flag_is_public 
		FROM trn_tournaments t, (SELECT c.id, c.id_trn, cu.id_user, c.flag_is_public FROM cmn_club_users cu, cmn_clubs c WHERE c.id = cu.id_club AND id_user='.$_SESSION['id_user'].') cs  
		WHERE cs.id_trn=t.id AND t.id_template IS NOT NULL 
		ORDER BY t.begin_date ASC';
		$req = mysql_query($sql_requete);
		while($ligne_req = mysql_fetch_array($req)){
	?>
			<option value="<?php echo $ligne_req['id']; ?>" <?php if($_SESSION['tournament']==$ligne_req['id'] AND $ligne_req['flag_is_public']==1){echo "selected='selected'";}?>>Discussion - <?php echo $ligne_req['label']." ".$ligne_req['edition'];?></option>
	<?php
		}
	?>		
		</select>
		<img src="img/load4.gif"/>
		<form action="#" method="post" name="cmn_rnk_frm">
		<table class="tacorps" width="90%">
			<tr class="ttitle">
				<td width="15%" style="text-align:center;">#</td>
				<td width="25%" style="text-align:left;">Joueur</td>
				<td width="15%" style="text-align:center;">Points de prono</td>
				<td width="15%" style="text-align:center;">Points de bonus</td>
				<td width="15%" style="text-align:center;">Points Nostradamus</td>
				<td width="15%" style="text-align:center;">Total</td>
			</tr>
			<tr height="30px">
				<td style="text-align:center;">1</td>
				<td style="text-align:left;">Guilhem C</td>
				<td style="text-align:center;">1</td>
				<td style="text-align:center;">1</td>
				<td style="text-align:center;">1</td>
				<td style="text-align:center;background-color:#ddd;">1</td>
			</tr>
		</table>
		</form>
	</div>
	<?php
	}
?>