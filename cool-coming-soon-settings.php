<?php
if (!defined('ABSPATH'))
    exit();

echo wp_nonce_field('_');

?>
<style>
    li {list-style: circle;margin-left: 30px;}#background, code {float: right }input.input-field {width: 95% }#background {width: 62%;margin-right: 5%;}#bg-options {width: 25%;float: left }.form-table th {width: 24% }#ccs_logo_image, #ccs_background_image {max-width: 100px;max-height: 100px;left: 0;display: block;margin: 10px 0;box-shadow: 2px 2px 1px gray;border: 2px solid #fff;}#ccsWrapper .form-table td {padding: 0px 10px;}#or {font-size: 20px;padding: 0 0.7%;}#background_code {float: left;font-weight: normal;}#date {width: 10rem;padding: .25rem .35rem .25rem .5rem;border-right-width: 0;}#time {width: 5.5rem;padding: .25rem .5rem .25rem .50rem;border-left-width: 0;}#dateAndTime {display: inline-flex;align-items: center;background-color: #fff;border: 4px solid #f1f1f1;border-radius: 8px;}#dateAndTime:focus-within {border-color: #dedede;}#dateAndTime input {color: inherit;border: 0;background-color: transparent;}#dateAndTime:focus {border: none;}#dateAndTime span {height: 1rem;margin-right: .25rem;margin-left: .25rem;border-right: 1px solid #ddd;}#ccsWrapper {display: -webkit-flex;display: -ms-flexbox;display: flex;-webkit-flex-wrap: wrap;-ms-flex-wrap: wrap;flex-wrap: wrap;overflow: hidden }#ccsWrapper code {font-size: 10px;}#ccsMainContainer .inside {width: 100%;}#ccsMainContainer {width: 72%;margin-bottom: 0 }#ccsSideContainer {width: 24%;margin-top: 49px;}#ccsSideContainer .postbox:first-child {margin-left: 20px;padding-top: 15px }.ccs-columns {float: left;display: block;margin-top: 5px }#ccsSideContainer .postbox {margin-bottom: 0;float: none }#ccsSideContainer .inside {margin-bottom: 0 }#ccsSideContainer hr {width: 70%;margin: 38px auto }#ccsSideContainer h3 {cursor: default;text-align: center;font-size: 16px }#ccsSideContainer li {list-style: disclosure-closed;margin-left: 25px }#ccsSideContainer li a img {display: inline-block;vertical-align: middle }#ccsDevelopedBy {text-align: center }
</style>

