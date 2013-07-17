<!DOCTYPE html>
<html>
<head></head>
<body>

<h1>Hello Admin,</h1>
<p>Some changes has been made into your site</p>
<p>

    <?php if(!empty($data)): ?>
        <?php $lastData = '<tr><td>No backup</td></tr>'; ?>
        <?php foreach($data as $list): ?>

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

            $lastData = "<tr>
                <td>$filename</td>
                <td>$date</td>
                <td>$type</td>
                <td>$data</td>
            </tr>";

            ?>

        <?php endforeach; ?>

    <?php endif; ?>


<table>
    <?php echo $lastData; ?>
</table>

</p>

</body>
</html>
