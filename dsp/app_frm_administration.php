<?php
	function app_users_frm(){
?>
	<div id="app_adm" class="bloc">
		<form action="#" method="post" name="app_adm_frm">
		<input class="submit" style="float:right;" type="submit" name="val_app_adm" value="Valider"/>
		<h2 class="commut">Administrer les utilisateurs</h2>
	<?php
		if ($_SESSION['tournament']<=0){
	?>
			<h3 style="text-align:center;">Aucun tournoi n'est s&eacute;lectionn&eacute; ci-dessus !</h2>
	<?php
		}
	?>
		<table class="tacorps" width="90%">
			<tr class="ttitle">
				<td width="5%" style="text-align:center;">id</td>
				<td width="10%" style="text-align:center;">Login</td>
				<td width="15%" style="text-align:center;">Nom affich&eacute;</td>
				<td width="20%" style="text-align:center;">mail</td>
				<td width="20%" style="text-align:center;">Mot de passe</td>
				<td width="5%" style="text-align:center;">Admin</td>
				<td width="5%" style="text-align:center;">Actif</td>
				<td width="5%" style="text-align:center;">Inscrit</td>
				<td width="5%" style="text-align:center;">FRC</td>
				<td width="5%" style="text-align:center;">NDM</td>
			</tr>
	<?php
			$sql_requete = '
			SELECT u.*,fm.frc,fn.ndm,IFNULL(ccu.id_user,-1) user_trn
			FROM app_users u LEFT JOIN (SELECT cu.id_user FROM cmn_clubs c,cmn_club_users cu WHERE c.id=cu.id_club AND c.id_trn='.$_SESSION['tournament'].' AND c.flag_is_public=1) ccu ON ccu.id_user=u.id,
				 app_users um LEFT JOIN (SELECT id_user,count(id) frc FROM frc_matches WHERE id_trn='.$_SESSION['tournament'].' GROUP BY id_user) fm ON um.id=fm.id_user,
				 app_users un LEFT JOIN (SELECT id_user,count(id) ndm FROM frc_nostradamus WHERE id_trn='.$_SESSION['tournament'].' GROUP BY id_user) fn ON un.id=fn.id_user
			WHERE u.id=um.id
			  AND u.id=un.id
			ORDER BY u.dsp_name ASC';
			$req = mysql_query($sql_requete);
			while($ligne_req = mysql_fetch_array($req)){
	?>
			<tr height="30px" class="tcorps">
				<td style="text-align:center;"><?php echo $ligne_req['id']; ?></td>
				<td style="text-align:center;"><?php echo $ligne_req['login']; ?></td>
				<td style="text-align:center;"><?php echo $ligne_req['dsp_name']; ?></td>
				<td style="text-align:center;"><?php echo $ligne_req['mail']; ?></td>
				<td style="text-align:center;">
					<input type="text" name="app_adm_pwd_<?php echo $incr;?>" size="8" style="text-align:center;"value=""/>
				</td>
				<td style="text-align:center;">
					<input type="checkbox" name="app_adm_isadm_<?php echo $incr;?>" <?php if($ligne_req['is_app_admin']==1){echo 'checked';}?>/>
				</td>
				<td style="text-align:center;">
					<input type="checkbox" name="app_adm_active_<?php echo $incr;?>" <?php if($ligne_req['active']==1){echo 'checked';}?>/>
				</td>
				<td style="text-align:center;">
					<input type="checkbox" name="app_adm_istrn_<?php echo $incr;?>" <?php if($ligne_req['user_trn']!=-1){echo 'checked';}?>/>
				</td>
				<td style="text-align:center;"><?php echo $ligne_req['frc']; ?></td>
				<td style="text-align:center;"><?php echo $ligne_req['ndm']; ?></td>
			</tr>
	<?php
			}
	?>
		</table>
			<input type="hidden" name="action" value="app_adm" />
			<input type="hidden" name="app_adm_incr" value="<?php echo $incr; ?>" />
		</form>
	</div>
	<?php
	}
?>