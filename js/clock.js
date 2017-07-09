$(document).ready(function() {

	var monthNames = [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sept", "Oct", "Nov", "Dic" ]; 
	var dayNames= ["Dom","Lun","Mart","Mier","Jue","Vie","Sab"]

	var newDate = new Date();
	newDate.setDate(newDate.getDate());	 
	$('#Date').html(dayNames[newDate.getDay()] + ", " + newDate.getDate() + '. ' + monthNames[newDate.getMonth()] + ' ' + newDate.getFullYear());

	setInterval( function() {
		var seconds = new Date().getSeconds();
		$("#sec").html(( seconds < 10 ? "0" : "" ) + seconds);
		},1000);
		
	setInterval( function() {
		var minutes = new Date().getMinutes();
		$("#min").html(( minutes < 10 ? "0" : "" ) + minutes);
	    },1000);
		
	setInterval( function() {
		var hours = new Date().getHours();
		$("#hours").html(( hours < 10 ? "0" : "" ) + hours);
	    }, 1000);	
	});