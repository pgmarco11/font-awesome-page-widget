<?php
/**
 * Plugin Name: Font Awesome Page Widget 
 * Description: Easily add Page links into your WordPress posts, pages, and custom post types with Font Awesome Icons
 * Version: 1.0.0
 */

/**
 * Do not load this file directly.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Adds widget.
 */
class faw_widget extends WP_Widget {

		/**
	 * Register widget with WordPress.
	 */

	public function __construct(){	

		parent::__construct( 'faw_page_widget', 'Font Awesome Page Widget', 
			array('classname' => 'faw_widget', 'description' => __('Display nice looking links to other pages') )
		);

		add_action('admin_enqueue_scripts', array(&$this, 'faw_assets'), 10, 1);
		
	}

	public function faw_assets(){

			wp_enqueue_style( 'wp-color-picker' );

		    wp_enqueue_script(
		        'iris',
		        admin_url( 'js/iris.min.js' ),
		        array( 
		            'jquery-ui-draggable', 
		            'jquery-ui-slider', 
		            'jquery-touch-punch'
		        ),
		        false,
		        1
		    );

		    // Now we can enqueue the color-picker script itself, 
		    //    naming iris.js as its dependency
		    wp_enqueue_script(
		        'wp-color-picker',
		        admin_url( 'js/color-picker.min.js' ),
		        array( 'iris' ),
		        false,
		        1
		    );

		    // Manually passing text strings to the JavaScript
		    $colorpicker_l10n = array(
		        'clear' => __( 'Clear' ),
		        'defaultString' => __( 'Default' ),
		        'pick' => __( 'Select Color' ),
		        'current' => __( 'Current Color' ),
		    );
		    wp_localize_script( 
		        'wp-color-picker',
		        'wpColorPickerL10n', 
		        $colorpicker_l10n 
		    ); 

	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance){

		$instance = $new_instance;

		$instance['icon_color'] = $new_instance['icon_color']; 
		$instance['background_color'] = $new_instance['background_color'];

		return $new_instance;

	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */

	public function form($instance){

		$icon = '';
		$link_text = '';
		$page_id = '';
		$description = '';	
		$select = '';
		$shape = '';
		$padding_top = '';
		$padding_right = '';
		$padding_bottom = '';
		$padding_left = '';

		if( !empty( $instance['icon'] ) ) { $icon = $instance['icon']; }
		if( !empty( $instance['link_text'] ) ) { $link_text = $instance['link_text']; }
		if( !empty( $instance['page_id'] ) ) { $page_id = $instance['page_id']; }
		if( !empty( $instance['description'] ) ) { $description = $instance['description']; }
		if( !empty( $instance['select'] ) ) { $select = $instance['select']; }
		if( !empty( $instance['shape'] ) ) { $shape = $instance['shape']; }
		if( !empty( $instance['padding_top'] ) ) { $padding_top = $instance['padding_top']; }
		if( !empty( $instance['padding_right'] ) ) { $padding_right = $instance['padding_right']; }
		if( !empty( $instance['padding_left'] ) ) { $padding_left = $instance['padding_left']; }
		if( !empty( $instance['padding_bottom'] ) ) { $padding_bottom = $instance['padding_bottom']; }

	?>
	
	<p>
	<label for="<?php echo esc_attr( $this->get_field_name('pageSelect')); ?>">
				<?php _e('Page:'); ?>
	</label>
	<br>
	<?php 

		if( isset( $instance['page_id'] ) ){
			$page_id = $instance['page_id'];
		} else {
			$page_id = 0;
		}

		$args = array(
			'id' => $this->get_field_id('page_id'),
			'name' => $this->get_field_name('page_id'),
			'selected' => $page_id
			);

		wp_dropdown_pages($args);

	?>

	</p>

	<p>
		<label for="<?php echo esc_attr( $this->get_field_name('icon')); ?>">
				<?php _e('Font Awesome Icon:'); ?>
		</label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon' ) ); ?>" type="text" value="<?php echo esc_attr_e( $icon ); ?>"/>
		
		<br>
		<?php

		$defaults = array(
            'icon_color' => '#e3e3e3',
            'background_color' => '#e3e3e3'
        );

        // Merge the user-selected arguments with the defaults
        $instance = wp_parse_args( (array) $instance, $defaults );

         ?> 

        <script type='text/javascript'>

        	jQuery(document).ready(function($) { 
	            jQuery('.my-color-picker').on('focus', function(){
	                jQuery(this).wpColorPicker();
			    }); 
		    }); 
       
        </script> 

        <label for="<?php echo esc_attr( $this->get_field_name('icon_color')); ?>"><?php _e( 'Icon Color:' ); ?></label>
        <br>
        <input class="my-color-picker" type="text" id="<?php echo $this->get_field_id( 'icon_color' ); ?>" name="<?php echo $this->get_field_name( 'icon_color' ); ?>" 
        value="<?php echo esc_attr( $instance['icon_color'] ); ?>" /> 
        <br>
        <label for="<?php echo esc_attr( $this->get_field_name('icon_size')); ?>"><?php _e( 'Icon Size:' ); ?></label>
        <br>
        <select class="widefat" name="<?php echo esc_attr( $this->get_field_name('select')); ?>" id="<?php echo $this->get_field_id('select'); ?>" >
		<?php
		$options = array('Small', 'Medium', 'Large', 'X-Large');
		foreach ($options as $option) {
		echo '<option value="' . $option . '" id="' . $option . '"', $select == $option ? ' selected="selected"' : '', '>', $option, '</option>';
		}
		?>
		</select>
        <br> 
        <a href="http://fontawesome.io/icons/" target="_blank">Font Awesome Icons</a>                          

	</p>
	<p>
		<label for="<?php echo esc_attr( $this->get_field_name('shape')); ?>">
			<?php _e('Background Shape:'); ?>
		</label>
		<?php 
		$shapes = array('Circle', 'Square');
		foreach($shapes as $background) { ?>
		<input class="widefat" id="<?php echo esc_attr( $background ); ?>" name="<?php echo esc_attr( $this->get_field_name('shape') ); ?>" type="radio" value="<?php echo esc_attr_e( $background ); ?>" <?php if($shape === $background){ echo 'checked="checked"'; }?> >
		<label for="<?php echo esc_attr( $this->get_field_name('shape')); ?>">
			<?php _e($background); ?>
		</label>
		<?php } ?>
		<br>
		 <label for="<?php echo esc_attr( $this->get_field_name('background_color')); ?>"><?php _e( 'Background Color:' ); ?></label>
        <br>
        <input class="my-color-picker" type="text" id="<?php echo $this->get_field_id( 'background_color' ); ?>" name="<?php echo $this->get_field_name( 'background_color' ); ?>" 
        value="<?php echo esc_attr( $instance['background_color'] ); ?>" /> 
    <p>
		<label for="<?php echo esc_attr( $this->get_field_name('padding')); ?>">
				<?php _e('Padding'); ?>
		</label>
		<br>
		<span>Top Padding:</span>
		<input class="smallw" id="<?php echo esc_attr( $this->get_field_id( 'padding_top' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'padding_top' ) ); ?>" type="text" 
		value="<?php if ( !empty($padding_top) ) { echo esc_attr_e( $padding_top ); } else { echo '.15em'; } ?>"/>
		<br>
		<span>Right Padding:</span>
		<input class="smallw" id="<?php echo esc_attr( $this->get_field_id( 'padding_right' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'padding_right' ) ); ?>" type="text" value="<?php if ( !empty($padding_right) ) { echo esc_attr_e( $padding_right ); } else { echo '.35em'; } ?>"/>
		<br>
		<span>Bottom Padding:</span>
		<input class="smallw" id="<?php echo esc_attr( $this->get_field_id( 'padding_bottom' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'padding_bottom' ) ); ?>" type="text" 
		value="<?php if ( !empty($padding_bottom) ) { echo esc_attr_e( $padding_bottom ); } else { echo '.15em'; } ?>"/>
		<br>
		<span>Left Padding:</span>
		<input class="smallw" id="<?php echo esc_attr( $this->get_field_id( 'padding_left' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'padding_left' ) ); ?>" type="text" value="<?php if ( !empty($padding_left) ) { echo esc_attr_e( $padding_left ); } else { echo '.35em'; } ?>"/>


	</p>  
	<p>
		<label for="<?php echo esc_attr( $this->get_field_name('description')); ?>">
				<?php _e('Page Description:'); ?>
		</label>
		<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>">
			<?php echo esc_attr_e( $description ); ?></textarea>
	</p>

	<p>
		<label for="<?php echo esc_attr( $this->get_field_name('link_text')); ?>">
				<?php _e('Page Link text:'); ?>
		</label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'link_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link_text' ) ); ?>" type="text" value="<?php echo esc_attr_e( $link_text ); ?>"/>
	</p>


