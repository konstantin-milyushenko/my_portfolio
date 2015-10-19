<?php
/*
Plugin Name: My_portfolio
Plugin URI: garmoniagroup.ru
Description: Плагин портфолио (проектов) в виде custom_post_type
Author: Konstantin Milyushenko
Version: 1.0
Author URI: garmoniagroup.ru
Copyright: 2015

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/* Disallow direct access to the plugin file */
if (basename($_SERVER['PHP_SELF']) == basename (__FILE__)) {
	die('Sorry, but you cannot access this page directly.');
}

if(!class_exists("My_portfolio")){

	class My_portfolio{

		function __construct(){
			/**/
			// Register Custom Post Type
			function portfolio_post_type() {
				
				$labels = array(
				'name'                => _x( 'Проекты', 'Post Type General Name', 'garmoniagroup.ru' ),
				'singular_name'       => _x( 'Проект', 'Post Type Singular Name', 'garmoniagroup.ru' ),
				'menu_name'           => __( 'Портфолио', 'garmoniagroup.ru' ),
				'parent_item_colon'   => __( 'Проекты:', 'garmoniagroup.ru' ),
				'all_items'           => __( 'Все проекты', 'garmoniagroup.ru' ),
				'view_item'           => __( 'Посмотреть проект', 'garmoniagroup.ru' ),
				'add_new_item'        => __( 'Добавить новый проект', 'garmoniagroup.ru' ),
				'add_new'             => __( 'добавить новый', 'garmoniagroup.ru' ),
				'edit_item'           => __( 'Редактировать проект', 'garmoniagroup.ru' ),
				'update_item'         => __( 'Обновить проект', 'garmoniagroup.ru' ),
				'search_items'        => __( 'Поиск проекта', 'garmoniagroup.ru' ),
				'not_found'           => __( 'Проект не найден', 'garmoniagroup.ru' ),
				'not_found_in_trash'  => __( 'Проект не найден в корзине', 'garmoniagroup.ru' ),
				);
				$args = array(
				'label'               => __( 'portfolio', 'garmoniagroup.ru' ),
				'description'         => __( 'Портфолио, проекты', 'garmoniagroup.ru' ),
				'labels'              => $labels,
				'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions', 'custom-fields', 'post-formats' ),
				'taxonomies'          => array( 'categories', 'post_tag' ),
				'has_archive' 		  => true,
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => 10,
				'menu_icon'			  => 'dashicons-portfolio',
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => 'post',
				);
				register_post_type( 'portfolio', $args );
				
			}
			
			add_action( 'init', 'portfolio_post_type', 0 );

			function portfolio_taxonomy() {
				
				$labels = array(
				'name'                       => _x( 'Категории проекта', 'Taxonomy General Name', 'garmoniagroup.ru' ),
				'singular_name'              => _x( 'Категория проекта', 'Taxonomy Singular Name', 'garmoniagroup.ru' ),
				'menu_name'                  => __( 'Категории проектов', 'garmoniagroup.ru' ),
				'all_items'                  => __( 'Все категории', 'garmoniagroup.ru' ),
				'parent_item'                => __( 'Категория проекта', 'garmoniagroup.ru' ),
				'parent_item_colon'          => __( 'Категория проекта:', 'garmoniagroup.ru' ),
				'new_item_name'              => __( 'Новая категория проекта', 'garmoniagroup.ru' ),
				'add_new_item'               => __( 'Добавить новую категорию проекта', 'garmoniagroup.ru' ),
				'edit_item'                  => __( 'Редактировать категорию проекта', 'garmoniagroup.ru' ),
				'update_item'                => __( 'Обновить категорию проекта', 'garmoniagroup.ru' ),
				'separate_items_with_commas' => __( 'Сайт,логотип,верстка', 'garmoniagroup.ru' ),
				'search_items'               => __( 'Поиск категории проекта', 'garmoniagroup.ru' ),
				'add_or_remove_items'        => __( 'Добавить или удалить категорию проекта', 'garmoniagroup.ru' ),
				'choose_from_most_used'      => __( 'Выберите из наиболее используемых категорий проекта', 'garmoniagroup.ru' ),
				'not_found'                  => __( 'Не найдено категории проекта', 'garmoniagroup.ru' ),
				);
				$args = array(
				'labels'                     => $labels,
				'hierarchical'               => true,
				'public'                     => true,
				'show_ui'                    => true,
				'show_admin_column'          => true,
				'show_in_nav_menus'          => true,
				'show_tagcloud'              => true,
				);
				register_taxonomy( 'categories', array( 'portfolio' ), $args );
				
			}
			
			add_action( 'init', 'portfolio_taxonomy', 0 );
			
			function default_product_title( $title ){
				$screen = get_current_screen();
				if  ( $screen->post_type == 'portfolio' ) {
					return 'Введите название проекта';
					}else{
					return 'Введите заголовок';
				}
				
			}
			add_filter( 'enter_title_here', 'default_product_title' );
			
			function udalit_attributi_shirini_i_visoti($content) {
				return preg_replace('/(height|width)="\d*"\s/', "", $content);
			}
			add_filter('post_thumbnail_html', 'udalit_attributi_shirini_i_visoti');
			add_filter('image_send_to_editor', 'udalit_attributi_shirini_i_visoti');
			
			add_filter('the_content', 'my_addfancybox');
			function my_addfancybox($content) {
				global $post;
				$pattern ="/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
				$replacement = '<a$1 class="fancybox" href=$2$3.$4$5 title="'.$post->post_title.'"$6>';
				$content = preg_replace($pattern, $replacement, $content);
				return $content;
			}
			
			add_filter('template_include', 'include_template_function', 99);
			function include_template_function($template_path)
			{
				if (get_post_type() == 'portfolio') {
					if (is_single()) {
						$template_path = plugin_dir_path(__FILE__) . '/single-portfolio.php';
                    }
					if (is_archive()){
						$template_path = plugin_dir_path(__FILE__) . '/archive-portfolio.php';
					}
                }
				return $template_path;
			}
		 include plugin_dir_path(__FILE__) . '/post_shortcode.php';

			add_action("admin_menu", array(&$this,"My_portfolio_plugin_menu"));
			add_action("wp_print_scripts", array(&$this,"wppg_user_script"));
			add_action("wp_print_styles", array(&$this,"wppg_user_stylesheet"));
		}
		function My_portfolio(){
			$this->__construct();
		}
		function My_portfolio_plugin_menu() {
			//add_options_page('Портфолио', 'Портфолио', 'administrator', __FILE__, array(&$this,'My_portfolio_options_page'));
		}
		function My_portfolio_options_page() {
			echo "<h1>Настройки портфолио</h1>";
			echo 'Короткий код для выборки из портфолио: <br />[portfolio_post post_type="portfolio" category_name="sayty" posts_per_page="1"]';
		}
		function wppg_admin_script(){
			wp_register_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js', false );
			wp_enqueue_script( 'jquery' );
			wp_register_script( 'wppg_bxslider_js', plugins_url('/admin_js/bxslider.js', __FILE__) );
			wp_enqueue_script(array("wppg_bxslider_js"));
			wp_register_script( 'wppg_jquery_fancybox', plugins_url('/admin_js/jquery.fancybox-1.3.4.pack.js', __FILE__) );
			wp_enqueue_script(array("wppg_jquery_fancybox"));
			wp_register_script( 'wppg_mousewheel_js', plugins_url('/admin_js/jquery.mousewheel-3.0.4.pack.js', __FILE__) );
			wp_enqueue_script(array("wppg_mousewheel_js"));
			wp_register_script( 'wppg_portfolio_js', plugins_url('/admin_js/portfolio_js.js', __FILE__) );
			wp_enqueue_script(array("wppg_portfolio_js"));
		}
		function wppg_admin_stylesheet(){
			wp_register_style( 'wppg_portfolio-style_css', plugins_url('/admin_css/portfolio-style.css', __FILE__) );
			wp_enqueue_style(array("wppg_portfolio-style_css"));
			wp_register_style( 'wppg_portfolio-fancybox_css', plugins_url('/admin_css/jquery.fancybox-1.3.4.css', __FILE__) );
			wp_enqueue_style(array("wppg_portfolio-fancybox_css"));
		}
		function wppg_user_script(){
			wp_register_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js', false );
			wp_enqueue_script( 'jquery' );
			wp_register_script( 'wppg_bxslider_js', plugins_url('/user_js/bxslider.js', __FILE__) );
			wp_enqueue_script(array("wppg_bxslider_js"));
			wp_register_script( 'wppg_jquery_fancybox', plugins_url('/user_js/jquery.fancybox-1.3.4.pack.js', __FILE__) );
			wp_enqueue_script(array("wppg_jquery_fancybox"));
			wp_register_script( 'wppg_mousewheel_js', plugins_url('/user_js/jquery.mousewheel-3.0.4.pack.js', __FILE__) );
			wp_enqueue_script(array("wppg_mousewheel_js"));
			wp_register_script( 'wppg_portfolio_js', plugins_url('/user_js/portfolio_js.js', __FILE__) );
			wp_enqueue_script(array("wppg_portfolio_js"));
		}
		function wppg_user_stylesheet(){
			wp_register_style( 'wppg_portfolio-style_css', plugins_url('/user_css/portfolio-style.css', __FILE__) );
			wp_enqueue_style(array("wppg_portfolio-style_css"));
			wp_register_style( 'wppg_portfolio-fancybox_css', plugins_url('/user_css/jquery.fancybox-1.3.4.css', __FILE__) );
			wp_enqueue_style(array("wppg_portfolio-fancybox_css"));
		}
		function install() {
			// do not generate any output here
		}
		function My_portfolio_deactivate() {
			// do not generate any output here
		}

	} //End Class My_portfolio
} // end if

if(!isset($wp_My_portfolio)){
	$wp_My_portfolio = new My_portfolio();
}

if (isset($wp_My_portfolio)) {
	//Actions
	register_activation_hook( __FILE__, array(&$wp_My_portfolio, 'install') );
	register_deactivation_hook( __FILE__, array(&$wp_My_portfolio, 'My_portfolio_deactivate') );
}
?>
