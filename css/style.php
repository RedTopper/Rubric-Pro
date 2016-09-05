<?php
	/*
	This is a configuration file for the CSS stylesheet.
	You can change settings here.
	
	Note: the //esc: parts of the php tags in the CSS file
	fix the parsing that Notepad++ uses to display CSS.
	
	Note: PHP variables are used when you need to update
	values that appear in multiple places. For example, editing
	the footer might require you to change a value in 3 
	different places to keep the same layout.
	*/
	header("Content-type: text/css; charset: UTF-8");
	$LOG_BAR_HEIGHT = "90px";
	$NAV_LINK_HEIGHT = "50px";
	include "style.css";
?>