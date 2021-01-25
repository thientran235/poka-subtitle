<?php
// Block direct access to file
defined('ABSPATH') or die('Not Authorized!');

/**
* Subtitle class
*/
class PokaSubtitle {
	/**
	 *
	 * Constructor
	 *
	 * @access public
	 * @author Ralf Hortt
	 **/
	public function __construct() {
		add_action( 'admin_print_styles-post-new.php', array( $this, 'enqueue_styles' ) );
		add_action( 'admin_print_styles-post.php', array( $this, 'enqueue_styles' ) );
		add_action( 'edit_form_before_permalink', array( $this, 'edit_form_before_permalink' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );

		add_post_type_support( 'post', 'subtitle' );
		//add_post_type_support( 'page', 'subtitle' );

		load_plugin_textdomain( 'poka-subtitle', false, dirname( PST_DIRECTORY_BASENAME ) . '/languages' );
	}

	/**
	 * Add subtitle field
	 *
	 * @access public
	 * @author Ralf Hortt
	 **/
	public function edit_form_before_permalink() {
		global $post;

		if ( isset( $_GET['post_type'] ) )
			$post_type = sanitize_text_field( $_GET['post_type'] );
		elseif ( isset( $post->post_type ) )
			$post_type = $post->post_type;
		else
			$post_type = 'post';

		if ( post_type_supports( $post_type, 'subtitle' ) )
			$this->subtitle_field( $post );
	}

	/**
	 * Enqueue Styles
	 *
	 * @access public
	 * @author Ralf Hortt
	 **/
	public function enqueue_styles() {
		wp_enqueue_style( 'poka-subtitle', PST_DIRECTORY_URL . '/css/poka-subtitle.css' );
	}

	/**
	 * Get Subtitle
	 *
	 * @static
	 * @access public
	 * @param int $post_id Post ID
	 * @return str Subtitle
	 * @author Ralf Hortt
	 **/
	public static function get_subtitle( $post_id = FALSE ) {
		$post_id = ( FALSE !== $post_id ) ? $post_id : get_the_ID();
		return esc_html( apply_filters( 'the_subtitle', get_post_meta( $post_id, '_subtitle', TRUE ) ) );
	}

	/**
	 *
	 * Save subtitle
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt
	 */
	public function save_post( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		if ( !isset( $_POST['save-subtitle'] ) || !wp_verify_nonce( $_POST['save-subtitle'], plugin_basename( __FILE__ ) ) )
			return;

		if ( '' != $_POST['poka-subtitle'] ) :
			update_post_meta( $post_id, '_subtitle', sanitize_text_field( $_POST['poka-subtitle'] ) );
		else :
			delete_post_meta( $post_id, '_subtitle' );
		endif;

	}

	/**
	 * Metabox content
	 *
	 * @access public
	 * @author Ralf Hortt
	 */
	public function subtitle_field( $post ) {
		$subtitle = $this->get_subtitle( $post->ID );
		?>
		<input type="text" autocomplete="off" id="poka-subtitle" value="<?php echo esc_attr( $subtitle ) ?>" name="poka-subtitle" placeholder="<?php _e( 'Enter subtitle here', 'poka-subtitle' ); ?>">
		<?php
		wp_nonce_field( plugin_basename( __FILE__ ), 'save-subtitle' );
	}

	/**
	 * Display Subtitle
	 *
	 * @static
	 * @access public
	 * @param str $before Before the subtitle
	 * @param str $after After the subtitle
	 * @author Ralf Hortt
	 */
	public static function the_subtitle( $before = '', $after = '' ) {
		$subtitle = get_subtitle( get_the_ID() );
		if ( '' != $subtitle )
			echo $before . $subtitle . $after;
	}



}
new PokaSubtitle();

/**
 * Getter: Subtitle
 *
 * @param int $post_id Post ID
 * @return str Subtitle
 * @author Ralf Hortt
 **/
function get_subtitle( $post_id = FALSE ) {
	return PokaSubtitle::get_subtitle( $post_id );
}

/**
 * Conditional Tag: Subtitle
 *
 * @param int $post_id Post ID
 * @return bool
 * @author Ralf Hortt
 **/
function has_subtitle( $post_id = FALSE ) {
	if ( '' !== PokaSubtitle::get_subtitle( $post_id ) )
		return TRUE;
	else
		return FALSE;
}

/**
 * Template Tag: Display Subtitle
 *
 * @param str $before Before the subtitle
 * @param str $after After the subtitle
 * @author Ralf Hortt
 */
function the_subtitle( $before = '', $after = '' ) {
	PokaSubtitle::the_subtitle( $before, $after );
}

function poka_subtitle_shortcode() {
    if (!has_subtitle())
        return;

    ob_start();
    ?>
        <div class="post-subtitle" style=" margin-bottom: 8px; font-size: 16px;color: rgb(0 0 0 / 84%);"><?php the_subtitle(); ?></div>
    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

add_shortcode("poka_subtitle", "poka_subtitle_shortcode");
