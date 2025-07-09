<?php

add_action('init', function () {
    add_rewrite_rule('^room-invoice/([0-9]+)(?:/)?$', 'index.php?room_invoice_id=$matches[1]', 'top');
});
add_filter('query_vars', function ($vars) {
    $vars[] = 'room_invoice_id';
    return $vars;
});
add_action('template_redirect', function () {
    $id = get_query_var('room_invoice_id');
    if ($id) {
        if (!is_user_logged_in() || !current_user_can('manage_options')) {
            wp_die('You are not allowed to view this invoice.');
        }
        room_invoice_render_public($id);
        exit;
    }
});

function room_invoice_render_public($id) {
    $post = get_post(intval($id));
    if (!$post) {
        echo '<div class="notice notice-error"><p>Invoice not found.</p></div>';
        return;
    }
    $meta = function ($key) use ($post) {
        return esc_html(get_post_meta($post->ID, $key, true));
    };
    function th_label($label)
    {
        return '<span class="th-flex"><span class="th-label-text">' . $label . '</span><span class="th-colon">:</span></span>';
    }
    $logo_url = plugins_url('assets/logo.png', dirname(__FILE__));
    $auto_print = isset($_GET['print']) && $_GET['print'] == '1';
?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Registration Card - <?= $meta('guest_name') ?></title>
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <style>
            body {
                font-family: Arial, Helvetica, sans-serif;
                background: #f5f5f5;
                margin: 0;
                padding: 0;
            }

            .reg-card-wrap {
                background: #fff;
                max-width: 900px;
                margin: 12px auto;
                padding: 10px 14px;
                box-shadow: 0 0 6px rgba(0, 0, 0, 0.06);
                position: relative;
            }

            .reg-logo {
                position: absolute;
                top: 0px;
                left: 40px;
                width: 80px;
                height: 80px;
                object-fit: contain;
                z-index: 2;
            }

            .reg-header-block {
                text-align: center;
                margin-bottom: 0.1em;
                margin-top: 0;
            }

            .reg-header-block>* {
                margin: 0;
                margin-bottom: 7px;
                line-height: 1.2;
            }

            .reg-header {
                font-size: 1.55em;
                font-weight: bold;
            }

            .reg-subheader {
                font-size: 0.95em;
                text-decoration: underline;
                text-underline-offset: 2px;
                font-weight: 600;
            }

            .reg-contact,
            .reg-address {
                font-size: 0.85em;
            }

            .reg-title {
                text-align: center;
                font-size: 1em;
                font-weight: bold;
                text-decoration: underline;
                letter-spacing: 0.3px;
            }

            .section-title {
                font-size: 0.92em;
                font-weight: bold;
                margin: 8px 0 2px 0;
                text-align: center;
                background: #f7f7f7;
                border: 1px solid #bbb;
                border-bottom: none;
                border-radius: 6px 6px 0 0;
                padding: 4px 0 2px 0;
            }

            .section-box {
                border: 1px solid #bbb;
                background: #f7f7f7;
                border-radius: 0 0 6px 6px;
                margin-bottom: 7px;
                padding: 0 0 4px 0;
            }

            table.reg-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 0;
                table-layout: fixed;
            }

            table.reg-table th,
            table.reg-table td {
                border: none;
                padding: 2px 4px;
                text-align: left;
                font-size: 0.82em;
            }

            table.reg-table th {
                background: none;
                font-weight: 550;
                color: #444;
                width: 20%;
                min-width: 70px;
            }

            .th-flex {
                display: flex;
                align-items: center;
            }

            .th-label-text {
                flex: 1 1 auto;
                min-width: 0;
            }

            .th-colon {
                flex: 0 0 18px;
                text-align: right;
                padding-left: 2px;
            }

            table.reg-table td {
                width: 30%;
                word-break: break-word;
            }

            tr.reg-row-sep td {
                border-bottom: 1px solid #bbb;
                height: 2px;
                padding: 0;
            }

            .terms {
                font-size: 0.78em;
                margin-top: 12px;
                line-height: 1.3;
            }

            .terms ul {
                margin: 0 0 0 12px;
                padding: 0;
            }

            .terms li {
                margin-bottom: 2px;
            }

            .sign-row {
                display: flex;
                justify-content: space-between;
                margin-top: 14px;
                font-size: 0.82em;
            }

            .invoice-print-btn {
                display: block;
                margin: 28px auto 0 auto;
                background: #2563eb;
                color: #fff;
                border: none;
                border-radius: 6px;
                padding: 10px 22px;
                font-size: 1.08em;
                font-weight: 600;
                box-shadow: 0 2px 8px rgba(37, 99, 235, 0.08);
                cursor: pointer;
                transition: background 0.2s, box-shadow 0.2s;
                letter-spacing: 0.5px;
                width: auto;
                min-width: 0;
            }

            .invoice-print-btn:hover,
            .invoice-print-btn:focus {
                background: #1742a0;
                box-shadow: 0 4px 16px rgba(37, 99, 235, 0.15);
            }

            @media print {
                body {
                    background: #fff !important;
                }

                .reg-card-wrap {
                    box-shadow: none;
                    margin: 0;
                }

                .invoice-print-btn,
                button {
                    display: none !important;
                }
            }
        </style>
    </head>
    <body<?= $auto_print ? ' onload="window.print()"' : '' ?>>
        <div class="reg-card-wrap">
            <img src="<?= esc_attr($logo_url) ?>" class="reg-logo" alt="Logo">
            <div class="reg-header-block">
                <div class="reg-header">The Celebration Resort</div>
                <div class="reg-subheader">(Unit of PTDP LLP)</div>
                <div class="reg-contact"><b>Contact No.</b> 9713414721, 9302375009, 7000176855</div>
                <div class="reg-address"><b>Add:</b> The Celebrations Resort Behind Maanshiva Complex, Near CI Heights, Kolar Road Bhopal</div>
                <div class="reg-title">Registration Card</div>
            </div>
            <div class="section-title">Reservation Details</div>
            <div class="section-box">
                <table class="reg-table">
                    <colgroup>
                        <col style="width:20%">
                        <col style="width:30%">
                        <col style="width:20%">
                        <col style="width:30%">
                    </colgroup>
                    <tr>
                        <th><?= th_label('Guest Name') ?></th>
                        <td><?= $meta('guest_name') ?></td>
                        <th><?= th_label('Confirmation No') ?></th>
                        <td><?= $meta('confirmation_number') ?></td>
                    </tr>
                    <tr>
                        <th><?= th_label('Company/Travel Agent') ?></th>
                        <td><?= $meta('company') ?></td>
                        <th><?= th_label('Company GST No') ?></th>
                        <td><?= $meta('company_gst') ?></td>
                    </tr>
                    <tr>
                        <th><?= th_label('Address') ?></th>
                        <td><?= $meta('address') ?></td>
                        <th><?= th_label('Arrival Time') ?></th>
                        <td><?= $meta('arrival_time') ?></td>
                    </tr>
                    <tr>
                        <th><?= th_label('Arrived From') ?></th>
                        <td><?= $meta('arrived_from') ?></td>
                        <th><?= th_label('Departure Time') ?></th>
                        <td><?= $meta('departure_time') ?></td>
                    </tr>
                    <tr>
                        <th><?= th_label('Arrival Date') ?></th>
                        <td><?= $meta('arrival_date') ?></td>
                        <th><?= th_label('Mode Of Payment') ?></th>
                        <td><?= $meta('mode_of_payment') ?></td>
                    </tr>
                    <tr>
                        <th><?= th_label('Departure Date') ?></th>
                        <td><?= $meta('departure_date') ?></td>
                        <th><?= th_label('Room Rate (Excl. Tax)') ?></th>
                        <td><?= $meta('room_rate') ?></td>
                    </tr>
                </table>
            </div>
            <div class="section-title">Guest Details</div>
            <div class="section-box">
                <table class="reg-table">
                    <colgroup>
                        <col style="width:20%">
                        <col style="width:30%">
                        <col style="width:20%">
                        <col style="width:30%">
                    </colgroup>
                    <tr>
                        <th><?= th_label('DOB') ?></th>
                        <td><?= $meta('dob') ?></td>
                        <th><?= th_label('Anniversary') ?></th>
                        <td><?= $meta('anniversary') ?></td>
                    </tr>
                    <tr>
                        <th><?= th_label('Email') ?></th>
                        <td><?= $meta('email') ?></td>
                        <th><?= th_label('Mobile No.') ?></th>
                        <td><?= $meta('mobile_no') ?></td>
                    </tr>
                    <tr>
                        <th><?= th_label('ID No.') ?></th>
                        <td><?= $meta('id_no') ?></td>
                        <th><?= th_label('ID Type') ?></th>
                        <td><?= $meta('id_type') ?></td>
                    </tr>
                    <tr>
                        <th><?= th_label('Date of ID Issue') ?></th>
                        <td><?= $meta('date_of_id_issue') ?></td>
                        <th><?= th_label('Date of ID Expiry') ?></th>
                        <td><?= $meta('date_of_id_expiry') ?></td>
                    </tr>
                    <tr>
                        <th><?= th_label('Arrival In India') ?></th>
                        <td><?= $meta('arrival_in_india') ?></td>
                        <th><?= th_label('Nationality') ?></th>
                        <td><?= $meta('nationality') ?></td>
                    </tr>
                    <tr>
                        <th><?= th_label('Visa Number') ?></th>
                        <td><?= $meta('visa_number') ?></td>
                        <th><?= th_label('Stay Duration In India') ?></th>
                        <td><?= $meta('stay_duration_in_india') ?></td>
                    </tr>
                    <tr>
                        <th><?= th_label('Date of VISA Issue') ?></th>
                        <td><?= $meta('date_of_visa_issue') ?></td>
                        <th><?= th_label('Date of VISA Expiry') ?></th>
                        <td><?= $meta('date_of_visa_expiry') ?></td>
                    </tr>
                </table>
            </div>
            <div class="section-title">Hotel Use</div>
            <div class="section-box">
                <table class="reg-table">
                    <colgroup>
                        <col style="width:20%">
                        <col style="width:30%">
                        <col style="width:20%">
                        <col style="width:30%">
                    </colgroup>
                    <tr>
                        <th><?= th_label('Room No') ?></th>
                        <td><?= $meta('room_no') ?></td>
                        <th><?= th_label('Room Type') ?></th>
                        <td><?= $meta('room_type') ?></td>
                    </tr>
                    <tr>
                        <th><?= th_label('Adults') ?></th>
                        <td><?= $meta('adults') ?></td>
                        <th><?= th_label('Children') ?></th>
                        <td><?= $meta('children') ?></td>
                    </tr>
                    <tr>
                        <th><?= th_label('Extra Bed') ?></th>
                        <td><?= $meta('extra_bed') ?></td>
                        <th><?= th_label('Billing') ?></th>
                        <td><?= $meta('billing') ?></td>
                    </tr>
                    <tr>
                        <th><?= th_label('Remarks') ?></th>
                        <td colspan="3"><?= $meta('remarks') ?></td>
                    </tr>
                </table>
            </div>
            <div class="section-title">Terms & Conditions:</div>
            <div class="terms">
                <ul>
                    <li>All disputes subject to local jurisdiction and law of the land</li>
                    <li>Check in time is 12:00 hours &amp; Check out time is 10:00 hours unless otherwise specified on your confirmation voucher</li>
                    <li>We levy Service Charge on Room tariff and on Food &amp; Beverage services</li>
                    <li>House Keeping will service your room between 09:00 hours to 18:00 hours as per policy in your absence too, unless specified by you explicitly</li>
                    <li>Safe lockers are available for valuables; hotel shall not be liable for any loss or damage of your belongings left in the room. No claims whatsoever will be entertained.</li>
                    <li>Visitors are permitted in guest rooms from 0900 hours till 20.00 hours upon producing a valid photo ID. This is at sole discretion of management. The establishment reserves the right to deny entry without any reason whatsoever</li>
                    <li>Guests will have to pay for any loss or damage to the hotel property caused by themselves, their friends or visitors or for any person for whom they are responsible. Hotel reserves the right to determine the cost to be recovered at its sole discretion</li>
                    <li>This Hotel is a private property, for which “Rights of Admission are reserved”. The management can ask any guest to vacate the room in the event of posing threat to the employee/guests or violating any policy(s) of the hotel as also without assigning any reason whatsoever</li>
                    <li>The Terms &amp; Conditions &amp; House Rules can change at any time by sole discretion of management</li>
                    <li>By signing this form, you agree to the Hotel House Rules displayed in Lobby and consenting to the usage of your personal information for administrative purpose</li>
                </ul>
            </div>
            <div class="sign-row">
                <div>Front Office</div>
                <div>Guest Signature</div>
            </div>
            <button onclick="window.print()" class="invoice-print-btn">Print Registration Card</button>
        </div>
        </body>

    </html>
<?php
}
