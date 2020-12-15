/* enabling highlight when object is dragged and saving data for the transfer */
function startDrag(ev){
	var id_tg = ev.target.id;
	ev.dataTransfer.setData("Text",id_tg);
	ev.target.className="drag_obj_handled";
}

/* disabling highlight on the object when it is dropped */
function endDrag(ev){
	ev.target.className=(ev.target.draggable)?"drag_obj":"drag_obj_empty";
}

/* enabling highlight when target is dragged over */
function allowDrop(ev){
	if (ev.target.id.indexOf("tozone_")!=-1){
		ev.preventDefault();
		ev.target.className="drag_obj_on";
	}
}

/* disabling highlight on the target when object leaves it */
function leaveDrag(ev){
	if (ev.target.id.indexOf("tozone_")!=-1){
		ev.preventDefault();
		var classe=(ev.target.draggable)?"drag_obj":"drag_obj_empty";
		ev.target.className=classe;
	}
}

/* when object A is dropped on a target B, validate the move */
function validateDrop(ev){
	var idA=ev.dataTransfer.getData("Text");
	var aInToZone = (idA.indexOf("tozone_")==-1)?false:true; // to distinguish wether A is taken from the dropzone or not
	var idB=ev.target.id;
	var bIsDrag = ev.target.draggable; // to distinguish wether B is draggable or not
	
	if (idA!=idB && idB.indexOf("tozone_")!=-1){
		ev.preventDefault();
		var dataA = (aInToZone)?document.getElementById(idA+'_data').value:idA;
		var newA = (aInToZone)?document.getElementById(idA):document.getElementById(idA).cloneNode(true);
			
		var dataB = (bIsDrag)?document.getElementById(idB+'_data').value:0;
		var parentB = document.getElementById(idB).parentNode;
		var newIdA  = (bIsDrag)?idB:idB.replace("_empty","");
		
		// transferring data from A to B
		document.getElementById(newIdA+'_data').value=dataA;
		
		// Putting A before B and deleting B
		parentB.insertBefore(newA,document.getElementById(idB));
		newA.id=newIdA+'_copy';
		if (!bIsDrag){
			document.getElementById(idB).className="drag_obj_empty";
			document.getElementById(idB).style.display="none";
		}
		else{
			parentB.removeChild(document.getElementById(idB));
		}
		newA.id=newIdA;
		newA.className="drag_obj";
		
		// Disabling A in its original zone (data and display)
		if (aInToZone){
			document.getElementById(idA+'_data').value=0;
			document.getElementById(idA+'_empty').style.display="block";
		}
		else{
			document.getElementById(idA).className="drag_obj_empty";
			document.getElementById(idA).draggable=false;
		}	
		
		// Enabling B in its original zone if draggable
		if (bIsDrag){
			document.getElementById(dataB).className="drag_obj";
			document.getElementById(dataB).draggable=true;
		}
	}
}