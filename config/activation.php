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


    //Set all save data to null
    //Setting Email

    $validate = array(
        'http://',
        'https://',
        'http://www.',
        'https://www.',
        'www.'
    );

    $form_email = 'upwardsbackup@'.str_replace($validate, '', get_option('siteurl'));
    if(filter_var($form_email, FILTER_VALIDATE_EMAIL) == false)
    {
        $form_email = 'upwardsbackup@wordpress.org';
    }

    $configEmail = array();
    $configEmail['enable'] = 1;
    $configEmail['from']['email'] = $form_email;
    $configEmail['subject'] = "UpwardsBackup Notification Changed";
    $configEmail['to'] = get_option('admin_email');

    $get_UpwardsSave = array();
    $get_UpwardsSave['config_email'] = $configEmail;
    update_option( UTSAVE, json_encode($get_UpwardsSave) );

}

function Upwardsbackups_on_deactivation ()
{
    //Delete all file backup and folder
    $get_UpwardsSave = @json_decode(get_option(UTSAVE));
    $results = $get_UpwardsSave->data_save;
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