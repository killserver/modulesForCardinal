var consoleOR = console;
var console = {
  sendError: function(data) {
    var json = JSON.stringify(data);
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "{home_url}consoleDebug.php", true);
    xhr.setRequestHeader('Content-type','application/json; charset=utf-8');
    xhr.onload = function () {
      if (xhr.readyState == 4 && xhr.status == "200") {}
    }
    xhr.send(json);
  },
  __on : {},
  addEventListener : function (name, callback) {
    this.__on[name] = (this.__on[name] || []).concat(callback);
    return this;
  },
  dispatchEvent : function (name, value) {
    this.__on[name] = (this.__on[name] || []);
    for (var i = 0, n = this.__on[name].length; i < n; i++) {
      this.__on[name][i].call(this, value);
    }
    return this;
  },
  error: function() {
    var a = [];
    // For V8 optimization
    for (var i = 0, n = arguments.length; i < n; i++) {
      a.push(arguments[i]);
    }
    this.sendError({"type": "error", "trace": a});
  },
  warn: function() {
    var a = [];
    // For V8 optimization
    for (var i = 0, n = arguments.length; i < n; i++) {
      a.push(arguments[i]);
    }
    this.sendError({"type": "warn", "trace": a});
  },
  log: function () {
    var a = [];
    // For V8 optimization
    for (var i = 0, n = arguments.length; i < n; i++) {
      a.push(arguments[i]);
    }
    this.sendError({"type": "log", "trace": a});
  }
};
var orError = window.onerror;
window.onerror = function (message, file, line, col, error) {
  console.error(error.stack);
  if(orError!==null) {
    orError(message, file, line, col, error);
  }
  return true;
};