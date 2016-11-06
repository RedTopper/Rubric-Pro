tutorialIndex = 0;
lastTutorialBox = $("<div></div>");
lastTutorialBoxTriangle = $("<div></div>");
navigation = $("<div></div>");

//Actual tutorial information.
tutorial = [
	["#js_tutorial","Welcome to the Rubric Pro tutorial!<br><br>This tutorial will guide you through the application and all of it's features.", function() {
		changeColor(0, $("#js_tutorial")); //from access.js
	}],
	["#navigation","This is the navigation bar. You can control aspects of the application here.", function() {
		$('#navigation').children().each(function () {
			$(this).removeClass('selectedsidebar');
			$(this).removeAttr("select");
		});
	}],
	["#js_components", "In this tab you can outline components of your cirriculum. You will use these in your rubric's criteria."],
	["#js_accounts", "You can add student accounts here. If another teacher in your school already uses Rubric Pro, we'll use their student accounts."],
	["#js_rubrics", "You can create and modify your rubric here. You will use these rubrics in your assignments. Using this tab, you can also bind the components of your cirriculum."],
	["#js_assignments", "In this tab you can create assignments and assign them to your classes."],
	["#js_classes", "Here you can view and grade assignments assigned to your classes."],
	["#js_components", "Let's start with the components tab", function() {
		$("#js_components").trigger("click");
	}],
	[".help", "Before we continue, you can view help on sections where this icon appears.<br><br>You can try this now, or press next to continue the tutorial."],
	[".js_components_create:first", "To start, we'll need to create a root component.<br><br>Root components allow you to seperate your curriculums.<br><br>For example, a Computer Science teacher might have these root componennts: <ul><li>AP Computer Science<li>Programming 1<li>Database Design<li>etc...</ul>", function() {
		$(".js_components_create:first").trigger("click");
	}],
	["#componentname", "I'll go ahead and type out an example root component name for you. We'll assume that we teach a Math class for now.", function() {
		slowType($("#componentname"), "AP Calculus");
	}],
	["#symbol", "Symbols are short representations of a component. Here is what one might look like for our math class.", function() {
		slowType($("#symbol"), "APCAL");
	}],
	["#symbol", "A symbol will end up looking something like this:<br><div class='monotut'><ul><li>'APCAL.I.A.1'<li>'APCS.II.B.3.i'<li>'HIST.CIVWAR.A'<li>etc...</ul></div><br>If you are creating component <div class='monotut'>'1'</div>, for example, then you would type <div class='monotut'>'1'</div> in this feild, NOT <div class='monotut'>'APCAL.I.A.1'</div>"],
	["#description", "In order for students to understand the curriculum better, a description can be typed here. Students will be able to view this description for more information.", function() {
		slowType($("#description"), "A college level Math class that focuses on derivatives, integration, limits, and more.");
	}],
	["#js_components_create_submit", "This button creates the component. For the tutorial's sake, we'll create it as an example."],
	[".js_components_select:first","This is a root component.",function() {
		alreadyHasTutorialComponent = false;
		$(".js_components_select").each(function() {
			if($(this).find("h3").html().trim() == "(APCAL) AP Calculus") {
				alreadyHasTutorialComponent = true;
			}
		})
		if(!alreadyHasTutorialComponent) {
			$("#js_components_create_submit").trigger("click");
		}
		$(".js_components_select:first").trigger("click");
	}],
	[".js_components_create:last", "We can further describe a cirriculum with sub components. This works exactly like a course outline."],
	[".js_components_create:last", "You can extend as far down as you would like..."],
	[".js_components_create:last", "...or create as many sub components as you would like. <br><br>It all depends on how your cirriculum is organized!"],
	["#js_accounts", "Next, we have student accounts.", function() {
		$("#js_accounts").trigger("click");
	}],
	["#js_accounts_create", "This button allows you to create a new student or bind a student from another class to your account.<br><br>Binding an already existing student will allow that student to access both another teacher's class as well as your own class.", function() {
		$("#js_accounts_create").trigger("click");
	}],
	["#js_tutorial","Congratulations, you finished the Rubric Pro tutorial! Press Quit to exit.", function() {
		changeColor(0, $("#js_tutorial")); //from access.js
		removeToTier(0); //also from access.js
	}]
]

//Checkpoints so the user can skip parts they already know.
checkpoints = [
	0, //The beginning of the tutorial.
	1, //Explanation of the nav bar.
	7, //Components tutorial
	19, //Students tutorial
	tutorial.length - 1, //the end
	tutorial.length  //the end
]

/**
 * Types in a textbox slowly, as to represent a human typing in a text box.
 * object: The object to type in
 * words: the words to type into the object
 * clear: Don't pass this parameter.
 */
function slowType(object, words, clear) {
	if(clear) {
		object.val("");
	}
	setTimeout(function() {
		object.val(object.val() + words.substring(0,1));
		if(words.length > 1) {
			slowType(object, words.substring(1), false);
		}
	}, 10 + Math.random() * 90);
}

