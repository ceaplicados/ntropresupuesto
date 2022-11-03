var UPs, URs;
$(document).ready(function(){
	$('.datepicker').pickadate({
		selectMonths: true, // Creates a dropdown to control month
		selectYears: 15, // Creates a dropdown of 15 years to control year,
		today: 'Today',
		clear: 'Clear',
		close: 'Ok'
	});
	Materialize.updateTextFields()
	$('select').material_select();
	$(".modal").modal();
	$("table#versionesPresupuestos span.version").click(function(){
		$("#modalActual .version").html($(this).text());
		$("#modalActual .anio").html($(this).closest("tr").find(".anio").text());
		$("#modalActual .estado").html($(this).closest(".estado").find("h6").text());
		$("#modalActual").modal("open");
	});
})

function setPrincipal(){
	$("#setPrincipal").html("Actualizando...");
	$("#setPrincipal").addClass("disabled");
	var params=new Object();
	params.action="setPrincipal";
	params.Id=$("#modalActual .version").text();
	$.post("presupuestos_aj.php",params,function(resp){
		Materialize.toast('Año actualizado!', 4000);
		window.location.reload();
	},"json")
}

function show_addPresupestosOG(){
	$("#addPresupestosOG").show();
	$("#listPresupuestos").hide();
}

function procesarExcel(){
	$("#procesarExcel").addClass("disabled");
	$("#procesarExcel").html("Procesando...");
	
	var files = $("#archivoExcel").prop('files');

	// FileReader support
	if (FileReader && files && files.length) {
		for (i = 0; i < files.length; i++) {
			uploadExcel(files[i]);
		}
	}else{
		Materialize.toast('Tu navegador no soporta procesamiento de archivos, te recomendamos utilizar Chrome', 4000)
	}
}

function readExcel(){
	var params=new Object();
	params.action="readFile";
	params.nonce=$("#archivoExcel").attr("data-nonce");
	$.post("presupuestos_aj.php",params,function(resp){
		$("body").addClass("paso2");
		for (let i = 0; i < resp.length; i++) {
			$("#paso2 select").append('<option value="'+resp[i].letra+'">'+resp[i].letra+': '+resp[i].valor+'</option>');
		}
		$('select').material_select();
	},"json");
}
function seleccionarColumnas(){
	$("#seleccionarColumnas").addClass("disabled");
	$("#seleccionarColumnas").html("Guardando...");
	var params=new Object();
	params.action="missingUPUR";
	params.columnaUP=$("#columnaUP").val();
	params.columnaUR=$("#columnaUR").val();
	params.Estado=$("#estado").val();
	params.nonce=$("#archivoExcel").attr("data-nonce");
	$.post("presupuestos_aj.php",params,function(resp){
		$("body").removeClass("paso2");
		$("body").addClass("paso3");
		UPs=resp.actualUP;
		URs=resp.actualUR;
		if(resp.missingUP.length>0){
			for (let i = 0; i < resp.missingUP.length; i++) {
				$("div.unidadesPresupuestales tbody").append('<tr data-clave="'+resp.missingUP[i]+'">'
					+'<td>'+resp.missingUP[i]+'</td>'
					+'<td><input type="text"></td>'
					+'</tr>');
			}
		}else{
			$("div.unidadesPresupuestales").hide();
		}
		if(resp.missingUR.length>0){
			for (let i = 0; i < resp.missingUR.length; i++) {
				classMissingUP='missingUP';
				nombreUP='<i>Sin definir</i>';
				claveUP=resp.missingUR[i].substr(0,resp.missingUR[i].indexOf("-"));
				if(UPs[claveUP]){
					nombreUP=UPs[claveUP].Nombre;
					classMissingUP='';
				}
				$("div.unidadesResponsables tbody").append('<tr data-clave="'+resp.missingUR[i]+'" class="'+classMissingUP+'">'
					+'<td class="UP" data-clave="'+resp.missingUR[i]+'" data-claveup="'+claveUP+'">'+nombreUP+'</td>'
					+'<td>'+resp.missingUR[i]+'</td>'
					+'<td><input type="text"></td>'
					+'</tr>');
			}
		}else{
			$("div.unidadesResponsables").hide();
		}
		if(resp.missingUP.length+resp.missingUR.length==0){
			$("#paso3 .sinNuevas").show();
		}
		$("#paso3 .rowsCount").html(resp.maxRow);
		$("div.unidadesPresupuestales tbody input").change(function(){
			$("div.unidadesResponsables tbody tr td.UP[data-claveup='"+$(this).closest("tr").attr("data-clave")+"']").html($(this).val());
		})
	},"json");	
}

