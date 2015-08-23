$(document).ready(function(){
	
	//Tool Tip
    $('[data-toggle="tooltip"]').tooltip();   
	//Tool Tip End
	
	$("#edit_appointment").hide();
		
	//Show Edit Confirm Appointment Form
	$(".edit_appointment").click(function() {
		$("#edit_appointment").show();
	});
	
	$("#cancel-edit-appointment").click(function () {
		$("#edit_appointment").hide();
	});

	
	//Dashboard Functions Start
	$("#confirmAppointment").hide();
	$(".view_appointment").click(function() {
		$("#confirmAppointment").show();
		$("#formCreateAppointment").focus();
	});
	
	$("#cancel-appointment").click(function () {
		$("#confirmAppointment").hide();
	});
	
	$(".patient-registration").click(function() {
		$("#patientRegistrationForm").show();
	});
	$("#cancel-patientRegistration").click(function () {
		$("#patientRegistrationForm").hide();
	});
	
	
	//Accordion Function on click
	$(".panel-heading").click( function(){
		//var id = $(".panel-heading").closest("a").attr("id");
		//var id = $('.panel-heading').find(':first');
		var id = $('.panel-heading').find('a').first();
		var className = id.attr("id");
		$("a#"+className+ "i.glyphicon-chevron-down").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-up");
		
	});
	
});