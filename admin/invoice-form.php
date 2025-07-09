<?php
if (!current_user_can('manage_options')) wp_die('Unauthorized');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['room_invoice_nonce']) && wp_verify_nonce($_POST['room_invoice_nonce'], 'add_room_invoice')) {
    $title = sanitize_text_field($_POST['guest_name']) . ' - ' . sanitize_text_field($_POST['arrival_date']);
    $post_id = wp_insert_post([
        'post_type' => 'room_invoice',
        'post_title' => $title,
        'post_status' => 'publish',
    ]);
    if ($post_id) {
        $site_url = get_site_url();
        echo '<div class="notice notice-success"><p>Invoice created! <a href="' . $site_url . '/room-invoice/' . $post_id . '" target="_blank">View Invoice</a></p></div>';

        update_post_meta($post_id, 'guest_name', sanitize_text_field($_POST['guest_name']));
        update_post_meta($post_id, 'company', sanitize_text_field($_POST['company']));
        update_post_meta($post_id, 'address', sanitize_text_field($_POST['address']));
        update_post_meta($post_id, 'arrived_from', sanitize_text_field($_POST['arrived_from']));
        update_post_meta($post_id, 'arrival_date', sanitize_text_field($_POST['arrival_date']));
        update_post_meta($post_id, 'departure_date', sanitize_text_field($_POST['departure_date']));
        update_post_meta($post_id, 'room_rate', sanitize_text_field($_POST['room_rate']));
        update_post_meta($post_id, 'confirmation_number', sanitize_text_field($_POST['confirmation_number']));
        update_post_meta($post_id, 'company_gst', sanitize_text_field($_POST['company_gst']));
        update_post_meta($post_id, 'arrival_time', sanitize_text_field($_POST['arrival_time']));
        update_post_meta($post_id, 'departure_time', sanitize_text_field($_POST['departure_time']));
        update_post_meta($post_id, 'mode_of_payment', sanitize_text_field($_POST['mode_of_payment']));

        update_post_meta($post_id, 'dob', sanitize_text_field($_POST['dob']));
        update_post_meta($post_id, 'email', sanitize_email($_POST['email']));
        update_post_meta($post_id, 'id_no', sanitize_text_field($_POST['id_no']));
        update_post_meta($post_id, 'id_type', sanitize_text_field($_POST['id_type']));
        update_post_meta($post_id, 'date_of_id_issue', sanitize_text_field($_POST['date_of_id_issue']));
        update_post_meta($post_id, 'date_of_id_expiry', sanitize_text_field($_POST['date_of_id_expiry']));
        update_post_meta($post_id, 'arrival_in_india', sanitize_text_field($_POST['arrival_in_india']));
        update_post_meta($post_id, 'nationality', sanitize_text_field($_POST['nationality']));
        update_post_meta($post_id, 'visa_number', sanitize_text_field($_POST['visa_number']));
        update_post_meta($post_id, 'date_of_visa_issue', sanitize_text_field($_POST['date_of_visa_issue']));
        update_post_meta($post_id, 'date_of_visa_expiry', sanitize_text_field($_POST['date_of_visa_expiry']));
        update_post_meta($post_id, 'stay_duration_in_india', sanitize_text_field($_POST['stay_duration_in_india']));
        update_post_meta($post_id, 'anniversary', sanitize_text_field($_POST['anniversary']));
        update_post_meta($post_id, 'mobile_no', sanitize_text_field($_POST['mobile_no']));

        update_post_meta($post_id, 'room_no', sanitize_text_field($_POST['room_no']));
        update_post_meta($post_id, 'room_type', sanitize_text_field($_POST['room_type']));
        update_post_meta($post_id, 'adults', sanitize_text_field($_POST['adults']));
        update_post_meta($post_id, 'children', sanitize_text_field($_POST['children']));
        update_post_meta($post_id, 'extra_bed', sanitize_text_field($_POST['extra_bed']));
        update_post_meta($post_id, 'billing', sanitize_text_field($_POST['billing']));
        update_post_meta($post_id, 'remarks', sanitize_text_field($_POST['remarks']));
    }
}
?>
<div class="wrap">
    <h1>Add New Room Invoice</h1>
    <form method="post">
        <?php wp_nonce_field('add_room_invoice', 'room_invoice_nonce'); ?>
        <h2>Reservation Details</h2>
        <table class="form-table">
            <tr>
                <th>Guest Name</th>
                <td><input type="text" name="guest_name" required></td>
            </tr>
            <tr>
                <th>Company/Travel Agent</th>
                <td><input type="text" name="company"></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><input type="text" name="address"></td>
            </tr>
            <tr>
                <th>Arrived From</th>
                <td><input type="text" name="arrived_from"></td>
            </tr>
            <tr>
                <th>Arrival Date</th>
                <td><input type="date" name="arrival_date" required></td>
            </tr>
            <tr>
                <th>Departure Date</th>
                <td><input type="date" name="departure_date" required></td>
            </tr>
            <tr>
                <th>Room Rate (Excl. Tax)</th>
                <td><input type="text" name="room_rate"></td>
            </tr>
            <tr>
                <th>Confirmation Number</th>
                <td><input type="text" name="confirmation_number"></td>
            </tr>
            <tr>
                <th>Company GST No</th>
                <td><input type="text" name="company_gst"></td>
            </tr>
            <tr>
                <th>Arrival Time</th>
                <td><input type="text" name="arrival_time"></td>
            </tr>
            <tr>
                <th>Departure Time</th>
                <td><input type="text" name="departure_time"></td>
            </tr>
            <tr>
                <th>Mode Of Payment</th>
                <td><input type="text" name="mode_of_payment"></td>
            </tr>
        </table>
        <h2>Guest Details</h2>
        <table class="form-table">
            <tr>
                <th>DOB</th>
                <td><input type="date" name="dob"></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><input type="email" name="email"></td>
            </tr>
            <tr>
                <th>ID No.</th>
                <td><input type="text" name="id_no"></td>
            </tr>
            <tr>
                <th>ID Type</th>
                <td><input type="text" name="id_type"></td>
            </tr>
            <tr>
                <th>Date of ID Issue</th>
                <td><input type="date" name="date_of_id_issue"></td>
            </tr>
            <tr>
                <th>Date of ID Expiry</th>
                <td><input type="date" name="date_of_id_expiry"></td>
            </tr>
            <tr>
                <th>Arrival In India</th>
                <td><input type="text" name="arrival_in_india"></td>
            </tr>
            <tr>
                <th>Nationality</th>
                <td><input type="text" name="nationality"></td>
            </tr>
            <tr>
                <th>Visa Number</th>
                <td><input type="text" name="visa_number"></td>
            </tr>
            <tr>
                <th>Date of VISA Issue</th>
                <td><input type="date" name="date_of_visa_issue"></td>
            </tr>
            <tr>
                <th>Date of VISA Expiry</th>
                <td><input type="date" name="date_of_visa_expiry"></td>
            </tr>
            <tr>
                <th>Stay Duration In India</th>
                <td><input type="text" name="stay_duration_in_india"></td>
            </tr>
            <tr>
                <th>Anniversary</th>
                <td><input type="date" name="anniversary"></td>
            </tr>
            <tr>
                <th>Mobile No.</th>
                <td><input type="text" name="mobile_no"></td>
            </tr>
        </table>
        <h2>Hotel Use</h2>
        <table class="form-table">
            <tr>
                <th>Room No</th>
                <td><input type="text" name="room_no"></td>
            </tr>
            <tr>
                <th>Room Type</th>
                <td><input type="text" name="room_type"></td>
            </tr>
            <tr>
                <th>Adults</th>
                <td><input type="number" name="adults" min="1"></td>
            </tr>
            <tr>
                <th>Children</th>
                <td><input type="number" name="children" min="0"></td>
            </tr>
            <tr>
                <th>Extra Bed</th>
                <td><input type="text" name="extra_bed"></td>
            </tr>
            <tr>
                <th>Billing</th>
                <td><input type="text" name="billing"></td>
            </tr>
            <tr>
                <th>Remarks</th>
                <td><input type="text" name="remarks"></td>
            </tr>
        </table>
        <p><input type="submit" class="button button-primary" value="Create Invoice"></p>
    </form>
</div>