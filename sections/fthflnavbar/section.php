<?php
/*
	Section: Faithful NavBar
	Author: James Giroux
	Author URI: http://jamesgiroux.ca
	Description: A responsive and sticky navigation bar for your website.
	Class Name: fthflNavBar
	Workswith: header
	Compatibility: 2.2
	Cloning: false
	Filter: nav
*/

/**
 * Main section class
 *
 * @package PageLines DMS
 * @author PageLines
 */
class fthflNavBar extends PageLinesSection {

	var $default_limit = 2;


	/**
	 * Load styles and scripts
	 */
	function section_styles() {

		wp_enqueue_script( 'fthflnavbar', $this->base_url.'/fthflnavbar.js', array( 'jquery' ), PL_CORE_VERSION, true );
	}

	function section_head() {
		?>
			<!--[if IE 8]>
				<style>
					.nav-collapse.collapse {
						height: auto;
						overflow: visible;
					}
				</style>
			<![endif]-->
		<?php
	}

	function section_opts(){

		$opts = array(


			array(
				'default' 	=> '',
				'key'		=> 'fthflnavbar_multi_option_theme',
				'type' 		=> 'multi',
				'opts'=> array(

					 array(
							'key'			=> 'fthflnavbar_theme',
							'default'		=> 'base',
							'type' 			=> 'select',
							'label' 	=> __( 'Standard NavBar - Select Theme', 'pagelines' ),
							'opts'	=> array(
								'base'			=> array( 'name'	=> __( 'Base Color', 'pagelines' ) ),
								'black-trans'	=> array( 'name'	=> __( 'Black (Default)', 'pagelines' ) ),
								'blue'			=> array( 'name'	=> __( 'Blue', 'pagelines' ) ),
								'grey'			=> array( 'name'	=> __( 'Light Grey', 'pagelines' ) ),
								'orange'		=> array( 'name'	=> __( 'Orange', 'pagelines' ) ),
								'red'			=> array( 'name'	=> __( 'Red', 'pagelines' ) ),
							),
						),
				),
				'title'					=> __( 'NavBar Theme', 'pagelines' ),
				'help'					=> __( 'The NavBar comes with several color options. Select one to automatically configure.', 'pagelines' )

			),
			array(
				'key'		=> 'fthflnavbar_multi_option_menu',
				'type' 		=> 'multi',
				'title'		=> __( 'NavBar Menu', 'pagelines' ),
				'help'		=> __( 'The NavBar uses WordPress menus. Select one for use.', 'pagelines' ),
				'opts'		=> array(
					array(
							'key'			=> 'fthflnavbar_menu' ,
							'type' 			=> 'select_menu',
							'label' 	=> __( 'Select Menu', 'pagelines' ),
						),
				),


			),
			array(
				'key'		=> 'fthflnavbar_multi_check',
				'type' 		=> 'multi',
				'title'					=> __( 'NavBar Configuration Options', 'pagelines' ),
				'opts'		=> array(
					array(
						'key'	=> 'fthflnavbar_title',
						'type' 		=> 'text',
						'label'=> __( 'NavBar Title', 'pagelines' ),
						'title'		=> __( 'NavBar Title', 'pagelines' ),
						'help'		=> __( 'Add text to the NavBar to serve as a title, but only displayed on small screens.', 'pagelines' ),
					),
					array(
						'key'			=> 'fthflnavbar_enable_hover',
						'type'			=> 'check',
						'label'			=> __( 'Activate dropdowns on hover.', 'pagelines' ),
					),

					array(
						'key'			=> 'fthflnavbar_alignment',
						'type'			=> 'check',
						'default'		=> true,
						'label'		=> __( 'Align Menu Right? (Defaults Left)', 'pagelines' ),
					),
					array(
						'key'			=> 'fthflnavbar_hidesearch',
						'type'			=> 'check',
						'label'		=> __(  'Hide Search?', 'pagelines' ),
					),
				),

			),



		);

		return $opts;

	}

	function add_settings( $settings ){

		$settings[ $this->id ] = array(
				'name' 	=> 'Faithful NavBar',
				'icon'	=> 'icon-list-alt',
				'pos'	=> 5,
				'opts' 	=> $this->fixed_options()
		);

		return $settings;
	}

