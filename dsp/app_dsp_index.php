<div class="shortcut" style="margin-top:0px;width:80%;float:none;height:200px;">
	<a style="background-image:url('img/whistle_on.png');padding-top:40px;">
		<span>
			<ul>
				<li style="list-style:none;margin-left:-20px;">Attention ! D&eacute;cision arbitrale (donc constestable...) - Nouveau mode de jeu retroactif sur les phase finales</li>
				<li>Pronostiquez sur le r&eacute;sultat/score au bout de 90 min et sur l'&eacute;quipe qui passe si vous mettez match nul</li>
				<li>Toujours 10pts pour le bon r&eacute;sultat et +5pts pour le score parfait. En revanche, si vous vous &ecirc;tes tromp&eacute;s sur ce prono mais avez juste sur le vainqueur final : + 5pts</li>
				<li>C'est ce nouveau bonus qui est retroactif sur le 8&egrave;mes de finales, avec don de 5pts pour tous les matches nuls pronostiqu&eacute;s</li>
			</ul>
		</span>
	</a>
</div>
<?php 
	// Import des librairies de fonctions d'affichage
	include('dsp/frc_frm.php');
	frc_frc_frm_next($_SESSION['tournament'],5);
?>