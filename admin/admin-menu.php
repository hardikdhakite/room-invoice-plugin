<?php

add_action('admin_menu', function () {
    if (!current_user_can('manage_options')) return;

    add_menu_page(
        'Create Room Invoice',
        'Room Invoices',
        'manage_options',
        'room-invoice-add',
        'room_invoice_add_page',
        'dashicons-media-spreadsheet',
        26
    );
    add_submenu_page(
        'room-invoice-add',
        'Create Room Invoice',
        'Create Invoice',
        'manage_options',
        'room-invoice-add',
        'room_invoice_add_page'
    );
    add_submenu_page(
        'room-invoice-add',
        'Invoice History',
        'Invoice History',
        'manage_options',
        'room-invoice-list',
        'room_invoice_list_page'
    );
    add_submenu_page(
        'room-invoice-add',
        'Settings',
        'Settings',
        'manage_options',
        'room-invoice-settings',
        'room_invoice_settings_page'
    );
});

function room_invoice_list_page() {
    require_once plugin_dir_path(__FILE__) . 'invoice-list.php';
}

function room_invoice_add_page() {
    require_once plugin_dir_path(__FILE__) . 'invoice-form.php';
}

function room_invoice_settings_page() {
    require_once plugin_dir_path(__FILE__) . 'settings.php';
}
