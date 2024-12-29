<?php

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

function welcome_popup_render_admin_page() {
    // Form gönderimini işleme
    if (isset($_POST['submit_popup_settings'])) {
        update_option('welcome_popup_text', sanitize_text_field($_POST['welcome_popup_text']));
        update_option('welcome_popup_image', esc_url_raw($_POST['welcome_popup_image']));
        update_option('welcome_popup_image_width', intval($_POST['welcome_popup_image_width']));
    }

    $popup_text = get_option('welcome_popup_text', '');
    $popup_image = get_option('welcome_popup_image', '');
    $popup_image_width = get_option('welcome_popup_image_width', 300);

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
            <div class="form-actions">
                <button type="submit" name="submit_popup_settings" class="button button-primary">Save Settings</button>
            </div>
        </form>
    </div>
    <?php
}

function welcome_popup_admin_enqueue_scripts($hook) {
    if ($hook !== 'toplevel_page_welcome-popup') {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_script(
        'welcome-popup-admin-script',
        plugin_dir_url(__FILE__) . '../assets/js/admin-script.js',
        array('jquery'),
        null,
        true
    );

    wp_enqueue_style(
        'welcome-popup-admin-style',
        plugin_dir_url(__FILE__) . '../assets/css/admin-style.css'
    );
}
add_action('admin_enqueue_scripts', 'welcome_popup_admin_enqueue_scripts');
