<?php
/**
 * The template for displaying BC MENUS archive page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package bc-theme
 */
get_header();
?>

<section class="bc-plugins-menus-archive-page-title">
    <h1 class="h1 text-center">Our Menus</h1> 
</section>


<section class="bc-plugins-menus-archive">
    <div class="container">
        <div class="row">
            
            <?php while (have_posts()) : the_post(); ?>
            <div class="col-md-6 col-lg-3">
                <div class="bc-plugins-menus-archive__inner">
                    <h2 class="h2"><?php the_title(); ?></h2>
                    <a href="<?php the_permalink(); ?>" class="bc-button">View Menu</a>
                </div>
            </div>
            <?php endwhile; ?>

        </div>
    </div>
</section>


<?php
get_footer();
