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

/**
 * Takes a server response, does some loose parsing, then appends it to a tier.
 */
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
		success: function(data, textStatus, xhr) {
			appendServerResponse(tier, title, data, true);
			switch(xhr.getResponseHeader("JS-Redirect")) {
				case "account":
					setTimeout(doAccounts, 2000);
					break;
				case "classes":
					setTimeout(doClass, 2000);
					break;
				case "removeto1":
					setTimeout(function() {
						removeToTier(1);
					}, 2000);
					
			}
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

/**
 * Changes the color when a user clicks on an element. 
 */
function changeColor(tier, object) {
	if(object.parent().attr('id') == "navigation") {
		
		//If we are looking at the navigation bar, we need to handle it specially
		//because we are not in a "tier" yet.
		$('#navigation').children().each(function () {
			$(this).removeAttr('style');
		});
		
		//We'll set it to black.
		object.css("background-color", "#000");
	} else {
		
		//In a tier, it's fairly modular.
		$('#tier' + tier).children().each(function () {
			$(this).removeAttr('style');
		});
		
		//Blue gradient.
		//object.css("background", "linear-gradient(to bottom, rgba(0,75,150,1) 0%,rgba(0,38,76,1) 100%)");
		
		//Solid black.
		object.css("background-color", "#000");
		object.css("background-image", "none");
	}
}


//==========================================================================================================
//===============Stuff beyond this point is used for sending and getting data from the server===============
//==========================================================================================================


//Special cases: The search function replaces "tier 0", so even though it's called from a "tier 0"
//object, it belongs as a root function.
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

//Sidebar: Accounts tab. 
//Bound to function because it can be called during a JS-Redirect: account
function doAccounts(e) {
	var tier = 0; //This function originates from the sidebar, a tier 0 item.
	log("JQUERY/user", "Request accounts tab.");
	changeColor(tier, $(this));
	createTier(tier, "Accounts");
	callServer(tier, "/backend/accounts.php", "accounts");
	return false;
}
$(document).on('click', '#js_accounts', doAccounts);
	//Accounts tab: Create accounts
	$(document).on('click', '#js_accounts_create', function(e) {
		var tier = 1; 
		log("JQUERY/user", "Request accounts > create tab");
		changeColor(tier, $(this));
		createTier(tier, "Create a new account");
		callServer(tier, "/backend/accounts_create.php", "accounts_create");
		return false;
	});
		//Create accounts: submit
		$(document).on('click', '#js_accounts_create_submit', function(e) {
			var tier = 2;
			log("JQUERY/user", "Request accounts > create tab > submit");
			changeColor(tier, $(this));
			createTier(tier, "Submit");
			callServer(tier, "/backend/accounts_create_submit.php", "accounts_create_submit", 
			{
				USERNAME: $("#username").val(),
				LAST_NAME: $("#last").val(),
				FIRST_NAME: $("#first").val(),
				NICK_NAME: $("#nick").val(),
				GRADE: $("#grade").val(),
				EXTRA: $("#comment").val()
			});
			return false;
		});
			//Submit: bind
			$(document).on('click', '#js_accounts_create_submit_bind', function(e) {
				var tier = 3;
				log("JQUERY/user", "Request accounts > create tab > submit > bind");
				changeColor(tier, $(this));
				createTier(tier, "Bind");
				callServer(tier, "/backend/accounts_create_submit_bind.php", "accounts_create_submit_bind",
				{
					NUM: $(this).data('num'),
					USERNAME: $(this).data('username')
				});
				return false;
			});
	//Accounts tab: Action on ANY student
	$(document).on('click', '.js_accounts_student', function(e) {
		var tier = 1; 
		log("JQUERY/user", "Request accounts > student tab");
		changeColor(tier, $(this));
		createTier(tier, "Edit a student");
		callServer(tier, "/backend/accounts_student.php", "accounts_student (view)",
		{
			STUDENT: $(this).data('num'), 
			REQUEST: "VIEW"
		});
		return false;
	});
		//Action on ANY student: add to class
		$(document).on('click', '#js_accounts_student_addclass', function(e) {
			var tier = 2; 
			log("JQUERY/user", "Request accounts > student tab > add a student to class");
			changeColor(tier, $(this));
			createTier(tier, "Pick a class");
			callServer(tier, "/backend/accounts_student.php", "accounts_student (addclass)",
			{
				STUDENT: $(this).data('num'),
				REQUEST: "ADDCLASS"
			});
			return false;
		});
			//add to class: actual selection of the class
			$(document).on('click', '.js_accounts_student_addclass_select', function(e) {
				var tier = 3; 
				log("JQUERY/user", "Request accounts > student tab > add a student to class > select");
				changeColor(tier, $(this));
				createTier(tier, "Added.");
				callServer(tier, "/backend/accounts_student.php", "accounts_student (addclass-select)",
				{
					//Student is found from previous tier ID.
					STUDENT: $("#js_accounts_student_addclass").data('num'),
					
					//Class is the current data entry for the clicked object when this class is selected.
					CLASS: $(this).data('num'),
					REQUEST: "ADDCLASS-SELECT"
				});
				return false;
			});
		//Action on ANY student: reset password
		$(document).on('click', '#js_accounts_student_reset', function(e) {
			var tier = 2; 
			log("JQUERY/user", "Request accounts > student tab > reset password");
			changeColor(tier, $(this));
			createTier(tier, "Reset Password");
			callServer(tier, "/backend/accounts_student.php", "accounts_student (reset-ask)",
			{
				STUDENT: $(this).data('num'),
				REQUEST: "RESET-ASK"
			});
			return false;
		});
			//reset password: agree!
			$(document).on('click', '#js_accounts_student_reset_yes', function(e) {
				var tier = 3; 
				log("JQUERY/user", "Request accounts > student tab > reset password > reset!");
				changeColor(tier, $(this));
				createTier(tier, "Reset!");
				callServer(tier, "/backend/accounts_student.php", "accounts_student (reset)",
				{
					STUDENT: $(this).data('num'),
					REQUEST: "RESET"
				});
				return false;
			});
		//Action on ANY student: unblind account
		$(document).on('click', '#js_accounts_student_unbind', function(e) {
			var tier = 2; 
			log("JQUERY/user", "Request accounts > student tab > unbind account");
			changeColor(tier, $(this));
			createTier(tier, "Unbind account");
			callServer(tier, "/backend/accounts_student.php", "accounts_student (unbind-ask)",
			{
				STUDENT: $(this).data('num'),
				REQUEST: "UNBIND-ASK"
			});
			return false;
		});
			//unblind account: agree!
			$(document).on('click', '#js_accounts_student_unbind_yes', function(e) {
				var tier = 3; 
				log("JQUERY/user", "Request accounts > student tab > unbind account > unbind!");
				changeColor(tier, $(this));
				createTier(tier, "Unbound!");
				callServer(tier, "/backend/accounts_student.php", "accounts_student (unbind)",
				{
					STUDENT: $(this).data('num'),
					REQUEST: "UNBIND"
				});
				return false;
			});

//Sidebar: Classes tab.
//Function used during a JS-Redirect: classes
function doClass(e) {
	var tier = 0;
	log("JQUERY/user", "Request classes tab.");
	changeColor(tier, $(this));
	createTier(tier, "Classes");
	callServer(tier, "/backend/classes.php", "classes");
	return false;
}
$(document).on('click', '#js_classes', doClass);
	//Classes tab: create
	$(document).on('click', '#js_classes_create', function(e) {
		var tier = 1; 
		log("JQUERY/user", "Request classes > create");
		changeColor(tier, $(this));
		createTier(tier, "Create a new class");
		callServer(tier, "/backend/classes_create.php", "classes_create");
		return false;
	});
		//create: submit
		$(document).on('click', '#js_classes_create_submit', function(e) {
			var tier = 2; 
			log("JQUERY/user", "Request classes > create > submit");
			changeColor(tier, $(this));
			createTier(tier, "Submit");
			callServer(tier, "/backend/classes_create_submit.php", "classes_create",
			{
				NAME: $("#classname").val(),
				YEAR: $("#year").val(),
				TERM: $("#term").val(),
				PERIOD: $("#period").val(),
				DESCRIPTOR: $("#descriptor").val(),
			});
			return false;
		});
	//Classes tab: editor
	$(document).on('click', '.js_classes_edit', function(e) {
		var tier = 1; 
		log("JQUERY/user", "Request classes > edit");
		changeColor(tier, $(this));
		createTier(tier, "Edit this class");
		callServer(tier, "/backend/classes_edit.php", "classes_edit",
		{
			CLASS: $(this).data('num')
		});
		return false;
	});

//"View new messages" button.
$(document).on('click', '#js_consolebottom', function(e) {
	jumplog();
	log("JQUERY/user", "New messages are above. The console will now automatically scroll as new messages appear.");
	return false;
});