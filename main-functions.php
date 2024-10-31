<?php
/*
Plugin Name: My WP FAQs
Plugin URI: http://prowpexpert.com
Description: This is My WP FAQs wordpress plugin really looking awesome WP FAQs. Everyone can use the My WP FAQs plugin easily like other wordpress plugin. Here everyone can use from post, page or other custom post. Also can use from every category. By using [faq] shortcode use the faq every where post, page and template.
Author: Md Sohel
Version: 1.0
Author URI: http://prowpexpert.com/
*/
function my_wp_faq_add_jquery() {
	wp_enqueue_script('jquery');
}
add_action('init', 'my_wp_faq_add_jquery');

function my_wp_script_faq_and_style_files() {
    wp_enqueue_script( 'faq-js-d', plugins_url( '/js/bootstrap.js', __FILE__ ), true);
    wp_enqueue_style( 'faq-css-d', plugins_url( '/css/bootstrap.css', __FILE__ ));
    wp_enqueue_style( 'faqcss-d', plugins_url( '/style.css', __FILE__ ));
}

add_action('init','my_wp_script_faq_and_style_files');
function my_wp_script_faq () {?>
	<script type="text/javascript">
		
			jQuery(document).ready(function($){


				jQuery('.accordion-item').each(function(){if(jQuery(this).find('.accordion-title a').hasClass('collapsed')){jQuery(this).removeClass('active');}else{jQuery(this).addClass('active');}
				jQuery(this).find('.accordion-title a').click(function(){if(jQuery(this).hasClass('collapsed')){jQuery(this).parents('.accordion-item').addClass('active');}else{jQuery(this).parents('.accordion-item').removeClass('active');}});});

			});
			
	</script>
	

<?php
}
add_action('wp_head','my_wp_script_faq');

function jeba_faq_shortcode_d($atts){
	extract( shortcode_atts( array(
		'category' => '',
		'post_type' => 'faq-items',
		'count' => '5',
	), $atts) );
	
    $q = new WP_Query(
        array('posts_per_page' => $count, 'post_type' => $post_type, 'category_name' => $category)
        );		
		
		$plugins_url = plugins_url();
		
	$list = '  	
			
			<div class="accordion faq toggle">
						
						';
	while($q->have_posts()) : $q->the_post();
		$idd = get_the_ID();
		$jeba_img_large = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large-portfolio' );
		
		$list .= '
			
			
			 <div class="panel accordion-item bottom-20">
				<div class="accordion-heading">
					<h5 class="accordion-title">
						<a data-toggle="collapse" href="#collapse'.get_the_ID().'">
								'.get_the_title().'
						</a>
					</h5> 
				</div>
				<div id="collapse'.get_the_ID().'" class="accordion-collapse collapse">
					<div class="accordion-body">
						'.get_the_excerpt().'
					</div>
				</div>
			</div>
		
		';        
	endwhile;
	$list.= '
		
		</div>
		';
	wp_reset_query();
	return $list;
}
add_shortcode('faq', 'jeba_faq_shortcode_d');



add_action( 'init', 'my_register_faq_custom_post' );
function my_register_faq_custom_post() {

	register_post_type( 'faq-items',
		array(
			'labels' => array(
				'name' => __( 'FAQs' ),
				'singular_name' => __( 'FAQ' ),
				'add_new' => __( 'Add New FAQ' )
			),
			'public' => true,
			'supports' => array('title', 'editor'),
			'has_archive' => true,
			'rewrite' => array('slug' => 'faq-item'),
		)
	);
	
	register_taxonomy(
		'faq_cat',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
		'faq-items',                  //post type name
		array(
			'hierarchical'          => true,
			'label'                         => 'FAQ Category',  //Display name
			'query_var'             => true,
			'show_admin_column'             => true,
			'rewrite'                       => array(
				'slug'                  => 'faq-category', // This controls the base slug that will display before each term
				'with_front'    => true // Don't display the category base before
				)
			)
	);
	
	}

?>