	function fixed_options(){

		$fixed_opts = array(
			array(
					'key'		=> 'fthflnavbar_fixed',
					'default'	=> true,
					'version'	=> 'pro',
					'type'		=> 'check',
					'inputlabel'=> __( 'Enable Fixed Navigation Bar', 'pagelines' ),
					'title'		=> __( 'Enable Fixed Navigation Bar', 'pagelines' ),
					'help'		=> __( 'Use this feature to add the NavBar section as a fixed navigation bar on the top of your site.', 'pagelines' )
				),
			array(
					'key'		=> 'fthflnavbar_logo',
					'default'	=> PL_IMAGES.'/dms.png',
					'version'	=> 'pro',
					'type'		=> 'image_upload',
					'label'		=> __( 'NavBar Image', 'pagelines' ),
					'title'		=> __( 'NavBar Image', 'pagelines' ),
					'ref'		=> __( 'Use this feature to add the NavBar section as a fixed navigation bar on the top of your site.<br/><br/><strong>Notes:</strong> <br/>1. Only visible in Fixed Mode.<br/>2. Image Height is constricted to a maximum 29px.', 'pagelines' )
				),

		);


		$opts = $this->section_opts();

		foreach($opts as $index => &$opt){
			if($opt['type'] == 'multi'){
				foreach($opt['opts'] as $sub_index => &$sub_opt){

					if( $sub_opt['key'] == 'fthflnavbar_title' )
						unset($opt['opts'][$sub_index]);

					$sub_opt['key'] = 'fixed_'.$sub_opt['key'];

					if(isset($sub_opt['title']))
						$sub_opt['title'] = 'Fixed '.$sub_opt['title'];


				}
				unset($sub_opt);
			}

			$opt['key'] = 'fixed_'.$opt['key'];
			$opt['title'] = 'Fixed '.$opt['title'];

		}
		unset($opt);

		return array_merge($fixed_opts, $opts);

	}

	function section_persistent() {


		add_filter('pl_settings_array', array(&$this, 'add_settings'));

		$option_args = array(

			'name'		=> 'NavBar',
			'array'		=> $this->old_options(),
			'icon'		=> $this->icon,
			'position'	=> 6
		);

		pl_add_options_page( $option_args );

		//pl_global_option( array( 'menu' => 'header_and_footer', 'options' => $header_options, 'location' => 'top' ) );


		if($this->opt('fthflnavbar_fixed')){

			build_passive_section( array( 'sid' => $this->class_name ) );



			if(pl_has_editor()){

				pagelines_add_bodyclass( 'editor_fthflnavbar_fixed' );
				add_action( 'pagelines_fixed_top', array( &$this,'passive_section_template' ), 11, 2);

			} else {
				add_action( 'pagelines_before_page', create_function( '',  'echo pl_source_comment("Fixed NavBar Section");' ) );
				add_action( 'pagelines_before_page', array( &$this,'passive_section_template' ), 10, 2);
				pagelines_add_bodyclass( 'fthflnavbar_fixed' );
			}

		}



	}


	function before_section_template( $location = '' ) {

		$format = ( $location == 'passive' ) ? 'open' : 'standard';
		$this->special_classes = ( $location == 'passive' ) ? ' fixed-top' : '';
		$this->settings['format'] = $format;

	}

