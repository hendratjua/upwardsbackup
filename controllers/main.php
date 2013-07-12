<?php

class UpwardsbackupsMain
{
    private $path;

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

        $results = @json_decode(get_option(UTSAVE));
        $path = get_option(UTDPATH);

        $upwardsbackup->viewSet('list_files', $results);
        $upwardsbackup->viewSet('path', $path);

	}


    /**
     * Backup all data
     */
    public function backupAllData()
    {
        global $upwardsbackup;

        $file_name = UTNAME.date(" M d, Y H-i-s").'.zip';

        $get_save = @json_decode(get_option(UTSAVE));
        $path = get_option(UTDPATH);

        $upwardsbackup->import('HelperUpwardsBackup.php');
        $helperUpwardsBackup = new HelperUpwardsBackup();
        $getAllInformationFile = $helperUpwardsBackup->getFileContent(ROOTPATH, null);

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
            $data_backup[$i]['data'] = "All Data";
            $get_save = $data_backup;
        }
        else
        {
            $data_backup[$i]['filename'] = $file_name;
            $data_backup[$i]['date'] = date("M d, Y H:i:s");
            $data_backup[$i]['data'] = "All Data";
            $get_save = $data_backup;
        }

        update_option( UTSAVE, json_encode($get_save) );

        $phar = new PharData($path.$file_name);
        $phar->buildFromDirectory(ROOTPATH);

        update_option( UTSAVE, json_encode($get_save) );
        update_option( UTSET, json_encode($getAllInformationFile) );

        $upwardsbackup->redirect(array('function'=>'home'));

    }



    /**
     * checking change file and tar it
     */
    public function checkingChangeFile()
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

        $upwardsbackup->redirect(array('function'=>'home'));

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
            '.idea',
            'backupUpwardsTech-7bea3e4ce2'
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



    public function deleteBackup()
    {
        global $upwardsbackup;

        $path = get_option(UTDPATH);

        $results = @json_decode(get_option(UTSAVE));
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

        update_option( UTSAVE, json_encode($temp) );

        $upwardsbackup->redirect(array('function'=>'home'));

    }

    /**
     * Backup all change data
     */
    private function backupData($new_file = array())
    {
        if(!empty($new_file))
        {

            $file_name = UTNAME.date(" M d, Y H-i-s").'.zip';

            $get_save = @json_decode(get_option(UTSAVE));
            $path = get_option(UTDPATH);

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
                $data_backup[$i]['data'] = $new_file;
                $get_save = $data_backup;
            }
            else
            {
                $data_backup[$i]['filename'] = $file_name;
                $data_backup[$i]['date'] = date("M d, Y H:i:s");
                $data_backup[$i]['data'] = $new_file;
                $get_save = $data_backup;
            }

            update_option( UTSAVE, json_encode($get_save) );

            $phar = new PharData($path.$file_name);
            foreach($new_file as $file)
            {
                $file_location = $file['parent'].DS.$file['name'];
                $phar->addFile($file['path'], $file_location);
            }

            return 1;
        }

        return 0;

    }


}


?>
