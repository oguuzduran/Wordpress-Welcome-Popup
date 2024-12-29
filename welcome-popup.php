<?php
/**
 * Plugin Name: Welcome Popup
 * Description: A plugin to display a popup with an image and text on the website.
 * Version: 1.0
 * Author: Oğuz Duran
 */

// Admin panelindeki ayarlar menüsünü oluştur
function welcome_popup_add_admin_menu() {
    add_menu_page(
        'Welcome Popup Settings',
        'Popup Settings',
        'manage_options',
        'welcome-popup',
        'welcome_popup_render_admin_page',
        'dashicons-format-image',
        20
    );
}
add_action('admin_menu', 'welcome_popup_add_admin_menu');

// Admin paneli ayar sayfasını oluştur
function welcome_popup_render_admin_page() {
    if (isset($_POST['submit_popup_settings'])) {
        update_option('welcome_popup_text', sanitize_text_field($_POST['welcome_popup_text']));
        update_option('welcome_popup_image', esc_url_raw($_POST['welcome_popup_image']));
        update_option('welcome_popup_image_width', intval($_POST['welcome_popup_image_width']));
        update_option('welcome_popup_display_once', isset($_POST['welcome_popup_display_once']) ? '1' : '0');
    }

    $popup_text = get_option('welcome_popup_text', '');
    $popup_image = get_option('welcome_popup_image', '');
    $popup_image_width = get_option('welcome_popup_image_width', 300);
    $popup_display_once = get_option('welcome_popup_display_once', '0');

    ?>
    <div class="welcome-popup-settings">
        <h1>Welcome Popup Settings</h1>
        <form method="post" class="welcome-popup-form">
            <div class="form-group">
                <label for="welcome_popup_text">Popup Text</label>
                <textarea id="welcome_popup_text" name="welcome_popup_text" rows="4"><?php echo esc_textarea($popup_text); ?></textarea>
            </div>
            <div class="form-group">
                <label for="welcome_popup_image">Popup Image</label>
                <button type="button" id="upload_image_button" class="button">Select Image</button>
                <input type="hidden" id="welcome_popup_image" name="welcome_popup_image" value="<?php echo esc_attr($popup_image); ?>">
                <div id="image_preview" style="margin-top: 10px;">
                    <?php if ($popup_image): ?>
                        <img src="<?php echo esc_url($popup_image); ?>" alt="Popup Image" style="width: 300px; height: auto;">
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <label for="welcome_popup_image_width">Popup Image Width (px)</label>
                <input type="number" id="welcome_popup_image_width" name="welcome_popup_image_width" value="<?php echo esc_attr($popup_image_width); ?>" min="100" max="1000">
            </div>
            <div class="form-group">
                <label for="welcome_popup_display_once">
                    <input type="checkbox" id="welcome_popup_display_once" name="welcome_popup_display_once" value="1" <?php checked($popup_display_once, '1'); ?>>
                    Show Popup Only Once
                </label>
            </div>
            <div class="form-actions">
                <button type="submit" name="submit_popup_settings" class="button button-primary">Save Settings</button>
            </div>
        </form>
    </div>
    <?php
}


// Admin paneli için gerekli CSS ve JavaScript dosyalarını yükle
function welcome_popup_admin_enqueue_scripts($hook) {
    if ($hook !== 'toplevel_page_welcome-popup') {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_script(
        'welcome-popup-admin-script',
        plugin_dir_url(__FILE__) . 'assets/js/admin-script.js',
        array('jquery'),
        null,
        true
    );

    wp_enqueue_style(
        'welcome-popup-admin-style',
        plugin_dir_url(__FILE__) . 'assets/css/admin-style.css'
    );
}
add_action('admin_enqueue_scripts', 'welcome_popup_admin_enqueue_scripts');

// Frontend için CSS ve JavaScript dosyalarını yükle
function welcome_popup_enqueue_frontend_scripts() {
    wp_enqueue_script(
        'welcome-popup-frontend',
        plugin_dir_url(__FILE__) . 'assets/js/popup-script.js',
        array('jquery'),
        null,
        true
    );

    wp_localize_script('welcome-popup-frontend', 'welcomePopupSettings', array(
        'text' => get_option('welcome_popup_text', ''),
        'image' => get_option('welcome_popup_image', ''),
        'imageWidth' => get_option('welcome_popup_image_width', 300),
        'displayOnce' => get_option('welcome_popup_display_once', '0'),
    ));
}
add_action('wp_enqueue_scripts', 'welcome_popup_enqueue_frontend_scripts');

// Eklenti aktif hale getirildiğinde varsayılan ayarları oluştur
function welcome_popup_activate() {
    add_option('welcome_popup_text', 'Welcome to our website!');
    add_option('welcome_popup_image', '');
    add_option('welcome_popup_image_width', 300);
}
register_activation_hook(__FILE__, 'welcome_popup_activate');

// Eklenti devre dışı bırakıldığında ayarları temizleme (opsiyonel)
function welcome_popup_deactivate() {
    delete_option('welcome_popup_text');
    delete_option('welcome_popup_image');
    delete_option('welcome_popup_image_width');
}
register_deactivation_hook(__FILE__, 'welcome_popup_deactivate');
