<?php
	/*
	Plugin Name: Upwards Backup
    Plugin URI: http://upwardstech.com
    Description: Upwards Technologies Active Plugins
    Version: 1.0
    Author: Upwardstech
    Author URI: http://upwardstech.com
    License: GPL2
	*/

define( 'UTNAME', 'upwardsTech' );
define( 'UTDPATH', 'upwardstech_default_path' );
define( 'UTSAVE', 'upwardstech_save' );
define( 'UTSET', 'upwardstech_last_data' );
define( 'ROOTPATH', str_replace('/wp-content/themes', '', get_theme_root()) );


	//init framework
	require_once( 'core/FPL.php' );
	global $FramePress;

/**
*	Create your global instance of framepress, and configure it
*	see "Creating and configuring your instance of framepress" documentation
* 	--------------------------------------------------------------------------------------------------------------------
*/
	global $upwards;
    $upwards = new $FramePress(__FILE__, array(
		'prefix' => 'upwardsprefix',
	));


/**
*	Modifing / Adding paths
*	see "Adding custom Paths" documentation
* 	--------------------------------------------------------------------------------------------------------------------
*/

/*
	$test->mergePaths(array(
		'superlibs' => $test->path['lib'] . DS . 'super';
		'duperlibs' => $test->path['lib'] . DS . 'super' . DS . 'duper';
	));
*/


/**
*	Examples for admin pages and actios
* 	see "Adding admin pages" documentation
* 	--------------------------------------------------------------------------------------------------------------------
*/


	//Admin pages to add
	$my_pages = array (
		'menu' => array (
			array (
				'page.title' => 'Upwards Technologies Backup',
				'menu.title' => 'Upwards Backup',
				'capability' => 'administrator',
				'controller' => 'backup',
				'function' => 'setting',
				'icon' => '',
			)
		)
	);
	$upwards->pages($my_pages);



/**
*	Examples of Actions / filters
* 	see "Adding actions/filters handlers" documentation
* 	--------------------------------------------------------------------------------------------------------------------
*/


	//action/filters
	$my_actions = array (
		array(
			'tag' => 'init',
			'controller' => 'backup',
			'function' => 'filterChangeFile',
		),
	);
	$upwards->actions($my_actions);


/**
*	Examples of shortcodes
* 	see "Adding shortcodes handlers" documentation
* 	--------------------------------------------------------------------------------------------------------------------
*/

/*
	$my_shortcodes = array (
		array(
			'tag' => 'my_shortcode',   // will handle [my_shortcode]
			'controller' => 'test',
			'function' => 'shortA',
		),

	);
	$upwards->shortcodes($my_shortcodes);
*/

?>
