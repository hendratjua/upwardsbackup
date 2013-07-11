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

    $getUpdatePath = self::setFolder();

    add_option( UTSET, null );
    add_option( UTDPATH, $getUpdatePath );
    add_option( UTSAVE, null );


    $randChar = substr(md5(time()), 0, 10);
    $defaultPath = ROOTPATH.'wp-content/backupUpwardsTech-'.$randChar.'/';
    self::saveData(UTDPATH, self::setFolder(), '', 'no');


    $options_names = array(
        UTSAVE,
        UTSET,
        UTDPATH
    );

    if ( get_option( UTSAVE ) != false )
        update_option( UTSAVE, null );
    else
        add_option( UTSAVE, null );

    if ( get_option( UTSET ) != false )
        update_option( UTSET, null );
    else
        add_option( UTSET, null );

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


private function setFolder()
{
    $randChar = substr(md5(time()), 0, 10);
    $setFolder = ROOTPATH.'wp-content/backupUpwardsTech-'.$randChar.'/';

    if(is_dir($setFolder) == false)
        mkdir($setFolder, 0755);

    return $setFolder;
}

?>