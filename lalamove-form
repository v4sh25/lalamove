<?php
/**
 * Plugin Name: lalamove Shipping Form
 * Description: lalamove Shipping Form
 * Author: John Gerald Catague
 * Author URI:  https://www.facebook.com/hdgayt187eadszjxb/
 * Text Domain: wc_ccf
 * Domain Path: /languages/
 * Version: 1.0.0
 */

function typed_script_init()
{
    wp_enqueue_script('typedJS', 'https://code.jquery.com/jquery-3.3.1.min.js', array(
        'jquery'
    ));
}
add_action('wp_enqueue_scripts', 'typed_script_init');

function palce_google_map()
{
    wp_enqueue_script('gmap', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDtd36fKIgErB2y463Fx_ecNaOJtG-zsl4&libraries=places', array(
        'jquery'
    ));
}
add_action('wp_enqueue_scripts', 'palce_google_map');

function typed_init()
{
    echo '<script>
    jQuery(function($){
            
     });</script>';
}
add_action('wp_footer', 'typed_init');

if (!defined('ABSPATH'))
{
    exit;
}

//========================================
//
//  ##   ##  ######  ##  ##       ####
//  ##   ##    ##    ##  ##      ##
//  ##   ##    ##    ##  ##       ###
//  ##   ##    ##    ##  ##         ##
//   #####     ##    ##  ######  ####
//
//========================================

/**
 * Get form posted data from WooCommerce: in some cases, it's serialized in a "post_data" key.
 *
 * @return array
 */
function get_wc_posted_data()
{
    $form_data = $_POST;

    if (isset($_POST['post_data']))
    {
        parse_str($_POST['post_data'], $form_data);
    }

    return $form_data;
}
//==========================================================
//
//  ####    ##   ####  #####   ##        ###    ##    ##
//  ##  ##  ##  ##     ##  ##  ##       ## ##    ##  ##
//  ##  ##  ##   ###   #####   ##      ##   ##    ####
//  ##  ##  ##     ##  ##      ##      #######     ##
//  ####    ##  ####   ##      ######  ##   ##     ##
//
//==========================================================

/**
 * Display our custom extra fields: a checkbox and a select dropdown.
 *
 * @return void
 */
function display_custom_shipping_methods()
{
?>
    <fieldset class="extra-fields">
        <legend><?php _e('Lalamove Delivery Form', 'wc_ccf'); ?></legend>

       
    </fieldset>
    <div class="container text-center">
    <div class="col-md-6 offset-md-3">
            <input type="text" class="form-control"  placeholder="Enter Address" name="address" onFocus="initializeAutocomplete()" id="locality" >
            <input type="text" class="form-control" name="city" id="city" placeholder="City" value="" readonly="" >
            <input type="hidden" class="form-control" name="latitude" id="latitude" placeholder="Latitude" value="" >
            <input type="hidden" class="form-control" name="longitude" id="longitude" placeholder="Longitude" value="" >
            <input type="hidden" class="form-control" name="place_id" id="location_id" placeholder="Location Ids" value="" >


    </div>
  </div>
 <p>
    <div class="opt">

            <label for="msk-lalamove-shipping">
                <input type="checkbox" name="msk-lalamove-shipping" id="msk-lalamove-shipping" value="on" class="msk-custom-field" />
                <span><?php esc_html_e('Please confirm your location', 'wc_ccf'); ?></span>
            </label>
        </p>
        <p>
            <label for="msk-urgency-level">
             <input  name="msk-urgency-level" id="msk-urgency-level" class="msk-custom-field" type="hidden" >
            </label>
        </p>

</div>
   <style>
       /* Set the size of the div element that contains the map */
      #map {
        height: 400px;  /* The height is 400 pixels */
        width: 100%;  /* The width is the width of the web page */
       }
    </style>

    <h3>Map</h3>
    <!--The div element for the map -->
    <div id="map"></div>
<script  src="/wp-content/themes/lib/script.js"></script>
    <script>
        // When one of our custom field value changes, tell WC to update the checkout data (AJAX request to the back-end).
        jQuery(document).ready(function($) {
            $('form.checkout').on('change', '.msk-custom-field', function() {
                $('body').trigger('update_checkout');
            });
        });
    </script>
    <?php
}
add_action('woocommerce_before_order_notes', __NAMESPACE__ . '\\display_custom_shipping_methods', 10);


