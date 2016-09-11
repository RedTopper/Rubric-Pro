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
	$("#content").append('<div class="bar" id="tier' + tier + '"><div class="title"><h1>' + name + '</h1></div></div>').find("#tier" + tier).hide().fadeIn("normal");
	
	//Remove the white space between inline-block elements (to prevent gaps)
	$("#content").contents().filter(function () {
		return this.nodeType === 3;
	}).remove();
	
	$('#contentscroller').animate({scrollLeft: "+=401px"});
}

function appendServerResponse(tier, title, data, success, errorcode) {
	if(success) {
		errorcode = "Invalid response. The server sent data, but the format was not standard.";
	}
	
	//If the data is too short
	if(data.length < 30) {
		$("#tier" + tier).append(
			'<div class="object subtitle"><h2>Programming Error</h2></div>' +
			'<div class="object subtext">' +
				'<p>An error in the programming occured.' +
				'<p>The server returned an empty response.' +
			'</div>'
		);
		log("AJAX/server", "Fatal error requesting data for " + title + ".");
		
	//else if the data does not have ther right header.
	} else if(!(data.includes('<div class="object subtitle">'))) {
		$("#tier" + tier).append(
			'<div class="object subtitle"><h2>Undefined Error</h2></div>' +
			'<div class="object subtext">' +
				'<p>An undefined error in the application occured.' +
				'<p>The server returned a non-empty and non-standard response.' +
				'<p>General information: ' + errorcode +
			'</div>'
		);
		log("AJAX/server", "Fatal error requesting data for " + title + ".");
		
	//else the data has the right header...
	} else {
		$("#tier" + tier).append(data);
		
		//and it's successfull
		if(success) {
			log("AJAX/server", "Obtained data successfully for " + title + ".");
		} else {
			log("AJAX/server", "Fatal error requesting data for " + title + ".");
		}
	}
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
			appendServerResponse(tier, title, data, true);
		},
		error: function(xhr, status, error) {
			appendServerResponse(tier, title, xhr.responseText, false, error);
		}
	});
}

/**
 * Used for sending specific searches to the database.
 * tier is the tier to create the result on.
 * tiername is the tier's name
 * dir is the path on the webserver to query
 * searchbox is the html ID of the search box.
 * database is the database we are searching. Exclusively for the user.
 * where is the is the variable
 */
function search(tier, tiername, dir, searchbox, database, where) {
	var tier = 0;
	var search = $(searchbox).val();
	log("JQUERY/user", "You searched the " + database + " database where the " + where + " is: " + search);
	createTier(tier, tiername);
	callServer(tier, dir, tiername.toLowerCase(), {SEARCH: search, WHERE: where});
}

//Sidebar: Classes tab.
$(document).on('click', '#js_classes', function(e) {
	var tier = 0; //This function originates from the sidebar, a tier 0 item.
	createTier(tier, "Classes");
	log("JQUERY/user", "Request classes tab.");
	return false;
});

//Sidebar: Accounts tab.
$(document).on('click', '#js_accounts', function(e) {
	var tier = 0; //This function originates from the sidebar, a tier 0 item.
	log("JQUERY/user", "Request accounts tab.");
	createTier(tier, "Accounts");
	callServer(tier, "/backend/accounts.php", "accounts");
	return false;
});

	//Accounts tab: search accounts by username.
	$(document).on('click', '#js_accounts_search_username', function(e) {
		search(0, "Accounts", "/backend/accounts.php", "#js_accounts_search", "student", "username");
		return false;
	});
	//Accounts tab: search accounts by last name.
	$(document).on('click', '#js_accounts_search_last', function(e) {
		search(0, "Accounts", "/backend/accounts.php", "#js_accounts_search", "student", "last");
		return false;
	});
	//Accounts tab: search accounts by first name.
	$(document).on('click', '#js_accounts_search_first', function(e) {
		search(0, "Accounts", "/backend/accounts.php", "#js_accounts_search", "student", "first");
		return false;
	});
	//Accounts tab: Create accounts
	$(document).on('click', '#js_accounts_create', function(e) {
		var tier = 1; //This function originates from a tier 0 item, so it is 
		log("JQUERY/user", "Request accounts > create tab");
		createTier(tier, "Create a new account");
		callServer(tier, "/backend/accounts_create.php", "accounts_create");
		return false;
	});
		//Create accounts: submit
		$(document).on('click', '#js_accounts_create_submit', function(e) {
			var tier = 2; //This function originates from a tier 0 item, so it is 
			log("JQUERY/user", "Request accounts > create tab > submit");
			createTier(tier, "Submit");
			callServer(tier, "/backend/accounts_create_submit.php", "accounts_create_submit", 
			{
				USERNAME: $("#username").val(),
				LAST_NAME: $("#last").val(),
				FIRST_NAME: $("#first").val(),
				NICK_NAME: $("#nick").val(),
				EXTRA: $("#comment").val()
			});
			return false;
		});

//"View new messages" button.
$(document).on('click', '#js_consolebottom', function(e) {
	jumplog();
	log("JQUERY/user", "New messages are above. The console will now automatically scroll as new messages appear.");
	return false;
});