<?php
	// variable contenant les menus latéraux
	$app_navigators = array('left'  => array(),'right' => array());
	$app_navigators['left'][0] = array('tournament','cup');
	$app_navigators['left'][1] = array('forecast','dice');
	$app_navigators['right'][0] = array('community','world');
	$app_navigators['right'][1] = array('profile','gears');
	if (isset($_SESSION['user_is_admin']) AND $_SESSION['user_is_admin'] == 1){
		$app_navigators['right'][2] = array('administration','whistle');
	}
	
	// variables contenant les grands titres des pages
	$app_titles = array(
							'connection' => 'Connectez-vous !',
							'tournament' => 'Suivez l\'&eacute;volution du tournoi !',
							'forecast' => 'Faites vos jeux !',
							'community' => 'Eh! Les copains !',
							'profile' => 'Mettez &agrave; jour vos infos !',
							'index' => 'Alors ? Quoi de neuf aujourd\'hui ?',
							'administration' => 'Administration'
						);
?>
<header class="left">
	<div id="header_title"><a class="entete" href="index.php">RZZ</a></div>
	<?php
		if (isset($_SESSION['user_is_logged']) AND $_SESSION['user_is_logged']){
			for ($i=0;$i<count($app_navigators['left']);$i++){
				$on_off = 'off';
				if ($app_navigators['left'][$i][0]==$app_screen){$on_off = 'on';}
	?>
	<nav class="<?php echo $on_off;?>left">
		<a href="<?php echo $app_navigators['left'][$i][0];?>.php">
			<img alt="<?php echo $app_navigators['left'][$i][1];?>" src="img/<?php echo $app_navigators['left'][$i][1]."_".$on_off;?>.png"  <?php if ($on_off=='off'){?> onmouseover="this.parentNode.parentNode.className='onleft';this.src='img/<?php echo $app_navigators['left'][$i][1];?>_on.png';" onmouseout="this.parentNode.parentNode.className='offleft';this.src='img/<?php echo $app_navigators['left'][$i][1];?>_off.png';" onmousedown="this.parentNode.parentNode.className='onleft';this.src='img/<?php echo $app_navigators['left'][$i][1];?>_on.png';"<?php } ?>/>
		</a>
	</nav>
	<?php
			}
		}
	?>
</header>
<header class="right">
	<div id="header_title"></div>
	<?php
		if (isset($_SESSION['user_is_logged']) AND $_SESSION['user_is_logged']){
			for ($i=0;$i<count($app_navigators['right']);$i++){
				$on_off = 'off';
				if ($app_navigators['right'][$i][0]==$app_screen){$on_off = 'on';}
	?>
	<nav class="<?php echo $on_off;?>right">
		<a href="<?php echo $app_navigators['right'][$i][0];?>.php">
			<img alt="<?php echo $app_navigators['right'][$i][1];?>" src="img/<?php echo $app_navigators['right'][$i][1]."_".$on_off;?>.png"  <?php if ($on_off=='off'){?> onmouseover="this.parentNode.parentNode.className='onright';this.src='img/<?php echo $app_navigators['right'][$i][1];?>_on.png';" onmouseout="this.parentNode.parentNode.className='offright';this.src='img/<?php echo $app_navigators['right'][$i][1];?>_off.png';" onmousedown="this.parentNode.parentNode.className='onright';this.src='img/<?php echo $app_navigators['right'][$i][1];?>_on.png';"<?php } ?>/>
		</a>
	</nav>
	<?php
			}
		}
	?>
</header>