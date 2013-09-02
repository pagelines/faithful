<?php
/*
	Section: Faithful iBox
	Author: James Giroux
	Author URI: http://jamesgiroux.ca
	Description: An easy way to create and configure several box type sections at once.
	Class Name: fthfliBox
	Filter: component
	Loading: active
*/


class fthfliBox extends PageLinesSection {

	var $default_limit = 4;

	function section_opts(){

		$options = array();

		$options[] = array(

			'title' => __( 'Faithful iBox Configuration', 'pagelines' ),
			'type'	=> 'multi',
			'opts'	=> array(
				array(
					'key'			=> 'fthflibox_count',
					'type' 			=> 'count_select',
					'count_start'	=> 1,
					'count_number'	=> 12,
					'default'		=> 4,
					'label' 	=> __( 'Number of iBoxes to Configure', 'pagelines' ),
				),
				array(
					'key'			=> 'fthflibox_cols',
					'type' 			=> 'count_select',
					'count_start'	=> 1,
					'count_number'	=> 12,
					'default'		=> '3',
					'label' 	=> __( 'Number of Columns for Each Box (12 Col Grid)', 'pagelines' ),
				),
				array(
					'key'			=> 'fthflibox_media',
					'type' 			=> 'select',
					'opts'		=> array(
						'icon'	 	=> array( 'name' => __( 'Icon Font', 'pagelines' ) ),
						'image'		=> array( 'name' => __( 'Images', 'pagelines' ) ),
						'text'		=> array( 'name' => __( 'Text Only, No Media', 'pagelines' ) )
					),
					'default'		=> 'icon',
					'label' 	=> __( 'Select iBox Media Type', 'pagelines' ),
				),
				array(
					'key'			=> 'fthflibox_format',
					'type' 			=> 'select',
					'opts'		=> array(
						'top'		=> array( 'name' => __( 'Media on Top', 'pagelines' ) ),
						'left'	 	=> array( 'name' => __( 'Media at Left', 'pagelines' ) ),
					),
					'default'		=> 'top',
					'label' 	=> __( 'Select the iBox Media Location', 'pagelines' ),
				),
			)

		);

		$slides = ($this->opt('fthflibox_count')) ? $this->opt('fthflibox_count') : $this->default_limit;
		$media = ($this->opt('fthflibox_media')) ? $this->opt('fthflibox_media') : 'icon';

		for($i = 1; $i <= $slides; $i++){

			$opts = array(

				array(
					'key'		=> 'fthflibox_title_'.$i,
					'label'		=> __( 'iBox Title', 'pagelines' ),
					'type'		=> 'text'
				),
				array(
					'key'		=> 'fthflibox_text_'.$i,
					'label'	=> __( 'iBox Text', 'pagelines' ),
					'type'	=> 'textarea'
				),
				array(
					'key'		=> 'fthflibox_link_'.$i,
					'label'		=> __( 'iBox Link (Optional)', 'pagelines' ),
					'type'		=> 'text'
				),
				array(
					'key'		=> 'fthflibox_class_'.$i,
					'label'		=> __( 'iBox Class (Optional)', 'pagelines' ),
					'type'		=> 'text'
				),
			);

			if($media == 'icon'){
				$opts[] = array(
					'key'		=> 'fthflibox_icon_'.$i,
					'label'		=> __( 'iBox Icon', 'pagelines' ),
					'type'		=> 'select_icon',
				);
			} elseif($media == 'image'){
				$opts[] = array(
					'key'		=> 'fthflibox_image_'.$i,
					'label'		=> __( 'iBox Image', 'pagelines' ),
					'type'		=> 'image_upload',
				);
			}


			$options[] = array(
				'title' 	=> __( 'Faithful iBox ', 'pagelines' ) . $i,
				'type' 		=> 'multi',
				'opts' 		=> $opts,

			);

		}

		return $options;
	}



   function section_template( ) {

		$boxes = ($this->opt('fthflibox_count')) ? $this->opt('fthflibox_count') : $this->default_limit;
		$cols = ($this->opt('fthflibox_cols')) ? $this->opt('fthflibox_cols') : 3;

		$media_type = ($this->opt('fthflibox_media')) ? $this->opt('fthflibox_media') : 'icon';
		$media_format = ($this->opt('fthflibox_format')) ? $this->opt('fthflibox_format') : 'top';

		$width = 0;
		$output = '';

		for($i = 1; $i <= $boxes; $i++):

			// TEXT
			$text = ($this->opt('fthflibox_text_'.$i)) ? $this->opt('fthflibox_text_'.$i) : '';

			$text = sprintf('<div data-sync="fthflibox_text_%s">%s</div>', $i, $text );
			$user_class = ($this->opt('fthflibox_class_'.$i)) ? $this->opt('fthflibox_class_	'.$i) : '';

			$title = ($this->opt('fthflibox_title_'.$i)) ? $this->opt('fthflibox_title_'.$i) : __('iBox '.$i, 'pagelines');
			$title = sprintf('<h4 data-sync="fthflibox_title_%s">%s</h4>', $i, $title );

			// LINK
			$link = $this->opt('fthflibox_link_'.$i);
			$text_link = ($link) ? sprintf('<div class="fthflibox-link"><a href="%s">%s <i class="icon-angle-right"></i></a></div>', $link, __('More', 'pagelines')) : '';


			$format_class = ($media_format == 'left') ? 'media left-aligned' : 'top-aligned';
			$media_class = 'media-type-'.$media_type;

			$media_bg = '';
			$media_html = '';

			if( $media_type == 'icon' ){
				$media = ($this->opt('fthflibox_icon_'.$i)) ? $this->opt('fthflibox_icon_'.$i) : false;
				if(!$media){
					$icons = pl_icon_array();
					$media = $icons[ array_rand($icons) ];
				}
				$media_html = sprintf('<i class="icon-3x icon-%s"></i>', $media);

			} elseif( $media_type == 'image' ){

				$media = ($this->opt('fthflibox_image_'.$i)) ? $this->opt('fthflibox_image_'.$i) : false;

				$media_html = '';

				$media_bg = ($media) ? sprintf('background-image: url(%s);', $media) : '';

			}
			
			$media_link = '';
			$media_link_close = '';
			
			if( $link ){
				$media_link = sprintf('<a href="%s">',$link);
				$media_link_close = '</a>';
			}

			if($width == 0)
				$output .= '<div class="row fix">';


			$output .= sprintf(
				'<div class="span%s fthflibox %s %s fix">
					<div class="fthflibox-media img">
						%s
						<span class="fthflibox-icon-border pl-animation pl-appear pl-contrast %s" style="%s">
							%s
						</span>
						%s
					</div>
					<div class="fthflibox-text bd">
						%s
						<div class="fthflibox-desc">
							%s
							%s
						</div>
					</div>
				</div>',
				$cols,
				$format_class,
				$user_class,
				$media_link,
				$media_class,
				$media_bg,
				$media_html,
				$media_link_close,
				$title,
				$text,
				$text_link
			);

			$width += $cols;

			if($width >= 12 || $i == $boxes){
				$width = 0;
				$output .= '</div>';
			}


		 endfor;

		printf('<div class="fthflibox-wrapper pl-animation-group">%s</div>', $output);

	}


}