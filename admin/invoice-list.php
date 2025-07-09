<?php
if (!current_user_can('manage_options')) wp_die('Unauthorized');

if (isset($_POST['bulk_action']) && isset($_POST['invoice_ids']) && is_array($_POST['invoice_ids'])) {
    $action = $_POST['bulk_action'];
    $ids = array_map('intval', $_POST['invoice_ids']);
    if ($action === 'delete') {
        foreach ($ids as $id) {
            wp_delete_post($id, true);
        }
        echo '<div class="notice notice-success"><p>Selected invoices deleted.</p></div>';
    }
}

$search = isset($_GET['invoice_search']) ? sanitize_text_field($_GET['invoice_search']) : '';
$args = [
    'post_type' => 'room_invoice',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
];
if ($search) {
    $args['meta_query'] = [
        [
            'key' => 'guest_name',
            'value' => $search,
            'compare' => 'LIKE',
        ]
    ];
}
$invoices = get_posts($args);
$site_url = get_site_url();
?>
<div class="wrap">
    <h1>Room Invoice History</h1>
    <div class="room-invoice-search-box">
        <form method="get" style="margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
            <input type="hidden" name="page" value="room-invoice-list">
            <input type="text" name="invoice_search" placeholder="Search by Guest Name" value="<?= esc_attr($search) ?>">
            <button type="submit" class="button">Search</button>
            <?php if ($search): ?><a href="admin.php?page=room-invoice-list" class="button">Clear</a><?php endif; ?>
        </form>
    </div>
    <form method="post" id="bulk-invoice-form">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
            <select name="bulk_action" required>
                <option value="">Bulk Actions</option>
                <option value="delete">Delete</option>
            </select>
            <button type="submit" class="button" onclick="return confirmBulkAction();">Apply</button>
        </div>
        <table class="widefat">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all-invoices"></th>
                    <th>Guest Name</th>
                    <th>Arrival Date</th>
                    <th>Departure Date</th>
                    <th>Room No</th>
                    <th>Room Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoices as $inv):
                    $view_url = $site_url . "/room-invoice/{$inv->ID}";
                    $print_url = $view_url . "?print=1";
                ?>
                    <tr>
                        <td><input type="checkbox" name="invoice_ids[]" value="<?= $inv->ID ?>"></td>
                        <td><?= esc_html(get_post_meta($inv->ID, 'guest_name', true)) ?></td>
                        <td><?= esc_html(get_post_meta($inv->ID, 'arrival_date', true)) ?></td>
                        <td><?= esc_html(get_post_meta($inv->ID, 'departure_date', true)) ?></td>
                        <td><?= esc_html(get_post_meta($inv->ID, 'room_no', true)) ?></td>
                        <td><?= esc_html(get_post_meta($inv->ID, 'room_type', true)) ?></td>
                        <td>
                            <a href="<?= $view_url ?>" class="button" target="_blank">View</a>
                            <a href="<?= $print_url ?>" class="button" target="_blank">Print</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </form>
    <style>
        .bulk-checkbox,
        #select-all-invoices {
            width: 18px;
            height: 18px;
            vertical-align: middle;
            margin: 0 auto;
            display: block;
        }

        th:first-child,
        td:first-child {
            text-align: center;
            width: 40px;
        }

        .room-invoice-search-box {
            float: right;
            margin-bottom: 10px;
        }
    </style>
    <script>
        document.getElementById('select-all-invoices').addEventListener('change', function() {
            const checked = this.checked;
            document.querySelectorAll('input[name="invoice_ids[]"]').forEach(cb => cb.checked = checked);
        });
        document.getElementById('select-all-invoices').classList.add('bulk-checkbox');
        document.querySelectorAll('input[name="invoice_ids[]"]').forEach(cb => cb.classList.add('bulk-checkbox'));

        function confirmBulkAction() {
            const action = document.querySelector('select[name="bulk_action"]').value;
            if (action === 'delete') {
                return confirm('Are you sure you want to delete the selected invoices? This cannot be undone.');
            }
            return true;
        }
    </script>