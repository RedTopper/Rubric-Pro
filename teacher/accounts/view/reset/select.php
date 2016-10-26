<?php
#Libraries.
include "../../../../restricted/headaccount.php";

header("JS-Redirect: removeto1");

#Do the reset!
sql_resetStudentPassword($info["NUM"]);

#Show that it's been reset
db_showError("Ok!", "The acccount password has been reset.", "", 201);