<?php
	if (!isset($_SESSION['user_is_logged']) OR !$_SESSION['user_is_logged']){
		$tab_menu = array();
		$tab_menu['connection'] = "Me connecter";
		$tab_menu['subscribe'] = "Me cr&eacute;er un compte";
		app_dsp_menu($tab_menu,'index',$curr_tab);
	}
	if ($curr_tab =='connection'){	
?>
	<div id="connection" style="margin-top:10px;">
		<form action="" id="app_connection" method="post">
			<input type="hidden" name="action" value="connection"/>
			<input class="login" type="text" size="20" name="login" value="login" onfocus="if(this.value=='login'){this.value='';}" onblur="if(this.value==''){this.value='login';}"/>
			<input class="login" type="password" name="psw" value="xxxxxx" onfocus="if(this.value=='xxxxxx'){this.value='';}" onblur="if(this.value==''){this.value='xxxxxx';}"/>
			<input class="submit_login" type="submit" name="go" value="Se Connecter"/>
		</form>
	</div>
<?php	
	}
	elseif ($curr_tab =='subscribe'){	
?>
	<div id="subscribe" style="margin-top:10px;">
		<form action="" id="app_subscribe" method="post">
			<input type="hidden" name="action" value="subscribe"/>
			<label class="login" for="sub_login">Cr&eacute;e ton login</label>
			<input class="login" type="text" size="20" name="sub_login"/>
			<label class="login" for="dsp_name" style="margin-top:26px;">Quel nom veux-tu afficher ?</label>
			<input class="login" type="text" size="20" name="dsp_name"/>
			<label class="login" for="mail" style="margin-top:26px;">Renseigne ton mail (facultatif)</label>
			<input class="login" type="email" size="40" name="mail"/>
			<label class="login" for="sub_psw" style="margin-top:26px;">Cr&eacute;e ton mot de passe</label>
			<input class="login" type="password" name="sub_psw" value=""/>
			<label class="login" for="conf_psw" style="margin-top:26px;">Confirme ton mot de passe</label>
			<input class="login" type="password" name="conf_psw" value=""/>
			<input class="submit_login" type="submit" name="go" value="Cr&eacute;er un compte"/>
		</form>
	</div>
<?php	
	}
?>