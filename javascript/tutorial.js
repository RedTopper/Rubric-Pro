tutorialIndex = 0;
lastTutorialBox = $("<div></div>");
lastTutorialBoxTriangle = $("<div></div>");
navigation = $("<div></div>");

OFFSET_DOWN = 50;
TUTORIAL_BOX_WIDTH = 394;

//Actual tutorial information.
tutorial = [

	//beginning
	["#js_tutorial","Welcome to the Rubric Pro tutorial!<br><br>I'll guide you through the application and all of it's features. <br><br>Press Next to continue, or use &gt;&gt;&gt; or &lt;&lt;&lt; to skip to other sections of this tutorial.", function() {
		changeColor(0, $("#js_tutorial")); //from access.js
	}],
	
	//navigation bar
	["#navigation","This is the navigation bar. You can control aspects of the application here.", function() {
		$('#navigation').children().each(function () {
			$(this).removeClass('selectedsidebar');
			$(this).removeAttr("select");
		});
	}],
	
	//components
	["#js_components", "Let's start with the components tab.<br><br>In this tab you can outline components of your curriculum. You will use these in your rubric's criteria.", function() {
		if(!$("#js_components").attr("select")) {
			$("#js_components").trigger("click");
		}
	}],
	[".help", "Please note, you can view help on sections where this icon appears!"],
	[".js_components_create:first", "To start, we'll need to create a root component.<br><br>Root components allow you to separate your curriculums.<br><br>For example, a Computer Science teacher might have these root components: <ul><li>AP Computer Science<li>Programming 1<li>Database Design<li>etc...</ul>", function() {
		$(".js_components_create:first").trigger("click");
	}],
	["#componentname", "I'll go ahead and type out an example root component name for you. We'll assume that we teach a Math class for now.", function() {
		slowType($("#componentname"), "AP Calculus");
	}],
	["#symbol", "Symbols are short representations of a component. Here is what one might look like for our math class.", function() {
		slowType($("#symbol"), "APCAL");
	}],
	["#symbol", "A symbol will end up looking something like this:<br><br><div class='monotut'><ul><li>APCAL.I.A.1<li>APCS.II.B.3.i<li>HIST.CIVWAR.A<li>etc...</ul></div><br>If you are creating component <div class='monotut'>'1'</div>, for example, then you would type <div class='monotut'>'1'</div> in this field, NOT <div class='monotut'>'APCAL.I.A.1'</div>"],
	["#description", "In order for students to understand the curriculum better, a description can be typed here. Students will be able to view this description for more information.", function() {
		slowType($("#description"), "A college level Math class that focuses on derivatives, integration, limits, and more.");
	}],
	["#js_components_create_submit", "This button creates the root component."],
	
	//await
	["#js_components","I'll wait here for you to create your own root component. Once you have at least one root component, press Next.", function() {
		if(!$("#js_components").attr("select")) {
			$("#js_components").trigger("click");
		}
	}],
	[".js_components_select:first", "This is your root component.", function() {
		$(".js_components_select:first").trigger("click");
	}],
	[".js_components_create:last", "We can further describe a curriculum with sub components. This works exactly like a course outline!<br><br>Each number or letter in your course outline represents a symbol (ex: I, IV, a, 6, i, etc), and the information at that symbol represents a title or description in Rubric Pro."],
	[".js_components_create:last", "Using this information as a template, you can extend your components as far down as you would like..."],
	[".js_components_create:last", "...or create as many sub components as you would like. <br><br>It all depends on how your curriculum is organized!"],
	
	//students
	["#js_accounts", "Next, we have the accounts tab.<br><br>You can add student accounts here. If another teacher in your school already uses Rubric Pro, we'll use their student accounts.", function() {
		if(!$("#js_accounts").attr("select")) {
			$("#js_accounts").trigger("click");
		}
	}],
	["#js_accounts_create", "This button allows you to create a new student or bind a student from another teacher to your account.<br><br>Binding an already existing student will allow that student to access both another teacher's class as well as your own class.", function() {
		$("#js_accounts_create").trigger("click");
	}],
	["#username", "We start by typing in the username of the student.", function() {
		slowType($("#username"), "0019247");
	}],
	["#js_accounts_create_submit", "If we submitted the form without typing in any extra information, Rubric Pro will search your school's database for a student that already exists."],
	["#js_accounts_create_submit", "If the student does not exist within your school's database, Rubric Pro ask you to enter extra information."],
	["#js_accounts_create_submit", "A student will be able to set their password when they first log in. If a student ever forgets their password, or another student 'steals their account' before they log in, you'll be able to reset it."],
	["#js_accounts_create_submit", "This button will bind the student."],
	
	//await
	["#js_accounts","I'll wait here for you to create your own  student. Once you have at least one student, press Next.", function() {
		if(!$("#js_accounts").attr("select")) {
			$("#js_accounts").trigger("click");
		}
	}],
	[".js_accounts_view:first", "Here is your student.", function() {
		$(".js_accounts_view:first").trigger("click");
	}],
	["#js_accounts_view_unbind", "This button will unbind the student from your account. If you do so, you can re-add them through the 'Create new account' button as long as you know their username."],
	["#js_accounts_view_unbind", "Unbinding a student account allows you to keep this list uncluttered."],
	["#js_accounts_view_addclass", "You can also bind students to your classes through this tab."],
	
	//rubrics
	["#js_rubrics", "Rubrics are the core of this application.<br><br>You will use these rubrics in your assignments. Using this tab, you can also bind the components of your curriculum.", function() {
		if(!$("#js_rubrics").attr("select")) {
			$("#js_rubrics").trigger("click");
		}
	}],
	["#js_rubrics_create", "We'll go ahead and create an example rubric.", function() {
		$("#js_rubrics_create").trigger("click");
	}],
	["#subtitle", "Let's consider that we teach a science class. A rubric might be called something like this...", function() {
		slowType($("#subtitle"), "Lab: Chemical Reactions");
	}],
	["#maxpoints", "Next, we need to determine how many points per criteria our rubric will be worth."],
	["#maxpoints", "It is important to understand that this does NOT represent the total points of the rubric. It represents the max grade a student can obtain per criteria."],
	["#maxpoints", "If your assignment requires a different amount of points in some criteria (for example, you have a criterion worth 10 points, and another that is worth 3 points), you should create another rubric and bind BOTH of them to an assignment."],
	["#maxpoints", "Also, if you plan to re-use parts of your rubrics a lot, consider breaking down your large rubrics into smaller rubrics. Then you do not have to copy and paste sections of your large rubrics many times because you can re-use your smaller rubrics in different assignments."],
	["#maxpoints", "In this tutorial, we'll make each criteria worth 10 points.", function() {
		slowType($("#maxpoints"), "10");
	}],
	["#js_rubrics_create_submit", "This button will create the rubric."],
	
	//await
	["#js_rubrics","Again, I'll wait here for you to create your own rubric. Once you have at least one rubric, press Next.", function() {
		if(!$("#js_rubrics").attr("select")) {
			$("#js_rubrics").trigger("click");
		}
	}],
	[".js_rubrics_view:first", "This is a rubric.", function() {
		$(".js_rubrics_view:first").trigger("click");
	}],
	["#js_rubrics_view_addquality", "You can create or modify the qualities of your rubric here.", function() {
		$("#js_rubrics_view_addquality").trigger("click");
	}],
	["#qualityname", "Qualities belong in the top row of your rubrics. They determine what grade a student can get on a criterion. Sometimes they have a name such as 'Not Included', 'Poor', 'Good', or 'Proficient'.<br><br>If you don't want to include a name, you can leave this blank.", function() {
		slowType($("#qualityname"), "Excellent");
	}],
	["#qualitypoints", "This text box represents how  many points a student will earn if they achieve this quality. Usually, qualities range from 0 to the maximum points per criteria. If you enter a number higher than the maximum points per criteria, then it would be considered extra credit!", function() {
		slowType($("#qualitypoints"), "10");
	}],
	["#qualityname", "Also, you do not have to create a quality for every single number between 0 and the maximum points per criteria! You can manually enter a score for a student's grade later. Only add the qualities that you would like to describe in the rubric's body."],
	["#js_rubrics_view_addcriteria", "Over here we have the criteria of a rubric.", function() {
		$("#js_rubrics_view_addcriteria").trigger("click");
	}],
	["#criterianame", "You can type a name for a criterion here. If we were teaching an English class, a rubric might contain the criteria 'Claim', 'Originality', 'Style and Conventions', or 'Spelling and Accuracy'.", function() {
		slowType($("#criterianame"), "Style and Conventions");
	}],
	["#js_rubrics", "In order to continue the tutorial, you'll need to create at least one criteria in your rubric. Once you have one, press Next!"],
	[".js_rubrics_view_addcriteria_component:first", "Sweet! Here is your criteria.", function() {
		$(".js_rubrics_view_addcriteria_component:first").trigger("click");
	}],
	["#js_rubrics", "You should now see a list of your root components to the right of the criteria selected. If you do not, select the components tab and create at least one root component. Then, press Next."],
	[".js_tutorial_component_rubric_selector:first", "The power of Rubric Pro comes from it's ability to bind components of your curriculum to your rubrics."],
	[".js_tutorial_component_rubric_selector:first", "In other words, the criteria of your rubrics should reference some amount of components in your curriculum!"],
	[".js_tutorial_component_rubric_selector:first", "If it doesn't, no worries! You don't have to assign a component to a criterion all the time. However, by assigning a component to a criterion, you can obtain critical data on how well students understand some amount of components as you grade your rubrics!"],
	[".js_tutorial_component_rubric_selector:first", "Hold up, let me explain!<br><br><br>If a student gets a 10/10 in  a criterion that contains component 'I.B.2', then that component will be 100% understood. If you grade another student as a 0/10, then that component drops to 50% understood."],
	[".js_tutorial_component_rubric_selector:first", "Ultimately, Rubric Pro uses rubrics to measure the amount your students understand your curriculum."],
	[".js_tutorial_component_rubric_selector:first", "This system provides valuable feedback to you! After grading assignments, you'll be able to understand what your class is struggling in."],
	[".js_tutorial_component_rubric_selector:first", "Let me give you an example: In music education, a teacher might have a rubric with 'Tone Quality', 'Tempo', 'Musicality', 'Technique', and 'Intonation'. Some of those criteria overlap, so multiple components may be assigned to each of them. When a teacher has finished grading all of their playing assessments, they might see that their band is struggling with 'Tone Quality', and the teacher will focus more in that area.", function() {
		$(".js_tutorial_component_rubric_selector:first").trigger("click");
	}],
	[".js_rubrics_view_addcriteria_component_select:first", "Selecting this button would cause this root component to be bound to the criteria. Any sub components of this component will also be calculated along with the root component, so be careful when selecting a root component!"],
	[".js_rubrics_view_addcriteria_component_select:first", "Rubric Pro will always tell you what components are selected when you select any component. You can experiment with how Rubric Pro selects parts of your criteria later."],
	["#js_rubrics_view_addassignment", "But wait! There's more! Using this button, you can bind this rubric to any amount of assignments! That also means that an assignment can  have any amount of rubrics! In other words, you'll only have to create a rubric once, then you can re-use it as much as you want.<br><br>With that said, that leads us to..."],

	//assignments
	["#js_assignments", "In this tab you can create assignments and assign them to your classes. Assignments include a description and some amount of rubrics.", function() {
		if(!$("#js_assignments").attr("select")) {
			$("#js_assignments").trigger("click");
		}
	}],
	["#js_assignment_create", "Here is the button you will use to create your assignments.", function() {
		$("#js_assignment_create").trigger("click");
	}],
	
	//await
	["#js_assignments", "So go ahead and try to create an assignment! I'll wait here.<br><br>When you are done, press Next to continue.", function() {
		if(!$("#js_assignments").attr("select")) {
			$("#js_assignments").trigger("click");
		}
	}],
	[".js_assignments_view:first", "Your assignments will appear on this list.", function() {
		$(".js_assignments_view:first").trigger("click");
	}],
	["#js_assignment_view_addclass", "The goal of assignments is to assign them to your classes. If you teach the same class, but in different periods with different students, you can use this button to assign a single assignment to multiple classes. You can also give assignments different due dates per class.", function() {
		$("#js_assignment_view_addclass").trigger("click");
	}],
	["#js_assignments_addclass_datepick", "To assign an assignment to a class, first you pick a due date here, then you pick a class below."],
	
	//classes.
	["#js_classes", "And finally, here you can view and grade assignments that are assigned to your classes.<br><br>I'm fairly confident that at this point you can navigate this interface and create your own class without much help. I'll wait here until you have at least one class.", function() {
		$("#js_classes").trigger("click");
	}],
	[".js_classes_edit:first", "Once you click on a class, you can view many aspects of it.", function() {
		$(".js_classes_edit:first").trigger("click");
	}],
	["#js_tutorial_activeassn", "You can view assignments that are active. An assignment is considered active if the current date is before or on the due date of the assignment."],
	["#js_tutorial_pastassn", "You can also view assignments that have occurred in the past."],
	["#js_tutorial_student", "And finally, you can view what students are in your class under the 'Attached Students' section."],
	
	//feedback
	["#js_mail","If you have any questions, or this tutorial wasn't complete enough, feel free to contact me!", function() {
		changeColor(0, $("#js_mail")); //from access.js
	}],
	["#js_mail","I'll use the best of my ability to answer your questions and implement missing features."],
	["#js_mail","<br>(Though please remember this: I am just a High School student)!"],

	
	["#js_tutorial","Congratulations, you finished the Rubric Pro tutorial! I hope I helped you understand how to use Rubric Pro to the fullest potential.<br><br>You can press Quit to exit or use &lt;&lt;&lt; to skip back to a checkpoint that you did not understand.", function() {
		changeColor(0, $("#js_tutorial")); //from access.js
		removeToTier(0); //also from access.js
	}],
]

