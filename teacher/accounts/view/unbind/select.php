<?php
#Libraries.
include "../../../../restricted/headaccount.php";

header("JS-Redirect: account");

#Unbind!
sql_unbindStudentFromTeacher($student["NUM"], $_SESSION["NUM"]);

#Show that it's been unbound
db_showError("Ok!", "The acccount has been unbound.", "", 201);