<?php
/*
 * Add my new menu to the Admin Control Panel
 */
// Hook the 'admin_menu' action hook, run the function named 'mfp_Add_My_Admin_Link()'
add_action( 'admin_menu','admission_Add_My_Admin_Link' );




// Add a new top level menu link to the ACP
function admission_Add_My_Admin_Link()
{
      add_menu_page(
        'NVNA Admission Admin Page', // Title of the page
        'NVNA Admission', // Text to show on the menu link
        'manage_options', // Capability requirement to see the link
        'admission-admin-page', // The 'slug' - file to display when clicking the link
        'nvna_admission_admin_page_html', // Callback функция, която ще рендерира HTML
        'dashicons-welcome-learn-more',  // Икона на менюто
    );

    // Add more submenus as needed
    add_submenu_page(
        'admission-admin-page', // Parent slug
        'Настройки', // Page title
        'Настройки', // Menu title
        'manage_options', // Capability
        'nvna-admission-settings', // Slug
        'nvna_admission_settings_page_html' // Callback function to render the HTML
    );  
}



/**
 * Callback function to render the HTML for the admin page.
 */
function nvna_admission_settings_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Include the HTML for the applicants page
    require_once plugin_dir_path(__FILE__) . 'nvna_admission_settings_page_html.php';
}

function nvna_admission_admin_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }

    require_once plugin_dir_path(__FILE__) . 'admission-admin-page.php';
}