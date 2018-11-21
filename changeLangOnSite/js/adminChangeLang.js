window.addEventListener("DOMContentLoaded", function(){
	document.body.addEventListener("click", function(e) {
		if(e.target.closest(".adminCoreCardinal a.editLang")) {
			if(e.target.closest(".adminCoreCardinal a.editLang").querySelector("span").innerHTML=="Редактировать текст") {
				e.target.closest(".adminCoreCardinal a.editLang").className += (" activeLang");
				e.target.closest(".adminCoreCardinal a.editLang").querySelector("span").innerHTML = "Выключить редактор";
				document.querySelectorAll("custom-lang").forEach(function(elem) {
					elem.querySelector("custom-text").setAttribute("contenteditable", "true");
					elem.className += (" active");
					var a = elem.closest("a");
					var btn = elem.closest("button");
					if(a!==null) {
						a.addEventListener("click", function(e) {
							e.stopPropagation();
						}, true);
					}
					if(btn!==null) {
						btn.addEventListener("click", function(e) {
							e.stopPropagation();
						}, true);
					}
				});
				document.querySelectorAll("custom-text").forEach(function(el) {
					el.addEventListener("mousedown", function(event) {
						event.stopPropagation();
					}, true)
				});
			} else {
				e.target.closest(".adminCoreCardinal a.editLang").classList.remove("activeLang");
				document.querySelectorAll("custom-lang").forEach(function(elem) {
					elem.querySelector("custom-text").removeAttribute("contenteditable");
					elem.classList.remove("active");
					var a = elem.closest("a");
					var btn = elem.closest("button");
					if(a!==null) {
						a.removeEventListener("click", function(e) {
							e.stopPropagation();
						}, true);
					}
					if(btn!==null) {
						btn.removeEventListener("click", function(e) {
							e.stopPropagation();
						}, true);
					}
				});
				document.querySelectorAll("custom-text").forEach(function(el) {
					el.removeEventListener("mousedown", function(event) {
						event.stopPropagation();
					}, true)
				});
				e.target.closest(".adminCoreCardinal a.editLang").querySelector("span").innerHTML = "Редактировать текст";
			}
			e.preventDefault();
			ret = false;
		}
		if(e.target.closest("custom-lang.active .done-lang")) {
			var or = e.target.closest("custom-lang").getAttribute("data-ortext");
			var translate = e.target.closest("custom-lang").querySelector("custom-text").innerHTML;
			fetch(adminLangPage, {  
				method: 'post',  
				headers: {  
					"Content-type": "application/x-www-form-urlencoded; charset=UTF-8"  
				},  
				body: 'orLang='+encodeURIComponent(or)+'&translate='+encodeURIComponent(translate)  
			}).then(function(response) {
				if(response.status === 200) {
					new Noty({
						theme: 'sunset',
						type: 'success',
						text: lang_save_success,
						timeout: 1500,
						animation: {
							open: mojsShow,
							close: mojsClose
						}
					}).show();
				} else {
					new Noty({
						theme: 'sunset',
						type: 'warning',
						text: lang_save_error,
						timeout: 1500,
						animation: {
							open: mojsShow,
							close: mojsClose
						}
					}).show();
				}
			});
			e.preventDefault();
			return false;
		}
		if(e.target.closest("custom-lang.active .close-lang")) {
			var text = e.target.closest("custom-lang").getAttribute("data-ortext");
			e.target.closest("custom-lang").querySelector("custom-text").innerHTML = (text);
			e.preventDefault();
			return false;
		}
	});
	document.querySelectorAll("custom-lang").forEach(function(el) {
		el.addEventListener("keydown", function(e) {
			if(e.keyCode === 13) {
				var selection = window.getSelection(),
				range = selection.getRangeAt(0),
				br = document.createElement('br');

				range.deleteContents();
				range.insertNode(br);
				range.setStartAfter(br);
				range.setEndAfter(br);
				range.collapse(false);

				selection.removeAllRanges();
				selection.addRange(range);
				e.preventDefault();
			}
		});
	});
});
fetch("https://cdn.rawgit.com/needim/noty/77268c46/lib/noty.min.js").then(function(resp){if(resp.status===200) {return resp.text();}}).then(function(t){var sr = document.createElement("script");sr.type="text/javascript";sr.innerHTML = t;sr.id = "scriptNewUniqIdNotHuman";document.body.appendChild(sr);document.body.removeChild(document.getElementById("scriptNewUniqIdNotHuman"));});
fetch("https://cdn.jsdelivr.net/mojs/latest/mo.min.js").then(function(resp){if(resp.status===200) {return resp.text();}}).then(function(t){var sr = document.createElement("script");sr.type="text/javascript";sr.innerHTML = t;sr.id = "scriptNewUniqIdNotHuman";document.body.appendChild(sr);document.body.removeChild(document.getElementById("scriptNewUniqIdNotHuman"));});
fetch("https://cdn.rawgit.com/needim/noty/77268c46/lib/noty.css").then(function(resp) {
	if(resp.status===200) {
		return resp.text();
	}
}).then(function(t) {
	var sr = document.createElement("style");
	sr.type="text/css";
	sr.innerHTML = t;
	document.body.appendChild(sr);
})
fetch("https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css").then(function(resp) {
	if(resp.status===200) {
		return resp.text();
	}
}).then(function(t) {
	var sr = document.createElement("style");
	sr.type="text/css";
	sr.innerHTML = t;
	document.body.appendChild(sr);
})
var mojsShow = function(promise) {
  var n = this
  var Timeline = new mojs.Timeline()
  var body = new mojs.Html({
    el: n.barDom,
    x: { 500: 0, delay: 0, duration: 500, easing: 'elastic.out' },
    isForce3d: true,
    onComplete: function () {
      promise(function (resolve) {
        resolve()
      })
    }
  })

  var parent = new mojs.Shape({
    parent: n.barDom,
    width: 200,
    height: n.barDom.getBoundingClientRect().height,
    radius: 0,
    x: { [150]: -150 },
    duration: 1.2 * 500,
    isShowStart: true
  })

  n.barDom.style['overflow'] = 'visible'
  parent.el.style['overflow'] = 'hidden'

  var burst = new mojs.Burst({
    parent: parent.el,
    count: 10,
    top: n.barDom.getBoundingClientRect().height + 75,
    degree: 90,
    radius: 75,
    angle: { [-90]: 40 },
    children: {
      fill: '#EBD761',
      delay: 'stagger(500, -50)',
      radius: 'rand(8, 25)',
      direction: -1,
      isSwirl: true
    }
  })

  var fadeBurst = new mojs.Burst({
    parent: parent.el,
    count: 2,
    degree: 0,
    angle: 75,
    radius: { 0: 100 },
    top: '90%',
    children: {
      fill: '#EBD761',
      pathScale: [.65, 1],
      radius: 'rand(12, 15)',
      direction: [-1, 1],
      delay: .8 * 500,
      isSwirl: true
    }
  })

  Timeline.add(body, burst, fadeBurst, parent)
  Timeline.play()
}
var mojsClose = function(promise) {
  var n = this
  new mojs.Html({
    el: n.barDom,
    x: { 0: 500, delay: 10, duration: 500, easing: 'cubic.out' },
    isForce3d: true,
    onComplete: function () {
      promise(function (resolve) {
        resolve()
      })
    }
  }).play()
}