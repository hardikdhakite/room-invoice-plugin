<?php
add_action('init', function () {
    register_post_type('room_invoice', [
        'labels' => [
            'name' => 'Room Invoices',
            'singular_name' => 'Room Invoice',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Room Invoice',
            'edit_item' => 'Edit Room Invoice',
            'new_item' => 'New Room Invoice',
            'view_item' => 'View Room Invoice',
            'search_items' => 'Search Room Invoices',
            'not_found' => 'No room invoices found',
            'not_found_in_trash' => 'No room invoices found in Trash',
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => false,
        'supports' => ['title'],
        'capability_type' => 'post',
    ]);
});
