<?php
include "../../restricted/view_verify.php";

###################################

header("JS-Redirect: account");

#Unbind!
sql_unbindStudentFromTeacher($info["NUM"], $_SESSION["NUM"]);

#Show that it's been unbound
showError("Ok!", "The acccount has been unbound.", "", 201);