	/**
	* Section template.
	*/
   function section_template( $location = false ) {

	$passive = ( 'passive' == $location ) ? true : false;
	$class = array();

	// if fixed mode
	if( $passive ){

		$class[] = 'navbar-full-width';
		$content_width_class = (pl_has_editor()) ? 'pl-content boxed-wrap boxed-nobg' : 'content';
		$theme = ( $this->opt('fixed_fthflnavbar_theme' ) ) ? $this->opt( 'fixed_fthflnavbar_theme' ) : false;
		$align = ( $this->opt( 'fixed_fthflnavbar_alignment' ) ) ? $this->opt( 'fixed_fthflnavbar_alignment' ) : false;
		$hidesearch = ( $this->opt( 'fixed_fthflnavbar_hidesearch' ) ) ? $this->opt( 'fixed_fthflnavbar_hidesearch' ) : false;
		$menu = ( $this->opt( 'fixed_fthflnavbar_menu' ) ) ? $this->opt( 'fixed_fthflnavbar_menu' ) : null;
		$class[] = ( $this->opt( 'fixed_fthflnavbar_enable_hover' ) ) ? 'plnav_hover' : '';

	} else {

		$class[] = 'navbar-content-width';
		$content_width_class = '';
		$theme = ( $this->opt( 'fthflnavbar_theme' ) ) ? $this->opt( 'fthflnavbar_theme' ) : false;
		$align = ( $this->opt('fthflnavbar_alignment' ) ) ? $this->opt( 'fthflnavbar_alignment' ) : false;
		$hidesearch = ( $this->opt( 'fthflnavbar_hidesearch' ) ) ? $this->opt( 'fthflnavbar_hidesearch' ) : false;
		$menu = ( $this->opt( 'fthflnavbar_menu' ) ) ? $this->opt( 'fthflnavbar_menu' ) : null;
		$class[] = ( $this->opt( 'fthflnavbar_enable_hover' ) ) ? 'plnav_hover' : '';
	}

	$pull = ( $align ) ? 'right' : 'left';
	$align_class = sprintf( 'pull-%s', $pull );


	$class[] = ( $theme ) ? sprintf( 'pl-color-%s', $theme ) : 'pl-color-black-trans';

	$classes = join(' ', $class);

	$brand = ( $this->opt( 'fthflnavbar_logo' ) || $this->opt( 'fthflnavbar_logo' ) != '') ? sprintf( '<img src="%s" alt="%s" />', $this->opt( 'fthflnavbar_logo' ), esc_attr( get_bloginfo('name') ) ) : sprintf( '<h2 class="plbrand-text">%s</h2>', get_bloginfo( 'name' ) );
    $navbartitle = $this->opt( 'fthflnavbar_title' );

	?>
	<div class="navbar fix <?php echo $classes; ?>">
	  <div class="navbar-inner <?php echo $content_width_class;?>">
	    <div class="navbar-content-pad fix">
	    	<?php
				if( current_user_can( 'edit_theme_options' ) && $passive )
					echo pl_dms_settings_link('settings', 'navbar', 'btn btn-edit btn-mini navbar-edit');
		
	   			if($navbartitle)
					printf( '<span class="navbar-title">%s</span>',$navbartitle );
			?>
	      <a href="javascript:void(0)" class="nav-btn nav-btn-navbar mm-toggle">
	        <?php _e('MENU', 'pagelines'); ?> <i class="icon-reorder"></i>
	      </a>
			<?php if($passive):
				printf( '<a class="plbrand" href="%s" title="%s">%s</a>',
					esc_url( home_url() ),
					esc_attr( get_bloginfo('name') ),
					apply_filters('fthflnavbar_brand', $brand)
				 );
			endif;
				pagelines_register_hook('pagelines_fthflnavbar_before_menu');
				?>
	      		<div class="nav-collapse collapse">
	       <?php 	if( !$hidesearch ) {
	       				pagelines_register_hook('pagelines_fthflnavbar_before_search');
						get_search_form();
						pagelines_register_hook('pagelines_fthflnavbar_after_search');

					}

					if ( is_array( wp_get_nav_menu_items( $menu ) ) || has_nav_menu( 'primary' ) ) {
					wp_nav_menu(
						array(
							'menu_class'		=> 'font-sub navline pldrop ' . $align_class,
							'menu'				=> $menu,
							'container'			=> null,
							'container_class'	=> '',
							'depth'				=> 3,
							'fallback_cb'		=> ''
						)
					);
					} else {
						pl_nav_fallback( 'navline pldrop '.$align_class );
					}
	?>
				</div>
				<?php
				pagelines_register_hook('pagelines_fthflnavbar_after_menu');
				?>
				<div class="clear"></div>
			</div>
		</div>
	</div>
<?php }