function add_shipping_fee($cart_object)
{
    if (is_admin() && !defined('DOING_AJAX') || !is_checkout())
    {
        return;
    }

    // Only trigger this logic once.
    

    $form_data = get_wc_posted_data();

    // Do not calculate anything if we do not have our emergency field checked or no emergency level is provided.
    if (!isset($form_data['msk-lalamove-shipping'], $form_data['msk-urgency-level'], $form_data['latitude'], $form_data['longitude'], $form_data['address']) || $form_data['msk-lalamove-shipping'] !== 'on')
    {
        return;
    }

    function getSignature($time, $body, $method, $path, $secret)
    {
        $_encryptBody = '';
        if ($method == "GET")
        {
            $_encryptBody = $time . "\r\n" . $method . "\r\n" . $path . "\r\n\r\n";
        }
        else
        {
            $_encryptBody = $time . "\r\n" . $method . "\r\n" . $path . "\r\n\r\n" . $body;
        }
        return hash_hmac("sha256", $_encryptBody, $secret);
    }

    function buildHeader($timesame, $key, $signature, $county)
    {

        return ["X-Request-ID" => uniqid() , "Content-type" => "application/json; charset=utf-8", "Authorization" => "hmac " . $key . ":" . $timesame . ":" . $signature, "Accept" => "application/json", "X-LLM-Country" => $county];
    }

    $time = time() * 1000;
    $body = array(
        "scheduleAt" => gmdate('Y-m-d\TH:i:s\Z', time() + 60 * 30) , // ISOString with the format YYYY-MM-ddTHH:mm:ss.000Z at UTC time
        "serviceType" => "MOTORCYCLE", // string to pick the available service type
        "specialRequests" => array() , // array of strings available for the service type
        "requesterContact" => array(
            "name" => "pickup name",
            "phone" => "+63xxxxxxx"
            // Phone number format must follow the format of your country
            
        ) ,
        "stops" => array(
            array(
                "location" => array(
                    "lat" => "xxxxxx",
                    "lng" => "xxxxxx"
                ) ,
                "addresses" => array(
                    "en_PH" => array(
                        "displayString" => "picup addresss",
                        "country" => "PH"
                        // Country code must follow the country you are at
                        
                    )
                )
            ) ,
            array(
                "location" => array(
                    "lat" => "" . $form_data['latitude'] . "",
                    "lng" => "" . $form_data['longitude'] . ""
                ) ,
                "addresses" => array(
                    "en_PH" => array(
                        "displayString" => "" . $form_data['address'] . "",
                        "country" => "PH"
                        // Country code must follow the country you are at
                        
                    )
                )
            )
        ) ,
        "deliveries" => array(
            array(
                "toStop" => 1,
                "toContact" => array(
                    "name" => "travis cat",
                    "phone" => "+63xxxxxx"
                    // Phone number format must follow the format of your country
                    
                ) ,
                "remarks" => "cla"
            )
        ) ,
    );

    //order
    

    $bodyjsonencode = json_encode((object)$body);

    $t = getSignature($time, $bodyjsonencode, 'POST', '/v2/quotations', '<paster you scret key here>');

    $apikey = '<paster you api key here';

    //echo $bodyjsonencode;
    

    //$key.":".$timesame.":".$signature,
    

    $all = "" . $apikey . ":" . $time . ":" . $t . "";

    $generateID = uniqid();

    $ch = curl_init('https://lalamove.com/v2/quotations');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $bodyjsonencode);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-LLM-Country: PH',
        'X-Request-ID: ' . $generateID . '',
        'content-type: application/json',
        'Accept: application/json',
        'Authorization: hmac ' . $all . ''
    ));

    $result = curl_exec($ch);

    $json = json_decode($result, true);
    $calculateFee = $json['totalFee'];

    // Add the extra fee to the user cart.
    // $test = WC()->cart->get_cart_contents_total() + $calculateFee + 50 ;
    function misha_remove_default_gateway($load_gateways)
    {

        unset($load_gateways[0]); // WC_Gateway_BACS
        unset($load_gateways[1]); // WC_Gateway_Cheque
        unset($load_gateways[2]); // WC_Gateway_COD (Cash on Delivery)
        //unset( $load_gateways[3] ); // WC_Gateway_Paypal
        return $load_gateways;
    }

    $check = WC()
        ->cart
        ->get_cart_contents_total() + $calculateFee + 50;
    WC()
        ->cart
        ->add_fee('Lalamove Shipping fee', $calculateFee, false);
    WC()
        ->cart
        ->add_fee('Purchase Service', '50', false);

    if ($check > 2000)
    {
        add_filter('woocommerce_payment_gateways', 'misha_remove_default_gateway', 10, 2);

    }
    return $available_gateways;

}
add_action('woocommerce_cart_calculate_fees', __NAMESPACE__ . '\\add_shipping_fee');

////


// Register main datetimepicker jQuery plugin script
add_action('wp_enqueue_scripts', 'enabling_date_time_picker');
function enabling_date_time_picker()
{

    // Only on front-end and checkout page
    if (is_checkout() && !is_wc_endpoint_url()):

        // Load the datetimepicker jQuery-ui plugin script
        wp_enqueue_style('datetimepicker', get_stylesheet_directory_uri() . '/assets/css/jquery.datetimepicker.min.css', array());
        wp_enqueue_script('datetimepicker', get_stylesheet_directory_uri() . '/assets/js/jquery.datetimepicker.full.min.js', array(
            'jquery'
        ) , '1.0', false);
    endif;
}