function crearUP_UR(){
	$("#crearUP_UR").html("Procesando...");
	$("#crearUP_UR").addClass("disabled");
	var params=new Object();
	params.action="crearUP_UR";
	params.nonce=$("#archivoExcel").attr("data-nonce");

	params.Estado=$("#estado").val();
	params.Anio=$("#anio").val();
	params.tipoPresupuesto=$("input[name='nombre']:checked").val();
	params.Descripcion=$("#descripcion").val();
	params.Fecha=$("#fecha").val();

	params.columnaUP=$("#columnaUP").val();
	params.columnaUR=$("#columnaUR").val();
	params.columnaClaveOG=$("#columnaClaveOG").val();
	params.columnaDescripcionOG=$("#columnaDescripcionOG").val();
	params.columnaMonto=$("#columnaMonto").val();
	
	params.newUPs=new Array();
	$("div.unidadesPresupuestales tbody input").each(function(){
		newUP=new Object();
		newUP.Clave=$(this).closest("tr").attr("data-clave");
		newUP.Nombre=$(this).val();
		params.newUPs.push(newUP);
	});
	params.newURs=new Array();
	$("div.unidadesResponsables tbody input").each(function(){
		newUR=new Object();
		newUR.Clave=$(this).closest("tr").attr("data-clave");
		newUR.Nombre=$(this).val();
		params.newURs.push(newUR);
	});
	$.post("presupuestos_aj.php",params,function(resp){
		Materialize.toast('Presupuesto añadido!', 4000);
		window.location.reload();
	},"json")
}

function confirmExit() {
	return "Se están subiendo el Excel!";
}

