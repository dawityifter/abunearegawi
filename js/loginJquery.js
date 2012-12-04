$(document).ready(function() {
	$("div.login_panel_button").click(function(){
		$("div#login_panel").animate({
			height: "400px"
		})
		.animate({
			height: "250px"
		}, "fast");
		$("div.login_panel_button").toggle();
	
	});	
	
   $("div#login_hide_button").click(function(){
		$("div#login_panel").animate({
			height: "0px"
		}, "fast");
		
	
   });	
	
});