<?php

	function app_profile_frm(){
	?>
	<div id="app_profile" class="bloc">
		<form action="#" method="post" name="app_profile_frm">
		<input class="submit" style="float:right;" type="submit" name="val_pro" value="Valider"/>
		<h2 class="commut">Mettre &agrave; jour mes infos personnelles</h2>
		<table class="tacorps" width="90%">
			<tr height="30px">
				<td style="text-align:right;">Mon nom affich&eacute;</td>
				<td style="text-align:left;"><input type="text" name="prof_dspn" size="20" style="text-align:center;" value="<?php echo $_SESSION['dsp_name']; ?>"/></td>
			</tr>
			<tr height="30px">
				<td style="text-align:right;">Mon mail</td>
				<td style="text-align:left;"><input type="email" name="prof_mail" size="20" style="text-align:center;" value="<?php if($_SESSION['mail']!='NULL'){echo $_SESSION['mail'];} ?>"/></td>
			</tr>
			<tr height="30px">
				<td style="text-align:right;">Mon nouveau mot de passe</td>
				<td style="text-align:left;"><input type="password" name="prof_psw" value=""/></td>
			</tr>
			<tr height="30px">
				<td style="text-align:right;">Confirmer le mot de passe</td>
				<td style="text-align:left;"><input type="password" name="prof_conf_psw" value=""/></td>
			</tr>
		</table>
		<input type="hidden" name="action" value="pro" />
		</form>
	</div>
	<?php
		unset($ligne_req);
	}
?>