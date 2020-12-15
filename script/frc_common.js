function frc_ndm_val(item,rank){
	// 0 - If alternative rank is already selected for this item, erase it and all data in next phases
	var alter_rk = 3-rank;
	if(document.getElementById(item+'_next_'+alter_rk)){
		var next_pm = document.getElementById(item+'_next_'+alter_rk).value;
		if (document.getElementById(item+'_rad_'+alter_rk).checked){
			//alert(alter_rk);
			document.getElementById(item+'_rad_'+alter_rk).checked = false;
			frc_delete(next_pm);
		}
	}
	
	next_pm = document.getElementById(item+'_next_'+rank).value;
	// 0 - Put the selected element in color (green for 1st position, blue for others)
	frc_highlight(item,rank);
	
	// 1 - search the place to move the selected element (if exists)
	if (document.getElementById(next_pm)){
		var item_ent = document.getElementById(item+'_id_ent').value;
		var item_code = document.getElementById(item+'_code').value;
		var next_item = 'frc_ndm_'+document.getElementById(next_pm).value;
		//alert(next_pm+'-'+next_item);
		var par_nxt_item = document.getElementById(next_item).parentNode;
		// 1.0 - clone the selected element there
			var newItem = document.getElementById(item).cloneNode(true);
			par_nxt_item.insertBefore(newItem,document.getElementById(next_item));
			newItem.id = next_item+'_temp';
		// 1.1 - if this place is empty, hide empty element
			if (document.getElementById(next_item+'_id_ent').value == -1){
				document.getElementById(next_item).id = next_item+'_empty';
				document.getElementById(next_item+'_empty').style.display = 'none';
			}
		// 1.2 - if this place is seeded with ANOTHER element
			else{
			// 1.2.1 - kill old element
				//par_nxt_item.removeChild(document.getElementById(next_item));
			// 1.2.2 - RECURRENCE - for each level in trn path next to this place, kill the element and show empty element if the place is not empty
				frc_delete(next_pm);
				document.getElementById(next_item).id = next_item+'_empty';
				document.getElementById(next_item+'_empty').style.display = 'none';
			}
			newItem.id = next_item;
			document.getElementById(next_item).className = 'final_item';
			if (document.getElementById(next_item+'_rad_1')){document.getElementById(next_item+'_rad_1').checked = false;}
			if (document.getElementById(next_item+'_rad_2')){document.getElementById(next_item+'_rad_2').checked = false;}
	// 2 - Fill the destination places with data
		// Destination : id entity & code
		document.getElementById(next_item+'_id_ent').value = document.getElementById(item+'_id_ent').value;
		document.getElementById(next_item+'_code').value = document.getElementById(item+'_code').value;
		
	// 3 - Enable radio buttons
		var rad_name = document.getElementById(next_item+'_rad_1').name;
		var items = document.getElementsByName(rad_name);
		var flag_dis = false;
		for (i=0;i<items.length;i++){
			var item_id = items[i].id;
			item_id=item_id.substring(0,item_id.indexOf('_rad_1'));
			if (document.getElementById(item_id+'_id_ent').value == -1){
				flag_dis = true;
			}
		}
		if (!flag_dis){
			for (i=0;i<items.length;i++){
				items[i].removeAttribute('disabled');
			}
		}
	}
	// Origin : rank & change flag
	document.getElementById(item+'_rank').value = rank;
	document.getElementById(item+'_change').value = 1;
}

function frc_highlight(item,rank){
	var items = document.getElementsByName(document.getElementById(item+'_rad_'+rank).name);
	var alt_rk = 3-rank;
	for (i=0;i<items.length;i++){
		var item_id = items[i].id;
		item_id=item_id.substring(0,item_id.indexOf('_rad_'+rank));
		if (!document.getElementById(item+'_rad_'+alt_rk) || !document.getElementById(item_id+'_rad_'+alt_rk).checked){
			document.getElementById(item_id).className = 'final_item';
		}
	}
	if (document.getElementById(item+'_rad_'+alt_rk)){document.getElementById(item+'_rad_'+alt_rk).checked = false;}
	if (rank == 1){
		document.getElementById(item).className = 'final_item m_eq_v';
	}
	if (rank == 2){
		document.getElementById(item).className = 'final_item m_eq_n';
	}
}

