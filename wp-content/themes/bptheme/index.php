<?php get_header(); ?>
    <section class="content" style="margin-top: 30px;">
        <div class="container">
            <?php
            if (have_posts()) :
                while (have_posts()) :
                    the_post();
                    echo '<h1 class="page_title">' . get_the_title() . '</h1>';
                    the_content();
                endwhile;
            endif;
            ?>
        </div>
        <div class="clearfix"></div>
    </section>
<?php get_footer(); ?>