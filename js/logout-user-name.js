/**
 * Force logout confirmation to show the logged-in user's name from PHP session.
 */
(function ($) {
	function getLoggedInUserName() {
		var n = (window.LOGGED_IN_USER_NAME || "").toString().trim();
		if (!n) {
			n = (
				$("#logout a[data-logout-user]").attr("data-logout-user") ||
				$("#show-shortcut").attr("data-user-name") ||
				$("#show-shortcut > span").first().text() ||
				"user"
			).toString().trim();
		}
		return n || "user";
	}

	function escapeHtml(text) {
		return $("<div/>").text(text).html();
	}

	function bindLogout() {
		if (!window.jQuery || !$.root_ || !$.SmartMessageBox) {
			return;
		}

		$.root_.off("click", '[data-action="userLogout"]');
		$.root_.on("click", '[data-action="userLogout"]', function (e) {
			e.preventDefault();
			var $a = $(this);
			var href = $a.attr("href");
			var userName = getLoggedInUserName();

			$.SmartMessageBox({
				title:
					"<i class='fa fa-sign-out txt-color-orangeDark'></i> Logout <span class='txt-color-orangeDark'><strong>" +
					escapeHtml(userName) +
					"</strong></span> ?",
				content:
					$a.attr("data-logout-msg") ||
					"You can improve your security further after logging out by closing this opened browser",
				buttons: "[No][Yes]"
			}, function (answer) {
				if (answer === "Yes") {
					$.root_.addClass("animated fadeOutUp");
					setTimeout(function () {
						window.location = href;
					}, 1000);
				}
			});
		});
	}

	$(function () {
		bindLogout();
	});
})(jQuery);
