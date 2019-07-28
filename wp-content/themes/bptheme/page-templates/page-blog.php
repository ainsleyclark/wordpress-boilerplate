<?php
/*
Template Name: Blog listing
*/
get_header(); ?>

    <section class="content">
        <div class="container">
            <?php
            // WP_Query arguments
            $args = array(
                'post_type'              => array( 'post' ),
            );

            // The Query
            $query = new WP_Query( $args );

            // The Loop
            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    // do something
                }
            } else {
                // no posts found
            }

            // Restore original Post Data
            wp_reset_postdata();
            ?>
        </div>
    </section>

<?php get_footer(); ?>