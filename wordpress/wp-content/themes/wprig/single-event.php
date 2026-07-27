<?php
/**
 * The template for displaying event post type single pages
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

get_header();

wp_rig()->print_styles( 'wp-rig-content' );

?>
    <main id="primary" class="site-main">
        <?php
        if ( have_posts() ) {
            while ( have_posts() ) {
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
                    <header class="single-header">
                        <?php
                        the_title( '<h1 class="single-title">', '</h1>' );
                        ?>
                    </header><!-- .entry-header -->

                    <div class="entry-content">
                        <?php
                        the_content();
                        ?>
                    </div><!-- .entry-content -->
                </article>
                <?php
            }
        } else {
            get_template_part( 'template-parts/content/error' );
        }
        ?>
    </main><!-- #primary -->
    <?php
get_footer();
