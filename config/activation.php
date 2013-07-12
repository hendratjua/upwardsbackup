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


function Upwardsbackups_on_activation ()
{
    global $upwardsbackup;

	//Create folder for backup file
    $randChar = substr(md5(time()), 0, 10);
    $setFolder = ROOTPATH.'/wp-content/backupUpwardsTech-'.$randChar.'/';
    if(is_dir($setFolder) == false)
        mkdir($setFolder, 0755);

    //Save path folder to database
    update_option( UTDPATH, $setFolder );


    //Get all data from folder and file from root
    $upwardsbackup->import('HelperUpwardsBackup.php');
    $helperUpwardsBackup = new HelperUpwardsBackup();
    $getAllInformationFile = $helperUpwardsBackup->getFileContent(ROOTPATH, null);
    update_option( UTSET, json_encode($getAllInformationFile) );


    //Reset all save data to null
    update_option( UTSAVE, null );
}

function Upwardsbackups_on_deactivation ()
{
    //Delete all file backup and folder
    $results = @json_decode(get_option(UTSAVE));
    $path = get_option(UTDPATH);
    foreach($results as $result)
    {
        $file = $path.$result->filename;
        //Delete all file backup
        if(file_exists($file))
            unlink($file);
    }

    //Delete folder
    if(is_dir($path))
        rmdir($path);

    //Delete all database from wordpress
    delete_option(UTSAVE);
    delete_option(UTSET);
    delete_option(UTDPATH);


    //Clear all the schedule
    wp_clear_scheduled_hook('upwardsbackups.writeThis');
    wp_clear_scheduled_hook('upwardsbackups.write');

}

?>