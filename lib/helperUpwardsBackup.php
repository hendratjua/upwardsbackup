<?php
/**
 * SettingPath
 * User: Hendra
 * Date: 7/12/13
 * Time: 10:18 AM
 * To change this template use File | Settings | File Templates.
 */

class HelperUpwardsBackup
{
    /**
     * collect all file in wordpress
     *
     * collect all file in wordpress from the root until end of file
     *
     * @param $path
     * @param null $root
     * @return array
     */
    public function getFileContent($path, $root = null){

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
                    $result[$i]['child'] = (is_file($path_file) == false) ? self::getFileContent($path_file, $root) : null;

                    $i++;
                }
            }

            closedir($handle);
        }

        return $result;

    }

}