<?php

function welcome_popup_enqueue_assets() {
    wp_enqueue_style('welcome-popup-style', plugin_dir_url(__FILE__) . '../assets/css/popup-style.css');
    wp_enqueue_script('welcome-popup-script', plugin_dir_url(__FILE__) . '../assets/js/popup-script.js', array('jquery'), null, true);

    // Popup içeriğini JavaScript'e geç
    wp_localize_script('welcome-popup-script', 'welcomePopupData', array(
        'text' => get_option('welcome_popup_text', ''),
        'image' => get_option('welcome_popup_image', '')
    ));
}
add_action('wp_enqueue_scripts', 'welcome_popup_enqueue_assets');
