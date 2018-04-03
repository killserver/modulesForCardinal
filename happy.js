jQuery(window).off("keydown").on("keydown", function (event) {
	var t = event.altKey===true && event.key==="c";
	if(t===true) {
		var tmpHeight = window.innerHeight;
		document.body.style.height = window.innerHeight+"px";
		document.body.style.position = "relative";
		function getRandomInt(min, max) {
			return Math.floor(Math.random() * (max - min)) + min;
		}
		var t = document.querySelectorAll("body *");
		var active = [];
		for(var i=0;i<t.length;i++) {
			if(t[i].children.length==0 && t[i].getAttribute("data-uniqid")===null) {
				active.push(t[i]);
			}
		}
		for(var i=0;i<active.length;i++) {
			var left = jQuery(active[i])[0].x;
			var top = jQuery(active[i])[0].y;
			jQuery(active[i]).attr("data-uniqid", "true");
			var zi = jQuery(active[i]).css("zIndex");
			if(zi=="auto") {
				zi = 1;
			}
			jQuery(active[i]).css({"position": "fixed", "top": top+"px", "left": left+"px", "transform": "rotate("+getRandomInt(90, 960)+"deg)", "transition": "all 2600ms ease-in-out", "zIndex": ""+(zi+500000)});
		}
		for(var i=0;i<active.length;i++) {
			var left = jQuery(active[i])[0].x;
			var top = jQuery(active[i])[0].y;
			var toTop = tmpHeight-(tmpHeight/100*getRandomInt(1, 15));
			jQuery(active[i]).animate({"top": toTop+"px"}, getRandomInt(200, 2400));
		}
		event.preventDefault();
	}
});