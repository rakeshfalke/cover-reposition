<?php
 $cover_reposition = new CoverReposition();
 $update_message = '';
 $cover_reposition_path = '';
// Save cover reposition path
  if ( isset( $_POST["save_path"]) and isset($_POST["cover_reposition_path"]) ) {
    if( $cover_reposition->cover_reposition_save_data( $_POST["cover_reposition_path"] )){
      $update_message = '<div id="setting-error-settings_updated" class="updated settings-error below-h2"><p><strong>Cover reposition path saved.</strong></p></div>';
    }
  }
  $cover_reposition_path = get_option('cover_reposition_path');
?>
<div class="wrap">
  <h2 class='opt-title'>
    <?php echo __( 'Cover Reposition Plugin Settings', 'cover-reposition'); ?>
  </h2>
  <?php
    if (isset($update_message)) echo $update_message;
  ?>
  <form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post" name="settings_form" id="settings_form">
    <table width="1004" class="form-table">
      <tbody>
        <tr>
          <p>Setup folder for Repositioned Cover images.<p>
        </tr>
        <tr>
          <th><?php esc_html_e('Folder Path:')?> </th>
          <td>
            <input type="text" name="cover_reposition_path" value="<?php print $cover_reposition_path; ?>" style="width:450px;"/>
          </td>
        </tr>
        <tr>
          <th></th>
          <td>
            <p class="submit">
              <input type="submit" class="button-primary" value = "Save Changes" name = "save_path" />
            </p>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
