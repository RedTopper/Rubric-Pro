<?php
include "../../restricted/view_verify.php";

###################################

header("JS-Redirect: account");

#Unbind!
unbindStudentFromTeacher($info["NUM"]);

#Show that it's been unbound
showError("Ok!", "The acccount has been unbound.", "", 201);
break;