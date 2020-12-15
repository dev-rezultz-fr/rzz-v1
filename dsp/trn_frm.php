<?php	
	function trn_phs_frm($trn_level,$req_phase){
		if($trn_level == 99){
	?>
	<div id="trn_phs_div" class="bloc" style="margin-bottom:0;margin-top:5px;">
		<div id="trn_phs_div_frm" class="ubloc" style="text-align:center;">		
			<div id="trn_phs_div_frm_tabs">
				<div id="tab_<?php echo $trn_level; ?>_phs">
	<?php
			app_bdd_conn($_SESSION['user_is_admin']);		
			$sql_requete = '
			SELECT m.id, m.mt_date, m.mt_day,DATE_FORMAT(m.mt_date,"%d/%m/%y") date_fr, DATE_FORMAT(m.mt_date,"%H:%i") heure_fr, IFNULL(m.nb_mks_home,-1) nb_mks_home, IFNULL(m.nb_mks_away,-1) nb_mks_away, IFNULL(m.pen_home,-1) pen_home, IFNULL(m.pen_away,-1) pen_away, IFNULL(m.flag_ap,-1) flag_ap, IFNULL(m.final_result,-1) final_result,
				   IFNULL(ed.code,"INC") code_ed, IFNULL(ed.label,"Inconnu") libelle_ed, IFNULL(ed.id,"blank") icone_ed, IFNULL(ee.code,"INC") code_ee, IFNULL(ee.label,"Inconnu") libelle_ee, IFNULL(ee.id,"blank") icone_ee,
				   p.trn_level, p.code, p.label, p.flag_group, p.id id_phase
			FROM trn_matches m,
				 trn_phase_members gd LEFT JOIN ref_entities ed on ed.id = gd.id_entity,
				 trn_phase_members ge LEFT JOIN ref_entities ee on ee.id = ge.id_entity,
				 trn_phases p
			WHERE p.id_trn='.$_SESSION['tournament'].' 
			  AND p.id = m.id_phase
			  AND ge.id = m.id_ph_mb_away
			  AND gd.id = m.id_ph_mb_home
			  AND p.trn_level='.$trn_level.'
			ORDER BY trn_level DESC, code ASC, mt_day ASC, mt_date ASC, id ASC';
			//echo $sql_requete;
			$req = mysql_query($sql_requete);
			$phase = "";
			$menu_poule = true;
			$incr = 0;
			$code_jr = "";
			while($ligne_req = mysql_fetch_array($req)){
				if ($ligne_req['trn_level'] == 99){
					$lib_jr = 'J'.$ligne_req['mt_day'];
				}
				else{
					$lib_jr = $ligne_req['code'];
				}
				if($req_phase == '0'){
					$req_phase = $ligne_req['code'];
				}
				if ($ligne_req['trn_level'] == 99 AND $menu_poule){
	?>
					<div id="tabs_<?php echo $trn_level; ?>_phs">
	<?php
					app_bdd_conn($_SESSION['user_is_admin']);		
					$sql_requete1 = 'SELECT p.label, p.code FROM trn_phases p WHERE p.id_trn='.$_SESSION['tournament'].' AND p.trn_level='.$ligne_req['trn_level'].' ORDER BY p.code ASC';
					$req1 = mysql_query($sql_requete1);
					while($ligne_req1 = mysql_fetch_array($req1)){
	?>
						<ul class="tabs">
							<li class="tabs">
								<a href="javascript:display_tabs('<?php echo $ligne_req1['code']."_phs_1','tab_".$ligne_req['trn_level']."_phs"; ?>');" class="<?php if($ligne_req1['code'] == $req_phase){echo 'curr_';}?>tabs" id="lien_<?php echo $ligne_req1['code']; ?>_phs_1"><?php echo $ligne_req1['label']; ?></a>
							</li>
						</ul>
	<?php
					}
	?>
					</div>
					<div>
	<?php
					unset($ligne_req1);
					$menu_poule = false;
				}
				//echo $phase."-".$ligne_req['code'];
				if($phase != $ligne_req['code']){
	?>				
					</div>
					<div id="tab_<?php echo $ligne_req['code']; ?>_phs_1" <?php if($ligne_req['trn_level'] == 99 AND $ligne_req['code'] != $req_phase){echo 'style="display:none;"';}?>>
	<?php
				
					// Ranking Display
	?>
						<div id="rnk_<?php echo $ligne_req['code']; ?>_phs_1" style="margin-bottom:30px;">
							<div class="match_entete">
								<ul class="match">
									<li class="m_rnk_b m_header">Classement</li>
									<li class="m_eqdom m_header">Equipe</li>
									<li class="m_rnk m_header">Pts</li>
									<li class="m_rnk m_header">Diff</li>
									<li class="m_rnk m_header">bp</li>
									<li class="m_rnk m_header">J.</li>
									<li class="m_rnk m_header">G.</li>
									<li class="m_rnk m_header">N.</li>
									<li class="m_rnk m_header">P.</li>
								</ul>
							</div>
	<?php
					app_bdd_conn($_SESSION['user_is_admin']);		
					$sql_requete2 = "SELECT c.*, p.trn_level, p.code, p.flag_validated, e.id id_ent, e.label label_ent, g.flag_qualified, g.final_ranking, g.type_qlf
					FROM trn_phases p, trn_rankings c, trn_phase_members g, ref_entities e
					WHERE e.id=g.id_entity AND g.id=c.id_ph_mb AND c.id_trn=".$_SESSION['tournament']." AND c.id_phase=p.id AND p.id='".$ligne_req['id_phase']."'
					ORDER BY p.code,c.calc_rank"
					;
					//echo $sql_requete2;
					$req2 = mysql_query($sql_requete2);
					while($ligne_req2 = mysql_fetch_array($req2)){
						if ($ligne_req2['trn_level']==99){$limit = get_trn_field($_SESSION['tournament'],'nb_qual_after_gr')+1;}
						else{$limit=2;}
	?>
							<div class="match_entete">
								<input type="hidden" name="rnk_id_groupe_<?php echo $incr; ?>" value="<?php echo $ligne_req2['id_ph_mb']; ?>" />
								<ul class="match">
	<?php 
						if ($ligne_req2['flag_validated']!=1){
	?>
									<li class="m_rnk_b"><?php echo $ligne_req2['dsp_rank']; ?></li>
	<?php 
						}
						else{
	?>
									<li class="m_rnk_b"><?php echo $ligne_req2['final_ranking']; ?></li>
	<?php 
						}
	?>
									<li class="m_eqdom<?php if($ligne_req2['flag_qualified']==1){echo " m_eq_v";}elseif($ligne_req2['calc_rank']!=0 AND $ligne_req2['calc_rank']<$limit){echo " m_eq_n";} ?>" style="background-image:url('img/<?php echo $ligne_req2['id_ent'];?>.png');"><?php echo $ligne_req2['label_ent'];?></li>
									<li class="m_rnk"><?php echo $ligne_req2['pts']; ?></li>
									<li class="m_rnk"><?php echo $ligne_req2['dsp_diff']; ?></li>
									<li class="m_rnk"><?php echo $ligne_req2['mf']; ?></li>
									<li class="m_rnk"><?php echo $ligne_req2['pl']; ?></li>
									<li class="m_rnk"><?php echo $ligne_req2['wn']; ?></li>
									<li class="m_rnk"><?php echo $ligne_req2['dr']; ?></li>
									<li class="m_rnk"><?php echo $ligne_req2['df']; ?></li>
									<?php if($ligne_req2['flag_qualified']==1){?><li class="m_jr">Qualifi&eacute;</li><?php } ?>
								</ul>
							</div>
	<?php
					$incr = $incr + 1;
					}
	?>
						</div>
	<?php
					unset($ligne_req2);
					// END Ranking Display
					$phase = $ligne_req['code'];
				}
	?>
						<div class="match_entete">
							<input type="hidden" name="res_id_<?php echo $incr; ?>" value="<?php echo $ligne_req['id']; ?>" />
							<ul class="match">
							<?php 
								if($ligne_req['code'].'-'.$ligne_req['mt_day'] != $code_jr){
									$code_jr = $ligne_req['code'].'-'.$ligne_req['mt_day'];
							?>
								<li class="m_jr"><?php echo $lib_jr;?></li>
							<?php
								}
								else{
							?>
								<li class="m_empty_small"></li>
							<?php
								}
							?>
								<li class="m_date"><?php echo $ligne_req['date_fr'];?></li>
								<li class="m_eqdom<?php if ($ligne_req['final_result']==1){echo " m_eq_v";}elseif ($ligne_req['final_result']=='N'){echo " m_eq_n";}?>" style="background-image:url('img/<?php echo $ligne_req['icone_ed'];?>.png');"><?php echo $ligne_req['libelle_ed'];?></li>
							<?php 
								if($ligne_req['final_result'] != -1){
							?>
								<li class="m_bdom"><?php echo app_max_void($ligne_req['nb_mks_home']);?></li>
								<li class="m_bext"><?php echo app_max_void($ligne_req['nb_mks_away']);?></li>
							<?php
								}
								else{
							?>
								<li class="m_hr"><?php echo $ligne_req['heure_fr'];?></li>
							<?php
								}
							?>
								<li class="m_eqext<?php if ($ligne_req['final_result']==2){echo " m_eq_v";}elseif ($ligne_req['final_result']=='N'){echo " m_eq_n";}?>" style="background-image:url('img/<?php echo $ligne_req['icone_ee'];?>.png');"><?php echo $ligne_req['libelle_ee'];?></li>
							</ul>
						</div>
	<?php
				$incr = $incr + 1;
			}
	?>
					</div>
	<?php
			unset($ligne_req);
	?>
				</div>
			</div>
		</div>
	</div>
	<?php
		}
	}
	
	
	function trn_fin_frm(){
	?>
	<div id="trn_phs_div" class="bloc" style="margin-bottom:0;margin-top:5px;">
		<ul class="match" style="display:block">
			<li class="m_rnk_b" style="display:block;width:99%;line-height:22px;vertical-align:middle;"><span class="comment">L&eacute;gende : Les chiffres jaunes repr&eacute;sentent le score final. Les &eacute;ventuels petits chiffres sont le score au bout de 90' et, entre parenthèses, les tirs aux buts</span></li>
		</ul>
		<div id="trn_phs_div_frm" class="ubloc" style="text-align:center;">
			<div class="dsp_eq_table">
	<?php
			app_bdd_conn($_SESSION['user_is_admin']);		
			$sql_requete = '
			SELECT m.id, m.mt_date, m.mt_day,DATE_FORMAT(m.mt_date,"%d/%m/%y") date_fr, DATE_FORMAT(m.mt_date,"%H:%i") heure_fr, m.nb_mks_home, m.nb_mks_away, m.int_mks_home, m.int_mks_away, m.pen_home, m.pen_away, IFNULL(m.flag_ap,-1) flag_ap, IFNULL(m.final_result,-1) final_result, gd.type_qlf qlf_d, ge.type_qlf qlf_e, IFNULL(gd.type_qlf,IFNULL(ge.type_qlf,-1)) qlf,
				   m.id id2, m.mt_date mt_date2, m.mt_day mt_day2,DATE_FORMAT(m.mt_date,"%d/%m/%y") date_fr2, DATE_FORMAT(m.mt_date,"%H:%i") heure_fr2, m.nb_mks_home nb_mks_home2, m.nb_mks_away nb_mks_away2, m.pen_home pen_home2, m.pen_away pen_away2, IFNULL(m.flag_ap,-1) flag_ap2, IFNULL(m.final_result,-1) final_result2,
				   ed.code code_ed, ed.label libelle_ed, ed.icone icone_ed, ee.code code_ee, ee.label libelle_ee, ee.icone icone_ee,
				   p.trn_level, p.code, p.label, p.flag_group, p.id id_phase
			FROM trn_matches m LEFT JOIN  trn_matches m2 on m2.id_fst_leg = m.id,				
				 trn_phase_members gd,
				 trn_phase_members ge,
				 (SELECT g1.id, IFNULL(e1.code,CONCAT(x1.rank_ent_from,"e ",p1.code)) code, IFNULL(e1.label,CONCAT(x1.rank_ent_from,"e ",p1.label)) label, IFNULL(e1.id,"blank") icone FROM trn_phase_members g1 LEFT JOIN ref_entities e1 on e1.id = g1.id_entity, trn_phases p1, trn_x_phases x1 WHERE x1.id_phase_to=g1.id_phase AND x1.seq_ent_to=g1.seq_entity AND p1.id=x1.id_phase_from) ed,
				 (SELECT g2.id, IFNULL(e2.code,CONCAT(x2.rank_ent_from,"e ",p2.code)) code, IFNULL(e2.label,CONCAT(x2.rank_ent_from,"e ",p2.label)) label, IFNULL(e2.id,"blank") icone FROM trn_phase_members g2 LEFT JOIN ref_entities e2 on e2.id = g2.id_entity, trn_phases p2, trn_x_phases x2 WHERE x2.id_phase_to=g2.id_phase AND x2.seq_ent_to=g2.seq_entity AND p2.id=x2.id_phase_from) ee,
				 trn_phases p
			WHERE p.id_trn='.$_SESSION['tournament'].' 
			  AND p.id = m.id_phase
			  AND ge.id = ee.id
			  AND gd.id = ed.id
			  AND ge.id = m.id_ph_mb_away
			  AND gd.id = m.id_ph_mb_home
			  AND p.trn_level<99
			  AND m.id_fst_leg IS NULL
			ORDER BY trn_level DESC, code ASC';
			//echo $sql_requete;
			$req = mysql_query($sql_requete);
			$level = "";
			$cnt_lev = 0;
			$fst_in_lev = true;
			// 1st level : 1/1
			// 2nd level : 3/5,5
			// 3rd level : 8/15
			// 4th level : 17,5(/8+2)
			$offset = array();
			$offset[1][0] = 1;
			$offset[1][1] = 1;
			$offset[2][0] = 3;
			$offset[2][1] = 5.5;
			$offset[3][0] = 8;
			$offset[3][1] = 15;
			$offset[4][0] = 17.5;
			$offset[4][1] = 10; // Small final
			while($ligne_req = mysql_fetch_array($req)){
				if ($ligne_req['trn_level'] != $level){
					$fst_in_lev = true;
					$level = $ligne_req['trn_level'];
					$cnt_lev ++;
	?>
			</div>
			<div class="dsp_eq_table" style="width:22%;">
				<ul class="dsp_eq_row_18">
					<li class="final_header"><?php echo app_level_to_str($ligne_req['trn_level']);?></li>
	<?php
				}
				if($fst_in_lev){$off_s = $offset[$cnt_lev][0];$fst_in_lev = false;}else{$off_s = $offset[$cnt_lev][1];}
				for($i=0;$i<$off_s;$i++){
	?>
					<li class="final_blank"></li>
	<?php
				}
				if($off_s-$i == 0.5){
	?>
					<li class="final_semi_blank"></li>
	<?php
				}
	?>
					<li class="final_m">
						<ul class="final_match">
							<li class="final_item<?php if ($ligne_req['final_result']==1){echo " m_eq_v";}elseif ($ligne_req['final_result']=='N'){echo " m_eq_n";}?>" style="background-image:url('img/<?php echo $ligne_req['icone_ed'];?>.png');"><?php echo $ligne_req['code_ed'];?></li>
	<?php
				if($ligne_req['final_result']!=-1){
	?>
							<li class="final_mark"><?php echo app_max_void($ligne_req['nb_mks_home']);?></li>
							<li class="final_qlf"><?php if($ligne_req['qlf']!='90'){echo $ligne_req['int_mks_home'];}  if($ligne_req['qlf']=='ap'){echo '  ap';} elseif($ligne_req['qlf']=='tab'){echo '('.$ligne_req['pen_home'].')';}?></li>
	<?php
				}
				else{
	?>
							<li class="final_qlf"><?php echo $ligne_req['date_fr'];?></li>
	<?php
				}
	?>
						</ul>
					</li>
					<li class="final_m">
						<ul class="final_match">
							<li class="final_item <?php if ($ligne_req['final_result']==2){echo " m_eq_v";}elseif ($ligne_req['final_result']=='N'){echo " m_eq_n";}?>" style="background-image:url('img/<?php echo $ligne_req['icone_ee'];?>.png');"><?php echo $ligne_req['code_ee'];?></li>
	<?php
				if($ligne_req['final_result']!=-1){
	?>
							<li class="final_mark"><?php echo app_max_void($ligne_req['nb_mks_away']);?></li>
							<li class="final_qlf"><?php if($ligne_req['qlf']!='90'){echo $ligne_req['int_mks_away'];} if($ligne_req['qlf']=='tab'){echo '('.$ligne_req['pen_away'].')';}?></li>
	<?php
				}
				else{
	?>
							<li class="final_qlf"><?php echo $ligne_req['heure_fr'];?></li>
	<?php
				}
	?>
						</ul>
					</li>
	<?php
			}
	?>
			</div>
			<div class="dsp_eq_table" style="width:4%;">
				<ul class="dsp_eq_row_18">
					<li class="final_blank"></li>
					<li class="final_blank"></li>
					<li class="final_blank"></li>
					<li class="final_blank"></li>
					<li class="final_blank"></li>
					<li class="final_blank"></li>
					<li class="final_blank"></li>
					<li class="final_blank"></li>
					<li class="final_blank"></li>
					<li class="final_blank"></li>
					<li class="final_blank"></li>
					<li class="final_blank"></li>
					<li class="final_blank"></li>
					<li class="final_blank"></li>
					<li class="final_blank"></li>
				</ul>
				<img src="img/fifa_wc.png"/>
			</div>
		</div>
	</div>
	<?php
	}
?>