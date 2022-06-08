<?php

use Spatie\SchemaOrg\Schema;

class menuPluginSchema
{
    public static function restaurantName()
    {
        return get_the_title();
    }
    

    public static function pageMenu()
    {
        return get_page_link();
    }


    public static function getSections()
    {
        //Set an empty array to house all the completed Menu Group items
        $sections = [];

        // Start by getting the "Menu Groups" Repeater which includes all "Menu Group" Groups
        $menuGroups = get_field('menu_group');
               
        //Loop through the "Menu Groups" and insert the group names with their items in Spatie Schema format
        if ($menuGroups):
            foreach ($menuGroups as $group) {
                
                // Get the "Menu Group" name
                $groupName = $group['menu_group_title'];

                //Set an empty array to house all of the completed items for the Menu Group
                $menu_items = [];

                //Get the "Menu Group Items"
                $groupItems = $group['menu_item'];
                
                if ($groupItems) :
                    foreach ($groupItems as $item) {                                   

                        $offer = '';
                        if($item['menu_item_size_and_price']) {
                            $item_price = '';
                            $san_price = '';
                            foreach($item['menu_item_size_and_price'] as $price) {                               
                                if($price['item_price']) {
                                    $item_price = $price['item_price'];
                                    $san_price = filter_var($item_price, FILTER_SANITIZE_NUMBER_INT);
                                }                                                                      
                            }   
                            $offer = Schema::offer()->price($san_price)->priceCurrency('USD');
                        }                                                                     

                        if ($item['menu_item_featured_image']['url']) {
                            $menu_items[] = Schema::menuItem()->name($item['menu_item_title'])->description($item['menu_item_description'])->image($item['menu_item_featured_image']['url'])->offers($offer);
                        } else {
                            $menu_items[] = Schema::menuItem()->name($item['menu_item_title'])->description($item['menu_item_description'])->offers($offer);
                        }
                    }
                endif;
                               
                $sections[] = Schema::menuSection()->name($groupName)->hasMenuItem($menu_items);
            }

        return $sections;
        endif;       
    }
   
    public function bodyMenuSchema()
    {
        echo '<!-- SINGLE MENU SCHEMA -->';
    
        echo Schema::menu()
            ->name(menuPluginSchema::restaurantName())
            ->url(menuPluginSchema::pageMenu())
            ->mainEntityOfPage(menuPluginSchema::pageMenu())
            ->inLanguage('English')
            ->hasMenuSection(menuPluginSchema::getSections());             
    
        echo '<!-- //SINGLE MENU SCHEMA -->';
    }
}

if (class_exists('menuPluginSchema')) {
    $schema = new menuPluginSchema();
}
