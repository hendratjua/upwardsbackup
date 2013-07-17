<?php
$link_backup = $this->router(array('controller'=>'main', 'function'=>'home'));
$link_backup_manual = $this->router(array('controller'=>'main', 'function'=>'checkingChangeFileAu'));
$link_email = $this->router(array('controller'=>'main', 'function'=>'emailSetting'));
?>
<?php echo $this->css("framepress.default.css"); ?>
<?php echo $this->css("style.css"); ?>
<div class="wrap">
    <div id="icon-generic" class="icon32"><br/></div>
    <h2>Upwards Technologies Backup</h2><br/>

    <ul class="subsubsub">
        <li>
            <a href="<?php echo $link_backup; ?>">List Backup</a>
            |
        </li>
        <li>
            <a href="<?php echo $link_backup_manual; ?>">Run Backup Manually</a>
            |
        </li>
        <li>
            <a href="<?php echo $link_email; ?>">Setting Email</a>
        </li>
    </ul>
    <div class="clear"></div>

    <?php
    if(isset($_REQUEST['msg']) OR $msg):
        if($msg == null)
            $msg = $_REQUEST['msg'];
    ?>
        <div class="updated" id="message"><p><?php echo $msg; ?></p></div>
    <?php endif; ?>

    <?php echo $content_for_layout;?>

</div>