function frc_delete(id_itm){
	if (id_itm == -1) return;
	var itm = 'frc_ndm_'+document.getElementById(id_itm).value;
	//alert(itm);
	if (document.getElementById(itm+'_id_ent').value == -1) return;
	var par_itm = document.getElementById(itm).parentNode;
	par_itm.removeChild(document.getElementById(itm));
	document.getElementById(itm+'_empty').style.display = 'block';
	document.getElementById(itm+'_empty').id = itm;
	document.getElementById(itm+'_id_ent').value = -1;
	document.getElementById(itm+'_code').value = -1;
	document.getElementById(itm+'_rad_1').checked = false;
	// désactivation des boutons radio si tout le niveau est renseigné
	var rad_name = document.getElementById(itm+'_rad_1').name;
	var items = document.getElementsByName(rad_name);
	for (i=0;i<items.length;i++){
		items[i].setAttribute('disabled',true);
		items[i].checked=false;
	}
	// Passage au niveau suivant
	id_itm = document.getElementById(itm+'_next_1').value;
	frc_delete(id_itm);
}

function frc_val(increment,mode,origin){
	// modification du flag 'change' pour être pris en compte par le process de màj
	document.getElementById('frc_change_'+increment).value = '1';
	
	// déduction de la valeur des boutons radios
	var result = '';
	var mhome = document.getElementById('frc_bdom_'+increment).value;
	var maway = document.getElementById('frc_bext_'+increment).value;
	if (mode == 'result'){
		result = origin.value;
		document.getElementById('frc_bdom_'+increment).value = '';
		document.getElementById('frc_bext_'+increment).value = '';
	}
	else{
		if(mhome == maway){
			result = 'N';
			document.getElementById('frc_n_'+increment).checked = true;
		}
		if(mhome > maway && mhome>0){
			result = '1';
			document.getElementById('frc_1_'+increment).checked = true;
		}
		if(mhome < maway && maway>0){
			result = '2';
			document.getElementById('frc_2_'+increment).checked = true;
		}
	}
	
	// colorisation des équipes selon le résultat, affichage des stats correspondantes au résultat pronostiqué et gestion de la sélection du qualifié si besoin
	if(document.getElementById('frc_stats_'+increment+'_none')){document.getElementById('frc_stats_'+increment+'_none').style.display = 'none';}
	if(document.getElementById('frc_stats_'+increment+'_1')){document.getElementById('frc_stats_'+increment+'_1').style.display = 'none';}
	if(document.getElementById('frc_stats_'+increment+'_2')){document.getElementById('frc_stats_'+increment+'_2').style.display = 'none';}
	if(document.getElementById('frc_stats_'+increment+'_n')){document.getElementById('frc_stats_'+increment+'_n').style.display = 'none';}
	if (result == '1'){
		document.getElementById('frc_eq1_'+increment).className = 'm_eqdom m_eq_v';
		document.getElementById('frc_eq2_'+increment).className = 'm_eqext';
		if(document.getElementById('frc_stats_'+increment+'_1')){document.getElementById('frc_stats_'+increment+'_1').style.display = 'inline';}
		if(document.getElementById('frc_q_'+increment)){document.getElementById('frc_q_'+increment).disabled = true;document.getElementById('frc_q_'+increment).value = '1';}
		if(document.getElementById('frc_q2_'+increment)){document.getElementById('frc_q2_'+increment).value = '1';}
	}
	if (result == '2'){
		document.getElementById('frc_eq1_'+increment).className = 'm_eqdom';
		document.getElementById('frc_eq2_'+increment).className = 'm_eqext m_eq_v';
		if(document.getElementById('frc_stats_'+increment+'_1')){document.getElementById('frc_stats_'+increment+'_2').style.display = 'inline';}
		if(document.getElementById('frc_q_'+increment)){document.getElementById('frc_q_'+increment).disabled = true;document.getElementById('frc_q_'+increment).value = '2';}
		if(document.getElementById('frc_q2_'+increment)){document.getElementById('frc_q2_'+increment).value = '2';}
	}
	if (result == 'N'){
		document.getElementById('frc_eq1_'+increment).className = 'm_eqdom m_eq_n';
		document.getElementById('frc_eq2_'+increment).className = 'm_eqext m_eq_n';
		if(document.getElementById('frc_stats_'+increment+'_n')){document.getElementById('frc_stats_'+increment+'_n').style.display = 'inline';}
		if(document.getElementById('frc_q_'+increment)){document.getElementById('frc_q_'+increment).disabled = false;document.getElementById('frc_q_'+increment).value = '0';}
		if(document.getElementById('frc_q2_'+increment)){document.getElementById('frc_q2_'+increment).value = '0';}
	}
}