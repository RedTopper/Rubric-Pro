<?php
include "view_verify.php";

header("JS-Redirect: account");

#Unbind!
unbindStudentFromTeacher($row["NUM"]);

#Show that it's been unbound
showError("Ok!", "The acccount has been unbound.", "", 201);
break;