  <?php
/*
  Plugin Name: Cover Reposition
  Plugin URI: http://dfordrupal.com/cover-reposition
  Description: Facebook Style Cover Image Reposition.
  Version: 1.0
  Author: Rakesh Falke
  Author URI: http://dfordrupal.com/rakesh-falke
  License: GPLv2+
  Text Domain: cover-reposition
*/

class CoverReposition{
  // Constructor
  function __construct() {

    add_action( 'admin_menu', array( $this, 'cover_reposition_add_menu' ));
    //add_filter( 'the_content', array( $this, 'cover_reposition_content' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'cover_reposition_js') );
    add_action( 'wp_default_scripts', array( $this, 'cover_reposition_default_scripts') );
    add_action( 'admin_footer', array( $this, 'cover_reposition_javascript' ) );
    add_action( 'wp_ajax_cover_reposition', array( $this, 'cover_reposition' ) );

    register_activation_hook( __FILE__, array( $this, 'cover_reposition_install' ) );
    register_deactivation_hook( __FILE__, array( $this, 'cover_reposition_uninstall' ) );
  }

function cover_reposition() {
  //global $wpdb; // this is how you get access to the database
  header("Access-Control-Allow-Origin: *");
  if(isset($_POST['pos']))
  {
    $upload = wp_upload_dir();
    $upload_dir = $upload['basedir'];
    $upload_dir = $upload_dir . '/cover_reposition';

    $url = wp_get_attachment_url( get_post_thumbnail_id($_POST['postid']) );
    $newURL = str_replace($upload['baseurl'], $upload['basedir'], $url);

    $from_top = abs($_POST['pos']);
    $default_cover_width = 1140;
    $default_cover_height = 380;
    // includo la classe
    include( dirname(__FILE__) . '/js/thumbncrop.inc.php' );
    // valorizzo la variabile
    $tb = new ThumbAndCrop();

    // apro l'immagine
    $tb->openImg($newURL); //original cover image

    $newHeight = $tb->getRightHeight($default_cover_width);

    $tb->creaThumb($default_cover_width, $newHeight);

    $tb->setThumbAsOriginal();

    $tb->cropThumb($default_cover_width, 276, 0, $from_top);

    $filename = 'cover-'. $_POST['postid'].'.jpg';

    $tb->saveThumb( $upload_dir."/".$filename ); //save cropped cover image

    $tb->resetOriginal();

    $tb->closeImg();

    $data['status'] = 200;
    $data['url'] = $upload_dir."/".$filename;
  } else {
    $data['status'] = 400;
    $data['error'] = 'something is missing here...';
  }
  echo json_encode($data);
  wp_die(); // this is required to terminate immediately and return a proper response
}

  /**
   ** Actions perform at loading of admin menu
   **/
  function cover_reposition_add_menu() {
    add_menu_page( 'Cover Reposition', 'Cover Reposition', 'manage_options', 'cover-reposition', array(__CLASS__,'cover_reposition_pages'), plugins_url('images/icon.png', __FILE__), '75');
  }

/*  function cover_reposition_content($content) {
    //saveReposition()
    return $content . '<a onclick="repositionCover();">Re-Position</a>&nbsp;&nbsp;<a onclick="saveReposition();">Save-Position</a>';
  }*/

  /*
   * Actions perform on loading of menu pages
   */
  static function cover_reposition_pages() {
    $screen = get_current_screen();
    if ( strpos( $screen->base, 'cover-reposition' ) !== false ) {
      include( dirname(__FILE__) . '/includes/cover-reposition-settings.php' );
    }
  }
  public function cover_reposition_save_data( $cover_reposition_path ) {
    update_option( 'cover_reposition_path', $cover_reposition_path );
    return true;
  }

  public function cover_reposition_js() {
    /*wp_enqueue_script('custom_script_1', plugins_url('/js/jquery.form.min.js', __FILE__), array('jquery'));*/
    wp_enqueue_script( 'custom_script', plugins_url( '/js/reposition_js.js', __FILE__ ), array( 'jquery' ) );
    wp_enqueue_script( 'jquery-ui-draggable' );
    wp_enqueue_script( 'jquery-ui-droppable' );
    wp_enqueue_script( 'jquery-form' );
  }

  public function cover_reposition_default_scripts( $scripts ) {
    if ( ! empty( $scripts->registered['jquery'] ) ) {
      $scripts->registered['jquery']->deps = array_diff( $scripts->registered['jquery']->deps, array( 'jquery-migrate' ) );
    }
  }

  /**
   ** Actions perform on activation of plugin
   **/
  function cover_reposition_install() {
    $upload = wp_upload_dir();
    $upload_dir = $upload['basedir'];
    $upload_dir = $upload_dir . '/cover_reposition';
    if (! is_dir($upload_dir)) {
       mkdir( $upload_dir, 0775 );
    }
  }

  /**
   ** Actions perform on de-activation of plugin
   **/
  function cover_reposition_uninstall() {
  }
}
new CoverReposition();
