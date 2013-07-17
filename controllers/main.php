<?php

class UpwardsbackupsMain
{
    public $layout = 'layout';

    public function __construct()
    {
        $setFolder = get_option( UTDPATH );
        if(is_dir($setFolder) == false)
            mkdir($setFolder, 0755);
    }


	/*
	 * Handler for  FramePress menu link
	 *
	 * Cause this is a page, this function will render
	 * its view automatically before the function finish
	*/
	public function home()
	{
        global $upwardsbackup;

        $get_UpwardsSave = @json_decode(get_option(UTSAVE));
        $path = get_option(UTDPATH);

        $upwardsbackup->viewSet('list_files', $get_UpwardsSave->data_save);
        $upwardsbackup->viewSet('path', $path);

	}

    public function emailSetting()
    {
        global $upwardsbackup;

        if($_POST)
        {

            $configEmailTemp = array();
            $configEmailTemp['enable'] = isset($_POST['enable']) ? mysql_real_escape_string($_POST['enable']) : 0;
            $configEmailTemp['from']['email'] = $_POST['form_email'] != null ? mysql_real_escape_string($_POST['form_email']) : 'upwardsbackup@wordpress.org';
            $configEmailTemp['subject'] = $_POST['subject'] != null ? mysql_real_escape_string($_POST['subject']) : "UpwardsBackup Notification Changed";
            $configEmailTemp['to'] = $_POST['to'] != null ? mysql_real_escape_string($_POST['to']) : get_option('admin_email');

            $get_UpwardsSave = @json_decode(get_option(UTSAVE));
            $get_UpwardsSave->config_email = $configEmailTemp;
            update_option( UTSAVE, json_encode($get_UpwardsSave) );

            $upwardsbackup->viewSet('msg', "Successful update Email Setting");

        }

        $get_UpwardsSave = @json_decode(get_option(UTSAVE));
        $config_email = isset($get_UpwardsSave->config_email) ? $get_UpwardsSave->config_email : null;

        $upwardsbackup->viewSet('config_email', $config_email);

    }


    /**
     * Backup all data
     */
    public function backupAllData()
    {
        global $upwardsbackup;

        $file_name = UTNAME.date(" M d, Y H-i-s").'.zip';

        $get_UpwardsSave = @json_decode(get_option(UTSAVE));
        $get_save = $get_UpwardsSave->data_save;
        $path = get_option(UTDPATH);

        $upwardsbackup->import('HelperUpwardsBackup.php');
        $helperUpwardsBackup = new HelperUpwardsBackup();
        $getAllInformationFile = $helperUpwardsBackup->getFileContent(ROOTPATH, null);

        $get_UpwardsSave->data_save = self::update_save_data($get_save, $file_name, 'All Data');

        $phar = new PharData($path.$file_name);
        $phar->buildFromDirectory(ROOTPATH);

        update_option( UTSAVE, json_encode($get_UpwardsSave) );
        update_option( UTSET, json_encode($getAllInformationFile) );

        $upwardsbackup->redirect(array('function'=>'home'));

    }



    public function checkingChangeFileAu()
    {
        global $upwardsbackup;

        $result = self::checkChangeFile();
        if($result)
            $params = '&msg=Successful do backup manually';
        else
            $params = '&msg=There have been no changes found';

        $upwardsbackup->redirect(array('function'=>'home', 'params' => $params));

    }


    public function deleteBackup()
    {
        global $upwardsbackup;

        $path = get_option(UTDPATH);

        $get_UpwardsSave = @json_decode(get_option(UTSAVE));
        $results = $get_UpwardsSave->data_save;

        $temp = array();
        foreach($results as $result)
        {
            if($result->filename == $_REQUEST['name'])
            {
                $file = $path.$result->filename;
                if(file_exists($file)) unlink($file);
            }
            else
            {
                $temp[] = $result;
            }
        }

        $get_UpwardsSave->data_save = $temp;

        update_option( UTSAVE, json_encode($get_UpwardsSave) );

        $upwardsbackup->redirect(array('function'=>'home'));

    }



    public function checkingChangeFile()
    {
        self::checkChangeFile();
        self::sendMail();
        return true;

    }





