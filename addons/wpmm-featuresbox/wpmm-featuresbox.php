<?php

add_action('widgets_init','register_wpmm_featuresbox_widget');
function register_wpmm_featuresbox_widget() {
	register_widget('wpmm_featuresbox_widget');
}

class wpmm_featuresbox_widget extends WP_Widget{
	function __construct() {
		parent::__construct( 'wpmm_featuresbox_widget','WPMM Features Box',array('description' => 'Features Box widget to display in WP Mega Menu'));
	}

	/*-------------------------------------------------------
	 *				Front-end display of widget
	 *-------------------------------------------------------*/
	function widget($args, $instance) {
		extract($args);

		$title = null;

		if ( ! empty($instance['title'])){
			$title 			= apply_filters('widget_title', $instance['title'] );
		}

		if ( ! empty($instance['box_layout']) ) {
			$layout     = $instance['box_layout'];
		} else {
			$layout     = 'wpmmlayout1';
		}

		if ( ! empty($instance['box_align']) ) {
			$box_align  = $instance['box_align'];
		} else {
			$box_align  = 'wpmmtextleft';
		}
		$featuretitle 	= ! empty($instance['feature_title'])? $instance['feature_title'] : '';
		$featureicon 	= ! empty($instance['feature_icon']) ? $instance['feature_icon'] : '';
		$featuredesc 	= ! empty($instance['feature_desc']) ? $instance['feature_desc']: '';
		$featurebtntext 	= ! empty($instance['feature_btn_text']) ? $instance['feature_btn_text'] : '';
		$featurebtnurl  = ! empty($instance['feature_btn_url']) ? $instance['feature_btn_url'] : '';
		$featureiconsize    = ! empty($instance['feature_icon_size']) ? $instance['feature_icon_size'] : '';
		$featuretitlesize   = ! empty($instance['feature_title_size']) ? $instance['feature_title_size'] : '';
		$featuretitlemargin   = ! empty($instance['feature_title_margin']) ? $instance['feature_title_margin'] : '';
		$titleweight   = ! empty($instance['title_weight']) ? $instance['title_weight'] : '';
		$featuredescsize 	= ! empty($instance['feature_desc_size']) ? $instance['feature_desc_size'] : '';
		$title_text_transform = ! empty($instance['title_text_transform']) ? $instance['title_text_transform'] : '';

		if ( ! empty($instance['btn_size']) ) {
			$btn_size 	= $instance['btn_size'];
		} else {
			$btn_size 	= 'wpmmbtnsize_m';
		}
		$featureiconcolor  =  ! empty($instance['feature_icon_color']) ? $instance['feature_icon_color'] : '';
		$featuretitlecolor  = ! empty($instance['feature_title_color']) ? $instance['feature_title_color'] : '';
		$featuredesccolor  = ! empty($instance['feature_desc_color']) ? $instance['feature_desc_color'] : '';
		$featurebtncolor  = ! empty($instance['feature_btn_color']) ? $instance['feature_btn_color'] : '';
		$featurebtnbgcolor  = ! empty($instance['feature_btn_bg_color']) ? $instance['feature_btn_bg_color'] : '';
		$featurebtnhcolor  = ! empty($instance['feature_btn_h_color']) ? $instance['feature_btn_h_color'] : '';
		$featurebtnhbgcolor  = ! empty($instance['feature_btn_h_bg_color']) ? $instance['feature_btn_h_bg_color'] : '';
		echo $args['before_widget'];

		$output = $iconstyle = $titlestyle = $descstyle = $btnstyle = '';

		//icon style
		if( $featureiconsize ) { $iconstyle .= 'font-size:'. (int)esc_attr( $featureiconsize ) .'px;';}
		if( $featureiconcolor ) { $iconstyle .= 'color:'. esc_attr( $featureiconcolor ) .';';}
		if( $featureiconsize || $featureiconcolor ) {
			$iconstyle = 'style="'.$iconstyle.'"';
		}

		//title style
		if( $title_text_transform ) { $titlestyle .= 'text-transform: '.$title_text_transform.';';}
		if( $featuretitlesize ) { $titlestyle .= 'font-size:'. (int)esc_attr( $featuretitlesize ) .'px;';}
		if( $titleweight ) { $titlestyle .= 'font-weight:'. esc_attr( $titleweight ) .';';}
		if( $featuretitlemargin ) { $titlestyle .= 'margin:'. esc_attr( $featuretitlemargin ) .';';}
		if( $featuretitlecolor ) { $titlestyle .= 'color:'. esc_attr( $featuretitlecolor ) .';';}
		if( $featuretitlesize || $featuretitlecolor ) {
			$titlestyle = 'style="'.$titlestyle.'"';
		}

		//Desc style
		if( $featuredescsize ) { $descstyle .= 'font-size:'. (int)esc_attr( $featuredescsize ) .'px;';}
		if( $featuredesccolor ) { $descstyle .= 'color:'. esc_attr( $featuredesccolor ) .';';}
		if( $featuredescsize || $featuredesccolor ) {
			$descstyle = 'style="'.$descstyle.'"';
		}

		//button style
		if( $featurebtnbgcolor ) { $btnstyle .= 'background:'. esc_attr( $featurebtnbgcolor ) .';';}
		if( $featurebtncolor ) { $btnstyle .= 'color:'. esc_attr( $featurebtncolor ) .';';}
		if( $featurebtnbgcolor || $featurebtncolor ) {
			$btnstyle = 'style="'.$btnstyle.'"';
		}

		$output .='<div class="wpmm-feature-box">';
		switch ( $layout ) {
			case 'wpmmlayout1':
				$output .='<div class="wpmm-feature-item '.esc_attr( $layout ).' '.esc_attr( $box_align) .'">';
				$output .='<div class="wpmm-feature-item">';
				if ( isset($featureicon) && !empty($featureicon) ) {
					$output .='<i class="fa '.esc_attr( $featureicon ).'" '.esc_attr( $iconstyle ).'></i>';
				}
				if (isset($featuretitle)) {
					$output .='<h4 class="wpmm-feature-title" '.esc_attr( $titlestyle ).'>'.esc_html( $featuretitle ).'</h4>';
				}
				if (isset($featuredesc)) {
					$output .='<div class="wpmm-feature-desc" '.esc_attr( $descstyle ).'>'.esc_html( $featuredesc ).'</div>';
				}
				if (isset($featurebtnurl) && !empty($featurebtnurl) ) {
					$output     .= '<a data-hover-color="'.esc_attr( $featurebtnhcolor ).'" data-hover-bg-color="'.esc_attr( $featurebtnhbgcolor ).'" class="wpmm-featurebox-hcolor wpmm-featurebox-btn '.esc_attr( $btn_size ) .'" href="'.esc_url( $featurebtnurl ).'" '.esc_attr( $btnstyle ).'>' . esc_html( $featurebtntext ) . '</a>';
				}
				$output .='</div>';
				$output .='</div>';
				break;

			case 'wpmmlayout2':
				break;

			default:

				break;
		}
		$output .='</div>';

		echo $output;
		echo $args['after_widget'];
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] 				    = strip_tags( $new_instance['title'] );
		$instance['box_align']              = strip_tags( $new_instance['box_align'] );
		$instance['box_layout'] 		    = strip_tags( $new_instance['box_layout'] );
		$instance['feature_icon_size']      = strip_tags($new_instance['feature_icon_size']);
		$instance['feature_title_size']     = strip_tags($new_instance['feature_title_size']);
		$instance['feature_title_margin']   = strip_tags($new_instance['feature_title_margin']);
		$instance['title_weight']           = strip_tags($new_instance['title_weight']);
		$instance['feature_desc_size']      = strip_tags($new_instance['feature_desc_size']);
		$instance['feature_icon'] 		    = strip_tags($new_instance['feature_icon']);
		$instance['feature_title'] 		    = strip_tags( $new_instance['feature_title'] );
		$instance['feature_desc'] 		    = esc_textarea( $new_instance['feature_desc'] );
		$instance['feature_btn_url'] 	    = strip_tags( $new_instance['feature_btn_url'] );
		$instance['feature_btn_text']       = strip_tags( $new_instance['feature_btn_text'] );
		$instance['title_text_transform']   = strip_tags( $new_instance['title_text_transform'] );
		$instance['btn_size']               = strip_tags( $new_instance['btn_size'] );
		$instance['feature_icon_color']     = strip_tags( $new_instance['feature_icon_color'] );
		$instance['feature_title_color']    = strip_tags( $new_instance['feature_title_color'] );
		$instance['feature_desc_color']     = strip_tags( $new_instance['feature_desc_color'] );
		$instance['feature_btn_color']      = strip_tags( $new_instance['feature_btn_color'] );
		$instance['feature_btn_bg_color']   = strip_tags( $new_instance['feature_btn_bg_color'] );
		$instance['feature_btn_h_color']    = strip_tags( $new_instance['feature_btn_h_color'] );
		$instance['feature_btn_h_bg_color'] = strip_tags( $new_instance['feature_btn_h_bg_color'] );