//Checkpoints so the user can skip parts they already know.
checkpoints = [
	0, //The beginning of the tutorial.
	2, //Components tutorial
	10, //Await for user to create a component.
	15, //Students tutorial
	22, //Await for a student creation.
	27, //Rubrics tutorial
	36, //await for a rubric.
	57, //Assignments
	59, //await for an assignment
	63, //Class
	68, //feedback
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

/**
 * Updates the position of the tutorial box. Should be called after it is created
 * or on a scroll event. This method places the tutorial box to the right of the element.
 * If the tutorial box were to overflow off the screen, it'll place the tutorial box fixed
 * to the right of the screen, slightly offset below the hilighted element.
 */
function updateTutorialBoxPosition() {
	
	//get positions
	tutorialObjectRight = $(tutorial[tutorialIndex][0]).position().left + $(tutorial[tutorialIndex][0]).outerWidth(false);
	tutorialObjectTop = $(tutorial[tutorialIndex][0]).position().top;
	
	if(tutorialObjectRight + TUTORIAL_BOX_WIDTH > $(window).width()) {
		
		//set the position where the tutorial appears BELOW the element, FIXED TO THE RIGHT OF THE SCREEN.
		lastTutorialBox.css("right", 0);
		lastTutorialBox.css("top", tutorialObjectTop + OFFSET_DOWN);
		lastTutorialBox.css("left", "");
		lastTutorialBoxTriangle.css("display", "none");
	} else {
		
		//set the position where the tutorial appears to the RIGHT of the element.
		lastTutorialBox.css("left", tutorialObjectRight);
		lastTutorialBox.css("top", tutorialObjectTop);
		lastTutorialBox.css("right", "");
		lastTutorialBoxTriangle.css("left", tutorialObjectRight);
		lastTutorialBoxTriangle.css("top", tutorialObjectTop);
		lastTutorialBoxTriangle.css("display", "");
	}
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
		"<p>The next element of the tutorial was not found!<p>Please follow the tutorial's instructions.</div>");
		tutorialIndex--;
	} else if(tutorial[tutorialIndex][2] != undefined) {
		tutorial[tutorialIndex][2]();
	}
	
	//append buttons
	lastTutorialBox.append("<img src='images/face.png' class='tutorialface' title='Aaron Walter: the tour guide!'>");
	lastTutorialBox.append("<div class='tutorialpara'>(" + (tutorialIndex + 1) + "/" + tutorial.length + ") " + tutorial[tutorialIndex][1] + "</div>");
	if(tutorialIndex != tutorial.length - 1) {
		lastTutorialBox.append("<hr><a href='#' class='js_tutorial_quit'>Quit</a>");
		lastTutorialBox.append("<a href='#' class='js_tutorial_next'>Next</a>")
		lastTutorialBox.append("<a href='#' class='js_tutorial_skiptonext'>&gt;&gt;&gt;</a>");
		lastTutorialBox.append("<a href='#' class='js_tutorial_skiptoprevious'>&lt;&lt;&lt;</a>");	
	} else {
		lastTutorialBox.append("<hr><a href='#' class='js_tutorial_quit right'>Quit</a>");
		lastTutorialBox.append("<a href='#' class='js_tutorial_skiptoprevious'>&lt;&lt;&lt;</a>");
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

/**
 * Gets the position of the tutorial relative to the checkpoints.
 */
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