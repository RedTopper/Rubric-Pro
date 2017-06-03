<?php
namespace RubricPro;

spl_autoload_register(function($class) {

	// Cut Root-Namespace
	$class = str_replace( __NAMESPACE__.'\\', '', $class );

	// Correct DIRECTORY_SEPARATOR
	$class = str_replace( array( '\\', '/' ), DIRECTORY_SEPARATOR, __DIR__.DIRECTORY_SEPARATOR.$class.'.php' );

	// Get file real path
	$class = realpath($class);

	//check if exists
	if($class === false) {
		return false;
	} else {
		require_once( $class );
		return true;
	}
});