function uploadExcel(file){
	var reader = new FileReader();
	var bin,name,type,size;
	
	reader.onload = (function(theFile) {
		return function(e) {
			name=theFile.name
			type=theFile.type
			size=theFile.size   
			
			if(reader.readAsBinaryString){
			   bin =e.target.result
			}else{
			   //Explorer
			   //Convert ArrayBuffer to BinaryString
				bin = "";
				bytes = new Uint8Array(reader.result);
				var length = bytes.byteLength;
				for(var i = 0; i < length; i++){
					bin += String.fromCharCode(bytes[i]);
				}	
			}
		};
	})(file);
	
	var loadEndFiles=function(e) {
		fileObj=new Object();
		fileObj.name=name;
		fileObj.type=type;
		fileObj.size=size;
		fileObj.bin=bin;
		
		window.onbeforeunload = confirmExit;
		var xhr
		var reader = new FileReader();
		
		if(window.XMLHttpRequest){
			 xhr = new XMLHttpRequest();
		}else if(window.ActiveXObject){
			 xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}
			
		// progress bar loadend
		var eventSource = xhr.upload || xhr;
			eventSource.addEventListener("progress", function(e) {  
			var pc = parseInt((e.loaded / e.total * 100));  
			//$('#mascara_img span').html(pc+'%') 
		}, false); 
						
		xhr.onreadystatechange=function(){
			if(xhr.readyState==4 && xhr.status==200){
				resp=JSON.parse(xhr.responseText);
				console.log(resp);
				if(resp.error){
					Materialize.toast('Ocurrió un error al subir el archivo: '+resp.error, 4000)
				}else{
					$("#archivoExcel").attr("data-nonce",resp.nonce);
					window.onbeforeunload=null;
					readExcel();
				}
			}else if(xhr.readyState==4){
				Materialize.toast('Ocurrió un error al subir el archivo', 4000)
			}
		}
		xhr.open('POST', 'presupuestos_aj.php?action=uploadFile', true);
		var boundary = 'xxxxxxxxx';
		var body = '--' + boundary + "\r\n";  
		body += "Content-Disposition: form-data; name='" + name + "\r\n";  
		body += "Content-Type: application/octet-stream\r\n\r\n";  
		body += bin + "\r\n";  
		body += '--' + boundary + '--';      
		xhr.setRequestHeader('content-type', 'multipart/form-data; boundary=' + boundary);
		// Firefox 3.6 provides a feature sendAsBinary ()
		if(xhr.sendAsBinary != null) { 
			xhr.sendAsBinary(body); 
			// Chrome 7 sends data but you must use the base64_decode on the PHP side
		} else {
			xhr.open('POST', 'presupuestos_aj.php?action=uploadFile&base64=ok&filename='+encodeURIComponent(name)+'&TypeFile='+encodeURIComponent(type), true);
			xhr.setRequestHeader('UP-FILENAME', utf8_encode (name));
			xhr.setRequestHeader('UP-SIZE', size);
			xhr.setRequestHeader('UP-TYPE', type);
			//Encode BinaryString to base64
			if(reader.readAsBinaryString){
			   xhr.send(window.btoa(bin));
			}else{
			   xhr.send("fileExplorer="+window.btoa(bin));
			}
		}
	}
	
	var loadErrorFiles=function(evt) {
		switch(evt.target.error.code) {
		  case evt.target.error.NOT_FOUND_ERR:
			  Materialize.toast('No se encontró el archivo!', 4000)
			break;
		  case evt.target.error.NOT_READABLE_ERR:
			  Materialize.toast('El archivo no es legible', 4000)
			break;
		  case evt.target.error.ABORT_ERR:
			break; // noop
		  default:
			  Materialize.toast('Ocurrió un error al leer el archivo', 4000)
		};
	}
	
	if(reader.readAsBinaryString){
		//Read in the image file as a binary string.
		reader.readAsBinaryString(file); 
	 }else{
		//Explorer
		//Contendrá los datos del archivo/objeto BLOB como un objeto ArrayBuffer.
		reader.readAsArrayBuffer(file)
	 }
	// Firefox 3.6, WebKit
	if(reader.addEventListener) { 
		//IE 10
		reader.addEventListener('loadend', loadEndFiles, false);
		// reader.addEventListener('loadstart', loadStartImg, false);
		if(status != null) {
			reader.addEventListener('error', loadErrorFiles, false);
		}
	// Chrome 7
	}else{ 
		reader.onloadend = loadEndFiles;
		// reader.onloadend = loadStartImg;
		if (status != null) {
			reader.onerror = loadErrorFiles;
		}
	}
}