    /**
     * checking change file and tar it
     */
    private function checkChangeFile()
    {
        global $upwardsbackup;

        //Get all data from folder and file from root
        $upwardsbackup->import('HelperUpwardsBackup.php');
        $helperUpwardsBackup = new HelperUpwardsBackup();

        $new_data = $helperUpwardsBackup->getFileContent(ROOTPATH, null);
        $last_update = @json_decode(get_option(UTSET));

        $result = self::checkingChangeArray($last_update, $new_data);

        self::backupData($result);

        update_option( UTSET, json_encode($new_data) );

        if(empty($result))
            return 0;
        else
            return 1;
    }



    /**
     * Checking if old and new array are same or not
     *
     * @param array $old_data
     * @param array $new_data
     * @return array
     */
    private function checkingChangeArray($old_data = array(), $new_data = array())
    {
        $ignore_list = array(
        );
        $result = array();
        if(!empty($old_data) AND !empty($new_data))
        {
            foreach($new_data as $new_key => $new_value)
            {
                foreach($old_data as $old_key => $old_value)
                {
                    if($new_value['name'] == $old_value->name)
                    {
                        if($new_value['type'] == 'D')
                        {
                            // this is directory
                            if(!in_array($new_value['name'], $ignore_list))
                            {
                                $temp = self::checkingChangeArray($old_value->child, $new_value['child']);
                                $result = array_merge($result, $temp);
                            }

                        }
                        else
                        {
                            // this is file
                            if($new_value['modified'] != $old_value->modified)
                                $result[] = $new_value;
                        }

                    }
                }
            }

        }

        return $result;
    }

    /**
     * Backup all change data
     */
    private function backupData($new_files = array())
    {
        if(!empty($new_files))
        {
            $change_log = '';
            $first = true;
            foreach($new_files as $file)
            {
                if($first)
                {
                    $first = false;
                    $change_log .= $file['path'];
                }
                else
                {
                    $change_log .= '<br/>'.$file['path'];
                }
            }

            $file_name = UTNAME.date(" M d, Y H-i-s").'.zip';

            $get_UpwardsSave = @json_decode(get_option(UTSAVE));
            $get_save = $get_UpwardsSave->data_save;
            $path = get_option(UTDPATH);

            $get_UpwardsSave->data_save = self::update_save_data($get_save, $file_name, $change_log);

            update_option( UTSAVE, json_encode($get_UpwardsSave) );

            $phar = new PharData($path.$file_name);
            foreach($new_files as $file)
            {
                $file_location = $file['parent'].DS.$file['name'];
                $phar->addFile($file['path'], $file_location);
            }

            return 1;
        }

        return 0;

    }


    private function update_save_data($get_save, $file_name, $change_log)
    {
        $i=0;
        if(isset($get_save))
        {
            $data_backup = array();
            foreach($get_save as $save)
            {
                $data_backup[$i]['filename'] = $save->filename;
                $data_backup[$i]['date'] = $save->date;
                $data_backup[$i]['data'] = $save->data;
                $i++;
            }
            $data_backup[$i]['filename'] = $file_name;
            $data_backup[$i]['date'] = date("M d, Y H:i:s");
            $data_backup[$i]['data'] = $change_log;
            $get_save = $data_backup;
        }
        else
        {
            $data_backup[$i]['filename'] = $file_name;
            $data_backup[$i]['date'] = date("M d, Y H:i:s");
            $data_backup[$i]['data'] = $change_log;
            $get_save = $data_backup;
        }

        return $get_save;
    }


    private function sendMail()
    {
        global $upwardsbackup, $FramePressEmail;

        //Disable layout
        $upwardsbackup->status['view.layout.file'] = '';

        $get_UpwardsSave = @json_decode(get_option(UTSAVE));
        $configEmail = $get_UpwardsSave->config_email ? $get_UpwardsSave->config_email : null;
        $data_save = $get_UpwardsSave->data_save ? $get_UpwardsSave->data_save : null;
        $enable_email = isset($configEmail->enable) ? $configEmail->enable : 0;
        if($enable_email == 1)
        {
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

            $form_email = isset($configEmail->from->email) ? $configEmail->from->email : $form_email;
            $subject    = isset($configEmail->subject) ? $configEmail->subject : "UpwardsBackup Notification Changed";
            $to         = isset($configEmail->to) ? $configEmail->to : get_option('admin_email');

            //import and create the built in mail object
            $upwardsbackup->import('mail.php');
            $mail = new $FramePressEmail($upwardsbackup);

            //configure it
            $mail->config(array(
                'from' => $form_email,
                'to' => $to,
                'subject' => $subject,
                'template' => 'superEmail',
            ));


            //set some view vars && send it
            $mail->viewSet('data', $data_save);
            $mail->send();

        }

    }


}


?>
