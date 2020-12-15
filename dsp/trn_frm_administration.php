<?php
	function trn_new_frm(){
		$app_type_sport = array('FOOT','RUGBY','TENNIS');
		$app_type_entity = array('WORLD','CONTINENT','NATION','CLUB','PLAYER');
	?>
		<div id="trn_new_frm" class="bloc">
			<form action="#" method="post" name="trn_new_frm">
			<h2>Créer un nouveau tournoi</h2>
			<table>
				<tr><td colspan="5"><h3>Description du tournoi</h3></td></tr>
				<tr>
					<td><label for="code">Code</label><input type="text" name="code"/></td>
					<td><label for="label">Libellé</label><input type="text" name="label"/></td>
					<td><label for="flag_is_template">Est-ce un template ?</label><input type="checkbox" name="flag_is_template"/></td>
					<td>Appliquer un template existant<select name="id_template" size=1>
							<option value=0 selected="selected">Cr&eacute;er uniquement le template</option>
	<?php
		app_bdd_conn($_SESSION['user_is_admin']);
		$sql_requete = 'SELECT id, code, label FROM trn_tournaments WHERE flag_is_template=1 ORDER BY label';
		$req = mysql_query($sql_requete);
		while($ligne_req = mysql_fetch_array($req)){
	?>
							<option value="<?php echo $ligne_req['id']; ?>"><?php echo $ligne_req['code']." - ".$ligne_req['label']; ?></option>
	<?php
		}
	?>
						</select>
					</td>
				</tr>
				<tr>
					<td><label for="sport">Sport</label>
						<select name="sport" size=1>
							<option value=0 selected="selected">- Sport -</option>
	<?php
		for($i=0;$i<count($app_type_sport);$i++){
	?>
							<option value="<?php echo $app_type_sport[$i]; ?>"><?php echo ucfirst(strtolower($app_type_sport[$i])); ?></option>
	<?php
		}
	?>
						</select>
					</td>
					<td><label for="type_qual_ent">Type de participants</label>
						<select name="type_qual_ent" size=1>
							<option value=0 selected="selected">- Participants -</option>
	<?php
		for($i=0;$i<count($app_type_entity);$i++){
	?>
							<option value="<?php echo $app_type_entity[$i]; ?>"><?php echo ucfirst(strtolower($app_type_entity[$i])); ?></option>
	<?php
		}
	?>
						</select>
					</td>
					<td><label for="nb_qual_ent">Nombre de participants</label><input type="number" name="nb_qual_ent" min="2" max="128" /></td>
					<td><label for="flag_groups">Phase de poules ?</label><input type="checkbox" name="flag_groups" /></td>
					<td><label for="flag_final_table">Tableau final ?</label><input type="checkbox" name="flag_final_table" /></td>
				</tr>
				<tr><td colspan="5"><h3>Phase de poule</h3></td></tr>
				<tr>
					<td><label for="flag_sels">S&eacute;lections ?</label><input type="checkbox" name="flag_sels" /></td>
					<td><label for="nb_groups">Nombre de poules</label><input type="number" name="nb_groups" min="1" max="16" /></td>
					<td><label for="label_groups">Label des poules</label>
						<select name="label_groups" size=1>
							<option value="A">Groupe A, Groupe B, ...</option>
							<option value="1">Groupe 1, Groupe 2, ...</option>
						</select>
					</td>
					<td><label for="flag_groups_rt">Matches de poules en A/R ?</label><input type="checkbox" name="flag_groups_rt"/></td>
					<td><label for="nb_qual_after_gr">Nombre de qualifi&eacute;s par poule</label><input type="number" name="nb_qual_after_gr"min="1" max="128" /></td>
				</tr>
				<tr><td colspan="5"><h3>Tableau final</h3></td></tr>
				<tr>
					<td><label for="level_final_table_det">Dernier tirage</label>
						<select name="level_final_table_det" size=1>
							<option value="2">Demi-Finales</option>
							<option value="4">Quarts de Finale</option>
							<option value="8">Huiti&egrave;mes de Finale</option>
							<option value="16">Seizi&egrave;mes de Finale</option>
							<option value="32">32-i&egrave;mes de Finale</option>
							<option value="64">64-i&egrave;mes de Finale</option>
							<option value="99">Poules</option>
						</select>
					</td>
					<td><label for="flag_final_table_rt">Matches de tableau en A/R ?</label><input type="checkbox" name="flag_final_table_rt"/></td>
					<td><label for="flag_finals_rt">Finales en A/R ?</label><input type="checkbox" name="flag_finals_rt"/></td>
					<td><label for="flag_small_final">Petite Finale ?</label><input type="checkbox" name="flag_small_final"/></td>
					<td></td>
				</tr>
				<tr><td colspan="5"><h3>Edition</h3></td></tr>
				<tr>
					<td><label for="flag_instance">Instancier ce template</label><input type="checkbox" name="flag_instance"/></td>
					<td><label for="edition">Edition du tournoi</label><input type="text" name="edition"/></td>
					<td><label for="begin_date">Date de d&eacute;but</label><input type="text" name="begin_date"/></td>
					<td></td>
					<td></td>
				</tr>
				<tr><td colspan="5"><h3>Points de classement de poules</h3></td></tr>
				<tr>
					<td><label for="rnk_trn_vc">Sur victoire</label><input type="number" name="rnk_trn_vc" min="0" max="16" value="3"/></td>
					<td><label for="rnk_trn_df">Sur d&eacute;faite</label><input type="number" name="rnk_trn_df" min="0" max="16" value="0"/></td>
					<td><label for="rnk_trn_dw">Sur match nul</label><input type="number" name="rnk_trn_dw" min="0" max="16" value="1"/></td>
					<td></td>
					<td></td>
				</tr>
				<tr><td colspan="5"><h3>Points de classements des pronos</h3></td></tr>
				<tr>
					<td><label for="rnk_frc_good">Sur bon prono</label><input type="number" name="rnk_frc_good" min="1" max="99" value="10"/></td>
					<td><label for="rnk_frc_bonus">Sur bonus score</label><input type="number" name="rnk_frc_bonus" min="1" max="99" value="5"/></td>
					<td><label for="rnk_frc_ndm">Sur prono NDM</label><input type="number" name="rnk_frc_ndm" min="1" max="99" value="10"/></td>
					<td></td>
					<td></td>
				</tr>
				<input type="hidden" name="action" value="new" />
				<input type="submit" name="val_new" value="Valider" style="float:right;"/>
			</table>
			</form>
		</div>
	<?php
	}

	function trn_xmote_frm($id_trn,$status){
	?>
	<div style="text-align:center">
	<?php
		if($status>1){
	?>
		<form action="#" method="post" name="trn_xmote_frm">
			<input type="hidden" name="action" value="promote" />
			<input type="hidden" name="status" value=<?php echo $status-1; ?> />
			<input class="promote" type="submit" name="val_dmt" value="Revenir au niveau <?php echo $status-1; ?>"/>
		</form>
	<?php
		}
		if($status<4){
	?>
		<form action="#" method="post" name="frm_adm_1_prm"">
			<input type="hidden" name="action" value="promote" />
			<input type="hidden" name="status" value=<?php echo $status+1; ?> />
			<input class="promote" type="submit" name="val_prm" value="Passer au niveau <?php echo $status+1; ?>"/>
		</form>
	</div>
	<?php
		}
	}

	function trn_init_frm($id_trn){
		//frm_adm_rgl($id_trn); --> tuto sur liste de courses
		if (trn_tab_is_det($id_trn)){trn_xph_frm($id_trn);}
		trn_qlf_frm($id_trn);
		//frm_adm_cha($id_trn); --> même type que tirage (cf ci-dessous) ?
		trn_xmote_frm($id_trn,1);
	}

	function trn_xph_frm($id_trn){
	?>
	<div id="trn_xph_div" class="bloc">
		<form action="#" method="post" name="trn_xph_frm">
		<input class="submit" style="float:right;" type="submit" name="val_xph" value="Valider"/>
		<h2 class="commut" onclick="display_commut('trn_xph_div_frm');">Construire le chemin entre les phases</h2>
		<div id="trn_xph_div_frm" class="ubloc" style="display:none;">		
			<div id="trn_xph_div_frm_tabs">
				<div>
	<?php
		app_bdd_conn($_SESSION['user_is_admin']);	
		$sql_requete = '
		SELECT p.trn_level 
		FROM trn_tournaments t,trn_phases p 
		WHERE p.id_trn=t.id
		  AND p.trn_level>=t.level_final_table_det
		  AND p.id_trn='.$id_trn.'
		GROUP BY p.trn_level
		ORDER BY p.trn_level DESC';
		$req = mysql_query($sql_requete);
		while($ligne_req = mysql_fetch_array($req)){
	?>
					<ul class="tabs">
						<li class="tabs">
							<a href="javascript:display_tabs('<?php echo $ligne_req['trn_level']."_xph','trn_xph_div_frm_tabs"; ?>');" class="tabs" id="lien_<?php echo $ligne_req['trn_level']; ?>_xph"><?php echo app_level_to_str($ligne_req['trn_level']); ?></a>
						</li>
					</ul>
	<?php
		}
		unset($ligne_req);
	?>
				</div>
	<?php
		app_bdd_conn($_SESSION['user_is_admin']);	
		$sql_requete = '
		SELECT p.trn_level
		FROM trn_tournaments t,trn_phases p
		WHERE p.id_trn=t.id
		  AND p.trn_level>=t.level_final_table_det
		  AND p.trn_level>1
		  AND p.id_trn='.$id_trn.'
		GROUP BY p.trn_level
		ORDER BY p.trn_level DESC';
		$req = mysql_query($sql_requete);
		$incr = 0;
		while($ligne_req = mysql_fetch_array($req)){
			$trn_level = $ligne_req['trn_level'];
			$next_level = get_next_trn_level($trn_level,$id_trn);
	?>
				<div id="tab_<?php echo $ligne_req['trn_level'];?>_xph" style="display:none;">
					<div id="from_<?php echo $ligne_req['trn_level'];?>" class="list_equipes">
						<div class="dsp_eq_table">
							<ul class="dsp_eq_row_18">
	<?php
			app_bdd_conn($_SESSION['user_is_admin']);		
			$sql_requete = '
			SELECT pm.id_phase, pm.id_trn, pm.seq_entity, p.code, p.label, p.flag_group, IFNULL(x.rank_ent_from,0) rank
			FROM trn_phases p, trn_phase_members pm 
					LEFT JOIN trn_x_phases x ON x.id_phase_from=pm.id_phase AND x.rank_ent_from=pm.seq_entity AND x.id_trn_from='.$id_trn.'
			WHERE pm.id_phase = p.id
			  AND p.id_trn='.$id_trn.'
			  AND p.trn_level='.$trn_level.'
			ORDER BY code ASC,id_phase ASC,seq_entity ASC';
			//echo $sql_requete;
			$req0 = mysql_query($sql_requete);
			$curr_p = 0;
			$nb = 0;
			while($ligne_req0 = mysql_fetch_array($req0)){
				if($curr_p != $ligne_req0['id_phase']){
					$curr_p = $ligne_req0['id_phase'];
					if($ligne_req0['flag_group']==0){$nb=1;}else{$nb=get_trn_field($id_trn,'nb_qual_after_gr');}
	?>
							</ul>
						</div>
						<div class="dsp_eq_table">
							<ul class="dsp_eq_row_18" id="<?php echo $ligne_req0['code'];?>">
								<li id="hd_<?php echo $ligne_req0['code'];?>" class="drag_obj_header"><?php echo $ligne_req0['code'];?></li>
	<?php				
				}
				if($ligne_req0['seq_entity']<=$nb){
	?>
								<li id="<?php echo $ligne_req0['id_phase']."_".$ligne_req0['seq_entity'];?>" class="drag_obj<?php if($ligne_req0['rank']!=0){echo '_empty';}?>" <?php if($ligne_req0['rank']==0){echo 'draggable="true"';}?> ondragstart="startDrag(event);" ondragend="endDrag(event);"  ondrop="validateDrop(event,true);" ondragleave="leaveDrag(event);" ondragover="allowDrop(event);" dropzone="copy"><?php echo app_int_to_str($ligne_req0['seq_entity'])." - ".$ligne_req0['code'];?></li>
	<?php			
				}
			}
	?>						</ul>
						</div>
	<?php
			unset($req0);
			unset($ligne_req0);
	?>
					</div>
					<div id="to_<?php echo $ligne_req['trn_level'];?>" class="list_equipes">
						<div class="dsp_eq_table">
							<ul class="dsp_eq_row_18">
	<?php
			app_bdd_conn($_SESSION['user_is_admin']);		
			$sql_requete = '
			SELECT pm.id_phase, pm.id_trn, pm.seq_entity, p.code, p.label, p.flag_group, IFNULL(s.id_phase_from,0) id_phase_from, IFNULL(s.code,0) code_from, IFNULL(s.label,0) label_from, IFNULL(s.rank_ent_from,0) rank_from, IFNULL(s.id,0) xid
			FROM trn_phases p, trn_phase_members pm 
					LEFT JOIN (SELECT x.*,p2.code,p2.label FROM trn_x_phases x, trn_phases p2 WHERE p2.id=x.id_phase_from) s ON s.id_phase_to=pm.id_phase AND s.seq_ent_to=pm.seq_entity AND s.id_trn_to='.$id_trn.'
			WHERE pm.id_phase = p.id
			  AND p.id_trn='.$id_trn.'
			  AND p.trn_level='.$next_level.'
			ORDER BY code ASC,id_phase ASC,seq_entity ASC';
			//echo $sql_requete;
			$req0 = mysql_query($sql_requete);
			$curr_p = 0;
			$nb = 2;
			while($ligne_req0 = mysql_fetch_array($req0)){
				if($curr_p != $ligne_req0['id_phase']){
					$curr_p = $ligne_req0['id_phase'];
					//if($ligne_req0['flag_group']==0){$nb=1;}else{$nb=get_trn_field($id_trn,'nb_qual_after_gr');}
	?>
							</ul>
						</div>
						<div class="dsp_eq_table">
							<ul class="dsp_eq_row_18" id="<?php echo $ligne_req0['code'];?>">
								<li id="hd_<?php echo $ligne_req0['code'];?>" class="drag_obj_header"><?php echo $ligne_req0['code'];?></li>
	<?php				
				}
				$display = "block";
				if($ligne_req0['seq_entity']<=$nb){
					if($ligne_req0['xid']!=0){
						$display = "none";
	?>
								<li id="tozone_<?php echo $incr; ?>" class="drag_obj" draggable="true" ondragstart="startDrag(event);" ondragend="endDrag(event);"  ondrop="validateDrop(event,true);" ondragleave="leaveDrag(event);" ondragover="allowDrop(event);" dropzone="copy"><?php echo app_int_to_str($ligne_req0['rank_from'])." - ".$ligne_req0['code_from'];?></li>
	<?php
					}
	?>
								<li id="tozone_<?php echo $incr; ?>_empty" style="display:<?php echo $display;?>;" class="drag_obj_empty" ondrop="validateDrop(event,true);" ondragleave="leaveDrag(event);" ondragover="allowDrop(event);" dropzone="copy"><?php echo 'Seq '.$ligne_req0['seq_entity'];?></li>
								<input type="hidden" id="tozone_<?php echo $incr; ?>_data" name="xph_ph_from_<?php echo $incr; ?>" value="<?php if($ligne_req0['id_phase_from']==0){echo 0;}else{echo $ligne_req0['id_phase_from']."_".$ligne_req0['rank_from'];}?>"/>
								<input type="hidden" name="xph_ph_to_<?php echo $incr; ?>" value="<?php echo $ligne_req0['id_phase'];?>"/>
								<input type="hidden" name="xph_sq_<?php echo $incr; ?>" value="<?php echo $ligne_req0['seq_entity'];?>"/>
								<input type="hidden" name="xph_xid_<?php echo $incr; ?>" value="<?php echo $ligne_req0['xid'];?>"/>				
	<?php		
					$incr = $incr + 1;
				}
			}
	?>						</ul>
						</div>
	<?php
			unset($req0);
			unset($ligne_req0);
	?>
					</div>
				</div>
<?php
		}
		unset($req);
		unset($ligne_req);
	?>
				<input type="hidden" name="action" value="xph" />
				<input type="hidden" name="xph_incr" value="<?php echo $incr; ?>"/>
			</div>
		</div>
		</form>
	</div>
	<?php	
	}

	function trn_qlf_frm($id_trn){
		app_bdd_conn($_SESSION['user_is_admin']);
		$sql_requete = 'SELECT count(*) nb_qu, t.nb_qual_ent, t.type_qual_ent FROM trn_qual_entities q, trn_tournaments t WHERE q.id_trn='.$id_trn.' AND t.id=q.id_trn';
		$req = mysql_query($sql_requete);
		$ligne_req = mysql_fetch_array($req);
		$nb_qu = $ligne_req['nb_qu'];
		$nb_eq = $ligne_req['nb_qual_ent'];
		$type_ent = $ligne_req['type_qual_ent'];
		unset($ligne_req);
		if ($nb_qu > $nb_eq){$ft_col = 'red';}else{$ft_col = 'green';}
	?>
	<div id="trn_qlf_div" class="bloc">
		<form action="#" method="post" name="trn_qlf_frm">
		<input class="submit" style="float:right;" type="submit" name="val_qlf" value="Valider"/>
		<h2 class="commut" onclick="display_commut('trn_qlf_div_frm');">S&eacute;lectionner les &eacute;quipes qualifi&eacute;es</h2>
		<div id="trn_qlf_div_frm" class="ubloc" style="display:none;">		
			<div id="trn_qlf_div_frm_tabs">
				<div>
					<span style="font-weight:bold;color:<?php echo $ft_col; ?>"><?php echo $nb_qu; ?>/<?php echo $nb_eq; ?></span>
				</div>
				<div>
	<?php
			app_bdd_conn($_SESSION['user_is_admin']);
			$sql_requete = "SELECT e2.code, count(e1.id) nb_ent FROM ref_entities e1, ref_entities e2 WHERE e1.id_parent=e2.id AND e1.type_entity='".$type_ent."' GROUP BY code";
			$req = mysql_query($sql_requete);
			while($ligne_req = mysql_fetch_array($req)){
	?>
					<ul class="tabs">
						<li class="tabs">
							<a href="javascript:display_tabs('<?php echo $ligne_req['code']."_qlf','trn_qlf_div_frm_tabs"; ?>');" class="tabs" id="lien_<?php echo $ligne_req['code']; ?>_qlf"><?php echo $ligne_req['code'].' ('.$ligne_req['nb_ent'].')'; ?></a>
						</li>
					</ul>
	<?php
			}
			unset($ligne_req);
	?>
				</div>
				<div>
					<div>
						<ul>
	<?php
			app_bdd_conn($_SESSION['user_is_admin']);
			$sql_requete = "
			SELECT e1.id, e2.code code_parent, e1.code, IFNULL(q.id,-1) id_qual, IFNULL(q.id_trn,-1) id_trn_q 
			FROM ref_entities e2, ref_entities e1 
					LEFT JOIN trn_qual_entities q ON q.id_entity=e1.id AND q.id_trn=".$id_trn."
			WHERE e1.id_parent=e2.id
			  AND e1.type_entity='".$type_ent."'
			ORDER BY code_parent ASC, code ASC";
			$req = mysql_query($sql_requete);
			$incr = 0;
			$inc_t = 0;
			$fede = "";
			while($ligne_req = mysql_fetch_array($req)){
				if ($ligne_req['code_parent'] != $fede){
					$inc_t = 0;
	?>
						</ul>
					</div>
				</div>
				<div id="tab_<?php echo $ligne_req['code_parent'];?>_qlf" style="display:none;">
					<div class="dsp_eq_table">
						<ul class="dsp_eq_row_18">
	<?php
					$fede = $ligne_req['code_parent'];
				}
	?>
							<li id="qlf_<?php echo $ligne_req['id'];?>" class="drag_obj<?php if($ligne_req['id_qual']<>-1){echo '_handled';}?>" style="background-image:url('img/<?php echo $ligne_req['id'];?>.png');">
								<?php echo $ligne_req['code'];?>
								<input type="checkbox" style="float:right;" onchange="this.parentNode.className=(this.checked)?'drag_obj_handled':'drag_obj';" name="qlf_<?php echo $incr;?>" <?php if($ligne_req['id_qual']<>-1){echo 'checked';}?>/>
								<input type="hidden" name="update_qlf_<?php echo $incr; ?>" value="<?php if($ligne_req['id_qual']<>-1){echo 'update';}else{echo 'insert';}?>" />
								<input type="hidden" name="qlf_id_eq_<?php echo $incr; ?>" value="<?php echo $ligne_req['id'];?>" />
								<input type="hidden" name="qlf_id_trn_q_<?php echo $incr; ?>" value="<?php echo $ligne_req['id_trn_q'];?>" />
								<input type="hidden" name="qlf_id_qual_<?php echo $incr; ?>" value="<?php echo $ligne_req['id_qual'];?>" />
							</li>
	<?php
				$incr = $incr + 1;
				$inc_t = $inc_t + 1;
				if ($inc_t%4 == 0){
	?>
						</ul>
					</div>
					<div class="dsp_eq_table">
						<ul class="dsp_eq_row_18">
	<?php
				}
			}
			unset($ligne_req);
	?>
						</ul>
					</div>
				</div>
			</div>
			<input type="hidden" name="action" value="qlf" />
			<input type="hidden" name="qlf_incr" value="<?php echo $incr; ?>" />
		</div>
		</form>
	</div>
	<?php
	}

	function frm_adm_2($id_trn){
		trn_tir_frm($id_trn,get_first_trn_level($id_trn));
		trn_cal_frm($id_trn);
		//frm_adm_sel($id_trn);
		trn_xmote_frm($id_trn,2);
	}

	function trn_tir_frm($id_trn,$trn_level){
		app_bdd_conn($_SESSION['user_is_admin']);
		$prev_level = get_prev_trn_level($trn_level,$id_trn);
		
		// peut être améliorée en laissant les équipes déjà tirées au sort --> pour permettre corrections par drag and drop
		if($prev_level == -1){
			$sql_requete = 'SELECT q.id_entity, e.id, e.label, e.code FROM trn_qual_entities q, ref_entities e WHERE e.id=q.id_entity AND q.id_trn='.$id_trn.' AND NOT EXISTS (SELECT 1 FROM trn_phase_members g1, trn_phases p1 WHERE p1.id=g1.id_phase AND p1.id_trn='.$id_trn.' AND p1.trn_level='.$trn_level.' AND g1.id_entity=e.id) ORDER BY code';
		}
		else{
			$sql_requete = 'SELECT g.id_entity, e.id, e.label, e.code FROM trn_phases p, trn_phase_members g, ref_entity e WHERE e.id=g.id_entity AND g.flag_qualified=1 AND g.id_trn='.$id_trn.' AND g.id_phase=p.id AND p.trn_level='.$prev_level.' AND NOT EXISTS (SELECT 1 FROM trn_phase_members g1, trn_phases p1 WHERE p1.id=g1.id_phase AND p1.id_trn='.$id_trn.' AND p1.trn_level='.$trn_level.' AND g1.id_entity=e.id) ORDER BY code';
		}
		$req = mysql_query($sql_requete);
		//echo $sql_requete;
	?>
	<div id="trn_tir_div" class="bloc">
		<form action="#" method="post" name="trn_tir_frm">
		<input class="submit" style="float:right;" type="submit" name="val_tir" value="Valider"/>
		<h2 class="commut" onclick="display_commut('trn_tir_div_frm');">R&eacute;partir les &eacute;quipes qualifi&eacute;es</h2>
		<div id="trn_tir_div_frm" class="ubloc" style="display:none;">
			<div class="list_equipes">
				<div>
					<ul>
	<?php
		$incr = 0;
		while($ligne_req = mysql_fetch_array($req)){
			if($incr%4 == 0){
	?>
					</ul>
				</div>
				<div class="dsp_eq_table">
					<ul class="dsp_eq_row_18">
	<?php
			}
	?>
						<li id="<?php echo $ligne_req['id_entity'];?>" class="drag_obj" draggable="true" ondragstart="startDrag(event);" ondragend="endDrag(event);"  ondrop="validateDrop(event,true);" ondragleave="leaveDrag(event);" ondragover="allowDrop(event);" dropzone="copy" style="background-image:url('img/<?php echo $ligne_req['id'];?>.png');"><?php echo $ligne_req['code'];?></li>
	<?php
				$incr = $incr + 1;
			}
			unset($ligne_req);
	?>
					</ul>
				</div>
			</div>
			<div class="list_phases">
				<div>
					<ul>
	<?php
		$sql_requete = 'SELECT p.code, g.id id_pm, g.seq_entity, IFNULL(g.id_entity,0) id_entity, e.code code_eq FROM trn_phases p, trn_phase_members g LEFT JOIN ref_entities e ON e.id=g.id_entity WHERE g.id_phase=p.id AND p.id_trn='.$id_trn.' AND p.trn_level='.$trn_level.' ORDER BY p.code ASC, g.seq_entity ASC';
		$req = mysql_query($sql_requete);
		//echo $sql_requete;
		$phase = '';
		$incr = 0;
		while($ligne_req = mysql_fetch_array($req)){
			if($ligne_req['code']!= $phase){
	?>
					</ul>
				</div>
				<div class="dsp_eq_table">
					<ul class="dsp_eq_row_18" id="<?php echo $ligne_req['code'];?>">
						<li id="hd_<?php echo $ligne_req['code'];?>" class="drag_obj_header"><?php echo 'Groupe '.$ligne_req['code'];?></li>
	<?php
			}
			$display = 'block';
			if ($ligne_req['id_entity'] != 0){
	?>								
						<li id="tozone_<?php echo $incr; ?>" class="drag_obj" draggable="true" ondragstart="startDrag(event);" ondragend="endDrag(event);"  ondrop="validateDrop(event,true);" ondragleave="leaveDrag(event);" ondragover="allowDrop(event);" dropzone="copy" style="background-image:url('img/<?php echo $ligne_req['id_entity'];?>.png');"><?php echo $ligne_req['code_eq'];?></li>
	<?php
				$display = 'none';
			}
	?>
						<li id="tozone_<?php echo $incr; ?>_empty" style="display:<?php echo $display;?>;" class="drag_obj_empty"  ondrop="validateDrop(event,true);" ondragleave="leaveDrag(event);" ondragover="allowDrop(event);" dropzone="copy"><?php echo 'Eq '.$ligne_req['seq_entity'];?></li>
	
							<input type="hidden" id="tozone_<?php echo $incr; ?>_data" name="tir_id_eq_<?php echo $incr; ?>" value="<?php echo $ligne_req['id_entity'];?>" />
							<input type="hidden" id="tozone_<?php echo $incr; ?>_sqe" name="tir_sq_eq_<?php echo $incr; ?>" value="<?php echo $ligne_req['seq_entity'];?>" />
							<input type="hidden" name="tir_id_gr_<?php echo $incr; ?>" value="<?php echo $ligne_req['id_pm'];?>" />
	<?php
				$phase = $ligne_req['code'];
			$incr = $incr + 1;
		}
			unset($ligne_req);
	?>
					</ul>
				</div>
			</div>
			<input type="hidden" name="action" value="tir" />
			<input type="hidden" name="tir_incr" value="<?php echo $incr; ?>" />
		</div>
		</form>
	</div>
	<?php
	}
	
	function trn_cal_frm($id_trn){
	?>
	<div id="trn_cal_div" class="bloc">
		<form action="#" method="post" name="trn_cal_frm">
		<input class="submit" style="float:right;" type="submit" name="val_cal" value="Valider"/>
		<h2 class="commut" onclick="display_commut('trn_cal_div_frm');">D&eacute;finir les dates des matches</h2>
		<div id="trn_cal_div_frm" class="ubloc" style="display:none;">		
			<div id="trn_cal_div_frm_tabs">
				<div>
	<?php
		app_bdd_conn($_SESSION['user_is_admin']);
		$sql_requete = '
		SELECT p.trn_level
		FROM trn_phases p
		WHERE p.id_trn='.$id_trn.'
		GROUP BY p.trn_level
		ORDER BY p.trn_level DESC';
		$req = mysql_query($sql_requete);
		while($ligne_req = mysql_fetch_array($req)){
	?>
					<ul class="tabs">
						<li class="tabs">
							<a href="javascript:display_tabs('<?php echo $ligne_req['trn_level']."_cal','trn_cal_div_frm_tabs"; ?>');" class="tabs" id="lien_<?php echo $ligne_req['trn_level']; ?>_cal"><?php echo app_level_to_str($ligne_req['trn_level']); ?></a>
						</li>
					</ul>
	<?php
		}
		unset($ligne_req);
	?>
				</div>
				<div>
	<?php
		app_bdd_conn($_SESSION['user_is_admin']);		
		$sql_requete = '
		SELECT ma.id id_ma, ma.mt_date date_tri, DATE_FORMAT(IFNULL(ma.mt_date,NOW()),"%d/%m/%y") date_ma, DATE_FORMAT(IFNULL(ma.mt_date,NOW()),"%H:%i") heure_ma, IFNULL(ma.mt_day,1) journee_ma, IFNULL(mr.id,0) id_mr, DATE_FORMAT(IFNULL(mr.mt_date,NOW()),"%d/%m/%y") date_mr, DATE_FORMAT(IFNULL(mr.mt_date,NOW()),"%H:%i") heure_mr, IFNULL(mr.mt_day,1) journee_mr,
			   IFNULL(ed.code,"INC") code_ed, IFNULL(ed.label,"Inconnu") libelle_ed, IFNULL(ed.id,"blank") icone_ed, IFNULL(ee.code,"INC") code_ee, IFNULL(ee.label,"Inconnu") libelle_ee, IFNULL(ee.id,"blank") icone_ee,
			   p.trn_level, p.code, p.label, ge.id id_away, gd.id id_home
		FROM trn_matches ma,
			 trn_matches mr,
			 trn_phase_members gd LEFT JOIN ref_entities ed on ed.id = gd.id_entity,
			 trn_phase_members ge LEFT JOIN ref_entities ee on ee.id = ge.id_entity,
			 trn_phases p
		WHERE p.id_trn='.$id_trn.' 
		  AND p.id = ma.id_phase
		  AND ge.id = ma.id_ph_mb_away
		  AND gd.id = ma.id_ph_mb_home
		  AND mr.id_fst_leg = ma.id
		  AND ma.id_fst_leg IS NULL
		UNION
		SELECT ma.id id_ma, ma.mt_date date_tri, DATE_FORMAT(IFNULL(ma.mt_date,NOW()),"%d/%m/%y") date_ma, DATE_FORMAT(IFNULL(ma.mt_date,NOW()),"%H:%i") heure_ma, IFNULL(ma.mt_day,1) journee_ma, 0 id_mr, -1 date_mr, -1 heure_mr, -1 journee_mr,
			   IFNULL(ed.code,"INC") code_ed, IFNULL(ed.label,"Inconnu") libelle_ed, IFNULL(ed.id,"blank") icone_ed, IFNULL(ee.code,"INC") code_ee, IFNULL(ee.label,"Inconnu") libelle_ee, IFNULL(ee.id,"blank") icone_ee,
			   p.trn_level, p.code, p.label, ge.id id_away, gd.id id_home 
		FROM trn_matches ma,
			 trn_phase_members gd LEFT JOIN ref_entities ed on ed.id = gd.id_entity,
			 trn_phase_members ge LEFT JOIN ref_entities ee on ee.id = ge.id_entity,
			 trn_phases p
		WHERE p.id_trn='.$id_trn.' 
		  AND p.id = ma.id_phase
		  AND ge.id = ma.id_ph_mb_away
		  AND gd.id = ma.id_ph_mb_home
		  AND ma.id_fst_leg IS NULL
		  AND NOT EXISTS (SELECT 1 FROM trn_matches mr WHERE mr.id_fst_leg IS NOT NULL AND mr.id_fst_leg=ma.id)
		ORDER BY trn_level DESC, code ASC, date_tri ASC, id_ma ASC';
		//echo $sql_requete;
		$req = mysql_query($sql_requete);
		$level = "";
		$menu_poule = true;
		$incr = 0;
		while($ligne_req = mysql_fetch_array($req)){
			if($level != $ligne_req['trn_level']){
	?>
				</div>
				<div id="tab_<?php echo $ligne_req['trn_level']; ?>_cal" style="display:none;">
	<?php
				$level = $ligne_req['trn_level'];
			}
			if ($ligne_req['trn_level'] == 99 AND $menu_poule){
	?>
					<div>
	<?php
				app_bdd_conn($_SESSION['user_is_admin']);
				$sql_requete1 = '
				SELECT p.label, p.code
				FROM trn_phases p
				WHERE p.id_trn='.$id_trn.' AND p.trn_level='.$ligne_req['trn_level'].'
				ORDER BY p.code ASC';
				$req1 = mysql_query($sql_requete1);
				while($ligne_req1 = mysql_fetch_array($req1)){
	?>
						<ul class="tabs">
							<li class="tabs">
								<a href="javascript:display_tabs('<?php echo $ligne_req1['code']."_cal_1','tab_".$ligne_req['trn_level']."_cal"; ?>');" class="tabs" id="lien_<?php echo $ligne_req1['code']; ?>_cal_1"><?php echo $ligne_req1['label']; ?></a>
							</li>
						</ul>
	<?php
				}
	?>
					</div>
	<?php
				unset($ligne_req1);
				$menu_poule = false;
			}
	?>
					<div id="tab_<?php echo $ligne_req['code']; ?>_cal_1" <?php if($ligne_req['trn_level'] == 99){echo 'style="display:none;"';}?>>
						<div class="match_entete">
							<input type="hidden" name="cal_id_aller_<?php echo $incr; ?>" value="<?php echo $ligne_req['id_ma']; ?>" />
							<input type="hidden" name="cal_id_home_<?php echo $incr; ?>" value="<?php echo $ligne_req['id_home']; ?>" />
							<input type="hidden" name="cal_id_away_<?php echo $incr; ?>" value="<?php echo $ligne_req['id_away']; ?>" />
							<ul class="match">
							<?php 
								if($ligne_req['trn_level'] == 99){
							?>
								<li class="m_jr"><input style="text-align:center;" type="number" name="cal_jr_aller_<?php echo $incr;?>" min="1" max="6" value="<?php echo $ligne_req['journee_ma'];?>"/></li>
							<?php
								}
								else{
							?>
								<li class="m_jr"><?php echo $ligne_req['code'];?></li>
							<?php
								}
							?>
								<li class="m_date"><input type="text" name="cal_date_aller_<?php echo $incr;?>" size="6" style="text-align:center;" value="<?php echo $ligne_req['date_ma'];?>"/></li>
								<li class="m_eqdom" style="background-image:url('img/<?php echo $ligne_req['icone_ed'];?>.png');"><?php echo $ligne_req['libelle_ed'];?></li>
								<li class="m_hr"><input type="text" name="cal_hr_aller_<?php echo $incr;?>" size="10" style="text-align:center;"value="<?php echo $ligne_req['heure_ma'];?>"/></li>
								<li class="m_eqext" style="background-image:url('img/<?php echo $ligne_req['icone_ee'];?>.png');"><?php echo $ligne_req['libelle_ee'];?></li>
	<?php
			if ($ligne_req['id_mr'] == 0){
	?>
								<input type="checkbox" name="cal_switch_<?php echo $incr;?>" style="float:right;"/>
	<?php
			}
	?>
								
							</ul>
						</div>
	<?php
			if ($ligne_req['id_mr'] != 0){
	?>
						<div class="match_entete">
							<input type="hidden" name="cal_id_retour_<?php echo $incr; ?>" value="<?php echo $ligne_req['id_mr']; ?>" />
							<ul class="match">
								<li class="m_jr">
							<?php 
								if($ligne_req['trn_level'] == 99){
							?>
								<input style="text-align:center;" type="number" name="cal_jr_retour_<?php echo $incr;?>" min="1" max="6" value="<?php echo $ligne_req['journee_mr'];?>"/>
							<?php
								}
							?>	
								</li>
								<li class="m_date"><input type="text" name="cal_date_retour_<?php echo $incr;?>" size="6" style="text-align:center;" value="<?php echo $ligne_req['date_mr'];?>" /></li>
								<li class="m_empty">Match Retour</li>
								<li class="m_hr"><input type="text" name="cal_hr_retour_<?php echo $incr;?>" size="10" style="text-align:center;" value="<?php echo $ligne_req['heure_mr'];?>" /></li>
								<li class="m_empty">Inverser le match aller <input type="checkbox" name="cal_switch_ar_<?php echo $incr;?>"/></li>
							</ul>
						</div>
	<?php
			}
			$incr = $incr + 1;
	?>
					</div>
	<?php
		}
		unset($ligne_req);
	?>
				</div>
			</div>
			<input type="hidden" name="action" value="cal" />
			<input type="hidden" name="cal_incr" value="<?php echo $incr; ?>" />
		</div>
		</form>
	</div>
	<?php
	}

	function frm_adm_3($id_trn){
		trn_res_frm($id_trn);
		//trn_mrk_frm($id_trn);
		trn_cmn_rnk_frm($id_trn);
		trn_rnk_frm($id_trn);
		//trn_tir_frm($id_trn,get_trn_level($id_trn));
	}
	
	function trn_res_frm($id_trn){
	?>
	<div id="trn_res_div" class="bloc">
		<form action="#" method="post" name="trn_res_frm">
		<input class="submit" style="float:right;" type="submit" name="val_res" value="Valider"/>
		<h2 class="commut" onclick="display_commut('trn_res_div_frm');">Enregistrer des scores</h2>
		<div id="trn_res_div_frm" class="ubloc" style="display:none;">		
			<div id="trn_res_div_frm_tabs">
				<div>
	<?php
		app_bdd_conn($_SESSION['user_is_admin']);
		$sql_requete = 'SELECT p.trn_level FROM trn_phases p WHERE p.id_trn='.$id_trn.' AND EXISTS (SELECT 1 FROM trn_matches m WHERE m.id_phase=p.id AND m.final_result IS NULL) GROUP BY p.trn_level ORDER BY p.trn_level DESC';
		// restreindre sur niveaux où il y a des résutats à saisir (date passée et résultat à NULL)
		$req = mysql_query($sql_requete);
		while($ligne_req = mysql_fetch_array($req)){
	?>
					<ul class="tabs">
						<li class="tabs">
							<a href="javascript:display_tabs('<?php echo $ligne_req['trn_level']."_res','trn_res_div_frm_tabs"; ?>');" class="tabs" id="lien_<?php echo $ligne_req['trn_level']; ?>_res"><?php echo app_level_to_str($ligne_req['trn_level']); ?></a>
						</li>
					</ul>
	<?php
		}
		unset($ligne_req);
	?>
				</div>
				<div>
	<?php
		// restreindre sur matches où il y a des résutats à saisir (date passée et résultat à NULL)
		app_bdd_conn($_SESSION['user_is_admin']);		
		$sql_requete = '
		SELECT m.id, m.mt_date, m.mt_day,DATE_FORMAT(IFNULL(m.mt_date,NOW()),"%d/%m/%y") date_fr, IFNULL(m.nb_mks_home,-1) nb_mks_home, IFNULL(m.nb_mks_away,-1) nb_mks_away, IFNULL(m.int_mks_home,-1) int_mks_home, IFNULL(m.int_mks_away,-1) int_mks_away, IFNULL(m.pen_home,-1) pen_home, IFNULL(m.pen_away,-1) pen_away, IFNULL(m.flag_ap,-1) flag_ap, IFNULL(m.final_result,-1) final_result,
			   IFNULL(ed.code,"INC") code_ed, IFNULL(ed.label,"Inconnu") libelle_ed, IFNULL(ed.id,"blank") icone_ed, IFNULL(ee.code,"INC") code_ee, IFNULL(ee.label,"Inconnu") libelle_ee, IFNULL(ee.id,"blank") icone_ee,
			   p.trn_level, p.code, p.label, p.flag_group
		FROM trn_matches m,
			 trn_phase_members gd LEFT JOIN ref_entities ed on ed.id = gd.id_entity,
			 trn_phase_members ge LEFT JOIN ref_entities ee on ee.id = ge.id_entity,
			 trn_phases p
		WHERE p.id_trn='.$id_trn.' 
		  AND p.id = m.id_phase
		  AND ge.id = m.id_ph_mb_away
		  AND gd.id = m.id_ph_mb_home
		  AND m.final_result IS NULL
		ORDER BY trn_level DESC, code ASC, mt_day ASC, mt_date ASC, id ASC';
		$req = mysql_query($sql_requete);
		$level = "";
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
			if($level != $ligne_req['trn_level']){
	?>
				</div>
				<div id="tab_<?php echo $ligne_req['trn_level']; ?>_res" style="display:none;">
	<?php
				$level = $ligne_req['trn_level'];
			}
			if ($ligne_req['trn_level'] == 99 AND $menu_poule){
	?>
					<div>
	<?php
				app_bdd_conn($_SESSION['user_is_admin']);		
				$sql_requete1 = 'SELECT p.label, p.code FROM trn_phases p WHERE p.id_trn='.$id_trn.' AND p.trn_level='.$ligne_req['trn_level'].' ORDER BY p.code ASC';
				$req1 = mysql_query($sql_requete1);
				while($ligne_req1 = mysql_fetch_array($req1)){
	?>
						<ul class="tabs">
							<li class="tabs">
								<a href="javascript:display_tabs('<?php echo $ligne_req1['code']."_res_1','tab_".$ligne_req['trn_level']."_res"; ?>');" class="tabs" id="lien_<?php echo $ligne_req1['code']; ?>_res_1"><?php echo $ligne_req1['label']; ?></a>
							</li>
						</ul>
	<?php
				}
	?>
					</div>
	<?php
				unset($ligne_req1);
				$menu_poule = false;
			}
	?>
					<div id="tab_<?php echo $ligne_req['code']; ?>_res_1" <?php if($ligne_req['trn_level'] == 99){echo 'style="display:none;"';}?>>
						<div class="match_entete">
							<input type="hidden" name="res_id_<?php echo $incr; ?>" value="<?php echo $ligne_req['id']; ?>" />
							<input type="hidden" name="res_group_<?php echo $incr; ?>" value="<?php echo $ligne_req['flag_group']; ?>" />
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
								<li class="m_eqdom<?php if ($ligne_req['final_result']==1){echo " m_eq_v";}elseif ($ligne_req['final_result']=='N'){echo " m_eq_n";}?>" style="background-image:url('img/<?php echo $ligne_req['icone_ed'];?>.png');"><?php echo $ligne_req['libelle_ed'];?></li>
								<li class="m_bdom"><input class="marks" style="text-align:center;" type="number" name="res_bdom_<?php echo $incr;?>" min="0" max="99" value="<?php echo app_max_void($ligne_req['nb_mks_home']);?>"/></li>
								<li class="m_bext"><input class="marks" style="text-align:center;" type="number" name="res_bext_<?php echo $incr;?>" min="0" max="99" value="<?php echo app_max_void($ligne_req['nb_mks_away']);?>"/></li>
								<li class="m_eqext<?php if ($ligne_req['final_result']==2){echo " m_eq_v";}elseif ($ligne_req['final_result']=='N'){echo " m_eq_n";}?>" style="background-image:url('img/<?php echo $ligne_req['icone_ee'];?>.png');"><?php echo $ligne_req['libelle_ee'];?></li>
							<?php
								if($ligne_req['flag_group'] != 1){
							?>
								<li class="m_jr">90</li>
								<li class="m_bdom"><input class="marks" style="text-align:center;" type="number" name="res_idom_<?php echo $incr;?>" min="0" max="99" value="<?php echo app_max_void($ligne_req['int_mks_home']);?>"/></li>
								<li class="m_bext"><input class="marks" style="text-align:center;" type="number" name="res_iext_<?php echo $incr;?>" min="0" max="99" value="<?php echo app_max_void($ligne_req['int_mks_away']);?>"/></li>
								<li class="m_jr">TAB</li>
								<li class="m_bdom"><input class="marks" style="text-align:center;" type="number" name="res_pdom_<?php echo $incr;?>" min="0" max="99" value="<?php echo app_max_void($ligne_req['pen_home']);?>"/></li>
								<li class="m_bext"><input class="marks" style="text-align:center;" type="number" name="res_pext_<?php echo $incr;?>" min="0" max="99" value="<?php echo app_max_void($ligne_req['pen_away']);?>"/></li>
								<?php
								}
							?>
							</ul>
						</div>
	<?php
			$incr = $incr + 1;
	?>
					</div>
	<?php
		}
		unset($ligne_req);
	?>
				</div>
			</div>
			<input type="hidden" name="action" value="res" />
			<input type="hidden" name="res_incr" value="<?php echo $incr; ?>" />
		</div>
		</form>
	</div>
	<?php
	}
	
	function trn_cmn_rnk_frm($id_trn){
	?>
	<div id="trn_cmn_rnk_div" class="bloc">	
		<form action="#" method="post" name="frm_cmn_rnk_upd">
			<input type="hidden" name="action" value="upd_cmn_rnk"/>
			<input class="submit" style="float:right;" type="submit" name="val_ucrk" value="Mettre &agrave; jour"/>
		</form>
		<h2 class="commut">Mettre &agrave; jour les classements des utilisateurs</h2>
	</div>
	<?php
	}
	
	function trn_rnk_frm($id_trn){
		$app_type_qlf = array('90','ap','ext','tab');
	?>
	<div id="trn_rnk_div" class="bloc">	
		<form action="#" method="post" name="frm_rnk_val">
			<input type="hidden" name="action" value="val_lev"/>
			<input class="submit" style="float:right;" type="submit" name="val_vph" value="Valider le niveau"/>
		</form>
		<form action="#" method="post" name="frm_rnk_upd">
			<input type="hidden" name="action" value="upd_trn_rnk"/>
			<input class="submit" style="float:right;" type="submit" name="val_urk" value="Mettre &agrave; jour les classements"/>
		</form>
		<form action="#" method="post" name="trn_res_frm">
			<input type="hidden" name="action" value="val_phs"/>
			<input class="submit" style="float:right;" type="submit" name="val_uph" value="Valider"/>
		<h2 class="commut" onclick="display_commut('trn_rnk_div_frm');">Valider les classements des phases et les &eacute;quipes qualifi&eacute;es</h2>
		<div id="trn_rnk_div_frm" class="ubloc" style="display:none;">
			<div id="trn_rnk_div_frm_tabs">
				<div>
	<?php
		app_bdd_conn($_SESSION['user_is_admin']);
		$curr_level = get_trn_field($id_trn,'current_level');
		$sql_requete = 'SELECT p.trn_level FROM trn_phases p WHERE p.id_trn='.$id_trn.' AND p.trn_level='.$curr_level.' GROUP BY p.trn_level ORDER BY p.trn_level DESC';
		$req = mysql_query($sql_requete);
		while($ligne_req = mysql_fetch_array($req)){
	?>
					<ul class="tabs">
						<li class="tabs">
							<a href="javascript:display_tabs('<?php echo $ligne_req['trn_level']."_rnk','trn_rnk_div_frm_tabs"; ?>');" class="tabs" id="lien_<?php echo $ligne_req['trn_level']; ?>_rnk"><?php echo app_level_to_str($ligne_req['trn_level']); ?></a>
						</li>
					</ul>
	<?php
		}
		unset($ligne_req);
	?>
				</div>
				<div>
	<?php
		app_bdd_conn($_SESSION['user_is_admin']);		
		$sql_requete = 'SELECT c.*, p.trn_level, p.code, p.flag_validated, e.id id_ent, e.label label_ent, g.flag_qualified, g.final_ranking, g.type_qlf
		FROM trn_phases p, trn_rankings c, trn_phase_members g, ref_entities e
		WHERE e.id=g.id_entity AND g.id=c.id_ph_mb AND c.id_trn='.$id_trn.' AND c.id_phase=p.id AND p.trn_level='.$curr_level.'
		ORDER BY p.code,c.calc_rank'
		;
		$req = mysql_query($sql_requete);
		$level = "";
		$code = "";
		$menu_poule = true;
		$incr = 0;
		while($ligne_req = mysql_fetch_array($req)){
			if($level != $ligne_req['trn_level']){
	?>
				</div>
				<div id="tab_<?php echo $ligne_req['trn_level']; ?>_rnk" style="display:none;">
					<div>
	<?php
				$level = $ligne_req['trn_level'];
			}
			if ($ligne_req['trn_level'] == 99 AND $menu_poule){
	?>
					<div>
	<?php
				app_bdd_conn($_SESSION['user_is_admin']);
				$sql_requete1 = 'SELECT p.label, p.code FROM trn_phases p WHERE p.id_trn='.$id_trn.' AND p.trn_level='.$ligne_req['trn_level'].' ORDER BY p.code ASC';
				$req1 = mysql_query($sql_requete1);
				while($ligne_req1 = mysql_fetch_array($req1)){
	?>
						<ul class="tabs">
							<li class="tabs">
								<a href="javascript:display_tabs('<?php echo $ligne_req1['code']."_rnk_1','tab_".$ligne_req['trn_level']."_rnk"; ?>');" class="tabs" id="lien_<?php echo $ligne_req1['code']; ?>_rnk_1"><?php echo $ligne_req1['label']; ?></a>
							</li>
						</ul>
	<?php
				}
	?>
					</div>
	<?php
				unset($ligne_req1);
				$menu_poule = false;
			}
			if($code != $ligne_req['code']){
	?>
					</div>
					<div id="tab_<?php echo $ligne_req['code']; ?>_rnk_1" <?php if($ligne_req['trn_level'] == 99){echo 'style="display:none;"';}?>>
						<div class="match_entete">
							<ul class="match">
								<li class="m_rnk" style="background:#eeeeee;color:green;font-weight:bold;">Rang D</li>
								<li class="m_rnk" style="background:#eeeeee;color:green;font-weight:bold;">Rang C</li>
								<li class="m_eqdom" style="background:#eeeeee;color:green;font-weight:bold;font-size:10px;">Equipe</li>
								<li class="m_rnk" style="background:#eeeeee;color:green;font-weight:bold;">Pts</li>
								<li class="m_rnk" style="background:#eeeeee;color:green;font-weight:bold;">Diff</li>
								<li class="m_rnk" style="background:#eeeeee;color:green;font-weight:bold;">J.</li>
								<li class="m_rnk" style="background:#eeeeee;color:green;font-weight:bold;">G.</li>
								<li class="m_rnk" style="background:#eeeeee;color:green;font-weight:bold;">N.</li>
								<li class="m_rnk" style="background:#eeeeee;color:green;font-weight:bold;">P.</li>
								<li class="m_rnk" style="background:#eeeeee;color:green;font-weight:bold;">bp</li>
								<li class="m_rnk" style="background:#eeeeee;color:green;font-weight:bold;">bc</li>
								<li class="m_rnk" style="background:#eeeeee;width:10%;color:green;font-weight:bold;">
									Valider la ph. 
									<input type="hidden" name="rnk_id_ph_<?php echo $incr; ?>" value="<?php echo $ligne_req['id_phase']; ?>" />
									<input type="checkbox" title="Phase valid&eacute;e ?" name="rnk_val_<?php echo $incr;?>" <?php if ($ligne_req['flag_validated']==1){echo "checked disabled";}?>/>
								</li>
							</ul>
						</div>
	<?php
				$code = $ligne_req['code'];
			}
			if ($ligne_req['trn_level']==99){$limit = get_trn_field($id_trn,'nb_qual_after_gr')+1;}
			else{$limit=2;}
	?>
						<div class="match_entete">
							<input type="hidden" name="rnk_id_groupe_<?php echo $incr; ?>" value="<?php echo $ligne_req['id_ph_mb']; ?>" />
							<ul class="match">
	<?php 
				if ($ligne_req['flag_validated']!=1){
	?>
								<li class="m_rnk"><?php echo $ligne_req['dsp_rank']; ?></li>
								<li class="m_rnk"><?php echo $ligne_req['calc_rank']; ?></li>
	<?php 
				}
				else{
	?>
								<li class="m_rnk"></li>
								<li class="m_rnk"><?php echo $ligne_req['final_ranking']; ?></li>
	<?php 
				}
	?>
								<li class="m_eqdom<?php if($ligne_req['flag_qualified']==1){echo " m_eq_v";}elseif($ligne_req['calc_rank']!=0 AND $ligne_req['calc_rank']<$limit){echo " m_eq_n";} ?>" style="background-image:url('img/<?php echo $ligne_req['id_ent'];?>.png');"><?php echo $ligne_req['label_ent'];?></li>
								<li class="m_rnk"><?php echo $ligne_req['pts']; ?></li>
								<li class="m_rnk"><?php echo $ligne_req['dsp_diff']; ?></li>
								<li class="m_rnk"><?php echo $ligne_req['pl']; ?></li>
								<li class="m_rnk"><?php echo $ligne_req['wn']; ?></li>
								<li class="m_rnk"><?php echo $ligne_req['dr']; ?></li>
								<li class="m_rnk"><?php echo $ligne_req['df']; ?></li>
								<li class="m_rnk"><?php echo $ligne_req['mf']."(".$ligne_req['pf'].")"; ?></li>
								<li class="m_rnk"><?php echo $ligne_req['ma']."(".$ligne_req['pa'].")"; ?></li>
	<?php 
				if ($ligne_req['flag_validated']!=1){
	?>
								<li class="m_rnk" style="width:10%;">
									<input type="hidden" name="rnk_id_<?php echo $incr; ?>" value="<?php echo $ligne_req['id_ph_mb']; ?>" />
	<?php 
					if ($ligne_req['trn_level']==99){
	?>
									<input style="text-align:center;border:none;" type="number" name="rnk_frnk_<?php echo $incr;?>" min="0" max="99" size="1" value="<?php echo $ligne_req['calc_rank'];?>"/>
	<?php 
					}
					else{
	?>
									<select name="rnk_typ_qlf_<?php echo $incr; ?>" size=1>
										<option value=0></option>
	<?php
						for($i=0;$i<count($app_type_qlf);$i++){
	?>
										<option value="<?php echo $app_type_qlf[$i]; ?>" <?php if($ligne_req['type_qlf']==$app_type_qlf[$i]){echo ' selected="selected"';}?>><?php echo $app_type_qlf[$i]; ?></option>
	<?php
						}
	?>
									</select>
	<?php 
				}
	?>
									<input type="checkbox" title="Qualifi&eacute; ?" name="rnk_qlf_<?php echo $incr;?>" <?php if ($ligne_req['flag_qualified']==1){echo "checked";}?>/>
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
			<input type="hidden" name="rnk_incr" value="<?php echo $incr; ?>" />
		</div>
		</form>
	</div>
	<?php
	}

	function frm_adm_4($id_trn){
		//trn_xmote_frm($id_trn,4);
	}
?>