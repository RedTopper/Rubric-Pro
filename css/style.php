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
	
	//use this to globally enable the dev console.
	//If you change the values here, update it in access.js!
	$LOG_BAR_HEIGHT = "0px";
	$LOG_BAR_DISPLAY = "none";
	
	//others
	$NAV_LINK_HEIGHT = "50px";
	$NAV_BAR_WIDTH = "180px";
	$SEPERATION_BORDER = "1px solid #777";
	include "style.css";
?>