		return $instance;
	}


	function form($instance) {
		$defaults = array(
			'title' 					=> 'Title',
			'box_align'                 => 'wpmmtextleft',
			'box_layout' 				=> 'wpmmlayout1',
			'feature_icon' 				=> '',
			'feature_title' 			=> '',
			'feature_desc' 				=> '',
			'feature_btn_url' 			=> '',
			'feature_btn_text'          => '',
			'feature_icon_size'         => '40',
			'feature_title_margin'      => '14px 0px 6px 0px',
			'feature_title_size'        => '15',
			'title_weight'              => '600',
			'feature_desc_size'         => '13',
			'btn_size' 			        => 'wpmmbtnsize_m',
			'feature_icon_color' 		=> '',
			'feature_title_color' 		=> '',
			'feature_desc_color' 		=> '',
			'feature_btn_color' 		=> '',
			'feature_btn_bg_color' 		=> '',
			'feature_btn_h_color' 		=> '',
			'feature_btn_h_bg_color' 	=> ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e('Widget Title', 'wp-megamenu'); ?></label>
            <input type="text" class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'title' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'title' ) ); ?>" value="<?php esc_attr_e( $instance['title'] ); ?>" style="width:100%;" />
        </p>
        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'box_layout' ) ); ?>"><?php esc_html_e('Select Layout', 'wp-megamenu'); ?></label>
			<?php
			$options = array(
				'wpmmlayout1' 	=> 'Layout 1',
				'wpmmlayout2' 	=> 'Layout 2',
			);
			if(isset($instance['box_layout'])) $box_layout = $instance['box_layout'];
			?>
            <select class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'box_layout' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'box_layout' ) ); ?>">
				<?php
				$op = '<option value="%s"%s>%s</option>';

				foreach ($options as $key=>$value ) {

					if ($box_layout === $key) {
						printf($op, $key, ' selected="selected"', $value);
					} else {
						printf($op, $key, '', $value);
					}
				}
				?>
            </select>
        </p>
        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'box_align' ) ); ?>"><?php esc_html_e('Alignment', 'wp-megamenu'); ?></label>
			<?php
			$options = array(
				'wpmmtextleft' 		=> 'Left',
				'wpmmtextcenter' 	=> 'Center',
				'wpmmtextright'		=> 'Right',
			);
			if(isset($instance['box_align'])) $box_align = $instance['box_align'];
			?>
            <select class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'box_align' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'box_align' ) ); ?>">
				<?php
				$op = '<option value="%s"%s>%s</option>';

				foreach ($options as $key=>$value ) {

					if ($box_align === $key) {
						printf($op, $key, ' selected="selected"', $value);
					} else {
						printf($op, $key, '', $value);
					}
				}
				?>
            </select>
        </p>
        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'feature_icon' ) ); ?>"><?php esc_html_e('Select Icon', 'wp-megamenu'); ?></label>
            <select id="<?php esc_attr_e( $this->get_field_id( 'feature_icon' ) ); ?>" class="wpmm-select2" name="<?php esc_attr_e( $this->get_field_name( 'feature_icon' ) ); ?>" >
				<?php
				$font_awesome = wpmm_font_awesome();
				foreach ($font_awesome as $icon_key => $icon_value){
					echo '<option value=""> '.__('None', 'wp-megamenu').' </option>';
					echo '<option '.selected( $instance['feature_icon'], $icon_value).' value="'.esc_attr( $icon_value ).'" selec data-icon="'.esc_attr( $icon_value ).'">'.str_replace( 'fa-', '', esc_html( $icon_value ) ).'</option>';
				}
				?>
            </select>
        </p>
        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'feature_title' ) ); ?>"><?php esc_html_e('Feature Title', 'wp-megamenu'); ?></label>
            <input type="text" class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'feature_title' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'feature_title' ) ); ?>" value="<?php esc_attr_e( $instance['feature_title'] ); ?>" style="width:100%;" />
        </p>
        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'feature_desc' ) ); ?>"><?php esc_html_e('Feature Description', 'wp-megamenu'); ?></label>
            <textarea id="<?php esc_attr_e( $this->get_field_id( 'feature_desc' ) ); ?>" rows="5" name="<?php esc_attr_e( $this->get_field_name( 'feature_desc' ) ); ?>" style="width:100%;"><?php esc_html_e( $instance['feature_desc'] ); ?></textarea>
        </p>

        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'feature_btn_text' ) ); ?>"><?php esc_html_e('Button Text', 'wp-megamenu'); ?></label>
            <input type="text" class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'feature_btn_text' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'feature_btn_text' ) ); ?>" value="<?php esc_attr_e( $instance['feature_btn_text'] ); ?>" style="width:100%;" />
        </p>
        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'feature_btn_url' ) ); ?>"><?php esc_html_e('Button Link', 'wp-megamenu'); ?></label>
            <input type="text" class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'feature_btn_url' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'feature_btn_url' ) ); ?>" value="<?php esc_attr_e( $instance['feature_btn_url'] ); ?>" style="width:100%;" />
        </p>
        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'feature_style' ) ); ?>"><?php esc_html_e('Feature Style', 'wp-megamenu'); ?></label></p>
        <hr/>

        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'title_text_transform' ) ); ?>"><?php esc_html_e('Title Text Transform', 'wp-megamenu'); ?></label>
			<?php
			$options = array(
				'uppercase'   => 'UPPERCASE',
				'lowercase'   => 'lowercase',
				'capitalize'   => 'Capitalize',
			);
			$title_text_transform = '';
			if(! empty($instance['title_text_transform'])){
				$title_text_transform = $instance['title_text_transform'];
			}
			?>
            <select class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'title_text_transform' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'title_text_transform' ) ); ?>">
				<?php
				$op = '<option value="%s"%s>%s</option>';
				foreach ($options as $key => $value ) {
					if ($title_text_transform === $key) {
						printf($op, $key, ' selected="selected"', $value);
					} else {
						printf($op, $key, '', $value);
					}
				}
				?>
            </select>
        </p>

        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'btn_size' ) ); ?>"><?php esc_html_e('Button Size', 'wp-megamenu'); ?></label>
			<?php
			$options = array(
				'wpmmbtnsize_s'   => 'Small',
				'wpmmbtnsize_m'   => 'Medium',
				'wpmmbtnsize_l'   => 'Large',
			);
			if(isset($instance['btn_size'])) $btn_size = $instance['btn_size'];
			?>
            <select class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'btn_size' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'btn_size' ) ); ?>">
				<?php
				$op = '<option value="%s"%s>%s</option>';

				foreach ($options as $key=>$value ) {

					if ($btn_size === $key) {
						printf($op, $key, ' selected="selected"', $value);
					} else {
						printf($op, $key, '', $value);
					}
				}
				?>
            </select>
        </p>
        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'feature_icon_size' ) ); ?>"><?php esc_html_e('Iocn Font Size Ex. 20px', 'wp-megamenu'); ?></label>
            <input type="text" class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'feature_icon_size' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'feature_icon_size' ) ); ?>" value="<?php esc_attr_e( $instance['feature_icon_size'] ); ?>" style="width:100%;" />
        </p>
        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'feature_title_size' ) ); ?>"><?php esc_html_e('Title Font Size Ex. 18px', 'wp-megamenu'); ?></label>
            <input type="text" class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'feature_title_size' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'feature_title_size' ) ); ?>" value="<?php esc_attr_e( $instance['feature_title_size'] ); ?>" style="width:100%;" />
        </p>
        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'feature_title_margin' ) ); ?>"><?php esc_html_e('Title Margin Ex. 10px 10px 10px 10px', 'wp-megamenu'); ?></label>
            <input type="text" class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'feature_title_margin' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'feature_title_margin' ) ); ?>" value="<?php esc_attr_e( $instance['feature_title_margin'] ); ?>" style="width:100%;" />
        </p>
        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'title_weight' ) ); ?>"><?php esc_html_e('Title font weight', 'wp-megamenu'); ?></label>
			<?php
			$weightoptions = array(
				'300'   => '300',
				'400'   => '400',
				'500'   => '500',
				'600'   => '600',
				'700'   => '700',
				'800'   => '800',
			);
			if(isset($instance['title_weight'])) $title_weight = $instance['title_weight'];
			?>
            <select class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'title_weight' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'title_weight' ) ); ?>">
				<?php
				$weightop = '<option value="%s"%s>%s</option>';

				foreach ($weightoptions as $weightkey=>$fontvalue ) {

					if ($title_weight === $weightkey) {
						printf($weightop, $weightkey, ' selected="selected"', $fontvalue);
					} else {
						printf($weightop, $weightkey, '', $fontvalue);
					}
				}
				?>
            </select>
        </p>
        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'feature_desc_size' ) ); ?>"><?php esc_html_e('Description Font Size Ex. 14px', 'wp-megamenu'); ?></label>
            <input type="text" class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'feature_desc_size' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'feature_desc_size' ) ); ?>" value="<?php esc_attr_e( $instance['feature_desc_size'] ); ?>" style="width:100%;" />
        </p>
        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'feature_icon_color' ) ); ?>"><?php esc_html_e('Icon Color', 'wp-megamenu'); ?></label>
            <input type="text" id="<?php esc_attr_e( $this->get_field_id( 'feature_icon_color' ) ); ?>"  name="<?php esc_attr_e( $this->get_field_name( 'feature_icon_color' ) ); ?>" value="<?php esc_attr_e( $instance['feature_icon_color'] ); ?>" style="width:100%;"  class="wpmmColorPicker" data-alpha="true" />
        </p>
        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'feature_title_color' ) ); ?>"><?php esc_html_e('Title Color', 'wp-megamenu'); ?></label>
            <input type="text" id="<?php esc_attr_e( $this->get_field_id( 'feature_title_color' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'feature_title_color' ) ); ?>" value="<?php esc_attr_e( $instance['feature_title_color'] ); ?>" style="width:100%;"  class="wpmmColorPicker" data-alpha="true" />
        </p>
        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'feature_desc_color' ) ); ?>"><?php esc_html_e('Description Color', 'wp-megamenu'); ?></label>
            <input type="text" id="<?php esc_attr_e( $this->get_field_id( 'feature_desc_color' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'feature_desc_color' ) ); ?>" value="<?php esc_attr_e( $instance['feature_desc_color'] ); ?>" style="width:100%;"  class="wpmmColorPicker" data-alpha="true" />
        </p>
        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'feature_btn_color' ) ); ?>"><?php esc_html_e('Button Color', 'wp-megamenu'); ?></label>
            <input type="text" id="<?php esc_attr_e( $this->get_field_id( 'feature_btn_color' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'feature_btn_color' ) ); ?>" value="<?php esc_attr_e( $instance['feature_btn_color'] ); ?>" style="width:100%;"  class="wpmmColorPicker" data-alpha="true" />
        </p>
        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'feature_btn_bg_color' ) ); ?>"><?php esc_html_e('Button Background Color', 'wp-megamenu'); ?></label>
            <input type="text" id="<?php esc_attr_e( $this->get_field_id( 'feature_btn_bg_color' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'feature_btn_bg_color' ) ); ?>" value="<?php esc_attr_e( $instance['feature_btn_bg_color'] ); ?>" style="width:100%;"  class="wpmmColorPicker" data-alpha="true" />
        </p>
        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'feature_btn_h_color' ) ); ?>"><?php esc_html_e('Button Hover Color', 'wp-megamenu'); ?></label>
            <input type="text" id="<?php esc_attr_e( $this->get_field_id( 'feature_btn_h_color' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'feature_btn_h_color' ) ); ?>" value="<?php esc_attr_e( $instance['feature_btn_h_color'] ); ?>" style="width:100%;"  class="wpmmColorPicker" data-alpha="true" />
        </p>
        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'feature_btn_h_bg_color' ) ); ?>"><?php esc_html_e('Button Hover Background Color', 'wp-megamenu'); ?></label>
            <input type="text" id="<?php esc_attr_e( $this->get_field_id( 'feature_btn_h_bg_color' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'feature_btn_h_bg_color' ) ); ?>" value="<?php esc_attr_e( $instance['feature_btn_h_bg_color'] ); ?>" style="width:100%;"  class="wpmmColorPicker" data-alpha="true" />
        </p>
		<?php
	}
}
/*-----------------------------------------------------
 * 				script load
*----------------------------------------------------*/
function wpmm_featuresbox_scripts() {
	wp_enqueue_style( 'featuresbox_css', WPMM_URL .'addons/wpmm-featuresbox/wpmm-featuresbox.css', WPMM_VER, true );
	wp_enqueue_script( 'featuresbox-style', WPMM_URL .'addons/wpmm-featuresbox/wpmm-featuresbox.js', WPMM_VER, true );
}
add_action( 'wp_enqueue_scripts', 'wpmm_featuresbox_scripts' );