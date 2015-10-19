<?php	
			function portfolio_custom_shortcode( $atts ) {
				ob_start();
				?>
				<?php
				// Attributes
				extract( shortcode_atts(
				array(
				'post_type' => 'portfolio',
				'category_name' => '',
				'posts_per_page' => '10',
				), $atts )
				);
		  
				// Code
				// WP_Query arguments
				$args = array (
				'post_type' => $atts['post_type'],
				'post_status' => 'publish',
				'categories' => $atts['category_name'],
				'posts_per_page' => $atts['posts_per_page'],
				);
				/*var_dump($args);*/
				// The Query
				$portfolio = new WP_Query( $args );
				
			 
			?>
			<div class='block archive'>
				<?php
					if ( $portfolio->have_posts() ) {
						while ( $portfolio->have_posts() ) {//:
						$portfolio->the_post();
					?>
					<div id='post-<?php the_ID(); ?>' <?php post_class(); ?>>
						
						<a class='portfolio-block' href='<?php the_permalink(); ?>'>
							
							<div class='thumb'>
								<?php //if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail(); ?>
								<?php //endif; ?>
							</div>
							
							<div class='portfolio-content text-left'>
								
								<div class='portfolio-title aligncenter'>
									<h2><?php the_title(); ?></h2>
								</div>
								<?php the_excerpt(); ?>
							</div>
						</a>
					</div>
					<?php 
					}
					} else {
						// no posts found
					}
					
					 wp_reset_postdata(); ?>
				<div class='clear'></div>
			</div>
			<?php
				$output_string = ob_get_contents();
				ob_end_clean();
				return $output_string;
			
			}
			add_shortcode( 'portfolio_post', 'portfolio_custom_shortcode' );
			
?>