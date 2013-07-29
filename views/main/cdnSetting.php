<?php
$checked = $config_cdn->enable == 1 ? " Checked" : "";
$access_key_id = $config_cdn->access_key_id;
$secret_access_key = $config_cdn->secret_access_key;
?>
<form action="" method="post">
    <table class="wpcf-types-form-table widefat" id="setting-Email">
        <thead>
        <tr>
            <th colspan="2">CDN Setting</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <label for="enable">
                    Send backup to CDN:
                </label>
            </td>
            <td>
                <input type="checkbox" value="1" name="enable" id="enable"<?php echo $checked?>>
                <label for="enable"> Enable </label>
            </td>
        </tr>
        <tr>
            <td>
                <label for="to">
                    Access Key ID:
                </label>
            </td>
            <td>
                <input type="text" class="full-width" value="<?php echo $access_key_id; ?>" name="access_key_id" id="access_key_id">
            </td>
        </tr>
        <tr>
            <td>
                <label for="form_email">
                    Secret Access Key:
                </label>
            </td>
            <td>
                <input type="text" class="full-width" value="<?php echo $secret_access_key; ?>" name="secret_access_key" id="secret_access_key">
            </td>
        </tr>
        </tbody>
    </table>
    <p class="submit">
        <input type="submit" value="Save Changes" class="button button-primary" id="submit" name="submit">
    </p>
</form>