function utf8_encode(argString) {
	//  discuss at: http://phpjs.org/functions/utf8_encode/
	// original by: Webtoolkit.info (http://www.webtoolkit.info/)
	// improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// improved by: sowberry
	// improved by: Jack
	// improved by: Yves Sucaet
	// improved by: kirilloid
	// bugfixed by: Onno Marsman
	// bugfixed by: Onno Marsman
	// bugfixed by: Ulrich
	// bugfixed by: Rafal Kukawski
	// bugfixed by: kirilloid
	//   example 1: utf8_encode('Kevin van Zonneveld');
	//   returns 1: 'Kevin van Zonneveld'
	
	if (argString === null || typeof argString === 'undefined') {
		return '';
	}
	
	var string = (argString + ''); // .replace(/\r\n/g, "\n").replace(/\r/g, "\n");
	var utftext = '',
	start, end, stringl = 0;
	
	start = end = 0;
	stringl = string.length;
	for (var n = 0; n < stringl; n++) {
		var c1 = string.charCodeAt(n);
		var enc = null;
		
		if (c1 < 128) {
			end++;
		} else if (c1 > 127 && c1 < 2048) {
			enc = String.fromCharCode(
				(c1 >> 6) | 192, (c1 & 63) | 128
			);
		} else if ((c1 & 0xF800) != 0xD800) {
			enc = String.fromCharCode(
				(c1 >> 12) | 224, ((c1 >> 6) & 63) | 128, (c1 & 63) | 128
			);
		} else { // surrogate pairs
			if ((c1 & 0xFC00) != 0xD800) {
				throw new RangeError('Unmatched trail surrogate at ' + n);
			}
			var c2 = string.charCodeAt(++n);
			if ((c2 & 0xFC00) != 0xDC00) {
				throw new RangeError('Unmatched lead surrogate at ' + (n - 1));
			}
			c1 = ((c1 & 0x3FF) << 10) + (c2 & 0x3FF) + 0x10000;
			enc = String.fromCharCode(
				(c1 >> 18) | 240, ((c1 >> 12) & 63) | 128, ((c1 >> 6) & 63) | 128, (c1 & 63) | 128
			);
		}
		if (enc !== null) {
			if (end > start) {
			utftext += string.slice(start, end);
			}
			utftext += enc;
			start = end = n + 1;
		}
	}
	
	if (end > start) {
		utftext += string.slice(start, stringl);
	}
	
	return utftext;
}

function show_addPresupestosPP(){
	$("#addPresupestosPP").show();
	$("#listPresupuestos").hide();
}


function getVersionesEstado(){
	$("#addPresupestosPP .versionPresupuestoPP").html('');
	$("div.estado[data-id='"+$("#estadoPP").val()+"'] li").each(function(){
		valor=$(this).attr("data-id");
		anio=$(this).closest("tr").find("td.anio").text();
		tipo=$(this).attr("data-tipo");
		$("#addPresupestosPP .versionPresupuestoPP").append('<div><input name="versionPP" type="radio" id="versionPP_'+valor+'" value="'+valor+'"/><label for="versionPP_'+valor+'">'+valor+': '+tipo+' '+anio+'</label></div>');
	})
}

