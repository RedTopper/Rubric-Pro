<?php
include "../../restricted/view_verify.php";

header("JS-Redirect: removeto1");

#Do the reset!
resetStudentPassword($info["NUM"]);

#Show that it's been reset
showError("Ok!", "The acccount password has been reset.", "", 201);