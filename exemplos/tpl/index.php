<?php 

	require_once("vendor/autoload.php");

	// namespace
	use Rain\Tpl;

	// config
	$config = array(
	    "tpl_dir"       => "templates/",
	    "cache_dir"     => "cache/",
	    "debug"         => true, // set to false to improve the speed
	);

	Tpl::configure( $config );
	
	// create the Tpl object
	$tpl = new Tpl;
	
	// assign a variable
	$tpl->assign( "name", "Nome Teste" );
	$tpl->assign( "version", PHP_VERSION );
	
	// assign an array
	//$tpl->assign( "week", array( "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday" ) );
	
	// draw the template
	$tpl->draw( "index" );


 ?>