function procesarExcelPP(){
	$("#procesarExcelPP").addClass("disabled");
	$("#procesarExcelPP").html("Procesando...");
	
	var files = $("#archivoExcelPP").prop('files');

	// FileReader support
	if (FileReader && files && files.length) {
		for (i = 0; i < files.length; i++) {
			uploadExcelPP(files[i]);
		}
	}else{
		Materialize.toast('Tu navegador no soporta procesamiento de archivos, te recomendamos utilizar Chrome', 4000)
	}
}
function uploadExcelPP(file){
	var reader = new FileReader();
	var bin,name,type,size;
	
	reader.onload = (function(theFile) {
		return function(e) {
			name=theFile.name
			type=theFile.type
			size=theFile.size   
			
			if(reader.readAsBinaryString){
			   bin =e.target.result
			}else{
			   //Explorer
			   //Convert ArrayBuffer to BinaryString
				bin = "";
				bytes = new Uint8Array(reader.result);
				var length = bytes.byteLength;
				for(var i = 0; i < length; i++){
					bin += String.fromCharCode(bytes[i]);
				}	
			}
		};
	})(file);
	
	var loadEndFiles=function(e) {
		fileObj=new Object();
		fileObj.name=name;
		fileObj.type=type;
		fileObj.size=size;
		fileObj.bin=bin;
		
		window.onbeforeunload = confirmExit;
		var xhr
		var reader = new FileReader();
		
		if(window.XMLHttpRequest){
			 xhr = new XMLHttpRequest();
		}else if(window.ActiveXObject){
			 xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}
			
		// progress bar loadend
		var eventSource = xhr.upload || xhr;
			eventSource.addEventListener("progress", function(e) {  
			var pc = parseInt((e.loaded / e.total * 100));  
			//$('#mascara_img span').html(pc+'%') 
		}, false); 
						
		xhr.onreadystatechange=function(){
			if(xhr.readyState==4 && xhr.status==200){
				resp=JSON.parse(xhr.responseText);
				console.log(resp);
				if(resp.error){
					Materialize.toast('Ocurrió un error al subir el archivo: '+resp.error, 4000)
				}else{
					$("#archivoExcelPP").attr("data-nonce",resp.nonce);
					window.onbeforeunload=null;
					readExcelPP();
				}
			}else if(xhr.readyState==4){
				Materialize.toast('Ocurrió un error al subir el archivo', 4000)
			}
		}
		xhr.open('POST', 'presupuestos_aj.php?action=uploadFile', true);
		var boundary = 'xxxxxxxxx';
		var body = '--' + boundary + "\r\n";  
		body += "Content-Disposition: form-data; name='" + name + "\r\n";  
		body += "Content-Type: application/octet-stream\r\n\r\n";  
		body += bin + "\r\n";  
		body += '--' + boundary + '--';      
		xhr.setRequestHeader('content-type', 'multipart/form-data; boundary=' + boundary);
		// Firefox 3.6 provides a feature sendAsBinary ()
		if(xhr.sendAsBinary != null) { 
			xhr.sendAsBinary(body); 
			// Chrome 7 sends data but you must use the base64_decode on the PHP side
		} else {
			xhr.open('POST', 'presupuestos_aj.php?action=uploadFile&base64=ok&filename='+encodeURIComponent(name)+'&TypeFile='+encodeURIComponent(type), true);
			xhr.setRequestHeader('UP-FILENAME', utf8_encode (name));
			xhr.setRequestHeader('UP-SIZE', size);
			xhr.setRequestHeader('UP-TYPE', type);
			//Encode BinaryString to base64
			if(reader.readAsBinaryString){
			   xhr.send(window.btoa(bin));
			}else{
			   xhr.send("fileExplorer="+window.btoa(bin));
			}
		}
	}
	
	var loadErrorFiles=function(evt) {
		switch(evt.target.error.code) {
		  case evt.target.error.NOT_FOUND_ERR:
			  Materialize.toast('No se encontró el archivo!', 4000)
			break;
		  case evt.target.error.NOT_READABLE_ERR:
			  Materialize.toast('El archivo no es legible', 4000)
			break;
		  case evt.target.error.ABORT_ERR:
			break; // noop
		  default:
			  Materialize.toast('Ocurrió un error al leer el archivo', 4000)
		};
	}
	
	if(reader.readAsBinaryString){
		//Read in the image file as a binary string.
		reader.readAsBinaryString(file); 
	 }else{
		//Explorer
		//Contendrá los datos del archivo/objeto BLOB como un objeto ArrayBuffer.
		reader.readAsArrayBuffer(file)
	 }
	// Firefox 3.6, WebKit
	if(reader.addEventListener) { 
		//IE 10
		reader.addEventListener('loadend', loadEndFiles, false);
		// reader.addEventListener('loadstart', loadStartImg, false);
		if(status != null) {
			reader.addEventListener('error', loadErrorFiles, false);
		}
	// Chrome 7
	}else{ 
		reader.onloadend = loadEndFiles;
		// reader.onloadend = loadStartImg;
		if (status != null) {
			reader.onerror = loadErrorFiles;
		}
	}
}

function readExcelPP(){
	var params=new Object();
	params.action="readFile";
	params.nonce=$("#archivoExcelPP").attr("data-nonce");
	$.post("presupuestos_aj.php",params,function(resp){
		$("body").addClass("paso2PP");
		for (let i = 0; i < resp.length; i++) {
			$("#paso2PP select").append('<option value="'+resp[i].letra+'">'+resp[i].letra+': '+resp[i].valor+'</option>');
		}
		$('select').material_select();
	},"json");
}