// Display custom checkout fields (+ datetime picker)
add_action('woocommerce_before_order_notes', 'display_custom_checkout_fields', 10, 1);
function display_custom_checkout_fields($checkout)
{
    // Define the time zone
    date_default_timezone_set('Europe/Paris'); // <== Set the time zone (http://php.net/manual/en/timezones.php)
    echo '<div id="my_custom_checkout_field">
    <h3>' . __('Delivery Info') . '</h3>';

    // Hide datetimepicker container field
    echo '<style> #datetimepicker_field.off { display:none; } </style>';

    // Radio buttons field: Selected option
    woocommerce_form_field('delivery_option', array(
        'type' => 'radio',
        'class' => array(
            'my-field-class form-row-wide'
        ) ,
        'options' => array(
            'asp' => __('As Soon As Possible') ,
            'date' => __('Select Delivery Date') ,
        ) ,
    ) , 'asap');

    // DateTimePicker
    woocommerce_form_field('delivery_date', array(
        'type' => 'text',
        'class' => array(
            'my-field-class form-row-wide off'
        ) ,
        'id' => 'datetimepicker',
        'required' => false,
        'label' => __('Select date') ,
        'placeholder' => __('') ,
        'options' => array(
            '' => __('', 'woocommerce')
        )
    ) , '');

    echo '</div>';
}

// The jQuery script
add_action('wp_footer', 'checkout_delivery_jquery_script');
function checkout_delivery_jquery_script()
{
    // Only on front-end and checkout page
    if (is_checkout() && !is_wc_endpoint_url()):

?>
    <script>
    jQuery(function($){
        var d = '#datetimepicker',
            f = d+'_field',
            r = 'input[name="delivery_option"]';

        $(f).hide();

        // On radio button change
        $(r).change(function(){
            if( $(this).val() == 'date' ){
                $(f).show();
            } else {
                $(f).hide();
            }
        });

        // Enable the datetime picker field
        $(d).datetimepicker({
            formatTime:"h:i a",
             step:60,
             format:"m/d/Y h:i a",
            allowTimes:[ '10:00', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00',
                '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00',
                '18:30', '19:00']
        });
    });
    </script>
    <?php
    endif;
}

// Check that the delivery date is not empty when it's selected
add_action('woocommerce_checkout_process', 'check_datetimepicker_field');
function check_datetimepicker_field()
{
    if (isset($_POST['delivery_option']) && $_POST['delivery_option'] === 'date' && isset($_POST['delivery_date']) && empty($_POST['delivery_date']))
    {

        wc_add_notice(__('Error: You must choose a delivery date and time', 'woocommerce') , 'error');
    }
}
//Check
add_action('woocommerce_checkout_process', 'check_location_field');
function check_location_field()
{

    if (empty($_POST['longitude']))
    {
        wc_add_notice(__('Error: Check Your address in lalamove From', 'woocommerce') , 'error');
    }
}

// Check that the delivery date is not empty when it's selected
add_action('woocommerce_checkout_create_order', 'save_order_delivery_data', 10, 2);
function save_order_delivery_data($order, $data)
{
    if (isset($_POST['delivery_option']) && $_POST['delivery_option'] == 'date' && !empty($_POST['delivery_date']))
    {
        $order->update_meta_data('_delivery_datetime', sanitize_text_field($_POST['delivery_date']));
    }
    if (isset($_POST['delivery_option']))
    {
        $msg = $_POST['delivery_option'];
        if ($msg == "date")
        {
            $order->update_meta_data('_delivery_option', esc_attr('Later'));
        }
        else
        {
            $order->update_meta_data('_delivery_option', esc_attr('Now'));
        }

    }

}

// View fields in Edit Order Page
add_action('woocommerce_admin_order_data_after_billing_address', 'display_custom_fields_value_admin_order', 10, 1);
function display_custom_fields_value_admin_order($order)
{
    // Display the delivery option
    if ($delivery_option = $order->get_meta('_delivery_option')) $timenow = date("m/d/y");
    echo '<p><strong>' . __('Delivery type') . ':</strong> ' . $delivery_option . '</p>';

    // Display the delivery date
    if ($delivery_datetime = $order->get_meta('_delivery_datetime'))
    {
        echo '<p><strong>' . __('Delivery Date') . ':</strong> ' . $delivery_datetime . '</p>';
    }
    else
    {
        echo '<p><strong>' . __('Delivery Date') . ':</strong> ' . $timenow . '</p>';
    }

}

// Display the chosen delivery information
add_filter('woocommerce_get_order_item_totals', 'chosen_delivery_item_order_totals', 10, 3);
function chosen_delivery_item_order_totals($total_rows, $order, $tax_display)
{;
    $new_total_rows = [];

    // Loop through Order total lines
    foreach ($total_rows as $key => $total)
    {
        // Get the chosen delivery values
        $delivery_option = $order->get_meta('_delivery_option');
        $delivery_datetime = $order->get_meta('_delivery_datetime');

        // Display delivery information before payment method
        if (!empty($delivery_option) && 'payment_method' === $key)
        {
            $label = empty($delivery_datetime) ? __('Delivery') : __('Delivery Date');
            $value = empty($delivery_datetime) ? __('Now', $domain) : $delivery_datetime;

            // Display 'Delivery method' line
            $new_total_rows['chosen_delivery'] = array(
                'label' => $label,
                'value' => $value
            );
        }
        $new_total_rows[$key] = $total;
    }

    return $new_total_rows;
}



