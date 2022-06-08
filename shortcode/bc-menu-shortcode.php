<?php
/**
 * Display Events in Grid Form
 */

add_shortcode('bc-menus', function ($arg) {
    global $post;
    $cat = isset($arg['category']) ? $arg['category'] : 'Uncategorized';
    $post_id = isset($arg['id']) ? $arg['id'] : '';

    $clean_name = trim($cat);
    $slug_clean = strtolower(str_replace(' ', '-', $clean_name));
    $today = date('Ymd');
    $query = new WP_Query(array(
        'post_type'         => 'menus',
        'posts_per_page'    => -1,
        'page_id'           => $post_id,
        'order'             => 'desc',
        'tax_query' => array(
            array(
                'taxonomy' => 'menu_categories',
                'field'    => 'slug',
                'terms'    => $slug_clean,
            ),
        ),        
    ));        
    ob_start();
    while ($query->have_posts()) :
        $query->the_post();

    // Schema
    // checks to make sure it's not 
    // in admin panel     
    if (!is_admin()) {
        include_once(plugin_dir_path(__DIR__) . '/schema/bc-menu-schema.php');
        echo $schema -> bodyMenuSchema();
    }
    ?>

<?php if (get_field('navigation_options', $post->ID)['bottom_sticky_menu']['turn_on']) : ?>
    <?php $bottom_display = get_field('navigation_options', $post->ID)['bottom_sticky_menu']; ?>
    <section 
        class="bc-menu-sticky-menu-bottom
        <?php echo $bottom_display['desktop'] ? 'd-xl-block d-lg-block' : 'd-xl-none d-lg-none'; ?>
        <?php echo $bottom_display['tablet'] ? 'd-md-block' : 'd-md-none'; ?>
        <?php echo $bottom_display['mobile'] ? 'd-sm-block' : 'd-sm-none d-xs-none'; ?>
        ">
        <div>
            <button id="bc-mobile-menu-bottom">
                <span class="button-text-start">View Menu Categories</span>
                <span class="button-text-end">Hide Menu Categories</span>
            </button>
        </div>
        <ul id="bottom-mobile-menu">
            <li><div class="close">
                <i class="fas fa-times"></i>
            </div></li>
            <li class="header"><i class="far fa-utensils"></i> Our Menu</li>
            <?php $mobile_link = get_field('menu_group', $post->ID); ?>
            <?php if ($mobile_link): ?>
                <?php foreach ($mobile_link as $ML) : ?>
                    <li><a href="#<?php echo str_replace(' ', '', $ML['menu_group_title']); ?>"><?php echo $ML['menu_group_title']; ?></a></li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </section>
<?php endif; ?>
<!-- //Menu Bottom -->

<!-- Side Bar -->
<?php if (get_field('navigation_options', $post->ID)['side_bar']) : ?>
<div class="side-bar-container">

    <div class="side-bar-wrapper">
        <div class="side-bar">
            <ul>
                <li class="header"><i class="fal fa-utensils"></i> <?php the_title(); ?></li>
                <?php $links = get_field('menu_group', $post->ID); ?>
                <?php if ($links): ?>
                    <?php foreach ($links as $L) : ?>
                        <li><a href="#<?php echo str_replace(' ', '', $L['menu_group_title']); ?>"><?php echo $L['menu_group_title']; ?></a></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <div class="menu-container"> <!-- Container for whole menu if side bar active -->
<?php endif; ?>
<!-- //Side Bar -->

<?php
$hero = [
    'hero_bg_img'           => get_field('menu_hero_background_image', $post->ID),
    'hero_centered_img'     => get_field('menu_centered_image', $post->ID),
    'hero_title'            => get_field('menu_hero_title', $post->ID),
    'hero_sub_title'        => get_field('menu_hero_sub_title', $post->ID),
]; ?>

<!-- Title -->
<section class="bc-single-menu-hero" style="background-image:url(<?php echo $hero['hero_bg_img']['url']; ?>)">
    <div>
        <?php if ($hero['hero_centered_img']) : ?> <img src="<?php echo $hero['hero_centered_img']['url']; ?>" alt="<?php echo $hero['hero_centered_img']['alt']; ?>"> <?php endif; ?>
        <h1 class="h1"><?php if ($hero['hero_title']) : echo $hero['hero_title']; else : echo the_title(); endif; ?></h1>    
        <?php if ($hero['hero_sub_title']) : ?><p><?php echo $hero['hero_sub_title']; ?></p> <?php endif; ?>
    </div>
</section>
<!-- //Title -->

<!-- Top bar Navigation -->
<?php if (get_field('navigation_options', $post->ID)['top_bar']['turn_on']) : ?>
    <?php $top_bar_displays = get_field('navigation_options', $post->ID)['top_bar']; ?>
    <section 
        class="bc-single-menu-top-bar 
        <?php echo get_field('navigation_options', $post->ID)['top_bar']['sticky'] ? '-make-sticky' :''; ?>
        <?php echo $top_bar_displays['desktop'] ? 'd-xl-block d-lg-block' : 'd-xl-none d-lg-none'; ?>
        <?php echo $top_bar_displays['tablet'] ? 'd-md-block' : 'd-md-none'; ?>
        <?php echo $top_bar_displays['mobile'] ? 'd-sm-block' : 'd-sm-none d-xs-none'; ?>
        ">
       <div class="<?php echo get_field('menu_options', $post->ID)['full_width_menu'] ? "container-fluid" : "container"; ?>">
           <div class="bc-single-menu-top-bar__inner">
                <?php $menu_link = get_field('menu_group', $post->ID); ?>
                <?php if ($menu_link): ?>
                    <?php foreach ($menu_link as $link) : ?>
                        <a href="#<?php echo str_replace(' ', '', $link['menu_group_title']); ?>"><?php echo $link['menu_group_title']; ?></a>
                    <?php endforeach; ?>
                <?php endif; ?>
           </div>
        </div>  
    </section>
<?php endif; ?>
<!-- //Top bar Navigation -->

<!-- Drop Down top Menu -->
<?php if (get_field('navigation_options', $post->ID)['drop_down_top_menu']['turn_on']) : ?>
    <?php $top_display = get_field('navigation_options', $post->ID)['drop_down_top_menu']; ?>

    <section 
        class="bc-menu-top-drop-down-menu
        <?php echo get_field('navigation_options', $post->ID)['drop_down_top_menu']['sticky'] ? '-make-sticky' : ""; ?>
        <?php echo $top_display['desktop'] ? 'd-xl-block d-lg-block' : 'd-xl-none d-lg-none'; ?>
        <?php echo $top_display['tablet'] ? 'd-md-block' : 'd-md-none'; ?>
        <?php echo $top_display['mobile'] ? 'd-sm-block' : 'd-sm-none d-xs-none'; ?>
        ">

        <div class="bc-menu-top-drop-down-menu__inner">
            <button id="top-drop-down-menu">
                <span class="start">View Our Menu Categories <i class="fas fa-caret-down"></i></span>
                <span class="end"></span>
            </button>
        </div>

        <div class="top-drop-down-menu">
            <ul>
                <?php $mobile_link_top = get_field('menu_group', $post->ID); ?>
                <?php if ($mobile_link_top): ?>
                    <?php foreach ($mobile_link_top as $TL) : ?>
                        <li><a href="#<?php echo str_replace(' ', '', $TL['menu_group_title']); ?>"><?php echo $TL['menu_group_title']; ?></a></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>

    </section>
    
<?php endif; ?>
<!-- //Drop Down top Menu -->

<!-- Menu -->
<?php $text_center = get_field('menu_options', $post->ID)['center_align_text']; ?>
<section class="bc-single-menu">

    <div class="<?php echo get_field('menu_options', $post->ID)['full_width_menu'] ? "container-fluid" : "container"; ?>">
        <?php
        $menu_group = get_field('menu_group', $post->ID);       
        if ($menu_group) :
            foreach ($menu_group as $menu) :
        ?>

        <?php if($menu['menu_group_title']): ?>
            <div id="<?php echo str_replace(' ', '', $menu['menu_group_title']); ?>" class="bc-single-menu__title <?php echo $text_center ? "text-center" : ""; ?> <?php echo $menu['menu_group_background_image'] ? "-bg-image" : ""; ?>" style="background-image: url(<?php echo $menu['menu_group_background_image']['url'] ? $menu['menu_group_background_image']['url'] : ""; ?>) ">
                <h2 class="h2"><?php echo $menu['menu_group_title'] ? $menu['menu_group_title'] : ""; ?></h2>

                <?php if($menu['menu_group_description']): ?>
                    <div class="bc-single-menu__description <?php echo $text_center ? "text-center" : ""; ?>">
                        <p><?php echo $menu['menu_group_description']; ?></p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
                
        <div class="row bc-single-menu__row">
        <?php if ($menu['menu_item']) : ?>
            <?php foreach ($menu['menu_item'] as $menu_item) :
                    //Filters array to add the bar in between sizes and prices
                    if ($menu_item['menu_item_size_and_price']) {
                        $menu_item['menu_item_size_and_price'] = array_values(array_filter(array_map(function ($i) {
                            return array_filter($i);
                        }, $menu_item['menu_item_size_and_price'])));
                    } ?>

                    <div class="<?php if ($menu['menu_items_per_row'] === "col-lg-12") : echo 'col-lg-12'; else: echo $menu['menu_items_per_row'];
                    echo " col-md-6 col-sm-12";
                    endif; ?> bc-single-menu__col">
                        <div class="bc-single-menu__single-item">                            
                            <div <?php echo get_field('menu_options', $post->ID)['featured_image'] ? "class='d-flex flex-fill bc-single-menu__feat-img'" : false; ?>> <!-- Featured Image -->
                                <?php if ($menu_item['menu_item_featured_image'] && get_field('menu_options', $post->ID)['featured_image']) : ?>
                                    <div class="bc-single-menu__single-featured-image">
                                        <img src="<?php echo $menu_item['menu_item_featured_image']['url']; ?>">
                                    </div>
                                <?php endif; ?>                        

                                <div class="bc-single-menu__single-item-main <?php echo get_field('menu_options', $post->ID)['featured_image'] ? '-feat-img' : ""; ?>"> <!-- Menu Item -->
                                    <div class="d-flex justify-content-between">
                                        <div class="bc-single-menu__single-item-title"> <!-- Menu Item Title -->                                    
                                            <?php if ($menu_item['menu_item_title']): ?>
                                                <div>
                                                    <p>
                                                        <?php echo $menu_item['menu_item_title']; ?>
                                                        <?php    
                                                        if($menu_item['menu_item_dietary_options']):                                                     
                                                            foreach($menu_item['menu_item_dietary_options'] as $diet) {
                                                                switch($diet) {
                                                                    case 'v':
                                                                        echo '<span class="diet-options -vegan">V</span>';
                                                                    break;
                                                                    case 'vg':
                                                                        echo '<span class="diet-options -vegetarian">VG</span>';
                                                                    break;
                                                                    case 'gf': 
                                                                        echo '<span class="diet-options -gluten-free">GF</span>';
                                                                    break;                                                                
                                                                }
                                                            }
                                                        endif;
                                                        ?>
                                                    </p>
                                                </div>
                                            <?php endif; ?>
                                        </div><!-- //Menu Item Title -->

                                        <div><!-- Menu Item Price -->
                                            <div class="bc-single-menu__single-item-price">
                                                <?php 
                                                // controls if line break or not
                                                if(get_field('menu_options', $post->ID)['display_prices_with_line_break']) {
                                                    $new_line = "<br />";
                                                } else {
                                                    $new_line = " | ";
                                                }
                                                ?>
                                                <div>
                                                    <p>
                                                        <?php if ($menu_item['menu_item_size_and_price']) { ?>
                                                            <?php foreach ($menu_item['menu_item_size_and_price'] as $idx => $spec) : ?>
                                                                <?php echo $idx > 0 ? $new_line : ""; ?>                                                                                                 
                                                                <span class="item-size"><?php echo isset($spec['item_size']) && $spec['item_size'] ? $spec['item_size'] : ""; ?></span>
                                                                <span class="item-price"><?php echo isset($spec['item_price']) && $spec['item_price'] ? $spec['item_price'] : ""; ?></span>
                                                            <?php endforeach; ?>
                                                        <?php } ?>
                                                    </p>
                                                </div>

                                            </div>
                                        </div><!-- //Menu Item Price -->

                                    </div>
                                    
                                    <div class="bc-single-menu__single-item-description"> <!-- Item description -->
                                        <p><?php echo $menu_item['menu_item_description']; ?></p>
                                    </div> <!-- //Item description -->                                    
                                </div> <!-- //Menu Item-->                                                       
                            </div> <!-- /featured Image -->
                        </div>

                    </div>

            <?php endforeach;
    endif; ?>
        </div>

        <?php
        // checks to see if any menu item
        // has diet optoins    
        $has_diet = false;    
        if($menu['menu_item']) {
            $diet_opions;                  
            foreach($menu['menu_item'] as $menu_item) {
                $diet_opions[] = $menu_item['menu_item_dietary_options'];                
            }       
            $has_diet = count(array_filter($diet_opions));            
        }                     
        ?>

        <?php if($menu['warning_or_allergies'] || $has_diet): ?>          
            <div class="bc-single-menu__warning">                 
                <?php if($has_diet): ?>
                    <div class="diet-info">
                        <span class="diet-options -vegan">V</span> <span class="diet-title -vegan"> - Vegan</span>
                        <span class="diet-options -vegan">VG</span> <span class="diet-title -vegan"> - Vegetarian</span>
                        <span class="diet-options -vegan">GF</span> <span class="diet-title -vegan"> - Gluten Free</span>
                    </div>
                <?php endif; ?>

                <?php if($menu['warning_or_allergies']): ?>
                    <p><?php echo $menu['warning_or_allergies']; ?></p>
                <?php endif; ?>
            </div> <!-- // warning -->
        <?php endif; ?>

       <?php
                endforeach;
    endif; ?>
    </div>
</section>
<!-- //Menu -->

<!-- Side Bar End -->
<?php if (get_field('navigation_options', $post->ID)['side_bar']) : ?>
    </div> <!-- //Container for whole menu if side bar active -->
</div>
<?php endif;
    endwhile;
    wp_reset_postdata();

    return ob_get_clean();
});
?>