<?php

class UpwardsprefixBackup
{

	/*
	 * Handler for  FramePress menu link
	 *
	 * Cause this is a page, this function will render
	 * its view automatically before the function finish
	*/
	public function setting ()
	{
        global $upwards;

        $results = @json_decode(get_option(UTSAVE));
        $path = self::getDefaultPath();

        $upwards->viewSet('list_files', $results);
        $upwards->viewSet('path', $path);

	}


    /**
     * Backup all data
     */
    public function backupAllData()
    {
        global $upwards;

        $path = self::getDefaultPath();
        if (!mkdir($path, 0, true));

        $file_name = UTNAME.date(" M d, Y H-i-s").'.zip';

        $get_save = @json_decode(get_option(UTSAVE));

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

        self::saveData(UTSAVE, json_encode($get_save));

        $phar = new PharData($path.$file_name);
        $phar->buildFromDirectory(ABSPATH);

        $result = self::showContent(ABSPATH);
        self::saveData(UTSET, json_encode($result), ' ', 'no');

        $upwards->redirect(array('function'=>'setting'));

    }

    /**
     * checking change file and tar it
     */
    public function checkingChangeFile()
    {
        global $upwards;
        $new_data = self::showContent(ABSPATH);
        $last_update = @json_decode(get_option(UTSET));

        $result = self::checkingChangeArray($last_update, $new_data);
        self::backupData($result);

        self::saveData(UTSET, json_encode($new_data));

        $upwards->redirect(array('function'=>'setting'));

    }

    public function deleteBackup()
    {
        global $upwards;

        $path = self::getDefaultPath();
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

        self::saveData(UTSAVE, json_encode($temp));

        $upwards->redirect(array('function'=>'setting'));

    }

    /**
     * Backup all change data
     */
    private function backupData($new_file = array())
    {
        global $upwards;

        if(!empty($new_file))
        {
            $path = self::getDefaultPath();
            if (!mkdir($path, 0, true));

            $file_name = UTNAME.date(" M d, Y H-i-s").'.zip';

            $get_save = @json_decode(get_option(UTSAVE));

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
            self::saveData(UTSAVE, json_encode($get_save));

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


    /**
     * get default Path to folder backup
     *
     * get default path to folder backup,
     * if path backup not found set a new backup
     *
     * @return string|void
     */
    private function getDefaultPath()
    {
        $result = get_option(UTDPATH);
        if($result)
            $detaulPath = $result;
        else
            $detaulPath = self::setNewDefaultPath();

        return $detaulPath;
    }


    /**
     * Set default Path to folder backup
     *
     * create a new backup folder
     *
     * @return string|void
     */
    private function setNewDefaultPath()
    {
        $randChar = substr(md5(time()), 0, 10);
        $defaultPath = ABSPATH.'wp-content/backupUpwardsTech-'.$randChar.'/';
        self::saveData(UTDPATH, $defaultPath, '', 'no');

        return $defaultPath;
    }


    /**
     * Save data to wordpress options
     *
     * if data already exists, it will override the value
     *
     * @param string $option_name
     * @param mixed $value
     * @param string $deprecated
     * @param string $autoLoad
     */
    private function saveData($option_name, $value, $deprecated = ' ', $autoLoad = 'no')
    {
        if ( get_option( $option_name ) != false ) {
            update_option( $option_name, $value );
        } else {
            add_option( $option_name, $value, $deprecated, $autoLoad );
        }
    }


    /**
     * collect all file in wordpress
     *
     * collect all file in wordpress from the root until end of file
     *
     * @param $path
     * @param null $root
     * @return array
     */
    private function showContent($path, $root = null){

        if($root == null)
            $root = $path;

        $result = array();
        $i=0;

        if ($handle = opendir($path))
        {

            while (false !== ($file = readdir($handle)))
            {
                if ($file != "." && $file != "..")
                {
                    $path_file = $path . DS . $file;
                    $parent = str_replace(array($root, DS.$file), '', $path_file);

                    $result[$i]['name'] = $file;
                    $result[$i]['type'] = is_file($path_file) ? "F" : "D";
                    $result[$i]['path'] = $path_file;
                    $result[$i]['modified'] = date ('d-m-Y H:i:s', filemtime($path_file));
                    $result[$i]['byte'] = filesize($path_file);
                    $result[$i]['parent'] = $parent;
                    $result[$i]['child'] = (is_file($path_file) == false) ? self::showContent($path_file, $root) : null;

                    $i++;
                }
            }

            closedir($handle);
        }

        return $result;

    }


	/**
	 * Handler for the action defined in FramePress
	 * main file (main.php)
	 *
	 * Cause this is an action, this function will render
	 * its view only if requested
	*/
	public function filterChangeFile ()
	{
		global $upwards;

	}


	/**
	 * Handler for the action defined in FramePress
	 * main file (main.php)
	 *
	 * Cause this is an action, this function will render
	 * its view only if requested
	*/
	public function actionA ()
	{
		global $test;

		//do some magic
		//like check something
		//or save something in options

		//now you can print some json if this is and ajax handler
		echo '{"msg"=>"done!"}';
		exit;


		//but if this is a heavy json or a XML, you may prefer to have a view for it

		$data = array('super heavy info...');

		$test->viewSet('data', $data);

		//first argument is the file with the view,
			//null means default (views/test/actionA.php)
		//second argument is print. if print is false
			//the renderer view will be returned as string
		$view = $test-drawView(null, false);
		echo $view;
		exit;

		//or you can simply
		$test-drawView(); exit;
	}


	/**
	 * Handler for the shortcode defined in FramePress
	 * main file (main.php)
	 *
	 * Cause this is an shortcode, this function must return
	 * a string as replacement of the shortcode tag
	*/
	public function shortA ()
	{
		global $test;

		//do some magic
		//like get some value of the database
		//and modify it a little


		//now you can return the string
		return '<a href="#jojo">go to jojo</a>';

		//or you can draw a whole view and returit
		//and rememeber that you can pass info to the view
		$test->viewSet('someval', 'jojo');
		return $test->drawView(null, false);
	}

}


?>