<div class="wrap">

    <h1 align="center">Cool Coming Soon</h1>

    <div id="ccsWrapper">
        <div id="ccsMainContainer" class="ccs-columns">

            <?php

            $cool_coming_soon_data              = get_option('cool_coming_soon_data');

            $cool_coming_soon_display           = get_option('cool_coming_soon_display');
            $ccs_errors                         = array();
            $active_tab                         = isset($_GET['tab']) ? $_GET['tab'] : 'data';

            if (isset($_POST['submit']) && ($active_tab == 'data')) {

                check_admin_referer('ccss_save_data_changes');


                $maintenance_mode               = (!empty($_POST['maintenance_mode']) ? sanitize_text_field(stripslashes($_POST['maintenance_mode'])) : '');
                $bg_options                     = (!empty($_POST['bg_options']) ? sanitize_file_name($_POST['bg_options']) : '');
                $background                     = (!empty($_POST['background'])) ? (filter_var(($_POST['background']), FILTER_VALIDATE_URL) ? $_POST['background'] : $ccs_errors[] = 'Invalid background URL') : '';
                $logo                           = (filter_var(($_POST['logo']), FILTER_VALIDATE_URL) ? $_POST['logo'] : $ccs_errors[] = 'Invalid logo URL');
                $logo_id                        = $_POST['logo_id'];
                $page_title                     = (!empty($_POST['page_title']) ? sanitize_text_field(stripslashes($_POST['page_title'])) : '');
                $heading                        = (!empty($_POST['heading']) ? sanitize_text_field(stripslashes($_POST['heading'])) : '');
                $description                    = (!empty($_POST['description']) ? wp_kses_post($_POST['description']) : '');
                $date                           = ((preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $_POST['date'])) ? $_POST['date'] : $ccs_errors[] = 'Invalid Date');
                $time                           = ((preg_match('/^[0-9]{2}:[0-9]{2}$/', $_POST['time'])) ? $_POST['time'] : $ccs_errors[] = 'Invalid Time');
                $auto_launch                    = $_POST['auto-launch'] ?? 0;

                if (!isset($ccs_data)) {
                    $ccs_data = new stdClass();
                }

                $ccs_data->name                 = 'Cool Coming Soon';
                $ccs_data->maintenance_mode     = $maintenance_mode;
                $ccs_data->bg_options           = $bg_options;
                $ccs_data->background_url       = $background;
                $ccs_data->logo_url             = $logo;
                $ccs_data->logo_id              = $logo_id;
                $ccs_data->page_title           = $page_title;
                $ccs_data->heading              = $heading;
                $ccs_data->description          = $description;
                $ccs_data->date                 = $date;
                $ccs_data->time                 = $time;
                $ccs_data->auto_launch          = $auto_launch;

                if (empty($ccs_errors)) {
                    update_option('cool_coming_soon_data', $ccs_data);
                    echo '<div class="notice notice-success is-dismissible"><p><strong>Changes saved!</strong></p></div>';
                } else {
                    echo '<div class="notice notice-error"><p><strong>Changes are not saved, Validation Error Occured!</strong></p></div>';

                    echo '<ul>';
                    foreach ($ccs_errors as $error_msg) {
                        echo "<li>$error_msg</li>";
                    }
                    echo '</ul>';
                }
                $cool_coming_soon_data          = get_option('cool_coming_soon_data');
            } elseif (isset($_POST['submit']) && ($_GET['tab'] == 'display_options')) {
                check_admin_referer('ccss_save_display_changes');

                $display_background             = (!empty($_POST['display-background']) ? sanitize_text_field(stripslashes($_POST['display-background'])) : '');
                $display_logo                   = (!empty($_POST['display-logo']) ? sanitize_text_field(stripslashes($_POST['display-logo'])) : '');
                $display_heading                = (!empty($_POST['display-heading']) ? sanitize_text_field(stripslashes($_POST['display-heading'])) : '');
                $display_description            = (!empty($_POST['display-description']) ? sanitize_text_field(stripslashes($_POST['display-description'])) : '');
                $display_date                   = (!empty($_POST['display-date']) ? sanitize_text_field($_POST['display-date']) : '');

                if (!isset($ccs_data)) {
                    $ccs_data = new stdClass();
                }

                $ccs_data->display_background   = $display_background;
                $ccs_data->display_logo         = $display_logo;
                $ccs_data->display_title        = $display_heading;
                $ccs_data->display_description  = $display_description;
                $ccs_data->display_date         = $display_date;

                update_option('cool_coming_soon_display', $ccs_data);
                echo '<div class="notice notice-success is-dismissible"><p><strong>Changes saved!</strong></p></div>';
                $cool_coming_soon_display       = get_option('cool_coming_soon_display');
            }



            $date_arr = explode("-", $cool_coming_soon_data->date);
            $time_arr = explode(":", $cool_coming_soon_data->time);

            ?>

            <h2 class="nav-tab-wrapper">
                <a href="?page=cool-coming-soon-settings&tab=data" class="nav-tab <?php echo $active_tab == 'data' ? 'nav-tab-active' : ''; ?>">Update Data</a>
                <a href="?page=cool-coming-soon-settings&tab=display_options" class="nav-tab  <?php echo $active_tab == 'display_options' ? 'nav-tab-active' : ''; ?>">Display Options</a>

            </h2>
            <form id="CCSInfoForm" method="post">
                <div id="WtiLikePostOptions" class="postbox">

                    <div class="inside">

                        <table class="form-table">
                            <tbody>


                                <?php


                                if ($active_tab == 'display_options') {
                                ?>
                                    <tr>
                                        <th scope="row"><label for="display-background">Display Background</label></th>
                                        <td>
                                            <input type="radio" name="display-background" value="Yes" <?php echo ($cool_coming_soon_display->display_background == 'Yes' ? 'checked' : '') ?>>Yes&nbsp;&nbsp;&nbsp;
                                            <input type="radio" name="display-background" value="No" <?php echo ($cool_coming_soon_display->display_background == 'No' ? 'checked' : '') ?>>No
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="display-logo">Display Logo</label></th>
                                        <td>
                                            <input type="radio" name="display-logo" value="Yes" <?php echo ($cool_coming_soon_display->display_logo == 'Yes' ? 'checked' : '') ?>>Yes&nbsp;&nbsp;&nbsp;
                                            <input type="radio" name="display-logo" value="No" <?php echo ($cool_coming_soon_display->display_logo == 'No' ? 'checked' : '') ?>>No
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="display-heading">Display Heading</label></th>
                                        <td>
                                            <input type="radio" name="display-heading" value="Yes" <?php echo ($cool_coming_soon_display->display_title == 'Yes' ? 'checked' : '') ?>>Yes&nbsp;&nbsp;&nbsp;
                                            <input type="radio" name="display-heading" value="No" <?php echo ($cool_coming_soon_display->display_title == 'No' ? 'checked' : '') ?>>No
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="display-description">Display Description</label></th>
                                        <td>
                                            <input type="radio" name="display-description" value="Yes" <?php echo ($cool_coming_soon_display->display_description == 'Yes' ? 'checked' : '') ?>>Yes&nbsp;&nbsp;&nbsp;
                                            <input type="radio" name="display-description" value="No" <?php echo ($cool_coming_soon_display->display_description == 'No' ? 'checked' : '') ?>>No
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="display-date">Display Launch Date</label></th>
                                        <td>
                                            <input type="radio" name="display-date" value="Yes" <?php echo ($cool_coming_soon_display->display_date == 'Yes' ? 'checked' : '') ?>>Yes&nbsp;&nbsp;&nbsp;
                                            <input type="radio" name="display-date" value="No" <?php echo ($cool_coming_soon_display->display_date == 'No' ? 'checked' : '') ?>>No
                                            <?php wp_nonce_field('ccss_save_display_changes'); ?>
                                        </td>
                                    </tr>

                                <?php
                                } else {
                                ?>
                                    <tr>
                                        <th scope="row"><label for="maintenance_mode">Maintenance Mode</label></th>
                                        <td>
                                            <input type="radio" required name="maintenance_mode" value="1" <?php echo ($cool_coming_soon_data->maintenance_mode == 1 ? 'checked' : '') ?>>On&nbsp;&nbsp;&nbsp;
                                            <input type="radio" name="maintenance_mode" value="0" <?php echo ($cool_coming_soon_data->maintenance_mode == 0 ? 'checked' : '') ?>>Off
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="logo">Background Image<code>1920 x 1080</code></label>
                                            <code id="background_code">NOTE: If you want to use pre-bundled background then custom background field must should be empty! <a href="?page=cool-coming-soon-settings&tab=display_options">Click Here</a> to disable other options and display only background.</code>

                                        </th>
                                        <td>
                                            <select name="bg_options" id="bg-options">
                                                <option value="bg.jpg" <?php echo ($cool_coming_soon_data->bg_options == 'bg.jpg' ? 'selected' : '') ?>>Sunrise</option>
                                                <option value="bg2.jpg" <?php echo ($cool_coming_soon_data->bg_options == 'bg2.jpg' ? 'selected' : '') ?>>Mountain</option>
                                                <option value="bg3.jpg" <?php echo ($cool_coming_soon_data->bg_options == 'bg3.jpg' ? 'selected' : '') ?>>City</option>
                                                <option value="bg4.jpg" <?php echo ($cool_coming_soon_data->bg_options == 'bg4.jpg' ? 'selected' : '') ?>>Stars & Moon</option>
                                                <option value="bg5.jpg" <?php echo ($cool_coming_soon_data->bg_options == 'bg5.jpg' ? 'selected' : '') ?>>Sky</option>
                                                <option value="bg6.jpg" <?php echo ($cool_coming_soon_data->bg_options == 'bg6.jpg' ? 'selected' : '') ?>>eCommerce</option>
                                                <option value="bg7.jpg" <?php echo ($cool_coming_soon_data->bg_options == 'bg7.jpg' ? 'selected' : '') ?>>Dark Maintenance</option>
                                                <option value="bg8.jpg" <?php echo ($cool_coming_soon_data->bg_options == 'bg8.jpg' ? 'selected' : '') ?>>Under-Construction</option>
                                                <option value="bg9.jpg" <?php echo ($cool_coming_soon_data->bg_options == 'bg9.jpg' ? 'selected' : '') ?>>Heart Maintenance</option>
                                                <option value="bg10.jpg" <?php echo ($cool_coming_soon_data->bg_options == 'bg10.jpg' ? 'selected' : '') ?>>URL Maintenance</option>
                                                <option value="bg11.jpg" <?php echo ($cool_coming_soon_data->bg_options == 'bg11.jpg' ? 'selected' : '') ?>>WWW Maintenance</option>
                                            </select>
                                            <strong id="or">OR</strong>
                                            <input type="url" name="background" id="background" title="Custom Background Image has Higher Priority, if you wanted to use pre-bundled background then leave this field empty!" value="<?php echo $cool_coming_soon_data->background_url; ?>" placeholder="To use own background, paste background image url here" />
                                            <img id="ccs_background_image" src="<?php echo (empty($cool_coming_soon_data->background_url) ? plugins_url('inc/assets/img/', __FILE__) . $cool_coming_soon_data->bg_options : $cool_coming_soon_data->background_url); ?>" />
                                            <input type="hidden" id="background_url" value="<?php echo plugins_url('inc/assets/img/', __FILE__); ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="logo">Logo<code>250 x 250</code></label></th>
                                        <td>
                                            <input type="url" name="logo" required class="input-field" id="logo" value="<?php echo esc_url($cool_coming_soon_data->logo_url) ?>" placeholder="Paste Logo URL" />

                                            <a href="#" class="ccs_logo_upload_button">
                                                <img id="ccs_logo_image" src="<?php echo esc_url($cool_coming_soon_data->logo_url); ?>" style="display:<?php echo $cool_coming_soon_data->logo_id == -1 || intval($cool_coming_soon_data->logo_id) ? 'block' : 'none'; ?>;" />

                                            </a>
                                            <a href="#" class="ccs_change_logo_button" style="display: <?php echo ($cool_coming_soon_data->logo_id == -1 || intval($cool_coming_soon_data->logo_id)) ? 'inline-block' : 'none'; ?>">Change Logo</a>&nbsp;&nbsp;&nbsp;
                                            <a href="#" class="ccs_remove_logo_button" style="display:<?php echo ($cool_coming_soon_data->logo_id == -1 || intval($cool_coming_soon_data->logo_id)) ? 'inline-block' : 'none'; ?>">Remove Logo</a>

                                            <input type="hidden" id="logo_id" name="logo_id" value="<?php echo ($cool_coming_soon_data->logo_id) ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="page_title">Page Title<code>upto 55 characters</code></label></th>
                                        <td>
                                            <input type="text" name="page_title" required class="input-field" id="page_title" title="This will appear in browser tab." value="<?php echo esc_attr($cool_coming_soon_data->page_title) ?>" placeholder="Heading or Coming soon title" maxlength="55" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="heading">Heading<code>upto 3 words</code></label></th>
                                        <td>
                                            <input type="text" name="heading" required class="input-field" id="heading" value="<?php echo esc_attr($cool_coming_soon_data->heading) ?>" placeholder="Heading or Coming soon title" maxlength="20" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="logo">Description</label></th>
                                        <td style="padding-right: 40px;">
                                            <?php
                                            $content = $cool_coming_soon_data->description;
                                            $settings = array(
                                                'wpautop'       => true,
                                                'media_buttons' => false,
                                                'textarea_name' => 'description',
                                                'textarea_rows' => 5,
                                                'teeny'         => true
                                            );

                                            wp_editor($content, 'description', $settings);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="date">Launch Date</label></th>
                                        <td>
                                            <div id="dateAndTime">
                                                <input type="date" id="date" name="date" class="input-field" min="<?php echo date('Y') . "-" . date('m') . "-" . date('d'); ?>" value="<?php echo $date_arr[0] . "-" . $date_arr[1] . "-" . $date_arr[2] ?>" />
                                                <span></span>
                                                <input type="time" id="time" name="time" value="<?php echo $time_arr[0] . ":" . $time_arr[1]; ?>">
                                            </div>

                                            <?php wp_nonce_field('ccss_save_data_changes'); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="autoLaunch">Auto Launch</label></th>
                                        <td>
                                            <div id="autoLaunch">
                                                <input type="radio" id="auto-launch" name="auto-launch" value="1" <?php echo ($cool_coming_soon_data->auto_launch == 1 ? 'checked' : '') ?> />
                                                <span>Yes</span>
                                                <input type="radio" id="auto-launch" name="auto-launch" value="0" <?php echo ($cool_coming_soon_data->auto_launch == 0 ? 'checked' : '') ?> />
                                                <span>No</span>
                                            </div>

                                            <?php wp_nonce_field('ccss_save_data_changes'); ?>
                                        </td>
                                    </tr>

                                <?php

                                }

                                ?>

                            </tbody>
                        </table>

                    </div>

                </div>
                <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
            </form>
        </div>

        <div id="ccsSideContainer" class="ccs-columns">
            <div class="postbox">
                <h3>Want to Support?</h3>
                <div class="inside">
                    <p>If you enjoyed the plugin, and want to support:</p>
                    <ul>
                        <li>
                            <a href="https://AtlasGondal.com/contact-me/?utm_source=self&utm_medium=wp&utm_campaign=cool-coming-soon&utm_term=hire-me" target="_blank">Hire me</a> on a project
                        </li>
                        <li>Buy me a Coffee
                            <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YWT3BFURG6SGS&source=url" target="_blank"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" /> </a>

                        </li>
                    </ul>
                    <hr>
                    <h3>Wanna say Thanks?</h3>
                    <ul>
                        <li>Leave <a href="https://wordpress.org/support/plugin/cool-coming-soon/reviews/?filter=5#new-post" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating
                        </li>
                        <li>Tweet me: <a href="https://twitter.com/atlas_gondal" target="_blank">@Atlas_Gondal</a>
                        </li>
                    </ul>
                    <hr>
                    <h3>Got a Problem?</h3>
                    <p>If you want to report a bug or suggest new feature. You can:</p>
                    <ul>
                        <li>Create <a href="https://wordpress.org/support/plugin/cool-coming-soon/" target="_blank">Support
                                Ticket</a></li>

                        <li>Write me an <a href="https://AtlasGondal.com/contact-me/?utm_source=self&utm_medium=wp&utm_campaign=cool-coming-soon&utm_term=write-an-email" target="_blank">Email</a></li>
                    </ul>
                    <strong>Reporting</strong> an issue is way better than leaving a <strong>negative</strong> feedback, which does not help, neither you, nor me, nor the community. So, please consider giving me a chance to help, before leaving your feedback.
                    <hr>
                    <h4 id="ccsDevelopedBy">Developed by: <a href="https://AtlasGondal.com/?utm_source=self&utm_medium=wp&utm_campaign=cool-coming-soon&utm_term=developed-by" target="_blank">Atlas Gondal</a></h4>
                </div>
            </div>
        </div>
    </div>
</div>