	<?php

	}


 	 /**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */

	public function widget($args, $instance){




			echo $args['before_widget'];

			$page = get_post($instance['page_id']);

	?>

			<?php if( !empty( $instance['icon'] ) ){ 

					if( !empty( $instance['page_id'] ) ) {

					//add custom padding
						
					?>

					<a href="<?php echo get_permalink($page->ID) ?>" title="<?php echo $page->post_title ?>"
					style="padding:	<?php 
					if ( !empty( $instance['padding_top'] ) ){ echo esc_attr_e($instance['padding_top']); } else { echo '.15em'; };
					?><?php _e(" "); ?> <?php 
					if ( !empty( $instance['padding_right'] ) ){  echo esc_attr_e($instance['padding_right']); } else { echo '.35em'; };
					?><?php _e(" "); ?> <?php
					if ( !empty( $instance['padding_bottom'] ) ){  echo esc_attr_e($instance['padding_bottom']); } else { echo '.15em'; };
					?><?php _e(" "); ?> <?php
					if ( !empty( $instance['padding_left'] ) ){  echo esc_attr_e($instance['padding_left']); } else { echo '.35em'; };
					?>;
					background-color: <?php echo esc_attr_e( $instance['background_color'] ); 
					?>;
					color: <?php echo esc_attr_e( $instance['icon_color'] );
					?>;
					<?php if($instance['select'] == 'Small' ){ ?> font-size: 200%; width: 45px;<?php } ?>
					<?php if($instance['select'] == 'Medium' ){ ?> font-size: 300%; width: 65px;<?php } ?>
					<?php if($instance['select'] == 'Large' ){ ?> font-size: 400%; width: 90px;<?php } ?>
					<?php if($instance['select'] == 'X-Large' ){ ?> font-size: 500%; width: 110px;<?php } ?>
					<?php if($instance['shape'] == 'Circle' ){ ?> border-radius: 80px;<?php } ?>
					<?php if($instance['shape'] == 'Square' ){ ?> border-radius: 0;<?php } ?>" >

					<?php echo sprintf( $instance['icon'] ); ?>

					</a>

					<?php } else { ?>

						<a href="#" style="color:<?php echo esc_attr_e( $instance['icon_color'] ); ?>;"><?php echo sprintf($instance['icon']); ?></a>

			<?php	}
						
				}
			?>	

			<h2 class="pg-title-small br-bottom-center color-on-dark"><?php if( !empty( $instance['page_id'] ) ) { echo $page->post_title; }  ?></h2>
			<p class="mb10 color-on-dark pg-text-description"><?php if( !empty( $instance['description'] ) ){ echo sprintf($instance['description']); }  ?></p>
			
			<?php if( !empty( $instance['link_text'] ) )  { ?>

			<p>
			<a href="<?php if( !empty( $instance['page_id'] ) ) { echo get_permalink($page->ID); }  ?>" class="btn btn-c">
			
			<?php echo esc_attr_e( $instance['link_text'] );  ?>
			
			</a>
			</p>

	<?php
			}

			echo $args['after_widget'];

	}

}

add_action( 'admin_init','callback_for_setting_up_scripts');
add_action('wp_enqueue_scripts', 'callback_for_setting_up_scripts');

function callback_for_setting_up_scripts() {

		wp_register_style( 'page-widget-style', plugins_url( 'css/fa-page-widget.css', __FILE__));
	    wp_enqueue_style( 'page-widget-style' );

}


add_action( 'widgets_init', create_function('', 'return register_widget("faw_widget");') );

?>
