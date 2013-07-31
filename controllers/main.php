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


    public function cdnSetting()
    {
        global $upwardsbackup;

        if($_POST)
        {

            $configCdnTemp = array();
            $configCdnTemp['enable'] = isset($_POST['enable']) ? mysql_real_escape_string($_POST['enable']) : 0;
            $configCdnTemp['access_key_id'] = $_POST['access_key_id'] != null ? mysql_real_escape_string($_POST['access_key_id']) : null;
            $configCdnTemp['secret_access_key'] = $_POST['secret_access_key'] != null ? mysql_real_escape_string($_POST['secret_access_key']) : null;

            $get_UpwardsSave = @json_decode(get_option(UTSAVE));
            $get_UpwardsSave->config_cdn = $configCdnTemp;
            update_option( UTSAVE, json_encode($get_UpwardsSave) );

            $upwardsbackup->viewSet('msg', "Successful update CDN Setting");

        }

        $get_UpwardsSave = @json_decode(get_option(UTSAVE));
        $config_cdn = isset($get_UpwardsSave->config_cdn) ? $get_UpwardsSave->config_cdn : null;

        $upwardsbackup->viewSet('config_cdn', $config_cdn);


        $upwardsbackup->import('S3.php');
        if($config_cdn->access_key_id != null AND $config_cdn->secret_access_key != null)
        {
            $s3 = new S3($config_cdn->access_key_id, $config_cdn->secret_access_key);
            if (!($buckets = $s3->listBuckets())) {
                $upwardsbackup->viewSet('error', "Access to cdn is failed.");
            }
        }

    }


    public function manualBackupToCDN()
    {
        global $upwardsbackup;

        $params = '&error=There no file has been found';

        if($_GET['filename'])
        {
            $filename = $_GET['filename'];
            $result = self::backupCdn($filename);
            if($result)
                $params = '&msg=Success send to CDN';
        }

        $upwardsbackup->redirect(array('function'=>'home', 'params' => $params));
    }


    public function manualBackupAllDataToCDN()
    {
        global $upwardsbackup;

        $get_UpwardsSave = @json_decode(get_option(UTSAVE));
        $config_cdn = $get_UpwardsSave->config_cdn;

        if($config_cdn->enable == 1)
        {
            self::backupAllDataToCdn();
            $params = '&msg=Success send all file to CDN';
        }
        else
        {
            $params = '&error=CDN setting is not enable';
        }

        $upwardsbackup->redirect(array('function'=>'home', 'params' => $params));
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

        $config_cdn = $get_UpwardsSave->config_cdn;

        if($config_cdn->enable == 1)
        {
            self::backupAllDataToCdn();
        }

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
        $result = self::checkChangeFile();
        if($result)
        {
            self::sendMail();
        }

        return true;

    }




    private function backupCdn($filename)
    {
        global $upwardsbackup;

        $bucketSource = 'utclient-backup';
        $upwardsbackup->import('S3.php');

        $url = get_option('siteurl');
        $list_array = array(
            'http://www.',
            'https://www.',
            'http://',
            'https://',
            'www.'
        );
        $url = str_replace($list_array, '', $url);

        $get_UpwardsSave = @json_decode(get_option(UTSAVE));
        $config_cdn = $get_UpwardsSave->config_cdn;

        if(substr($url, -1) == '/') {
            $url = substr($url, 0, -1);
        }
        $location = 'plugin/'.$url.'/'.$filename;
        $location = str_replace(' ', '-', $location);
        $path = get_option(UTDPATH);
        $file = $path.$filename;

        if (!file_exists($file) || !is_file($file) || !is_readable($file)) {
            return false;
        }
        else
        {
            $s3 = new S3($config_cdn->access_key_id, $config_cdn->secret_access_key);
            $result = $s3->putObjectFile($file, $bucketSource, $location);
            var_dump($result);
            die();
            return $result;
        }

    }


    private function backupAllDataToCdn()
    {
        global $upwardsbackup;

        $bucketSource = 'utclient-backup';
        $upwardsbackup->import('S3.php');

        $url = get_option('siteurl');
        $list_array = array(
            'http://www.',
            'https://www.',
            'http://',
            'https://',
            'www.'
        );
        $url = str_replace($list_array, '', $url);

        $get_UpwardsSave = @json_decode(get_option(UTSAVE));
        $data_save = $get_UpwardsSave->data_save;
        $config_cdn = $get_UpwardsSave->config_cdn;

        $s3 = new S3($config_cdn->access_key_id, $config_cdn->secret_access_key);

        if(substr($url, -1) == '/') {
            $url = substr($url, 0, -1);
        }
        $location = 'plugin/'.$url.'/';
        $location = str_replace(' ', '-', $location);
        $path = get_option(UTDPATH);

        foreach($data_save as $data)
        {
            $file = $path.$data->filename;
            if (file_exists($file) AND is_file($file) AND is_readable($file))
            {
                $s3->putObjectFile($file, $bucketSource, $location);
            }
        }

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
        global $upwardsbackup;

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

            $phar = new PharData($path.$file_name);
            foreach($new_files as $file)
            {
                $file_location = $file['parent'].DS.$file['name'];
                $phar->addFile($file['path'], $file_location);
            }

            $config_cdn = $get_UpwardsSave->config_cdn;
            if($config_cdn->enable == 1)
            {
                self::backupCdn($file_name);
            }


            update_option( UTSAVE, json_encode($get_UpwardsSave) );

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
            $form_email = isset($configEmail->from->email) ? $configEmail->from->email : "upwardsbackup@wordpress.org";
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
