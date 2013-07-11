<?php
 /**
 * Activation config file for FramePress Lite.
 *
 * DESCRIPTION NEEDED
 *
 * Licensed under The GPL v2 License
 * Redistributions of files must retain the above copyright notice.
 *
 * @link			https://github.com/perecedero/FramePressLite
 * @package		FramePress
 * @subpackage	config
 * @license		GPL v2 License
 * @author		Ivan Lansky (@perecedero)
 */


function upwardsprefix_on_activation ()
{
	//Your activation code here
    $option_name = UTSAVE;
    $value = "Hendra";
    if ( get_option( $option_name ) != false ) {
        update_option( $option_name, $value );
    } else {
        add_option( $option_name, $value );
    }

    $this->path = self::getDefaultPath();
    if(is_dir($this->path) == false)
        mkdir($this->path, 0755);

    if ( !wp_next_scheduled( 'writeTime' ) )
        wp_schedule_event( time() , 'daily', 'writeTime');

}

function upwardsprefix_on_deactivation ()
{
	//your deactivation code here
    delete_option(UTSAVE);
    delete_option(UTSET);
    delete_option(UTDPATH);

    wp_clear_scheduled_hook('writeTime');
}

?>