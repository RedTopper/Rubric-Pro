<?php
namespace RubricPro;

use RubricPro\ui\info\Title;

require_once "../src/load.php";

$obj = new Title();
$obj->sendJson();
