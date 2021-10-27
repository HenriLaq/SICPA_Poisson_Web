$(document).ready(function() {	

		var id = '#dialogLB';
	
		//Get the screen height and width
		var maskHeight = $(document).height();
		var maskWidth = $(window).width();
	
		//Set heigth and width to mask to fill up the whole screen
		$('#maskLB').css({'width':maskWidth,'height':maskHeight});
		
		//transition effect		
		$('#maskLB').fadeIn(500);	
		$('#maskLB').fadeTo("slow",0.9);	
	
		//Get the window height and width
		var winH = $(window).height();
		var winW = $(window).width();
              
		//Set the popup window to center
		$(id).css('top',  winH/2-$(id).height()/2);
		$(id).css('left', winW/2-$(id).width()/2);
	
		//transition effect
		$(id).fadeIn(2000); 	
	
	//if close button is clicked
	$('.windowLB .close').click(function (e) {
		//Cancel the link behavior
		e.preventDefault();
		
		$('#maskLB').hide();
		$('.windowLB').hide();
	});		
	
	//if mask is clicked
	$('#maskLB').click(function () {
		$(this).hide();
		$('.windowLB').hide();
	});		
	
});