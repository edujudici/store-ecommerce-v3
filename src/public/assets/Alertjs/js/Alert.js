var Alert = undefined;

(function (Alert) {
	var alert, error, info, success, warning, confirm, _container;
	info = function (message, title, options) {
		return alert("info", message, title, "ti-info-alt", options);
	};
	warning = function (message, title, options) {
		return alert("warning", message, title, "ti-alert", options);
	};
	error = function (message, title, options) {
		return alert("error", message, title, "ti-close", options);
	};
	success = function (message, title, options) {
		return alert("success", message, title, "ti-check", options);
	};
	confirm = function (message, title, callback) {
		return alert("confirm", message, title, "ti-angle-right", { displayDuration: 0 }, callback);
	};
	alert = function (type, message, title, icon, options, callback) {
		var alertElem, messageElem, titleElem, iconElem, innerElem, _container;
		if (typeof options === "undefined") {
			options = {};
		}
		options = $.extend({}, Alert.defaults, options);
		if (!_container) {
			_container = $("#alerts");
			if (_container.length === 0) {
				_container = $("<ul>").attr("id", "alerts").appendTo($("body"));
			}
		}
		if (options.width) {
			_container.css({
				width: options.width
			});
		}
		alertElem = $("<li>").addClass("alert").addClass("alert-" + type);
		setTimeout(function () {
			alertElem.addClass('open');
		}, 1);
		if (icon) {
			iconElem = $("<i>").addClass(icon);
			alertElem.append(iconElem);
		}
		innerElem = $("<div>").addClass("alert-block");
		alertElem.append(innerElem);
		if (title) {
			titleElem = $("<div>").addClass("alert-title").append(title);
			innerElem.append(titleElem);
		}
		if (message) {
			messageElem = $("<div>").addClass("alert-message").append(message);
			innerElem.append(messageElem);
		}
		if (options.displayDuration > 0) {
			setTimeout((function () {
				leave();
			}), options.displayDuration);
		} else {
			if (type !== "confirm") {
				innerElem.append("<em>Click aqui para fechar</em>");
			} else {
				var yes = document.createElement('button');
				yes.innerText = 'Sim';
				yes.onclick = function () {
					if(typeof(callback) == 'function') callback(true);
					leave();
				};
				var no = document.createElement('button');
				no.innerText = 'Não';
				no.onclick = function () {
					if(typeof(callback) == 'function') callback(false);
					leave();
				};
				innerElem.append(yes);
				innerElem.append(no);
			}
		}
		if (type !== "confirm") {
			alertElem.on("click", function () {
				leave();
			});
		}
		function leave() {
			alertElem.removeClass('open');
			alertElem.one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function () { return alertElem.remove(); });
		}
		return _container.prepend(alertElem);
	};
	Alert.defaults = {
		width: "",
		icon: "",
		displayDuration: 8000,
		pos: ""
	};
	Alert.info = info;
	Alert.warning = warning;
	Alert.error = error;
	Alert.success = success;
	Alert.confirm = confirm;
	return _container = void 0;


})(Alert || (Alert = {}));

this.Alert = Alert;
