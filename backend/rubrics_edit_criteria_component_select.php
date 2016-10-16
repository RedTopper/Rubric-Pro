<?php
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "db.php";

$needsFunction = true;
include "functions.php";
$COMPONENT_NUM = isset($_POST["COMPONENT_NUM"]) ? $_POST["COMPONENT_NUM"] : null;
$RUBRIC_NUM = isset($_POST["RUBRIC_NUM"]) ? $_POST["RUBRIC_NUM"] : null;
$CRITERIA_NUM = isset($_POST["CRITERIA_NUM"]) ? $_POST["CRITERIA_NUM"] : null;

$result = getCompiledSymbolTree($_SESSION["NUM"], $COMPONENT_NUM);

showError($result[0]["TREE"], "</p><pre class='monospace'>" . var_export($result, true) . "</pre>", "Your tree", 200);