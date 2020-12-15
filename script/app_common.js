function display_tabs(item,idSection) {
	var section = document.getElementById(idSection);
	var divs = section.childNodes;
	for (i=0;i<divs.length;i++) {
		if (divs[i].tagName=='DIV' && divs[i].id.indexOf('tab_')!=-1){
			divs[i].style.display=(divs[i].id=="tab_"+item)?"block":"none";
		}
	}
	var liens = section.getElementsByTagName("a");
	for (i=0;i<liens.length;i++) {
		liens[i].className = "tabs";
	}
	var lien = "lien_"+item;
	document.getElementById(lien).className = "curr_tabs";
}

function display_commut(object_id) 
{
	obj = document.getElementById(object_id); 
	obj.style.display = (obj.style.display == "block")?"none":"block";
}