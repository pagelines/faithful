<?php
/*
	Section: Faithful Callout
	Author: James Giroux
	Author URI: http://jamesgiroux.ca
	Description: A quick call to action for your users
	Class Name: fthflCallout
	Filter: component
	Loading: active
*/

class fthflCallout extends PageLinesSection {

	var $tabID = 'highlight_meta';


	function section_opts(){
		$opts = array(
			array(
				'type' 			=> 'select',
				'title' 		=> 'Select Format',
				'key'			=> 'fthflcallout_format',
				'label' 		=> __( 'Callout Format', 'pagelines' ),
				'opts'=> array(
					'top'			=> array( 'name' => __( 'Text on top of button', 'pagelines' ) ),
					'inline'	 	=> array( 'name' => __( 'Text/Button Inline', 'pagelines' ) )
				),
			),
			array(
				'type' 			=> 'multi',
				'title' 		=> __( 'Callout Text', 'pagelines' ),
				'opts'	=> array(
					array(
						'key'			=> 'fthflcallout_text',
						'type' 			=> 'text',
						'label' 		=> __( 'Callout Text', 'pagelines' ),
					),

				)
			),
			array(
				'type' 			=> 'multi',
				'title' 		=> 'Link/Button',
				'opts'	=> array(

					 array(
						'key'			=> 'fthflcallout_link',
						'type' 			=> 'text',
						'label'			=> __( 'URL', 'pagelines' )
					),
					array(
						'key'			=> 'fthflcallout_link_text',
						'type' 			=> 'text',
						'label'			=> __( 'Text on Button', 'pagelines' )
					),
					array(
						'key'			=> 'fthflcallout_btn_theme',
						'type' 			=> 'select_button',
						'default'		=> 'faithful',
						'label'			=> __( 'Button Color', 'pagelines' ),
					),

				)
			)

		);

		return $opts;

	}

	function section_template() {

		$text = $this->opt('fthflcallout_text');
		$format = ( $this->opt('fthflcallout_format') ) ? 'format-'.$this->opt('fthflcallout_format') : 'format-inline';
		$link = $this->opt('fthflcallout_link');
		$theme = ($this->opt('fthflcallout_btn_theme')) ? $this->opt('fthflcallout_btn_theme') : 'btn-faithful';
		$link_text = ( $this->opt('fthflcallout_link_text') ) ? $this->opt('fthflcallout_link_text') : 'Learn More <i class="icon-angle-right"></i>';

		if(!$text && !$link){
			$text = __("Call to action!", 'pagelines');
		}

		?>
		<div class="fthflcallout-container <?php echo $format;?>">

			<h2 class="fthflcallout-head" data-sync="fthflcallout_text"><?php echo $text; ?></h2>
			<a class="fthflcallout-action btn <?php echo $theme;?> btn-large" href="<?php echo $link; ?>"  data-sync="fthflcallout_link_text"><?php echo $link_text; ?></a>

		</div>
	<?php

	}
}