function seleccionarColumnasPP(){
	$("#seleccionarColumnasPP").addClass("disabled");
	$("#seleccionarColumnasPP").html("Guardando...");
	var params=new Object();
	params.action="missingPP";
	params.columnaUP=$("#columnaUP_PP").val();
	params.columnaUR=$("#columnaUR_PP").val();
	params.columnaClavePP=$("#columnaClavePP").val();
	params.columnaNombrePP=$("#columnaNombre_PP").val();
	params.columnaMonto=$("#columnaMontoPP").val();
	params.Estado=$("#estadoPP").val();
	params.nonce=$("#archivoExcelPP").attr("data-nonce");
	$.post("presupuestos_aj.php",params,function(resp){
		$("body").removeClass("paso2PP");
		$("body").addClass("paso3PP");
		UPs=resp.actualUP;
		URs=resp.actualUR;
		PPs=resp.actualPP;
		if(resp.missingPP.length>0){
			$("#missingPP tbody").html("");
			for (let i = 0; i < resp.missingPP.length; i++) {
				var UR="";
				if(URs[resp.missingPP[i].UR]){
					UR=resp.missingPP[i].UR+" "+URs[resp.missingPP[i].UR].Nombre;
				}
				$("#missingPP tbody").append('<tr data-clave="'+resp.missingPP[i].Clave+'" data-ur="'+URs[resp.missingPP[i].UR].Id+'">'
					+'<td>'+UR+'</td>'
					+'<td>'+resp.missingPP[i].Clave+'</td>'
					+'<td class="nombre">'+resp.missingPP[i].Nombre+'</td>'
					+'</tr>');
			}
		}else{
			$("#missingPP").hide();
			$("#paso3PP .sinNuevas").show();
		}
		if(resp.missingUP.length+resp.missingUR.length>0){
			var strError="";
			if(resp.missingUP.length>0){
				strError=strError+"Las siguientes Unidades Presupuestales no están dadas de alta: "+resp.missingUP.join(", ")+". ";
			}
			if(resp.missingUR.length>0){
				strError=strError+"Las siguientes Unidades Responsables no están dadas de alta: "+resp.missingUR.join(", ")+". ";
			}
			strError=strError+"No se puede continuar.";
			$("#paso3PP .sinNuevas").html(strError);
			$("#paso3PP .sinNuevas").show();
			$("#writePP").hide();
		}
		$("#paso3PP .rowsCount").html(resp.maxRow);
	},"json");
}

function writePP(){
	$("#writePP").html("Procesando...");
	$("#writePP").addClass("disabled");
	var params=new Object();
	params.action="writePP";
	params.nonce=$("#archivoExcelPP").attr("data-nonce");

	params.Estado=$("#estadoPP").val();
	params.Version=$("input[name='versionPP']:checked").val();

	params.columnaUP=$("#columnaUP_PP").val();
	params.columnaUR=$("#columnaUR_PP").val();
	params.columnaClavePP=$("#columnaClavePP").val();
	params.columnaNombrePP=$("#columnaNombre_PP").val();
	params.columnaMonto=$("#columnaMontoPP").val();
	
	params.newPPs=new Array();
	$("#missingPP tbody tr").each(function(){
		newPP=new Object();
		newPP.Clave=$(this).attr("data-clave");
		newPP.Nombre=$(this).find(".nombre").text();
		newPP.UnidadResponsable=$(this).attr("data-ur");
		params.newPPs.push(newPP);
	});
	$.post("presupuestos_aj.php",params,function(resp){
		Materialize.toast('Presupuesto añadido!', 4000);
		window.location.reload();
	},"json")
}

function consolidarProgramasDuplicados(){
	var params=new Object();
	params.action="consolidarProgramasDuplicados";
	$.post("presupuestos_aj.php",params,function(resp){
		Materialize.toast(resp.duplicados.length+' programas consolidados!', 4000);
	},"json")
}