	function old_options(){
			$options = array(
				'fthflnavbar_fixed' => array(
						'default'	=> false,
						'version'	=> 'pro',
						'type'		=> 'check',
						'inputlabel'=> __( 'Enable Fixed Navigation Bar', 'pagelines' ),
						'title'		=> __( 'Enable Fixed Navigation Bar', 'pagelines' ),
						'shortexp'	=> __( 'Applies a fixed navigation bar to the top of your site', 'pagelines' ),
						'exp'		=> __( 'Use this feature to add the NavBar section as a fixed navigation bar on the top of your site.', 'pagelines' )
					),
				'fthflnavbar_logo' => array(
						'default'	=> $this->base_url.'/logo.png',
						'version'	=> 'pro',
						'type'		=> 'image_upload',
						'inputlabel'=> __( 'Fixed NavBar Logo', 'pagelines' ),
						'title'		=> __( 'Fixed NavBar - NavBar Logo', 'pagelines' ),
						'shortexp'	=> __( 'Applies a fixed navigation bar to the top of your site', 'pagelines' ),
						'exp'		=> __( 'Use this feature to add the NavBar section as a fixed navigation bar on the top of your site.<br/><br/><strong>Notes:</strong> <br/>1. Only visible in Fixed Mode.<br/>2. Image Height is constricted to a maximum 29px.', 'pagelines' )
					),

				'fthflnavbar_multi_option_theme' => array(
					'default' => '',
					'type' => 'multi_option',
					'selectvalues'=> array(

						'fixed_fthflnavbar_theme' => array(
								'default'		=> 'black-trans',
								'type' 			=> 'select',
								'inputlabel' 	=> __( 'Fixed NavBar - Select Theme', 'pagelines' ),
								'selectvalues'	=> array(
									'black-trans'	=> array( 'name'	=> __( 'Black (Default)', 'pagelines' ) ),
									'blue'			=> array( 'name'	=> __( 'Blue', 'pagelines' ) ),
									'grey'			=> array( 'name'	=> __( 'Light Grey', 'pagelines' ) ),
									'orange'		=> array( 'name'	=> __( 'Orange', 'pagelines' ) ),
									'red'			=> array( 'name'	=> __( 'Red', 'pagelines' ) ),
								),
							),
						'fthflnavbar_theme' => array(
								'default'		=> 'black-trans',
								'type' 			=> 'select',
								'inputlabel' 	=> __( 'Standard NavBar - Select Theme', 'pagelines' ),
								'selectvalues'	=> array(
									'black-trans'	=> array( 'name'	=> __( 'Black (Default)', 'pagelines' ) ),
									'blue'			=> array( 'name'	=> __( 'Blue', 'pagelines' ) ),
									'grey'			=> array( 'name'	=> __( 'Light Grey', 'pagelines' ) ),
									'orange'		=> array( 'name'	=> __( 'Orange', 'pagelines' ) ),
									'red'			=> array( 'name'	=> __( 'Red', 'pagelines' ) ),
								),
							),
					),
					'title'					=> __( 'NavBar and Fixed NavBar Theme', 'pagelines' ),
					'shortexp'				=> __( 'Select the color and theme of the NavBar', 'pagelines' ),
					'exp'					=> __( 'The NavBar comes with several color options. Select one to automatically configure.', 'pagelines' )

				),
				'fthflnavbar_multi_option_menu' => array(
					'default' => '',
					'type' => 'multi_option',
					'selectvalues'=> array(

						'fixed_fthflnavbar_menu' => array(
								'default'		=> 'black-trans',
								'type' 			=> 'select_menu',
								'inputlabel' 	=> __( 'Fixed NavBar - Select Menu', 'pagelines' ),
							),
						'fthflnavbar_menu' => array(
								'default'		=> 'black-trans',
								'type' 			=> 'select_menu',
								'inputlabel' 	=> __( 'Standard NavBar - Select Menu', 'pagelines' ),
							),
					),
					'title'					=> __( 'NavBar and Fixed NavBar Menu', 'pagelines' ),
					'shortexp'				=> __( 'Select the WordPress Menu for the NavBar(s)', 'pagelines' ),
					'exp'					=> __( 'The NavBar uses WordPress menus. Select one for use.', 'pagelines' )

				),

				'fthflnavbar_multi_check' => array(
					'default' => '',
					'type' => 'check_multi',
					'selectvalues'=> array(

						'fthflnavbar_enable_hover'		=>	array(
							'inputlabel'			=> __( 'Activate dropdowns on hover.', 'pagelines' ),
						),

						'fixed_fthflnavbar_alignment'	=> array(
								'inputlabel'		=> __( 'Fixed NavBar - Align Menu Right? (Defaults Left)', 'pagelines' ),
							),
						'fixed_fthflnavbar_hidesearch'	=> array(
								'inputlabel'		=> __( 'Fixed NavBar - Hide Searchform?', 'pagelines' ),
							),
						'fthflnavbar_alignment'			=> array(
								'inputlabel'		=> __( 'Standard NavBar - Align Menu Right? (Defaults Left)', 'pagelines' ),
							),
						'fthflnavbar_hidesearch'			=> array(
								'inputlabel'		=> __(  'Standard NavBar - Hide Searchform?', 'pagelines' ),
							),
					),
					'inputlabel'			=> __( 'Configure Options for NavBars', 'pagelines' ),
					'title'					=> __( 'NavBar and Fixed NavBar Configuration Options', 'pagelines' ),
					'shortexp'				=> __( 'Control various appearance options for the NavBars', 'pagelines' ),
					'exp'					=> ''
				),
				'fthflnavbar_title' => array(
						'type' 		=> 'text',
						'inputlabel'=> __( 'NavBar Title', 'pagelines' ),
						'title'		=> __( 'NavBar Title', 'pagelines' ),
						'shortexp'	=> __( 'Applies text to NavBar on small screens. Not available on Fixed NavBar', 'pagelines' ),
						'exp'		=> __( 'Add text to the NavBar to serve as a title, but only displayed on small screens.', 'pagelines' ),
				),


			);

			return $options;
	}
}
