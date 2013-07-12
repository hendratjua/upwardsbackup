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
	global $upwardsbackup;
    $upwardsbackup = new $FramePress(__FILE__, array(
		'prefix' => 'upwardsbackups',
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
				'controller' => 'main',
				'function' => 'home',
				'icon' => '',
			)
		)
	);
	$upwardsbackup->pages($my_pages);



/**
*	Examples of Actions / filters
* 	see "Adding actions/filters handlers" documentation
* 	--------------------------------------------------------------------------------------------------------------------
*/


	//action/filters
	$my_actions = array (
		array(
			'tag' => 'init',
			'controller' => 'main',
			'function' => 'filter',
		),array(
            'tag' => 'upwardsbackups.writeThis',
            'controller' => 'main',
            'function' => 'writeThis',
        )
	);
	$upwardsbackup->actions($my_actions);


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
	$upwardsbackup->shortcodes($my_shortcodes);
*/

add_filter( 'cron_schedules', 'cron_add_ten_second' );

function cron_add_ten_second( $schedules ) {
    // Adds once weekly to the existing schedules.
    $schedules['tenSecond'] = array(
        'interval' => 10,
        'display' => __( '10 Second' )
    );
    return $schedules;
}

if (!wp_next_scheduled('upwardsbackups.writeThis')){
    wp_schedule_event(strtotime('now'), 'tenSecond', 'upwardsbackups.writeThis');
}

?>
