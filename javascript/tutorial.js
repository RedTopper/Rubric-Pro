def = 0;
tutorialIndex = def;
lastTutorialBox = $("<div></div>");
lastTutorialBoxTriangle = $("<div></div>");

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
	[".js_components_create", "To start, we'll need to create a root component.<br><br>Root components allow you to seperate your curriculums.<br><br>For example, a Computer Science teacher might have these root componennts: <ul><li>AP Computer Science<li>Programming 1<li>Database Design<li>etc...</ul>", function() {
		$(".js_components_create").trigger("click");
	}],
	["#componentname", "I'll go ahead and type out an example root component name for you. We'll assume that we teach a Math class for now.", function() {
		$("#componentname").focus();
		slowType($("#componentname"), "AP Calculus");
	}],
	["#symbol", "Symbols are short representations of a component. Here is what one might look like for our math class.", function() {
		$("#symbol").focus();
		slowType($("#symbol"), "APCAL");
	}],
	["#symbol", "A symbol will end up looking something like this:<br><div class='monotut'><ul><li>'APCAL.I.A.1'<li>'APCS.II.B.3.i'<li>'HIST.CIVWAR.A'<li>etc...</ul></div><br>If you are creating component <div class='monotut'>'1'</div>, for example, then you would type <div class='monotut'>'1'</div> in this feild, NOT <div class='monotut'>'APCAL.I.A.1'</div>"],
	["#description", "In order for students to understand the curriculum better, a description can be typed here. Students will be able to view this description for more information.", function() {
		$("#description").focus();
		slowType($("#description"), "A college level Math class that focuses on derivatives, integration, limits, and more.");
	}],
	["#js_components_create_submit", "This button would create the component. We'll skip over that for now, however."],
]

function slowType(object, words, clear) {
	if(clear) {
		object.val("");
	}
	setTimeout(function() {
		object.val(object.val() + words.substring(0,1));
		if(words.length > 1) {
			slowType(object, words.substring(1), false);
		}
	}, 25 + Math.random() * 50);
}

function updateTutorialBoxPosition() {
	var innerScrollerWidth = 0;
	$('#content').children().each(function() {
		innerScrollerWidth += $(this).outerWidth(false);
	});
	if(innerScrollerWidth < $('#content').outerWidth(false)) {
		innerScrollerWidth = $('#content').outerWidth(false)
	}
	tutorialObjectRight = $(tutorial[tutorialIndex][0]).offset().left + $(tutorial[tutorialIndex][0]).outerWidth(false);
	tutorialObjectTop = $(tutorial[tutorialIndex][0]).offset().top;
	
	lastTutorialBox.css("left", tutorialObjectRight);
	lastTutorialBox.css("top", tutorialObjectTop);
	lastTutorialBoxTriangle.css("left", tutorialObjectRight);
	lastTutorialBoxTriangle.css("top", tutorialObjectTop);
}
function destroyTutorialBox() {
	$(tutorial[(tutorialIndex < tutorial.length ? tutorialIndex : tutorial.length - 1)][0]).removeClass("tutorialhilight");
	lastTutorialBox.remove();
	lastTutorialBox = $("<div></div>");
	lastTutorialBox.addClass("tutorialbox");
	lastTutorialBoxTriangle.remove();
	lastTutorialBoxTriangle = $("<div></div>");
	lastTutorialBoxTriangle.addClass("tutorialboxtriangle");
}

function createTutorialBox() {
	if(!$(tutorial[tutorialIndex][0]).exists()) {
		modalAppendServerResponse("<div class='object subtitle'><h2>Hold up!</h2></div><div class='object subtext'>" +
		"<p>The next step in the tutorial has not been downloaded yet.<p>Please wait for your network to finish loading or try refreshing the page.</div>");
		tutorialIndex--;
	}
	lastTutorialBox.append(tutorial[tutorialIndex][1]);
	lastTutorialBox.append("<br><a href='#' class='js_tutorial_quit'>Quit</a>");
	if(tutorialIndex != tutorial.length - 1) {
		lastTutorialBox.append("<a href='#' class='js_tutorial_next'>Next</a>")
	}
	lastTutorialBox.appendTo($(tutorial[tutorialIndex][0]).parent());
	lastTutorialBoxTriangle.appendTo($(tutorial[tutorialIndex][0]).parent());
	$(tutorial[tutorialIndex][0]).addClass("tutorialhilight");
	updateTutorialBoxPosition();
	if(tutorial[tutorialIndex][2] != undefined) {
		tutorial[tutorialIndex][2]();
	}
}

$(document).on('click', '.js_tutorial_next', function(e) {
	destroyTutorialBox();
	tutorialIndex++;
	if(tutorialIndex < tutorial.length) {
		createTutorialBox();
	}
	e.stopPropagation();
});
$(document).on('click', '.js_tutorial_quit', function(e) {
	destroyTutorialBox();
	e.stopPropagation();
});
$(document).on('click', '#js_tutorial', function(e) {
	destroyTutorialBox();
	tutorialIndex = def;
	createTutorialBox();
});

//http://stackoverflow.com/a/920322
$.fn.exists = function () {
    return this.length !== 0;
}