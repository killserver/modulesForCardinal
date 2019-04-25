document.querySelectorAll(".feedback_module").forEach(function(elem) {
	elem.addEventListener("submit", function(e) {
		e.preventDefault();
		var form = e.target;
		var action = e.target.getAttribute("action");
		var from = new FormData(e.target);
		try {
			var type = e.target.getAttribute("data-type");
			fetch(e.target.getAttribute("action"), {
				method: "POST",
				body: from,
				headers: {
					'X-Requested-With': 'XMLHttpRequest'
				}
			}).then(function(resp) {
				return resp.json();
			}).then(function(resp) {
				if(type=="ajax" && resp.success===true) {
					var event;
					if(document.createEvent) {
						event = document.createEvent("HTMLEvents");
						event.initEvent("form_builder", true, true);
					} else {
						event = document.createEventObject();
						event.eventType = "form_builder";
					}
					event.typeSend = "success";
					event.form = form;
					event.action = action;
					if(document.createEvent) {
						document.body.dispatchEvent(event);
					} else {
						document.body.fireEvent("on"+event.eventType, event);
					}
				} else if(resp.success===true) {
					window.location.href = default_link+feedback_route;
				} else {
					var event;
					if(document.createEvent) {
						event = document.createEvent("HTMLEvents");
						event.initEvent("form_builder", true, true);
					} else {
						event = document.createEventObject();
						event.eventType = "form_builder";
					}
					event.typeSend = "error";
					event.form = form;
					event.action = action;
					if(document.createEvent) {
						document.body.dispatchEvent(event);
					} else {
						document.body.fireEvent("on"+event.eventType, event);
					}
				}
			});
		} catch(e) {}
	});
});