function updateTutorialBoxPosition() {
	
	//get positions
	tutorialObjectRight = $(tutorial[tutorialIndex][0]).offset().left + $(tutorial[tutorialIndex][0]).outerWidth(false);
	tutorialObjectTop = $(tutorial[tutorialIndex][0]).offset().top;
	
	//set the position.
	lastTutorialBox.css("left", tutorialObjectRight);
	lastTutorialBox.css("top", tutorialObjectTop);
	lastTutorialBoxTriangle.css("left", tutorialObjectRight);
	lastTutorialBoxTriangle.css("top", tutorialObjectTop);
}
function destroyTutorialBox() {
	//remove the hilight
	$(tutorial[(tutorialIndex < tutorial.length ? tutorialIndex : tutorial.length - 1)][0]).removeClass("tutorialhilight");
	
	//unbind scroll events
	lastTutorialBox.parent().unbind("scroll");
	lastTutorialBox.parent().parent().unbind("scroll");
	$("#contentscroller").unbind("scroll");
	
	//remove and recreate an empty self
	lastTutorialBox.remove();
	lastTutorialBox = $("<div></div>");
	lastTutorialBox.addClass("tutorialbox");
	lastTutorialBoxTriangle.remove();
	lastTutorialBoxTriangle = $("<div></div>");
	lastTutorialBoxTriangle.addClass("tutorialboxtriangle");
}

function createTutorialBox() {
	
	//check to see if this element actually exists.
	if(!$(tutorial[tutorialIndex][0]).exists()) {
		modalAppendServerResponse("<div class='object subtitle'><h2>Hold up!</h2></div><div class='object subtext'>" +
		"<p>The next element of the tutorial was not found!<p>Try refreshing the page.</div>");
		tutorialIndex--;
	} else if(tutorial[tutorialIndex][2] != undefined) {
		tutorial[tutorialIndex][2]();
	}
	
	//append buttons
	lastTutorialBox.append(tutorial[tutorialIndex][1]);
	if(tutorialIndex != tutorial.length - 1) {
		lastTutorialBox.append("<hr><a href='#' class='js_tutorial_quit'>Quit</a>");
		lastTutorialBox.append("<a href='#' class='js_tutorial_next'>Next</a>")
		lastTutorialBox.append("<a href='#' class='js_tutorial_skiptonext'>&gt;&gt;</a>");
		lastTutorialBox.append("<a href='#' class='js_tutorial_skiptoprevious'>&lt;&lt;</a>");	
	} else {
		lastTutorialBox.append("<hr><a href='#' class='js_tutorial_quit right'>Quit</a>");
		lastTutorialBox.append("<a href='#' class='js_tutorial_skiptoprevious'>&lt;&lt;</a>");
	}
	
	//position counter
	lastTutorialBox.append("<div class='position'>" + getPositionOfTutorial() + "/" + (checkpoints.length - 2) + "</div>");
	
	//attach tutorial to dom
	lastTutorialBox.appendTo($(tutorial[tutorialIndex][0]).parent());
	lastTutorialBoxTriangle.appendTo($(tutorial[tutorialIndex][0]).parent());
	
	//hilight the tutorial element (might not work on all browsers)
	$(tutorial[tutorialIndex][0]).addClass("tutorialhilight");
	
	//update position of tutorial box.
	updateTutorialBoxPosition();
	
	//add listeners in case of scroll.
	lastTutorialBox.parent().on("scroll", updateTutorialBoxPosition);
	lastTutorialBox.parent().parent().on("scroll", updateTutorialBoxPosition);
	$("#contentscroller").on("scroll", updateTutorialBoxPosition);
}

function getPositionOfTutorial() {
	position = 0;
	for(i = 0; i < checkpoints.length - 1; i++) {
		if(tutorialIndex >= checkpoints[i] && tutorialIndex < checkpoints[i + 1]) {
			return i;
		}
	}
	return 0;
}

$(document).on('click', '#js_tutorial', function(e) {
	destroyTutorialBox();
	tutorialIndex = 0;
	createTutorialBox();
});
$(document).on('click', '.js_tutorial_next', function(e) {
	e.stopPropagation();
	destroyTutorialBox();
	tutorialIndex++;
	if(tutorialIndex < tutorial.length) {
		createTutorialBox();
	}
});
$(document).on('click', '.js_tutorial_quit', function(e) {
	e.stopPropagation();
	destroyTutorialBox();
});
$(document).on('click', '.js_tutorial_skiptonext', function(e) {
	e.stopPropagation();
	destroyTutorialBox();
	if((getPositionOfTutorial() + 1) < checkpoints.length - 1) {
		tutorialIndex = checkpoints[getPositionOfTutorial() + 1];
		createTutorialBox();	
	}
});
$(document).on('click', '.js_tutorial_skiptoprevious', function(e) {
	e.stopPropagation();
	destroyTutorialBox();
	if((getPositionOfTutorial() - 1) >= 0) {
		tutorialIndex = checkpoints[getPositionOfTutorial() - 1];
		createTutorialBox();	
	}
});

//http://stackoverflow.com/a/920322
$.fn.exists = function () {
    return this.length !== 0;
}