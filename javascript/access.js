var currentTier = 0;

//http://stackoverflow.com/a/12034334
var entityMap = {"&": "&amp;", "<": "&lt;", ">": "&gt;", '"': '&quot;', "'": '&#39;', "/": '&#x2F;'};

/**
 * Removes bad user input to prevent accidental html from being parsed into the console.
 */
function escapeHtml(string) {
  return String(string).replace(/[&<>"'\/]/g, function (s) {
    return entityMap[s];
  });
}

/**
 * Adds a zero to the left of a number. 0 -> 00, 9 -> 09, 23 -> 23.
 */
function padTwo(number) {
	var input = "" + number;
	var pad = "00";
	return pad.substring(0, pad.length - input.length) + input;
}

/**
 * Jump the console window to the bottom.
 */
function jumplog() {
    $("#logbar").scrollTop($("#console").outerHeight(true) - $("#logbar").innerHeight());
}

/**
 * Check to see if the console window is already at the bottom.
 */
function isAtBottom() {
    return $("#logbar").scrollTop() + $("#logbar").innerHeight() >= $("#console").outerHeight(true) - 50; //50px to correct slight scroll ups.
}

/**
 * Logs a message to the console at the bottom of the page.
 *
 * If the user has scrolled away from the bottom, a small box to
 * prompt the user to "View new messages" will appear. Otherwise, this function
 * will automatically scroll the console window.
 */
function log(caller, message) {
	var wasAtBottom = isAtBottom();
	var date = new Date(); 
	var time = "[" + padTwo(date.getHours()) + ":" + padTwo(date.getMinutes()) + ":" + padTwo(date.getSeconds()) + "]";
	$("#console").append(time + " [" + caller + "]: " + escapeHtml(message) + "<br>\n");
	if (wasAtBottom) {
		jumplog();
		$("#js_consolebottom").css("display","none"); //If we are at the bottom, remove the box used to jump to the bottom.
	} else {
		$("#js_consolebottom").css("display","block");
	}
}

/**
 * Removes all tiers that are greater than  the specified tier.
 *
 * This function then updates currentTier with the passed parameter.
 * A tier is a colum that appears in the editor, so the "navbar" is tier 0,
 * the settings that appear from the navbar is tier 1,
 * and the settings that appear from those settings is tier 2,
 * and the settings that appear from those settings that appeared from those settings are tier 3.
 * You get the point.
 */
function removeToTier(tier) {
	//remove tiers
	for(var remove = currentTier; remove > tier; remove--) {
		$("#tier" + remove).remove();
	}
	
	currentTier = tier + 1;
}

/** 
 * Creates a new tier in the DOM. Auto removes any tiers that should not exist
 * by calling removeToTier().
 *
 * tier: The tier is the tier of the caller, if we are calling from tier "0", then data will be created in tier1.
 */
function createTier(tier, name) {
	//remove all the other tiers that come after the current tier.
	removeToTier(tier);
	
	//procede to next tier.
	tier = tier + 1;
	
	//create the tier.
	$("#content").append('<div class="bar" id="tier' + tier + '"><div class="title"><h1>' + name + '</h1></div></div>').find("#tier" + tier).hide().fadeIn();
	
	//Remove the white space between inline-block elements (to prevent gaps)
	$("#content").contents().filter(function () {
		return this.nodeType === 3;
	}).remove();
}

/**
 * Performs an AJAX request on the server.
 *
 * tier: "tier" is the tier of the caller, so if we are calling from tier "0", this will insert into the newly created tier1.
 * path: The path in the webserver.
 * title: The title to write in the log.
 * post: Extra server params.
 */
function callServer(tier, path, title, post) {
	//procede to next tier.
	tier = tier + 1;
	params = {AJAX: true};
	if(post !== undefined) {
		params = $.extend({}, params, post);
	}

	$.ajax({
		type: "POST",
		url: path,
		data: params,
		success: function(data) {
			$("#tier" + tier).append(data);
			log("AJAX/server", "Obtained data for " + title + ".");
		},
		error: function(xhr, status, error) {
			if(xhr.responseText.length < 10) {
				$("#tier" + tier).append(
					'<div class="object subtitle"><h2>Programming Error</h2></div>' +
					'<div class="object subtext">' +
						'<p>An error in the programming occured.' +
						'<p>The server returned an empty response.' +
					'</div>'
				);
				log("AJAX/server", "Fatal error requesting data for " + title + ".");
			} else {
				$("#tier" + tier).append(xhr.responseText);
				log("AJAX/server", "The server returned an error.");
			}
		}
	});
}

//Sidebar: Classes tab.
$(document).on('click', '#js_classes', function(e) {
	var tier = 0; //This function originates from the sidebar, a tier 0 item.
	createTier(tier, "Classes");
	log("JQUERY/user", "Request classes tab.");
	return false;
});

//Sidebar: Students tab.
$(document).on('click', '#js_students', function(e) {
	var tier = 0; //This function originates from the sidebar, a tier 0 item.
	log("JQUERY/user", "Request students tab.");
	createTier(tier, "Students");
	callServer(tier, "/backend/students.php", "Students");
	return false;
});

	//Students tab: search student.
	$(document).on('keydown', '#js_students_search', function(e) {
		if(e.which === 13) {
			var tier = 0;
			var search = $("#js_students_search").val();
			log("JQUERY/user", "You searched the student database for: " + search);
			createTier(tier, "Students");
			callServer(tier, "/backend/students.php", "Students", {SEARCH: search});
			return false;
		}
	});

//"View new messages" button.
$(document).on('click', '#js_consolebottom', function(e) {
	jumplog();
	log("JQUERY/user", "New messages are above. The console will now automatically scroll as new messages appear.");
	return false;
});