<?php
namespace RubricPro;

use RubricPro\ui\button\Button;

require_once "../php/load.php";

$obj = new Button("Test", "body", "/index.php");
$obj->addData("class",0);
$obj->addData("time",1);
$obj->addData("thing", false);
$obj->addParagraph("body2");
$obj->sendJson();
