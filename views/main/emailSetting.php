<?php
$checked = $config_email->enable == 1 ? " Checked" : "";
$to = $config_email->to;
$subject = $config_email->subject;
$cc = $config_email->cc;
$form_email = $config_email->from->email;
$form_name = $config_email->from->name;
?>
<form action="" method="post">
    <table class="wpcf-types-form-table widefat" id="setting-Email">
        <thead>
        <tr>
            <th colspan="2">Setting Email</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <label for="enable">
                    Enable Email
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
                    To
                </label>
            </td>
            <td>
                <input type="email" class="full-width" value="<?php echo $to; ?>" name="to" id="to">
            </td>
        </tr>
        <tr>
            <td>
                <label for="form_email">
                    Email Sender
                </label>
            </td>
            <td>
                <input type="email" class="full-width" value="<?php echo $form_email; ?>" name="form_email" id="form_email">
            </td>
        </tr>
        <tr>
            <td>
                <label for="subject">
                    Subject
                </label>
            </td>
            <td>
                <input type="text" class="full-width" value="<?php echo $subject; ?>" name="subject" id="subject">
            </td>
        </tr>
        </tbody>
    </table>
    <p class="submit">
        <input type="submit" value="Save Changes" class="button button-primary" id="submit" name="submit">
    </p>
</form>