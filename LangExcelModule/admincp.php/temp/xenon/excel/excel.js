(function(w){
	w.ajax = function(obj,dfn,efn){
		// --- var
		XHR = undefined;
		// --- стандартные настройки
		def = {
			type: "POST",
			async: false,
			url:  "/",
			data: null,
			form: "FormData"
		};
		// --- проверка и назначения настройки
		if(typeof(obj) !== "object"){
			return false;
		}
		for (k in def){
			if(typeof(obj[k]) == "undefined"){
				obj[k] = def[k];
			}
		}
		// --- функция исполнения
		fn = new Object(null);
		fn.completed = function(){
			if(fn.status() === true){
				if(typeof dfn == 'function'){
					dfn(XHR.responseText);
				}
			}
		}
		fn.status = function(){
			if(XHR.readyState == 4){
				if(XHR.status==200){
					return true;
				}else{
					if(typeof dfn == 'function'){
						dfn();
					}
					console.log("ajax : "+XHR.status);
				}
		  		return false;
		  	}
		  	return false;
		}
		// --- обявления метода
		try{
			XHR = new ActiveXObject("Msxml2.XMLHTTP");
		}catch (e){
			try{
				XHR = new ActiveXObject("Microsoft.XMLHTTP");
			}catch (E){
				XHR = false;
			}
		}
		if (!XHR && typeof XMLHttpRequest!='undefined') XHR = new XMLHttpRequest();
		
		XHR.open(obj.type, obj.url, obj.async);
		if(obj.form == "json"){
			XHR.setRequestHeader("Content-type", "application/json")	
		}
		
		if(obj.data != null){
			if(obj.form == "FormData" && obj.data instanceof FormData == false){
				var fd = new FormData();
				for (var k in obj.data) {
					fd.append(k,obj.data[k]);
				}
				obj.data = fd;
			}
			if(obj.form == "json"){
				obj.data = JSON.stringify(obj.data);
			}
		}
		
		XHR.send(obj.data);
		// --- тип отправки
		if(obj.async){
			XHR.onreadystatechange = function(){
				fn.completed();
			}
		}else{
			fn.completed();
		}
	}
})(window);

var excel = function() {
	var dropArea = document.querySelector("body");
	['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
	  dropArea.addEventListener(eventName, function (e) {
		  e.preventDefault()
		  e.stopPropagation()
		}, false)
	});
	['dragenter', 'dragover'].forEach(eventName => {
	  dropArea.addEventListener(eventName, function (e) {
		  dropArea.classList.add('view-drag')
		}, false)
	});
	['dragleave', 'drop'].forEach(eventName => {
	  dropArea.addEventListener(eventName, function (e) {
		  dropArea.classList.remove('view-drag')
		}, false)
	});
	dropArea.addEventListener('drop', function(e) {
	  excel.load(e.dataTransfer.files[0]);
	}, false);
	excel.body = document.querySelector(".excel-table");
}
excel.load = function(files) {
	var result = {};
	var workbook = false;
	var reader = new FileReader();
	reader.onload = function(e) {
		var data = e.target.result;
		workbook = XLSX.read(data, { type:'binary' });
		workbook.SheetNames.forEach(function(sheetName) {
			var roa = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName], { header: 1 });
			if(roa.length) result[sheetName] = roa;
		});
		excel.view(result);
	};
	reader.readAsBinaryString(files);
}
excel.langLoad = "";
excel.view = function(data) {
	var keys = Object.keys(data);
	var column = new Object(null);
	var column_keys = new Object(null);
	data = data[keys[0]];
	// --- поиск полей
	column.id = false;
	for(var j=1; j<data[1].length;j++) {
		excel.langLoad = data[2][j]
		column[data[1][j]] = false;
	}
	column_keys = Object.keys(column);
	// --- поиск данных
	excel.body.innerHTML = "";
	for(var i=3; i<data.length;i++) {
		if(data[i].length == 0) continue;
		var el = document.createElement("div");
		el.className = "excel-line";
		el.excel = JSON.parse(JSON.stringify(column));
		for(var j=0; j<data[i].length;j++) {
			 el.innerHTML += '<span>'+data[i][j]+'</span>';
			 el.excel[column_keys[j]] = data[i][j];
		}
		el.innerHTML += '<span><input type="checkbox"/></span>';
		excel.body.appendChild(el);
	}
}
excel.complete = function(k) {
	excel.data = new Array;
	[].forEach.call(excel.body.querySelectorAll('.excel-line'),function(e){
		if(e.querySelector("input").checked == true) excel.data.push(e);
	});
	excel.progress(excel.data.length);
	excel.send(0);
}
excel.send = function(k) {
	var el = excel.data[k];
	var data = excel.data[k].excel;
	
	ajax({ "url": "/admincp.php/?pages=LangExcel&save="+excel.langLoad, "data": data }, function(d) {
		d = JSON.parse(d);
		if(d["error"] == false) {
			if(excel.progress.ready() == false) {
				setTimeout(function() {
					excel.send(k+1);
				}, 100); 
			}
			el.classList.add("comp"); 
		} else {
			el.classList.add("error");
			toastr.error(d.msg);
			if(excel.progress.ready() == false) {
				setTimeout(function() {
					excel.send(k+1);
				}, 100); 
			}
		}
	});
}
excel.selected = function(selected) {
	[].forEach.call(excel.body.querySelectorAll('.excel-line input'),function(e){
		e.checked = selected;
	});
}
excel.progress = function(count) {
	excel.progress.el = document.querySelector(".progress i");
	excel.progress.count = count;
	excel.progress.comp = 0;
}
excel.progress.ready = function() {
	excel.progress.comp++;
	var p = parseInt(excel.progress.comp/excel.progress.count*100);
	excel.progress.el.style.width = p+"%";
	excel.progress.el.innerText = p+"%";
	return (excel.progress.comp>=excel.progress.count?true:false);
}

excel();
