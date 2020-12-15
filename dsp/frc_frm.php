<?php
	function frc_ndm_frm(){
	?>
	<div id="frc_ndm_frm" class="bloc" style="margin-bottom:0;margin-top:5px;">
		<form action="#" method="post" name="frc_ndm_frm">
		<input class="submit" style="float:right;" type="submit" name="val_ndm" value="Valider"/>
		<h2 class="commut" onclick="display_commut('frc_ndm_div_frm');">Simuler le tableau entier</h2>
		<div id="frc_ndm_div_frm" class="ubloc" style="text-align:center;display:block;">
			<div class="dsp_eq_table" style="width:0%">
				<ul>
	<?php
			app_bdd_conn($_SESSION['user_is_admin']);
			$sql_requete = '
			SELECT pm.id curr_pm_id,p.id curr_ph_id,p.code curr_ph_code,p.trn_level curr_ph_level,p.flag_small_final curr_ph_smfin,p.flag_group curr_ph_fgroup,pm.seq_entity curr_pm_seq,
				   IFNULL(xpp.frc_value,IFNULL(pme.id,-1)) curr_id_ent,IFNULL(xpp.frc_label,IFNULL(CONCAT(xpp.rank_ent_from,"e ",xpp.code),pme.code)) curr_code_ent, IFNULL(ndc.rnk_pm,-1) curr_frc_rank,IFNULL(CONCAT(xpp.rank_ent_from,"e ",xpp.code),-1) curr_code_x,IFNULL(ndc.id,-1) curr_id_frc,
				   IFNULL(xnn.id_pm_n,-1) next_pm_id,IFNULL(xnn.rank_ent_from,-1) next_rnk_from
			FROM trn_phases p,
				 trn_phase_members pm LEFT JOIN ref_entities pme ON pme.id=pm.id_entity,
				 trn_phase_members pmcf LEFT JOIN frc_nostradamus ndc ON ndc.id_ph_mb=pmcf.id AND ndc.id_user='.$_SESSION['id_user'].',
				 trn_phase_members pmp LEFT JOIN (SELECT pmpp.id id_pm,pp.id id_ph,pp.code,xp.rank_ent_from,ndp.frc_value,ndp.frc_label FROM trn_phases pp,trn_phase_members pmpp,trn_x_phases xp LEFT JOIN frc_nostradamus ndp ON ndp.id_ph=xp.id_phase_from AND ndp.rnk_pm=xp.rank_ent_from AND ndp.id_user='.$_SESSION['id_user'].' WHERE xp.seq_ent_to=pmpp.seq_entity AND xp.id_phase_to=pmpp.id_phase AND pp.id=xp.id_phase_from) xpp ON xpp.id_pm=pmp.id,
				 trn_phase_members pmn LEFT JOIN (SELECT pmnc.id id_pm,xn.rank_ent_from,pmnn.id id_pm_n FROM trn_phase_members pmnc,trn_phase_members pmnn,trn_x_phases xn WHERE xn.id_phase_to=pmnn.id_phase AND xn.seq_ent_to=pmnn.seq_entity AND pmnc.id_phase=xn.id_phase_from) xnn ON xnn.id_pm=pmn.id
			WHERE pmn.id=pm.id
			  AND pmp.id=pm.id
			  AND pmcf.id=pm.id
			  AND pm.id_phase=p.id
			  AND p.id_trn='.$_SESSION['tournament'].'
			  AND p.flag_small_final=0
			ORDER BY curr_ph_level DESC, curr_ph_code ASC, curr_pm_seq ASC, next_rnk_from ASC';
			//echo $sql_requete;
			$req = mysql_query($sql_requete);
			$level = "";
			$cnt_lev = -1;
			$fst_in_lev = true;
			$phase = 0;
			$fst_in_ph =  true;
			$phase_member = 0;
			$offset = array();
			$offset[0][0] = 1;
			$offset[0][1] = 1;
			$offset[1][0] = 1;
			$offset[1][1] = 1;
			$offset[2][0] = 3;
			$offset[2][1] = 5.5;
			$offset[3][0] = 8;
			$offset[3][1] = 15;
			$offset[4][0] = 17.5;
			$offset[4][1] = 10; // Small final
			$incr = -1;
			while($ligne_req = mysql_fetch_array($req)){
				if ($ligne_req['curr_ph_level'] != $level){
					$fst_in_lev = true;
					$level = $ligne_req['curr_ph_level'];
					$cnt_lev ++;
	?>
						</ul>
					</li>
				</ul>
			</div>
			<div class="dsp_eq_table" style="width:<?php if ($level==99){echo '20%';}else{echo '17%';}?>;">
				<ul class="dsp_eq_row_18">
					<li class="final_header"><?php echo app_level_to_str($ligne_req['curr_ph_level']);?></li>
	<?php
				}
				if($ligne_req['curr_ph_id'] != $phase AND !$fst_in_lev){
	?>
						</ul>
					</li>
	<?php
				}
				if($fst_in_lev){$off_s = $offset[$cnt_lev][0];$fst_in_lev = false;}else{$off_s = $offset[$cnt_lev][1];}
				
				if ($ligne_req['curr_ph_id'] != $phase){
					$phase = $ligne_req['curr_ph_id'];
					$fst_in_ph = true;
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
				}
				if($ligne_req['curr_pm_id'] != $phase_member AND !$fst_in_ph){
	?>
						</ul>
					</li>
	<?php
				}
				if($fst_in_ph){$class = 'final_group';$fst_in_ph = false;}else{$class = 'final_group_blank';}
								
				if ($ligne_req['curr_pm_id'] != $phase_member){
					$incr ++;
					$phase_member = $ligne_req['curr_pm_id'];
	?>
					<li class="final_m">
						<input type="hidden" id="<?php echo $ligne_req['curr_pm_id'];?>" name="incr_<?php echo $incr;?>" value="<?php echo $incr; ?>"/>
						<input type="hidden" id="frc_ndm_<?php echo $incr;?>_id" name="id_frc_<?php echo $incr;?>" value="<?php echo $ligne_req['curr_id_frc'];?>"/>
						<input type="hidden" id="frc_ndm_<?php echo $incr;?>_id_ph" name="id_ph_<?php echo $incr;?>" value="<?php echo $ligne_req['curr_ph_id'];?>"/>
						<input type="hidden" id="frc_ndm_<?php echo $incr;?>_id_pm" name="id_pm_<?php echo $incr;?>" value="<?php echo $ligne_req['curr_pm_id'];?>"/>
						<input type="hidden" id="frc_ndm_<?php echo $incr;?>_id_ent" name="id_ent_<?php echo $incr;?>" value="<?php echo $ligne_req['curr_id_ent'];?>"/>
						<input type="hidden" id="frc_ndm_<?php echo $incr;?>_code" name="code_<?php echo $incr;?>" value="<?php echo $ligne_req['curr_code_ent'];?>"/>
						<input type="hidden" id="frc_ndm_<?php echo $incr;?>_rank" name="rank_<?php echo $incr;?>" value="<?php echo $ligne_req['curr_frc_rank'];?>"/>
						<input type="hidden" id="frc_ndm_<?php echo $incr;?>_change" name="change_<?php echo $incr;?>" value="0"/>
						<ul class="final_match">
	<?php
					if($ligne_req['curr_ph_level'] == 99){
	?>
							<li class="<?php echo $class;?>"><?php echo $ligne_req['curr_ph_code'];?></li>
	<?php
					}
					$dsp="block";
					$empty="";
					if($ligne_req['curr_id_ent']!=-1){
						$dsp = "none";
						$empty="_empty";
	?>
							<li id="frc_ndm_<?php echo $incr;?>" class="final_item<?php if ($ligne_req['curr_frc_rank']==1){echo " m_eq_v";}elseif($ligne_req['curr_frc_rank']==2){echo " m_eq_n";}?>" style="display:block;background-image:url('img/<?php if($ligne_req['curr_id_ent']!=-1){echo $ligne_req['curr_id_ent'];}else{echo 'blank';}?>.png');"><?php echo $ligne_req['curr_code_ent'];?></li>
	<?php
					}
	?>
							<li id="frc_ndm_<?php echo $incr.$empty;?>" class="final_item" style="display:<?php echo $dsp;?>;background-image:url('img/blank.png');"><?php echo $ligne_req['curr_code_x'];?></li>
							<li class="final_mark"><input id="frc_ndm_<?php echo $incr;?>_rad_1" type="radio" title="Je clique si je pense que cette &eacute;quipe finit 1e de son groupe" onchange="frc_ndm_val('frc_ndm_<?php echo $incr;?>',1);" name="frc_ndm_<?php echo $ligne_req['curr_ph_id'];?>_1" <?php if($ligne_req['curr_frc_rank']==1){echo "checked";}?> <?php if($ligne_req['curr_id_ent']==-1){echo 'disabled';}?>></li>
							<input type="hidden" id="frc_ndm_<?php echo $incr;?>_next_1" name="id_next_<?php echo $incr;?>" value="<?php echo $ligne_req['next_pm_id'];?>"/>
	<?php
				}
				elseif($ligne_req['curr_ph_level'] == 99){
	?>
							<li class="final_mark"><input id="frc_ndm_<?php echo $incr;?>_rad_2" type="radio" title="Je clique si je pense que cette &eacute;quipe finit 2e de son groupe" onchange="frc_ndm_val('frc_ndm_<?php echo $incr;?>',2);" name="frc_ndm_<?php echo $ligne_req['curr_ph_id'];?>_2" <?php if($ligne_req['curr_frc_rank']==2){echo "checked";}?>  <?php if($ligne_req['curr_id_ent']==-1){echo 'disabled';}?>></li>
							<input type="hidden" id="frc_ndm_<?php echo $incr;?>_next_2" name="id_next_<?php echo $incr;?>" value="<?php echo $ligne_req['next_pm_id'];?>"/>
	<?php
				}
			}
	?>
						</ul>
					</li>
				</ul>
			</div>
			<div class="dsp_eq_table" style="width:4%;">
				<ul class="dsp_eq_row_18">
	<?php
			for($i=0;$i<15;$i++){
	?>
					<li class="final_blank"></li>
	<?php
			}
	?>
				</ul>
				<img src="img/fifa_wc.png"/>
			</div>
		</div>
		<input type="hidden" name="action" value="ndm" />
		<input type="hidden" name="ndm_incr" value="<?php echo $incr; ?>" />
	</form>
	</div>
	<?php
	}
	
	function frc_ndm_frm_ls($id_user){
	?>
	<div id="frc_ndm_frm_ls" class="bloc" style="margin-bottom:0;margin-top:5px;">
		<form action="#" method="post" name="frc_ndm_frm">
		<input class="submit" style="float:right;" type="submit" name="val_ndm_ls" value="Valider"/>
		<input type="hidden" name="action" value="visu_ndm" />
		<select size="1" style="height:20px;width:25%;font-size:13px;margin:4px;float:right;" name="visu_ndm_user">
			<option value="<?php echo $_SESSION['id_user']; ?>" selected='selected'>Mon prono Nostradamus</option>
	<?php
		app_bdd_conn($_SESSION['user_is_admin']);
		$sql_requete = '
		SELECT u.id, u.dsp_name
		FROM app_users u,cmn_club_users cu
		WHERE u.id=cu.id_user
		  AND cu.id_club IN (SELECT c.id FROM cmn_clubs c,cmn_club_users cu1 WHERE cu1.id_user='.$id_user.' AND c.id=cu1.id_club AND c.id_trn='.$_SESSION['tournament'].')
		ORDER BY u.dsp_name ASC';
		$req = mysql_query($sql_requete);
		while($ligne_req = mysql_fetch_array($req)){
	?>
			<option value="<?php echo $ligne_req['id']; ?>" <?php if($id_user==$ligne_req['id']){echo "selected='selected'";}?>>Les pronos de <?php echo $ligne_req['dsp_name'];?></option>
	<?php
		}
	?>		
		</select>
		</form>
		<h2 class="commut" onclick="display_commut('frc_ndm_div_frm');">Simulation du tableau entier</h2>
		<div id="frc_ndm_div_frm" class="ubloc" style="text-align:center;display:block;">
			<div class="dsp_eq_table" style="width:0%">
				<ul>
	<?php
			app_bdd_conn($_SESSION['user_is_admin']);
			$sql_requete = '
			SELECT pm.id curr_pm_id,p.id curr_ph_id,p.code curr_ph_code,p.trn_level curr_ph_level,p.flag_small_final curr_ph_smfin,p.flag_group curr_ph_fgroup,pm.seq_entity curr_pm_seq,
				   IFNULL(xpp.frc_value,"blank") curr_id_ent,IFNULL(xpp.frc_label,IFNULL(CONCAT(xpp.rank_ent_from,"e ",xpp.code),pme.code)) curr_code_ent, IFNULL(ndc.rnk_pm,-1) curr_frc_rank,IFNULL(CONCAT(xpp.rank_ent_from,"e ",xpp.code),-1) curr_code_x,IFNULL(ndc.id,-1) curr_id_frc,
				   IFNULL(ndc.frc_is_good,0) curr_frc_good
			FROM trn_phases p,
				 trn_phase_members pm LEFT JOIN ref_entities pme ON pme.id=pm.id_entity,
				 trn_phase_members pmcf LEFT JOIN frc_nostradamus ndc ON ndc.id_ph_mb=pmcf.id AND ndc.id_user='.$id_user.',
				 trn_phase_members pmp LEFT JOIN (SELECT pmpp.id_entity id,pmpp.id id_pm,pp.id id_ph,pp.code,xp.rank_ent_from,ndp.frc_value,ndp.frc_label FROM trn_phases pp,trn_phase_members pmpp,trn_x_phases xp LEFT JOIN frc_nostradamus ndp ON ndp.id_ph=xp.id_phase_from AND ndp.rnk_pm=xp.rank_ent_from AND ndp.id_user='.$id_user.' WHERE xp.seq_ent_to=pmpp.seq_entity AND xp.id_phase_to=pmpp.id_phase AND pp.id=xp.id_phase_from) xpp ON xpp.id_pm=pmp.id
			WHERE pmp.id=pm.id
			  AND pmcf.id=pm.id
			  AND pm.id_phase=p.id
			  AND p.id_trn='.$_SESSION['tournament'].'
			  AND p.flag_small_final=0
			ORDER BY curr_ph_level DESC, curr_ph_code ASC, curr_pm_seq ASC';
			//echo $sql_requete;
			$req = mysql_query($sql_requete);
			$level = "";
			$cnt_lev = -1;
			$fst_in_lev = true;
			$phase = 0;
			$fst_in_ph =  true;
			$phase_member = 0;
			$offset = array();
			$offset[0][0] = 1;
			$offset[0][1] = 1;
			$offset[1][0] = 1;
			$offset[1][1] = 1;
			$offset[2][0] = 3;
			$offset[2][1] = 5.5;
			$offset[3][0] = 8;
			$offset[3][1] = 15;
			$offset[4][0] = 17.5;
			$offset[4][1] = 10; // Small final
			$incr = -1;
			while($ligne_req = mysql_fetch_array($req)){
				if ($ligne_req['curr_ph_level'] != $level){
					$fst_in_lev = true;
					$level = $ligne_req['curr_ph_level'];
					$cnt_lev ++;
	?>
						</ul>
					</li>
				</ul>
			</div>
			<div class="dsp_eq_table" style="width:<?php if ($level==99){echo '20%';}else{echo '17%';}?>;">
				<ul class="dsp_eq_row_18">
					<li class="final_header"><?php echo app_level_to_str($ligne_req['curr_ph_level']);?></li>
	<?php
				}
				if($ligne_req['curr_ph_id'] != $phase AND !$fst_in_lev){
	?>
						</ul>
					</li>
	<?php
				}
				if($fst_in_lev){$off_s = $offset[$cnt_lev][0];$fst_in_lev = false;}else{$off_s = $offset[$cnt_lev][1];}
				
				if ($ligne_req['curr_ph_id'] != $phase){
					$phase = $ligne_req['curr_ph_id'];
					$fst_in_ph = true;
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
				}
				if($ligne_req['curr_pm_id'] != $phase_member AND !$fst_in_ph){
	?>
						</ul>
					</li>
	<?php
				}
				if($fst_in_ph){$class = 'final_group';$fst_in_ph = false;}else{$class = 'final_group_blank';}
								
				if ($ligne_req['curr_pm_id'] != $phase_member){
					$incr ++;
					$phase_member = $ligne_req['curr_pm_id'];
	?>
					<li class="final_m">
						<ul class="final_match">
	<?php
					if($ligne_req['curr_ph_level'] == 99){
	?>
							<li class="<?php echo $class;?>"><?php echo $ligne_req['curr_ph_code'];?></li>
	<?php
					}
					$dsp="block";
					$empty="";
					if($ligne_req['curr_id_ent']!=-1){
						$dsp = "none";
						$empty="_empty";
	?>
							<li id="frc_ndm_<?php echo $incr;?>" class="final_item<?php if ($ligne_req['curr_frc_rank']==1){echo " m_eq_v";}elseif($ligne_req['curr_frc_rank']==2){echo " m_eq_n";}?>" style="display:block;background-image:url('img/<?php if($ligne_req['curr_id_ent']!=-1){echo $ligne_req['curr_id_ent'];}else{echo 'blank';}?>.png');"><?php echo $ligne_req['curr_code_ent'];?></li>
	<?php
					}
	?>
							<li id="frc_ndm_<?php echo $incr.$empty;?>" class="final_item" style="display:<?php echo $dsp;?>;background-image:url('img/blank.png');"><?php echo $ligne_req['curr_code_x'];?></li>
	<?php
					if($ligne_req['curr_frc_good'] == 1){
	?>
							<img src="img/ok_small.png"/>
	<?php
					}
				}
			}
	?>
						</ul>
					</li>
				</ul>
			</div>
			<div class="dsp_eq_table" style="width:4%;">
				<ul class="dsp_eq_row_18">
	<?php
			for($i=0;$i<15;$i++){
	?>
					<li class="final_blank"></li>
	<?php
			}
	?>
				</ul>
				<img src="img/fifa_wc.png"/>
			</div>
		</div>
	</div>
	<?php
	}
	
	function frc_frc_frm($id_trn){
	?>
	<div id="frc_frc_div" class="bloc">
		<form action="#" method="post" name="frc_frc_frm">
		<input class="submit" style="float:right;" type="submit" name="val_frc" value="Valider"/>
		<h2 class="commut" onclick="display_commut('frc_frc_div_frm');">Mes pronostics sur chaque match</h2>
		<div id="frc_frc_div_frm" class="ubloc" style="display:block;">
			<ul class="match" style="display:block">
				<li class="m_rnk_b" style="display:block;width:99%;line-height:22px;vertical-align:middle;"><span class="comment">L&eacute;gende : XX % - Part des joueurs ayant pronostiqu&eacute; le m&ecirc;me r&eacute;sultat - </span><img src="img/ok_small.png"/><span class="comment">R&eacute;sultat correct trouv&eacute; </span><img src="img/star_small.png"/><span class="comment">Score exact ou qualifi&eacute; trouv&eacute; </span><img src="img/error_small.png"/><span class="comment">Prono tout faux</span></li>
			</ul>
			<div id="frc_frc_div_frm_tabs">
				<div>
	<?php
		app_bdd_conn($_SESSION['user_is_admin']);
		// restreindre sur phases où il y a toutes les équipes
		$sql_requete = '
		SELECT p.trn_level
		FROM trn_phases p,
			 (SELECT count(id) nb_pm, id_phase FROM trn_phase_members WHERE id_entity IS NOT NULL GROUP BY id_phase) pm
		WHERE p.id_trn='.$id_trn.'		  
		  AND pm.id_phase=p.id
		  AND p.nb_members = pm.nb_pm
		GROUP BY p.trn_level
		ORDER BY p.trn_level DESC';
		$req = mysql_query($sql_requete);
		$current_lev = get_trn_level($_SESSION['tournament']);
		while($ligne_req = mysql_fetch_array($req)){
			if($ligne_req['trn_level']==$current_lev){$class_lev = 'curr_tabs';}else{$class_lev = 'tabs';}
	?>
					<ul class="tabs">
						<li class="tabs">
							<a href="javascript:display_tabs('<?php echo $ligne_req['trn_level']."_frc','frc_frc_div_frm_tabs"; ?>');" class="<?php echo $class_lev;?>" id="lien_<?php echo $ligne_req['trn_level']; ?>_frc"><?php echo app_level_to_str($ligne_req['trn_level']); ?></a>
						</li>
					</ul>
	<?php
		}
		unset($ligne_req);
	?>
				</div>
				<div>
					<div>
	<?php
		app_bdd_conn($_SESSION['user_is_admin']);		
		$sql_requete = '
		SELECT m.id, m.mt_date, m.mt_day,DATE_FORMAT(IFNULL(m.mt_date,NOW()),"%d/%m %Hh") date_fr, DATE(m.mt_date) mt_date_comp, TIME(m.mt_date) mt_time_comp, IFNULL(m.nb_mks_home,-1) nb_mks_home, IFNULL(m.nb_mks_away,-1) nb_mks_away, IFNULL(m.pen_home,-1) pen_home, IFNULL(m.pen_away,-1) pen_away, IFNULL(m.flag_ap,-1) flag_ap, IFNULL(m.final_result,-1) final_result,
			   IFNULL(fm.id,-1) id_fm, IFNULL(fm.nb_mks_home,0) fm_mks_home, IFNULL(fm.nb_mks_away,0) fm_mks_away, IFNULL(fm.flag_ap,-1) fm_flag_ap, IFNULL(fm.frc_int_result,-1) fm_int_result, IFNULL(fm.frc_final_result,-1) fm_final_result, IFNULL(fm.frc_is_checked,-1) fm_is_checked, IFNULL(fm.frc_is_good,-1) fm_is_good, IFNULL(fm.frc_is_bonused,-1) fm_is_bonused, 
			   ed.code code_ed, ed.label libelle_ed, ed.id icone_ed, ee.code code_ee, ee.label libelle_ee, ee.id icone_ee,
			   p.trn_level, p.code, p.label, p.flag_group,p.id id_phase,
			   NOW() date_comp,
			   IFNULL(fs1.res1,0) res1,IFNULL(fs2.res2,0) res2,IFNULL(fsn.resn,0) resn,IFNULL(fst.rest,0) rest
		FROM trn_matches m LEFT JOIN frc_matches fm on fm.id_match = m.id AND fm.id_user = '.$_SESSION['id_user'].',
			 trn_matches ms1 LEFT JOIN (SELECT id_match,count(id) res1 FROM frc_matches WHERE frc_int_result="1" GROUP BY id_match) fs1 on fs1.id_match = ms1.id,
			 trn_matches ms2 LEFT JOIN (SELECT id_match,count(id) res2 FROM frc_matches WHERE frc_int_result="2" GROUP BY id_match) fs2 on fs2.id_match = ms2.id,
			 trn_matches msn LEFT JOIN (SELECT id_match,count(id) resn FROM frc_matches WHERE frc_int_result="N" GROUP BY id_match) fsn on fsn.id_match = msn.id,
			 trn_matches mst LEFT JOIN (SELECT id_match,count(id) rest FROM frc_matches GROUP BY id_match) fst on fst.id_match = mst.id,
			 trn_phase_members gd,
			 ref_entities ed,
			 trn_phase_members ge,
			 ref_entities ee,
			 trn_phases p
		WHERE p.id_trn='.$id_trn.' 
		  AND p.id = m.id_phase
		  AND ee.id = ge.id_entity
		  AND ge.id = m.id_ph_mb_away
		  AND ed.id = gd.id_entity
		  AND gd.id = m.id_ph_mb_home
		  AND mst.id=msn.id
		  AND msn.id=ms2.id
		  AND ms2.id=ms1.id
		  AND ms1.id=m.id
		  ORDER BY trn_level DESC, code ASC, mt_day ASC, mt_date ASC, id ASC';
		//echo $sql_requete;
		$req = mysql_query($sql_requete);
		$level = "";
		$menu_poule = true;
		$incr = 0;
		$code_ph = "";
		$fst_lev = true;
		$fst_gr = true;
		while($ligne_req = mysql_fetch_array($req)){
			if($level != $ligne_req['trn_level']){
				if($ligne_req['trn_level']==$current_lev){$dsp_lev = 'block';}else{$dsp_lev = 'none';}
	?>
					</div>
				</div>
				<div id="tab_<?php echo $ligne_req['trn_level']; ?>_frc" style="display:<?php echo $dsp_lev; ?>;">
					<div>
	<?php
				$level = $ligne_req['trn_level'];
				if($ligne_req['trn_level'] != 99){
	?>
						<ul class="match" style="display:block">
							<li class="m_rnk_b" style="display:block;width:99%;line-height:22px;vertical-align:middle;"><span class="comment" style="font-style:italic;color:green;">Les scores et/ou r&eacute;sultats pronostiqu&eacute;s sont ceux à la suite de 90 mins de jeu. La bo&icirc;te "Qui passe ?" permet de dire qui passe &agrave; la fin</span></li>
						</ul>
	<?php		
				}
			}
			if ($ligne_req['trn_level'] == 99 AND $menu_poule){
	?>
					</div>
					<div>
	<?php
				app_bdd_conn($_SESSION['user_is_admin']);		
				$sql_requete1 = 'SELECT p.label, p.code, p.id id_phase FROM trn_phases p WHERE p.id_trn='.$id_trn.' AND p.trn_level='.$ligne_req['trn_level'].' ORDER BY p.code ASC';
				$req1 = mysql_query($sql_requete1);
				while($ligne_req1 = mysql_fetch_array($req1)){
					if($fst_gr){$class_gr = 'curr_tabs';$fst_gr = false;}else{$class_gr = 'tabs';}
	?>
						<ul class="tabs">
							<li class="tabs">
								<a href="javascript:display_tabs('<?php echo $ligne_req1['id_phase']."_frc_1','tab_".$ligne_req['trn_level']."_frc"; ?>');" class="<?php echo $class_gr;?>" id="lien_<?php echo $ligne_req1['id_phase']; ?>_frc_1"><?php echo $ligne_req1['label']; ?></a>
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
				$fst_gr = true;
			}
			if($code_ph != $ligne_req['id_phase']){
				if(!$fst_gr){$dsp_gr = 'none';}else{$dsp_gr = 'block';$fst_gr=false;}
				if($ligne_req['trn_level'] != 99){$dsp_gr = 'block';}
	?>
					</div>
					<div id="tab_<?php echo $ligne_req['id_phase']; ?>_frc_1" style="display:<?php echo $dsp_gr;?>;">
	<?php
				$code_ph = $ligne_req['id_phase'];
			}
			// if current datetime is later than match datetime, forecast is disabled
			$frc_disabled = false;
			if($ligne_req['mt_date'] < $ligne_req['date_comp']){$frc_disabled = true;}
	?>
						<div class="match_entete">
							<input type="hidden" name="frc_id_<?php echo $incr; ?>" value="<?php echo $ligne_req['id']; ?>" />
							<input type="hidden" name="frc_id_fm_<?php echo $incr; ?>" value="<?php echo $ligne_req['id_fm']; ?>" />
							<input type="hidden" name="frc_mtdate_<?php echo $incr; ?>" value="<?php echo $ligne_req['mt_date']; ?>" />
							<input type="hidden" id="frc_change_<?php echo $incr; ?>" name="frc_change_<?php echo $incr; ?>" value="0" />
							<ul class="match">
								<li class="m_date"><?php echo $ligne_req['date_fr'];?></li>
								<li id="frc_eq1_<?php echo $incr;?>" class="m_eqdom<?php if ($ligne_req['fm_int_result']==1){echo " m_eq_v";}elseif ($ligne_req['fm_int_result']=='N'){echo " m_eq_n";}?>" style="background-image:url('img/<?php echo $ligne_req['icone_ed'];?>.png');">
									<?php echo $ligne_req['libelle_ed'];?>
									<input style="float:right;" id="frc_1_<?php echo $incr;?>" type="radio" title="Je clique si je pense que cette &eacute;quipe gagne le match" name="frc_int_result_<?php echo $incr;?>" value="1" <?php if($ligne_req['fm_int_result']=='1'){echo "checked";}?> onchange="frc_val(<?php echo $incr;?>,'result',this)" <?php if($frc_disabled){echo 'disabled';}?>>
								</li>
								<li class="m_bdom" style="color:white;">
							<?php if(!$frc_disabled){ ?>
									<input class="marks" type="number" id="frc_bdom_<?php echo $incr;?>" name="frc_bdom_<?php echo $incr;?>" min="0" max="99" value="<?php echo app_max_void($ligne_req['fm_mks_home']);?>" onchange="frc_val(<?php echo $incr;?>,'',this)"/>
							<?php }else{ echo app_max_void($ligne_req['fm_mks_home']);} ?>
								</li>
								<li class="m_bext" style="color:white;">
							<?php if(!$frc_disabled){ ?>
									<input class="marks" type="number" id="frc_bext_<?php echo $incr;?>" name="frc_bext_<?php echo $incr;?>" min="0" max="99" value="<?php echo app_max_void($ligne_req['fm_mks_away']);?>" onchange="frc_val(<?php echo $incr;?>,'',this)"/>
							<?php }else{ echo app_max_void($ligne_req['fm_mks_away']);} ?>
								</li>
								<li id="frc_eq2_<?php echo $incr;?>" class="m_eqext<?php if ($ligne_req['fm_int_result']==2){echo " m_eq_v";}elseif ($ligne_req['fm_int_result']=='N'){echo " m_eq_n";}?>" style="background-image:url('img/<?php echo $ligne_req['icone_ee'];?>.png');">
									<?php echo $ligne_req['libelle_ee'];?>
									<input style="float:left;" id="frc_2_<?php echo $incr;?>" type="radio" title="Je clique si je pense que cette &eacute;quipe gagne le match" name="frc_int_result_<?php echo $incr;?>" value="2" <?php if($ligne_req['fm_int_result']=='2'){echo "checked";}?> onchange="frc_val(<?php echo $incr;?>,'result',this)" <?php if($frc_disabled){echo 'disabled';}?>>
								</li>
								<li class="m_jr">Nul ?
									<input type="radio" id="frc_n_<?php echo $incr;?>" title="Je clique si je pense que le r&eacute;sultat est un match nul" name="frc_int_result_<?php echo $incr;?>" value="N" <?php if($ligne_req['fm_int_result']=='N'){echo "checked";}?> onchange="frc_val(<?php echo $incr;?>,'result',this)" <?php if($frc_disabled){echo 'disabled';}?>>
								</li>
							<?php
								if($ligne_req['flag_group'] != 1){
							?>
								<input type="hidden" id="frc_q2_<?php echo $incr;?>" name="frc_final_result_<?php echo $incr; ?>" value="<?php echo $ligne_req['fm_final_result']; ?>" />
								<li class="m_rnk_b" style="width:10%;">
								<?php if(!$frc_disabled){ ?>
									<select id="frc_q_<?php echo $incr;?>" name="frc_fin_result_<?php echo $incr;?>" <?php if($ligne_req['fm_int_result']!='N'){echo "disabled";}?> style="width:90%;border:none;" onchange="document.getElementById('frc_change_<?php echo $incr;?>').value = '1';">
										<option value="0" selected>Qui passe ?</option>
										<option value="1" <?php if($ligne_req['fm_final_result']=='1'){echo 'selected';}?>><?php echo $ligne_req['libelle_ed'];?></option>
										<option value="2" <?php if($ligne_req['fm_final_result']=='2'){echo 'selected';}?>><?php echo $ligne_req['libelle_ee'];?></option>
									</select>
								<?php }
									else{
										if($ligne_req['fm_final_result']=='1'){echo $ligne_req['libelle_ed'];}
										elseif($ligne_req['fm_final_result']=='2'){echo $ligne_req['libelle_ee'];}
										else{echo 'No winner';}
									}
								?>
								</li>
							<?php
								}
								if($ligne_req['id_fm'] == -1 OR $ligne_req['rest'] == 0){
							?>
								<li class="m_jr" style="color:red;display:inline;" id="frc_stats_<?php echo $incr;?>_none">No prono</li>
							<?php
								}elseif($ligne_req['fm_is_checked'] == 0){
									// Stats result = 1
									if($ligne_req['fm_int_result'] == '1'){$dsp_1='inline';}else{$dsp_1='none';}
									$val_1 = $ligne_req['res1']/$ligne_req['rest']*100;
									$val_1 = number_format($val_1,0,","," ");
									if($val_1<25){$col_1="red";}elseif($val_1<51){$col_1="orange";}else{$col_1="green";}
									// Stats result = 2
									if($ligne_req['fm_int_result'] == '2'){$dsp_2='inline';}else{$dsp_2='none';}
									$val_2 = $ligne_req['res2']/$ligne_req['rest']*100;
									$val_2 = number_format($val_2,0,","," ");
									if($val_2<25){$col_2="red";}elseif($val_2<51){$col_2="orange";}else{$col_2="green";}
									// Stats result = N
									if($ligne_req['fm_int_result'] == 'N'){$dsp_n='inline';}else{$dsp_n='none';}
									$val_n = $ligne_req['resn']/$ligne_req['rest']*100;
									$val_n = number_format($val_n,0,","," ");
									if($val_n<25){$col_n="red";}elseif($val_n<51){$col_n="orange";}else{$col_n="green";}
							?>
								<li class="m_jr" id="frc_stats_<?php echo $incr;?>_1" style="color:<?php echo $col_1;?>;font-weight:bold;display:<?php echo $dsp_1;?>;"><?php echo $val_1;?> %</li>
								<li class="m_jr" id="frc_stats_<?php echo $incr;?>_2" style="color:<?php echo $col_2;?>;font-weight:bold;display:<?php echo $dsp_2;?>;"><?php echo $val_2;?> %</li>
								<li class="m_jr" id="frc_stats_<?php echo $incr;?>_n" style="color:<?php echo $col_n;?>;font-weight:bold;display:<?php echo $dsp_n;?>;"><?php echo $val_n;?> %</li>
							<?php
								}
								else{
							?>
								<li class="m_jr">
							<?php
									if($ligne_req['fm_is_good'] == 1){
							?>
								<img src="img/ok_small.png"/>
							<?php
									}
									if($ligne_req['fm_is_bonused'] == 1){
							?>
								<img src="img/star_small.png"/>
							<?php
									}
									if($ligne_req['fm_is_bonused'] == 0 AND $ligne_req['fm_is_good'] == 0){
							?>
								<img src="img/error_small.png"/>
							<?php
									}
							?>
								</li>
							<?php
								}
							?>
							</ul>
						</div>
	<?php
			$incr = $incr + 1;
		}
		unset($ligne_req);
	?>
					</div>
				</div>
			</div>
			<input type="hidden" name="action" value="frc" />
			<input type="hidden" name="frc_incr" value="<?php echo $incr; ?>" />
		</div>
		</form>
	</div>
	<?php
	}
	
	function frc_frc_frm_next($id_trn,$limit){
	?>
	<div id="frc_frc_div" class="bloc" style="margin-top:50px;">
		<form action="#" method="post" name="frc_frc_frm">
		<input class="submit" style="float:right;" type="submit" name="val_frc" value="Valider"/>
		<h2 class="commut" onclick="display_commut('frc_frc_div_frm');">Ne pas rater les <?php echo $limit;?> matches &agrave; venir ! Mes pronostics</h2>
		<div id="frc_frc_div_frm" class="ubloc" style="display:block;">
			<div id="frc_frc_div_frm_tabs">
				<ul class="match" style="display:block">
					<li class="m_rnk_b" style="display:block;width:99%;line-height:22px;vertical-align:middle;"><span class="comment">L&eacute;gende : XX % - Part des joueurs ayant pronostiqu&eacute; le m&ecirc;me r&eacute;sultat</li>
				</ul>
				<div>
					<div>
	<?php
		app_bdd_conn($_SESSION['user_is_admin']);		
		$sql_requete = '
		SELECT m.id, m.mt_date, m.mt_day,DATE_FORMAT(IFNULL(m.mt_date,NOW()),"%d/%m %Hh") date_fr, DATE(m.mt_date) mt_date_comp, TIME(m.mt_date) mt_time_comp, IFNULL(m.nb_mks_home,-1) nb_mks_home, IFNULL(m.nb_mks_away,-1) nb_mks_away, IFNULL(m.pen_home,-1) pen_home, IFNULL(m.pen_away,-1) pen_away, IFNULL(m.flag_ap,-1) flag_ap, IFNULL(m.final_result,-1) final_result,
			   IFNULL(fm.id,-1) id_fm, IFNULL(fm.nb_mks_home,0) fm_mks_home, IFNULL(fm.nb_mks_away,0) fm_mks_away, IFNULL(fm.flag_ap,-1) fm_flag_ap, IFNULL(fm.frc_int_result,-1) fm_int_result, IFNULL(fm.frc_final_result,-1) fm_final_result, IFNULL(fm.frc_is_checked,-1) fm_is_checked, IFNULL(fm.frc_is_good,-1) fm_is_good, IFNULL(fm.frc_is_bonused,-1) fm_is_bonused, 
			   ed.code code_ed, ed.label libelle_ed, ed.id icone_ed, ee.code code_ee, ee.label libelle_ee, ee.id icone_ee,
			   p.trn_level, p.code, p.label, p.flag_group,p.id id_phase,
			   NOW() date_comp,
			   IFNULL(fs1.res1,0) res1,IFNULL(fs2.res2,0) res2,IFNULL(fsn.resn,0) resn,IFNULL(fst.rest,0) rest
		FROM (SELECT * FROM trn_matches WHERE mt_date>NOW() AND id_trn='.$id_trn.' ORDER BY mt_date) m LEFT JOIN frc_matches fm on fm.id_match = m.id AND fm.id_user = '.$_SESSION['id_user'].',
			 trn_matches ms1 LEFT JOIN (SELECT id_match,count(id) res1 FROM frc_matches WHERE frc_final_result="1" GROUP BY id_match) fs1 on fs1.id_match = ms1.id,
			 trn_matches ms2 LEFT JOIN (SELECT id_match,count(id) res2 FROM frc_matches WHERE frc_final_result="2" GROUP BY id_match) fs2 on fs2.id_match = ms2.id,
			 trn_matches msn LEFT JOIN (SELECT id_match,count(id) resn FROM frc_matches WHERE frc_final_result="N" GROUP BY id_match) fsn on fsn.id_match = msn.id,
			 trn_matches mst LEFT JOIN (SELECT id_match,count(id) rest FROM frc_matches GROUP BY id_match) fst on fst.id_match = mst.id,
			 trn_phase_members gd,
			 ref_entities ed,
			 trn_phase_members ge,
			 ref_entities ee,
			 trn_phases p
		WHERE p.id_trn='.$id_trn.' 
		  AND p.id = m.id_phase
		  AND ee.id = ge.id_entity
		  AND ge.id = m.id_ph_mb_away
		  AND ed.id = gd.id_entity
		  AND gd.id = m.id_ph_mb_home
		  AND mst.id=msn.id
		  AND msn.id=ms2.id
		  AND ms2.id=ms1.id
		  AND ms1.id=m.id
		  ORDER BY mt_date ASC
		  LIMIT 0,'.$limit;
		//echo $sql_requete;
		$req = mysql_query($sql_requete);
		$level = "";
		$menu_poule = true;
		$incr = 0;
		$code_ph = "";
		$fst_lev = true;
		$fst_gr = true;
		while($ligne_req = mysql_fetch_array($req)){
	?>
						<div class="match_entete">
							<input type="hidden" name="frc_id_<?php echo $incr; ?>" value="<?php echo $ligne_req['id']; ?>" />
							<input type="hidden" name="frc_id_fm_<?php echo $incr; ?>" value="<?php echo $ligne_req['id_fm']; ?>" />
							<input type="hidden" name="frc_mtdate_<?php echo $incr; ?>" value="<?php echo $ligne_req['mt_date']; ?>" />
							<input type="hidden" id="frc_change_<?php echo $incr; ?>" name="frc_change_<?php echo $incr; ?>" value="0" />
							<ul class="match">
								<li class="final_group"><?php if ($ligne_req['flag_group']==1){echo $ligne_req['code'];}else{echo '1/'.$ligne_req['trn_level'];}?></li>
								<li class="m_date"><?php echo $ligne_req['date_fr'];?></li>
								<li id="frc_eq1_<?php echo $incr;?>" class="m_eqdom<?php if ($ligne_req['fm_int_result']==1){echo " m_eq_v";}elseif ($ligne_req['fm_int_result']=='N'){echo " m_eq_n";}?>" style="background-image:url('img/<?php echo $ligne_req['icone_ed'];?>.png');">
									<?php echo $ligne_req['libelle_ed'];?>
									<input style="float:right;" id="frc_1_<?php echo $incr;?>" type="radio" title="Je clique si je pense que cette &eacute;quipe gagne le match" name="frc_int_result_<?php echo $incr;?>" value="1" <?php if($ligne_req['fm_int_result']=='1'){echo "checked";}?> onchange="frc_val(<?php echo $incr;?>,'result',this)">
								</li>
								<li class="m_bdom" style="color:white;">
									<input class="marks" type="number" id="frc_bdom_<?php echo $incr;?>" name="frc_bdom_<?php echo $incr;?>" min="0" max="99" value="<?php echo app_max_void($ligne_req['fm_mks_home']);?>" onchange="frc_val(<?php echo $incr;?>,'',this)"/>
								</li>
								<li class="m_bext" style="color:white;">
									<input class="marks" type="number" id="frc_bext_<?php echo $incr;?>" name="frc_bext_<?php echo $incr;?>" min="0" max="99" value="<?php echo app_max_void($ligne_req['fm_mks_away']);?>" onchange="frc_val(<?php echo $incr;?>,'',this)"/>
								</li>
								<li id="frc_eq2_<?php echo $incr;?>" class="m_eqext<?php if ($ligne_req['fm_int_result']==2){echo " m_eq_v";}elseif ($ligne_req['fm_int_result']=='N'){echo " m_eq_n";}?>" style="background-image:url('img/<?php echo $ligne_req['icone_ee'];?>.png');">
									<?php echo $ligne_req['libelle_ee'];?>
									<input style="float:left;" id="frc_2_<?php echo $incr;?>" type="radio" title="Je clique si je pense que cette &eacute;quipe gagne le match" name="frc_int_result_<?php echo $incr;?>" value="2" <?php if($ligne_req['fm_int_result']=='2'){echo "checked";}?> onchange="frc_val(<?php echo $incr;?>,'result',this)">
								</li>
								<li class="m_jr">Nul ?
									<input type="radio" id="frc_n_<?php echo $incr;?>" title="Je clique si je pense que le r&eacute;sultat est un match nul" name="frc_int_result_<?php echo $incr;?>" value="N" <?php if($ligne_req['fm_int_result']=='N'){echo "checked";}?> onchange="frc_val(<?php echo $incr;?>,'result',this)">
								</li>
							<?php
								if($ligne_req['flag_group'] != 1){
							?>
								<input type="hidden" id="frc_q2_<?php echo $incr;?>" name="frc_final_result_<?php echo $incr; ?>" value="<?php echo $ligne_req['fm_final_result']; ?>" />
								<li class="m_rnk_b" style="width:10%;">
									<select id="frc_q_<?php echo $incr;?>" name="frc_fin_result_<?php echo $incr;?>" <?php if($ligne_req['fm_int_result']!='N'){echo "disabled";}?> style="width:90%;border:none;" onchange="document.getElementById('frc_change_<?php echo $incr;?>').value = '1';">
										<option value="0" selected>Qui passe ?</option>
										<option value="1" <?php if($ligne_req['fm_final_result']=='1'){echo 'selected';}?>><?php echo $ligne_req['libelle_ed'];?></option>
										<option value="2" <?php if($ligne_req['fm_final_result']=='2'){echo 'selected';}?>><?php echo $ligne_req['libelle_ee'];?></option>
									</select>
								</li>
							<?php
								}
								if($ligne_req['id_fm'] == -1 OR $ligne_req['rest'] == 0){
							?>
								<li class="m_rnk_b" style="color:red;display:inline;" id="frc_stats_<?php echo $incr;?>_none">No prono</li>
							<?php
								}elseif($ligne_req['fm_is_checked'] == 0){
									// Stats result = 1
									if($ligne_req['fm_final_result'] == '1'){$dsp_1='inline';}else{$dsp_1='none';}
									$val_1 = $ligne_req['res1']/$ligne_req['rest']*100;
									$val_1 = number_format($val_1,0,","," ");
									if($val_1<25){$col_1="red";}elseif($val_1<51){$col_1="orange";}else{$col_1="green";}
									// Stats result = 2
									if($ligne_req['fm_final_result'] == '2'){$dsp_2='inline';}else{$dsp_2='none';}
									$val_2 = $ligne_req['res2']/$ligne_req['rest']*100;
									$val_2 = number_format($val_2,0,","," ");
									if($val_2<25){$col_2="red";}elseif($val_2<51){$col_2="orange";}else{$col_2="green";}
									// Stats result = N
									if($ligne_req['fm_final_result'] == 'N'){$dsp_n='inline';}else{$dsp_n='none';}
									$val_n = $ligne_req['resn']/$ligne_req['rest']*100;
									$val_n = number_format($val_n,0,","," ");
									if($val_n<25){$col_n="red";}elseif($val_n<51){$col_n="orange";}else{$col_n="green";}
							?>
								<li class="m_rnk_b" id="frc_stats_<?php echo $incr;?>_1" style="color:<?php echo $col_1;?>;font-weight:bold;display:<?php echo $dsp_1;?>;"><?php echo $val_1;?> %</li>
								<li class="m_rnk_b" id="frc_stats_<?php echo $incr;?>_2" style="color:<?php echo $col_2;?>;font-weight:bold;display:<?php echo $dsp_2;?>;"><?php echo $val_2;?> %</li>
								<li class="m_rnk_b" id="frc_stats_<?php echo $incr;?>_n" style="color:<?php echo $col_n;?>;font-weight:bold;display:<?php echo $dsp_n;?>;"><?php echo $val_n;?> %</li>
							<?php
								}
							?>
							</ul>
						</div>
	<?php
			$incr = $incr + 1;
		}
		unset($ligne_req);
	?>
					</div>
				</div>
			</div>
			<input type="hidden" name="action" value="frc" />
			<input type="hidden" name="frc_incr" value="<?php echo $incr; ?>" />
		</div>
		</form>
	</div>
	<?php
	}
?>