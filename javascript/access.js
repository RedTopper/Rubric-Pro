function log(caller, message) {
	var currentdate = new Date(); 
	var time = "[" + currentdate.getHours() + ":" + currentdate.getMinutes() + ":" + currentdate.getSeconds() + "]";
	$("#console").append(time + " [" + caller + "]: " + message + "<br>");
}

$(document).on('click', '#js_students', function(e) {
	log("JQUERY/user", "Request students tab.");
});