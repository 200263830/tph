(function () {
	require([
		'jquery'
	], function ($) {
		$(document).ready(function () {
            $('.size-chart-icon, .close-chart').on('click', function(){
                $(".size-chart-main").toggleClass("active");
            });
		});
	});
})();
