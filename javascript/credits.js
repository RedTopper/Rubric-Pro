txt = $("<div class='creditbox'></div>");
TITLE = 0;
IMAGE = 1;
PERSON = 2;
LINK = 3;

credits = [
	[IMAGE, "images/logo.svg", "credits_logo", "Rubric Pro"],
	[TITLE, "PRODUCTION"],
	[PERSON, "Aaron Walter", "Rubric Pro Developer", "Support", "Database Manager", "High School Student"],
	[IMAGE, "images/face.png", "credits_face", "My Face"],
	[TITLE, "INSPIRATION"],
	[PERSON, "Mr. Miller", "Computer Science Educator", "Quality Assurance", "Being Awesome"],
	[TITLE, "ICONS"],
	[PERSON, "Colton Kreischer", "Graphic Design"],
	[TITLE, "BETA TESTERS"],
	[PERSON, "Ms. Vriezen", "History Teacher"],
	[PERSON, "Ms. Calder", "English Teacher"],
	[PERSON, "Ms. Nauman", "Buisness Teacher"],
	[PERSON, "Ryan Pizzo", "Funny Student"],
	[TITLE, "SECURITY"],
	[PERSON, "Kurtis Bowen", "Engineering Student", "Hacking into the Mainframe"],
	[TITLE, "LIBRARIES"],
	[LINK, "https://github.com/rstacruz/nprogress", "NProgress", "Loading Bar"],
	[LINK, "https://jquery.org/team/", "the JQuery Team", "JavaScript Libraries"],
	[LINK, "http://stackoverflow.com/", "Stack Overflow", "Coding Resources"],
	[TITLE, "HONORABLE MENTIONS"],
	[PERSON, "Yorkville Information Technology Department", "For teaching me about networking and firewalls"],
	[PERSON, "YOU", "For educating the future generations of students"],
	[TITLE, "THANK YOU FOR USING RUBRIC PRO!"],
]

function initCredits() {
	for(var i = 0; i < credits.length; i++) {
		switch(credits[i][0]) {
			case TITLE:
				txt.append("<h2 class='credits_title'>" + credits[i][1] + "</h2>");
				break;
			case IMAGE:
				txt.append("<img src='" + credits[i][1] + "' class='" + credits[i][2] + "' title='" + credits[i][3] + "'>");
				break;
			case PERSON:
				txt.append("<h3 class='credits_person'>" + credits[i][1] + "</h3>");
				var j = 1;
				while(credits[i][++j] != undefined) {
					txt.append("<p class='credits_descriptor'>" + credits[i][j] + "</p>");
				}
				break;
			case LINK:
				txt.append("<a href='" + credits[i][1] + "' class='credits_link'>" + credits[i][2] + "</a>");
				txt.append("<p class='credits_descriptor'>" + credits[i][3] + "</p>");
		}
		txt.appendTo($("#tier1"));
	}
}

$(document).on('click', '#js_credits', function(e) {
	txt = $("<div class='creditbox'></div>");
	changeColor(0, $("#js_credits")); //from access.js
	createTier(0, "");
	$("#tier1").append("<div class='title'><h1 style='text-align: center'>Credits</h1></div>")
	log("JQUERY/user", "Credits");
	initCredits();
	
	$("#tier1").on("scroll DOMMouseScroll mousewheel touchmove keyup", function(e){
		if ( e.which > 0 || e.type === "mousewheel" || e.type === "touchmove"){
			$("#tier1").stop();
		}
	});
	setTimeout(function() {
		$("#tier1").animate({
			scrollTop: txt.height() - $("#tier1").outerHeight(true) + 161,
		}, 60000, "linear", function() {
			$("#tier1").off("scroll DOMMouseScroll mousewheel touchmove keyup");
		});
	}, 1500);
});