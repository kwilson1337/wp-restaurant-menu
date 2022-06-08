jQuery( document ).ready(function() {
    //heights for fixed items
    const desktopHeader = jQuery(".bc-gc-header").outerHeight();
    const mobileHeader = jQuery(".bc-gc-mobile-nav").outerHeight();
    var $admin_bar = jQuery('#wpadminbar').outerHeight();    
       
    const stickyTopBar = document.querySelector(".bc-single-menu-top-bar.-make-sticky");    
    let headerHeight;
           
    //Top bar Sticky            
    function topBarPlacement() {   
        var navWindow = jQuery(window);
        navWindow.on("load resize", () => {
            let viewPortWidth = window.innerWidth;
            if (viewPortWidth >= 992) {
                headerHeight = desktopHeader + $admin_bar;
            } else {
                headerHeight = mobileHeader + $admin_bar;
            }            
            let windowTop = stickyTopBar.offsetTop - headerHeight;            
            navWindow.scroll(function() {              
                if (window.pageYOffset >= windowTop) {            
                    stickyTopBar.classList.add("-sticky");       
                    stickyTopBar.style.top = headerHeight + "px";        
                } else {
                    stickyTopBar.classList.remove("-sticky");                      
                    stickyTopBar.style.top = '0px';
                }
            });                                                         
        });            
    }
    if (jQuery(stickyTopBar).length > 0) {
        topBarPlacement();
    }

    const stickyDropDown = document.querySelector('.bc-menu-top-drop-down-menu.-make-sticky');    
    //Top bar drop down sticky
    function dropDownPlacement() {
        var navWindow = jQuery(window);
        navWindow.on("load resize", () => {
            let viewPortWidth = window.innerWidth;
            if (viewPortWidth >= 992) {
                headerHeight = desktopHeader + $admin_bar;
            } else {
                headerHeight = mobileHeader + $admin_bar;
            }
            
            let sticky = stickyDropDown.offsetTop - headerHeight;                                          
            navWindow.scroll(function() {              
                if (window.pageYOffset >= sticky) {            
                    stickyDropDown.classList.add("-sticky");       
                    stickyDropDown.style.top = headerHeight + "px";        
                } else {
                    stickyDropDown.classList.remove("-sticky");                      
                    stickyDropDown.style.top = '0px';
                }
            });                          
        });        
    }
    if (jQuery(stickyDropDown).length > 0) {
        dropDownPlacement();
    }    

    //JS for top bar drop down 
    var $top_btn = jQuery('#top-drop-down-menu');
    var $top_menu = jQuery('.top-drop-down-menu');

    $top_btn.click( function() {
        $top_menu.slideToggle('fast');
        
        jQuery('.top-drop-down-menu a').click( function() {
            $top_menu.slideUp('fast');
            jQuery('.start').addClass('d-none');
            jQuery('.end').html(this.innerText + ' <i class="fas fa-caret-down" aria-hidden="true"></i>');
        } );
    });
    
    //Side Bar
    var $footer_height = jQuery('footer').outerHeight();
    jQuery('.side-bar').css('max-height')
    jQuery('.side-bar').css({ 'height': 'calc(100% - ' + $footer_height+ 'px)' });

    //Bottom Menu
    var $mobile_menu_bottom = jQuery('.bc-menu-sticky-menu-bottom');
    var $bottom_button = jQuery('#bc-mobile-menu-bottom');
    var $bottom_menu = jQuery('#bottom-mobile-menu');

    $bottom_button.click(function() {
        $mobile_menu_bottom.toggleClass('-open');
        $bottom_menu.css('margin-top', '75px');
        jQuery('.end').toggleClass('-open').removeClass('-d-none');

        jQuery('.close').click( function() {
            $mobile_menu_bottom.removeClass('-open');
        } )

        jQuery('#bottom-mobile-menu a').click( function() {
            $mobile_menu_bottom.removeClass('-open');
        } );
    });

});