<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/sachin160290/Guest-Post-Submission-Plugin
 * @since      1.0.0
 *
 * @package    Guest_Post_Submission_Plugin
 * @subpackage Guest_Post_Submission_Plugin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Guest_Post_Submission_Plugin
 * @subpackage Guest_Post_Submission_Plugin/public
 * @author     Sachin Yadav <sachin.singh.yadav2011@gmail.com>
 */
class Guest_Post_Submission_Plugin_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		if ( defined( 'IMG_PATH' ) ) {
			$this->img_path = IMG_PATH;
		} else {
			$this->img_path = plugin_dir_url( __DIR__ ) . '/admin/images/';
		}
		
		add_shortcode( 'guest_post_submission_form', array( $this, 'guest_post_submission_form_handler' ) );
		add_shortcode( 'show_pending_guest_posts', array( $this, 'get_pending_guest_posts_func' ) );
		add_shortcode( 'show_all_posts_by_guest', array( $this, 'get_all_guest_posts_func' ) );
		
		add_action( 'wp_ajax_guest_post_submit_ajax_form', array( $this, 'guest_post_submit_ajax_form' ) );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Guest_Post_Submission_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Guest_Post_Submission_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/guest-post-submission-plugin-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Guest_Post_Submission_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Guest_Post_Submission_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$params = array ( 'ajaxurl' => admin_url( 'admin-ajax.php' ) );	
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/guest-post-submission-plugin-public.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( $this->plugin_name, 'gpsp_params', $params );
	}
	
	/**
	 * Guest Post Submission Form Handler Function
	 *
	 * @since    1.0.0
	 */
	public function guest_post_submission_form_handler(){
		if( is_user_logged_in() ): 
			$user = wp_get_current_user();
			if ( in_array( 'author', (array) $user->roles ) || in_array( 'administrator', (array) $user->roles ) ) :
				$current_user_id = get_current_user_id();
				$content = '';
				$editor_id = 'description';
				$settings =   array(
					'wpautop' => true, // use wpautop?
					'media_buttons' => true, // show insert/upload button(s)
					'textarea_name' => $editor_id, // set the textarea name to something different, square brackets [] can be used here
					'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
					'tabindex' => '',
					'editor_css' => '', //  extra styles for both visual and HTML editors buttons, 
					'editor_class' => '', // add extra class(es) to the editor textarea
					'teeny' => false, // output the minimal editor config used in Press This
					'dfw' => false, // replace the default fullscreen with DFW (supported on the front-end in WordPress 3.4)
					'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
					'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
				);
				ob_start();
				echo '<div class="container">
							<h3 class="info-text">Please Enter details to submit a post</h3>
							<form methord="post" action="" id="guest_post_form" enctype="multipart/form-data">
								<input type="hidden" name="action" value="guest_post_submit_ajax_form">
								<input type="hidden" name="author_id" value="'.$current_user_id.'">

								<div class="form-group">
									<label for="post_title">'. __('Post Title *', 'guest-post-submission-plugin').'</label>
									<input type="text" id="post_title" name="post_title" placeholder="'. __('Enter post title', 'guest-post-submission-plugin').'">
								</div>	

								<div class="form-group">
									<label for="description">'. __('Description *', 'guest-post-submission-plugin').'</label>';
									echo wp_editor( $content, $editor_id, $settings );
				echo '			</div>

								<div class="form-group">
									<label for="excerpt">'. __('Excerpt *', 'guest-post-submission-plugin').'</label>
									<textarea type="text" id="excerpt" name="excerpt" placeholder="'. __('Enter post excerpt', 'guest-post-submission-plugin').'"></textarea>
								</div>

								<div class="form-group">
									<label for="post_type">'. __('Post Type *', 'guest-post-submission-plugin').'</label>
									<select id="post_type" name="post_type">';
										$args = array( 'public' => true, );
										$post_types = get_post_types( $args, 'objects' );
										foreach ( $post_types  as $post_type ) :
											if( 'guest_post_cpt' === $post_type->name ):
												$selected = 'selected="selected"';
											else:
												$selected = '';
											endif;	
											echo '<option value="'.$post_type->name.'" '.$selected.'>'. $post_type->labels->singular_name .'</option>';
										endforeach;
				echo '				</select>
								</div>	

								<div class="form-group">
									<label for="feature_image">'. __('Featured Image *', 'guest-post-submission-plugin').'</label>
									<input type="file" id="feature_image" name="feature_image" placeholder="'. __('Featured image', 'guest-post-submission-plugin').'"  multiple="false" value="" accept=".png, .jpg, .jpeg">
								</div>

								<div class="form-group">
									'. wp_nonce_field( 'guest_post_form_nonce', 'guest_post_form_nonce' ) .'
									<input type="submit" value="'. __('Submit', 'guest-post-submission-plugin').'">
								</div>

								<div class="ajax-message"></div>
								'.$this->gpsp_form_loader().'
							</form>
						</div>';
			else:
				echo '<p>'. __('You have no permission to access this. Please <a href="'.wp_login_url().'">login</a> first.', 'guest-post-submission-plugin').'</p>';
			endif;				
		else:
			echo '<p>'. __('You have no permission to access this. Please <a href="'.wp_login_url().'">login</a> first.', 'guest-post-submission-plugin').'</p>';
		endif;			
		$output = ob_get_clean();
		return $output;
	}

	/** 
     * Get the settings option array and print one of its values
	 * 
	 * @since     1.0.0
 	 * @return    string    HTML of Loading Icon.
     */
	public function gpsp_form_loader(){
		return ' <div class="overlay-loader-blk"><img src="'.$this->img_path.'loader.svg" alt="Loading"></div>';
	}

	/** 
     * Ajax Form Handler for Form Submission
	 * 
	 * @since     1.0.0
     */	
	public function guest_post_submit_ajax_form() {
		global $wpdb;
		$data  = $_REQUEST;		
		if(!empty($data) && wp_verify_nonce( $_REQUEST['guest_post_form_nonce'], 'guest_post_form_nonce' ) ):
			// sanitize the input
			$post_title 	= sanitize_text_field( $data['post_title'] );
			$description 	= sanitize_textarea_field( $data['description'] );
			$excerpt 		= sanitize_textarea_field( $data['excerpt'] );
			$post_type 		= sanitize_text_field( $data['post_type'] );
			$author_id 		= sanitize_text_field( $data['author_id'] );

			if(!empty($author_id)) :
				$author_id = $author_id; 
			else:
				$author_id = get_current_user_id();
			endif;	
			
			$new_post = array(
							'post_title' 	=> wp_strip_all_tags( $post_title ),
							'post_content' 	=> $description,
							'post_status' 	=> 'draft',
							'post_date' 	=> date('Y-m-d H:i:s'),
							'post_author' 	=> $author_id,
							'post_type' 	=> $post_type,
							'post_excerpt' 	=> $excerpt
						);
			$post_id = wp_insert_post($new_post);	

			if(!empty( $post_id  )) :
				if(!empty($_FILES['feature_image']['name'])):
					$wordpress_upload_dir = wp_upload_dir();
					$i = 1;		 
					$post_feature_image = $_FILES['feature_image'];
					$new_file_path = $wordpress_upload_dir['path'] . '/' . $post_feature_image['name'];
					$get_file_mime = wp_check_filetype( $post_feature_image['name'] );
					$new_file_mime = $get_file_mime['type'];
		
					if( empty( $post_feature_image ) ):
						echo json_encode(array('result' => 'fail', 'message' => '<span class="error-message">File is not selected.</span>'));	 
					endif;
						
					if( $post_feature_image['error'] ):
						echo json_encode(array('result' => 'fail', 'message' => '<span class="error-message">'.$post_feature_image['error'].'</span>'));
					endif;
		
					if( $post_feature_image['size'] > wp_max_upload_size() ):
						echo json_encode(array('result' => 'fail', 'message' => '<span class="error-message">It is too large than expected.</span>'));
					endif;
		
					$new_file_name = '';
					
					while( file_exists( $new_file_path ) ) {
						$i++;					
						$n1 = str_replace(' ', '_', $post_feature_image['name']);
						$n2 = str_replace('(', '_', $n1);
						$n3 = str_replace(')', '_', $n2);
						$new_file_name = str_replace('.', '', $n3);	
						$new_file_path = $wordpress_upload_dir['path'] . '/' . $i . '_' . $new_file_name;
					}
					
					if( move_uploaded_file( $post_feature_image['tmp_name'], $new_file_path ) ):					
						$upload_id = wp_insert_attachment( array(
							'guid'           => $new_file_path, 
							'post_mime_type' => $new_file_mime,
							'post_title'     => preg_replace( '/\.[^.]+$/', '', $post_feature_image['name'] ),
							'post_content'   => '',
							'post_status'    => 'inherit'
						), $new_file_path );
		
						// wp_generate_attachment_metadata() won't work if you do not include this file
						require_once( ABSPATH . 'wp-admin/includes/image.php' );		 
						// Generate and save the attachment metas into the database
						wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $new_file_path ) );
		
						// Update Feature Image of Post
						set_post_thumbnail( $post_id, $upload_id );
					endif;
				else:
					echo json_encode(array('result' =>  'fail', 'message' => '<span class="error-message">Please upload an image.</span>'));		
				endif;

				$admin_email  = get_option('admin_email'); 
				$site_title   = get_bloginfo( 'name' );
				$subject 	  = 'New post has been submit on website';
				$body 		  = '';
				$body 		  .= '<p>Hello Admin</p>';
				$body 		  .= '<p>A New Post has been sumitted on the website with post id <strong>'.$post_id.'</strong>. The post is in under your moderation, please make it public so it will visible on frontend.</p>';
				$headers[] = "From: $site_title <$admin_email>"; 
        		$headers[] = 'Content-type: text/html; ' . "\r\n";
				
				// Sent email to site Admin
				$mail = wp_mail( $admin_email, $subject, $body, $headers );

				echo json_encode(array('result' => 'success', 'message' => '<span class="success-message text-success">Your post has been submitted successfully. Your post is in under moderation. Your Post id is <strong>'.$post_id.'</strong>.</span>'));

			else :
				echo json_encode(array('result' => 'fail', 'message' => '<span class="error-message text-error">Oops! Something went wrong, post not created.</span>'));
			endif;
		else :
			echo json_encode(array('result' => 'fail', 'message' => '<span class="error-message text-error">Oops! Something went wrong. Please retry.</span>'));
		endif;
		die();
	}


	/**
	 * Get Pending Guest Posts Function
	 *
	 * @since    1.0.0
	 */
	public function get_pending_guest_posts_func(){
		$html = '';
		if( is_user_logged_in() ): 
			$user = wp_get_current_user();
			if ( in_array( 'author', (array) $user->roles ) || in_array( 'administrator', (array) $user->roles ) ) :
				$author_id = $user->ID;		
				$html .= '	<div class="guest-post-listing">
								<h3 class="info-text">List of all pending posts</h3>	
								<table>
									<tr>
										<th>'. __('Post Title', 'guest-post-submission-plugin').'</th>
										<th>'. __('Feature Image', 'guest-post-submission-plugin').'</th>
										<th>'. __('Posted On', 'guest-post-submission-plugin').'</th>
										<th>'. __('Status', 'guest-post-submission-plugin').'</th>
									</tr>';

									$per_page 	= 10;
									$post_type 	= 'guest_post_cpt';
									$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
									$cpt_args 	= array('post_type' => $post_type, 'post_status'=> 'draft', 'posts_per_page' => $per_page , 'paged' => $paged, 'orderby' => 'post_date', 'order' => 'DESC', 'author' => $author_id );
									$post_list 	= new WP_Query( $cpt_args );
									
									if ( $post_list->have_posts() ):                            
										while ( $post_list->have_posts() ) : $post_list->the_post();
											$post_id = get_the_ID();
											$post_status = get_post_status($post_id);
											$html 	.= '	<tr>
																<td>'.get_the_title($post_id).'</td>';
																if ( has_post_thumbnail($post_id) ):
																	$imgs = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'thumbnail' );
																	$large_image_url = $imgs[0];
																	$html .= '<td><img src="'.$large_image_url.'" alt="'.get_the_title($post_id).'"></td>';
																else:
																	$html .= '<td></td>';
																endif;
																
											$html 	.= '		<td>'.get_the_date( 'F d, Y', $post_id ).'</td>
																<td>Pending by Admin</td>
															</tr>';
										endwhile;
									else:
										$html .= '<tr class="no-item-found"><td colspan="4">'. __('Sorry, it seems that we didn&#39;t find any results.', 'guest-post-submission-plugin').'</td></tr>';
									endif;
									wp_reset_postdata();
				$html .= '		</table>';

								if($post_list->found_posts > $per_page): 
									$html .= $this->gpsp_show_pagination($post_list->max_num_pages); 
								endif;	
				$html .= '	</div>';
			else:
				$html .= '<p>'. __('You have no permission to access this. Please <a href="'.wp_login_url().'">login</a> first.', 'guest-post-submission-plugin').'</p>';	
			endif;	
		else:
			$html .= '<p>'. __('You have no permission to access this. Please <a href="'.wp_login_url().'">login</a> first.', 'guest-post-submission-plugin').'</p>';
		endif;
		
		ob_start();
		echo $html;
		$output = ob_get_clean();
		return $output;
	}


	/**
	 * Get All Posts by the User Function
	 *
	 * @since    1.0.0
	 */
	public function get_all_guest_posts_func(){
		$html = '';
		if( is_user_logged_in() ): 
			$user = wp_get_current_user();
			if ( in_array( 'author', (array) $user->roles ) || in_array( 'administrator', (array) $user->roles ) ) :
				$author_id = $user->ID;		
				$html .= '	<div class="guest-post-listing">
								<h3 class="info-text">List of all posts posted by '.$user->display_name.'</h3>	
								<table>
									<tr>
										<th>'. __('Post Title', 'guest-post-submission-plugin').'</th>
										<th>'. __('Feature Image', 'guest-post-submission-plugin').'</th>
										<th>'. __('Posted On', 'guest-post-submission-plugin').'</th>
										<th>'. __('Status', 'guest-post-submission-plugin').'</th>
									</tr>';

									$per_page 	= 10;
									$post_type 	= 'guest_post_cpt';
									$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
									$cpt_args 	= array('post_type' => $post_type, 'post_status'=> 'any', 'posts_per_page' => $per_page , 'paged' => $paged, 'orderby' => 'post_date', 'order' => 'DESC', 'author' => $author_id );
									$post_list 	= new WP_Query( $cpt_args );
									
									if ( $post_list->have_posts() ):                            
										while ( $post_list->have_posts() ) : $post_list->the_post();
											$post_id = get_the_ID();
											$post_status = get_post_status($post_id);
											
											$html 	.= '	<tr>
																<td>'.get_the_title($post_id).'</td>';
																if ( has_post_thumbnail($post_id) ):
																	$imgs = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'thumbnail' );
																	$large_image_url = $imgs[0];
																	$html .= '<td><img src="'.$large_image_url.'" alt="'.get_the_title($post_id).'"></td>';
																else:
																	$html .= '<td></td>';
																endif;
																
											$html 	.= '		<td>'.get_the_date( 'F d, Y', $post_id ).'</td>
																<td>'.ucfirst( $post_status ).'</td>
															</tr>';
										endwhile;
									else:
										$html .= '<tr class="no-item-found"><td colspan="4">'. __('Sorry, it seems that we didn&#39;t find any results.', 'guest-post-submission-plugin').'</td></tr>';
									endif;
									wp_reset_postdata();
				$html .= '		</table>';

								if($post_list->found_posts > $per_page): 
									$html .= $this->gpsp_show_pagination($post_list->max_num_pages); 
								endif;	
				$html .= '	</div>';
			else:
				$html .= '<p>'. __('You have no permission to access this. Please <a href="'.wp_login_url().'">login</a> first.', 'guest-post-submission-plugin').'</p>';	
			endif;	
		else:
			$html .= '<p>'. __('You have no permission to access this. Please <a href="'.wp_login_url().'">login</a> first.', 'guest-post-submission-plugin').'</p>';
		endif;
		
		ob_start();
		echo $html;
		$output = ob_get_clean();
		return $output;
	}


	/**
	 * Pagination Function
	 *
	 * @since    1.0.0
	 */
	public function gpsp_show_pagination( $max_num_pages ){
		if($max_num_pages > 1):
			$big = 999999999;		
			$pagination = '<div class="pagination">';
			$pagination .= paginate_links( array(
					'type' => 'list',
					'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format' => '?paged=%#%',
					'current' => max( 1, get_query_var('paged') ),
					'total' => $max_num_pages,
					'prev_next' => true,
					'prev_text' => '<span class="icon icon-prev"></span> '. __('Previous', 'guest-post-submission-plugin'),
					'next_text' => ''. __('Next', 'guest-post-submission-plugin').' <span class="icon icon-next"></span>',
				) ); 
			$pagination .= 	'</div>';
			return $pagination;
		else:
			return '';
		endif;
	}

}
