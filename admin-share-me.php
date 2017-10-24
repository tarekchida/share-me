<?php
$directories = array();
$theme_list = array();

$directories = glob(SM_THEMES_PATH . '*', GLOB_ONLYDIR);
foreach ($directories as $directory) {
    $directoryName = explode('/', $directory);
    $theme_list[] = end($directoryName);
}

if (isset($_POST['theme'])) {

    global $wpdb;
    $wpdb->update(SM_TABLE_PREFIX . "social_list", array('status' => 0),array('status' => 1));
 
    foreach ($_POST['status'] as $theme) {
        $wpdb->update(SM_TABLE_PREFIX . "social_list", array('status' => 1), array('name' => $theme));
    }
 
    $wpdb->update(SM_TABLE_PREFIX . "config", array('theme' => $_POST['theme'], 'v_pos' => $_POST['v_pos'], 'h_pos' => $_POST['h_pos'], 'size' => $_POST['size']),array('id' => 1));

    add_action('save_post', 'notify');
}
?>
<div class="wrap">  
    <?php echo "<img class='sm-plugin-logo' src='" . plugins_url('/assets/css/images/logo_big.png', __FILE__) . "'>"; ?>  
    <?php echo "<h2> SM " . __('Dashboard') . "</h2>"; ?>  
    <?php echo "<h3>" . __('Social network list') . "</h3>"; ?>  

    <form method="post" action="" id="social_list">
        <table class="widefat page fixed" cellspacing="0"  >

            <tbody>
                <tr>
                    <td>
                        <strong>Social network name </strong>
                    </td>
                    <td>
                        <strong>Status</strong>
                    </td>
                </tr>
                <?php
                global $wpdb;
                $socials = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . SM_TABLE_PREFIX . "social_list WHERE %d", 1));
                if (count($socials) > 0) {
                    foreach ($socials as $social) {
                        $checked = ($social->status == 1) ? 'checked' : '';
                        echo "<tr>
        <td>" . ucfirst($social->name) . "</td><td> <input " . $checked . " type='checkbox' name='status[]' value=" . $social->name . " ></td>
	</tr>";
                    }
                }
                ?>

            </tbody>
        </table> 


        <?php echo "<h3>" . __('Panel position (Front) ') . "</h2>"; ?>  
        <?php
        global $wpdb;
        $sm_theme = null;
        $sm_h_pos = null;
        $sm_v_pos = null;

        $data = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . SM_TABLE_PREFIX . "config WHERE %d", 1));
        foreach ($data as $item) {

            $sm_theme = $item->theme;
            $sm_h_pos = $item->h_pos;
            $sm_v_pos = $item->v_pos;
            $sm_size = $item->size;
        }
        ?>
        <table class="widefat page fixed" cellspacing="0"  >

            <tbody>
                <tr>
                    <td>
                        <strong>Horizontal position :</strong>  <input  <?php echo ($sm_h_pos == "left") ? 'checked' : '' ?> type="radio" name="h_pos" value="left"> Left 
                        <input  <?php echo ($sm_h_pos == "right") ? 'checked' : '' ?>  type="radio" name="h_pos" value="right"> Right
                    </td>
                    <td>
                        <strong>Vertical position : </strong><input   <?php echo ($sm_v_pos == "up") ? 'checked' : '' ?> type="radio" name="v_pos" value="up"> Before post 
                        <input <?php echo ($sm_v_pos == "down") ? 'checked' : '' ?> type="radio" name="v_pos" value="down"> After post 
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Icons size :</strong>  <input  <?php echo ($sm_size == "16") ? 'checked' : '' ?>  type="radio" name="size" value="16"> 16 px  <input  <?php echo ($sm_size == "20") ? 'checked' : '' ?>  type="radio" name="size" value="20"> 20 px
                        <input  <?php echo ($sm_size == "32") ? 'checked' : '' ?> type="radio" name="size" value="32"> 32 px
                    </td>
                    <td>

                    </td>
                </tr>
            </tbody>
        </table> 

        <?php echo "<h3>" . __('Theme collection ') . "</h3>"; ?>       

        <table class="widefat page fixed" cellspacing="0"  >

            <tbody>
                <?php foreach ($theme_list as $theme): ?>
                    <tr>
                        <td valign="middle"  >
                            <?php echo ucfirst($theme) ?> </td><td> <input   <?php echo ($sm_theme == $theme) ? 'checked' : '' ?>  type="radio" name="theme" value="<?php echo $theme ?>">    </td>
                        <td>
                            <?php foreach ($socials as $social): ?>
                                <img src=  "<?php echo SM_URL ?>/assets/images/<?php echo $theme ?>/<?php echo $social->name ?>.png"  height="32 px"/>
                            <?php endforeach; ?>
                        </td>
                        <td></td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table> 

        <p class="submit">
            <input id="submit" class="button button-primary" type="submit" value="Save changes" name="submit">
        </p>  

    </form>
</div>
