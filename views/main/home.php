<?php
$download_link = get_option('siteurl').substr($path, -41);
$link1 = $this->router(array('controller'=>'main', 'function'=>'backupAllData'));
$link2 = $this->router(array('controller'=>'main', 'function'=>'checkingChangeFile'));
$link3 = $this->router(array('controller'=>'main', 'function'=>'deleteBackup'));
?>
<div class="wrap">
    <div class="icon32" id="icon-generic">
        <br/>
    </div>
    <h2>Upwards Technologies Backup</h2>

    <div>
        <ul class="subsubsub">
            <li>
                <a href="<?php echo $link1; ?>">Back up All data</a>
                |
            </li>
            <li>
                <a href="<?php echo $link2; ?>">Check Change File</a>
            </li>
        </ul>

        <table class="widefat">
            <thead>
            <tr>
                <th>Name</th>
                <th>Date</th>
                <th>Size</th>
                <th>Details</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>

            <?php if(!empty($list_files)): ?>
                <?php foreach($list_files as $list): ?>

                    <?php
                    $filename = $list->filename;
                    $download = $download_link.rawurlencode($filename);

                    $type = filesize($path.$filename);
                    $date = $list->date;

                    if($type / 1073741824 >= 1)
                    {
                        $type = $type / 1073741824;
                        $type = number_format($type, 2) . ' TB';
                    }
                    elseif($type / 1048576 >= 1)
                    {
                        $type = $type / 1048576;
                        $type = number_format($type, 2) . ' MB';
                    }
                    elseif($type / 1024 >= 1)
                    {
                        $type = $type / 1024;
                        $type = number_format($type, 2) . ' KB';
                    }
                    else
                    {
                        $type = number_format($type, 2) . ' B';
                    }

                    $data = $list->data;
                    if( (is_object($data) || is_array($data)) AND !empty($data) )
                    {
                        $temp = '';
                        $first = true;
                        foreach($data as $file)
                        {
                            if($first)
                            {
                                $first = false;
                                $temp .= 'ROOT'.$file->parent.DS.$file->name;
                            }
                            else
                            {
                                $temp .= '<br/>ROOT'.$file->parent.DS.$file->name;
                            }

                        }
                        $data = $temp;
                    }
                    else
                    {
                        if(strlen($data) <= 0)
                            $data = 'Cannot found any detail';
                    }

                    ?>

                <tr>
                    <td><?php echo $filename; ?></td>
                    <td><?php echo $date; ?></td>
                    <td><?php echo $type; ?></td>
                    <td><?php echo $data; ?></td>
                    <td>
                        <a href="<?php echo $download; ?>">Download</a>
                        <a href="<?php echo $link3.'&name='.$filename; ?>">Deleted</a>
                    </td>
                </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="5">No backup</td>
                </tr>

            <?php endif; ?>

            </tbody>
        </table>

    </div>
</div>
