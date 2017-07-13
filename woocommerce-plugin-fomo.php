<?php
/*
Plugin Name: Fomo for WooCommerce
Plugin URI: https://www.usefomo.com
Description: Fomo displays recent orders on your WooCommerce storefront
Version: 1.0.13
Author: fomo
Author URI: https://www.usefomo.com
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {


    $fomofwc_domain = 'https://notifyapp.io';
    global $fomofwc_version;
    $fomofwc_version = '1.0.13';
    $fomofwc_public_key = "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwKXALE9j5lHEVt6Yj1hK
XD05Gc9CT7YnuiCf3945tuHnUMV9x/HAdI0H+q/sboJQxYneOYNyjABXukKGSe7t
yUnHt+iz0H6Cn1GU6jex4n7WIBSISkTHlzrIq+nQ8ZMKcB/k52ePVjH4+0neItng
JudT+/8a3Ua3yAeLCFx3FQEAkzSGGNpI/DV8stSmidk+NJT0MKzw7AFg6P8/wXqV
VqfX84J7gFc4HY9fCOZhDlejqcVMWRUlDXZq97/9L+7P+bx8o4voIgf6dsjJz08Y
6yze1fZxYlrVx7u+MZ3Pg3I7p91ekRfiwGVKaqOohD3WivBdhHyXKNwBO0TsZjcV
BwIDAQAB
-----END PUBLIC KEY-----";

    function fomofwc_enqueue_script()
    {
        global $fomofwc_domain;
        wp_enqueue_script('fomo', $fomofwc_domain . '/js/woocommerce_bootstrap.js', false);
    }

    add_action('wp_enqueue_scripts', 'fomofwc_enqueue_script');

    function fomofwc_set_and_get_key()
    {
        $key = get_option('fomofwc_key');
        if ($key == null || $key === false) {
            $key = hash('sha256', time() . uniqid());
            add_option('fomofwc_key', $key);
        }
        return $key;
    }

    register_activation_hook(__FILE__, 'fomofwc_set_and_get_key');

    function fomofwc_menu()
    {
        add_options_page('Fomo', 'Fomo', 'manage_options', 'fomo', 'fomofwc_plugin_options');
    }

    add_action('admin_menu', 'fomofwc_menu');

    function fomofwc_plugin_options()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        $key = fomofwc_set_and_get_key();
        ?>
        <h1 style="text-align: center;">Fomo for WooCommerce</h1>
        <div>
            <p>Fomo displays recent orders on your WooCommerce storefront.</p>

            <p>It's the online equivalent of a busy store, showing prospective customers that other people are buying
                your products.
                This has been proven to dramatically increase purchase conversions and create a sense of urgency for
                your website
                visitors.</p>

            <p>You know how seeing a packed restaurant makes you want to eat there? Fomo creates the same dynamic on
                your WooCommerce
                store.</p>

            <p>There's a reason Fomo is one of the most popular e-commerce apps for increasing sales and conversions.
                Here's why:
            </p>
            <ul style="list-style: disc; padding-left: 30px;">
                <li>Highly customizable: you can choose to display every order, some orders, or only orders from a
                    certain time period.
                    This means that Fomo will work for you, even if you don't have many orders!
                </li>
                <li>Store owners can position Fomo in any corner (bottom left, bottom right, top left, top right) -
                    whatever works best
                    for your store!
                </li>
                <li>FAST. Fomo is lightweight and built to scale, so it won't slow down your site (which can hurt your
                    Google rankings)
                </li>
                <li>Fully customizable CSS animations and colors</li>
                <li>Works on mobile! Choose how your notifications are displayed on mobile, or turn them off for mobile
                    devices
                </li>
                <li>Randomize notification delays for a more life-like experience.</li>
                <li>Hide Fomo from specific pages</li>
                <li>Remove individual notifications</li>
                <li>See users interact with your notifications in real time via our live notification list</li>
                <li>Store still young but growing? No worries - you can loop notifications and even disable the "time
                    ago" notification!
                </li>
                <li>Customize the message, style, position and timing to suit your branding</li>
            </ul>
            <?php
            if (fomofwc_check(home_url(), $key)['success']) {
                global $fomofwc_domain;
                $time = time();
                $url = $fomofwc_domain . '/auth?shop=' . urlencode(home_url()) . '&shop_type=woocommerce' .
                    '&timestamp=' . $time . '&hmac=' . hash_hmac('sha256', home_url() . 'woocommerce' . $time, $key);
                ?>
                <p style="text-align: center;">
                    <button style="text-align: center; font-weight: bold; font-size: 22px;"
                            onclick="window.open('<?php echo $url; ?>');"><?php _e('Go to Fomo Administration', 'woocommerce-plugin-fomo'); ?>
                    </button>
                </p>
                <?php
            } else {
                // show authorization button
                if (!class_exists('Crypt_RSA')) {
                    include_once(dirname(__FILE__) . '/phpseclib/Crypt/RSA.php');
                }
                $rsa = new Crypt_RSA();
                global $fomofwc_public_key;
                $rsa->loadKey($fomofwc_public_key);
                $payload = [
                    'url' => home_url(),
                    'key' => $key,
                    'email' => get_option('admin_email'),
                    'name' => get_option('blogname')
                ];
                $encrypted_payload = base64_encode($rsa->encrypt(json_encode($payload)));
                global $fomofwc_domain;
                $url = $fomofwc_domain . '/woocommerce_init?data=' . urlencode($encrypted_payload);
                ?>
                <p style="text-align: center;">
                    <button style="text-align: center; font-weight: bold; font-size: 22px;"
                            onclick="window.location.href='<?php echo $url; ?>';"><?php _e('Authorize your store with Fomo', 'woocommerce-plugin-fomo'); ?>
                    </button>
                </p>
                <?php
            }
            ?>
        </div>
        <?php
    }

    function fomofwc_rest_auth($request)
    {
        $data = json_decode($request->get_body(), true);
        if (count($data) == 0) {
            header('HTTP/1.0 401 Unauthorized');
            die();
        }
        $key = fomofwc_set_and_get_key();

        $timestamp = isset($data['timestamp']) ? $data['timestamp'] : null;
        $signature = isset($data['signature']) ? $data['signature'] : null;

        if (!class_exists('Crypt_RSA')) {
            include_once(dirname(__FILE__) . '/phpseclib/Crypt/RSA.php');
        }
        $rsa = new Crypt_RSA();
        global $fomofwc_public_key;
        $rsa->loadKey($fomofwc_public_key);
        if ($rsa->verify($timestamp, base64_decode($signature))) {
            $payload = [
                'key' => $key,
                'url' => home_url(),
                'email' => get_option('admin_email'),
                'name' => get_option('blogname')
            ];
            $encrypted_payload = base64_encode($rsa->encrypt(json_encode($payload)));
            return [
                'timestamp' => $timestamp,
                'signature' => $signature,
                'encrypted_payload' => $encrypted_payload
            ];
        } else {
            header('HTTP/1.0 401 Unauthorized');
            die();
        }
    }

    // Access via /wp-json/fomofwc/v1/auth
    add_action('rest_api_init', function () {
        register_rest_route('fomofwc', '/v1/auth/', [
            'methods' => 'POST',
            'callback' => 'fomofwc_rest_auth',
        ]);
    });

    function fomofwc_rest_orders($request)
    {
        $data = json_decode($request->get_body(), true);
        if (count($data) == 0) {
            header('HTTP/1.0 401 Unauthorized');
            die();
        }

        $timestamp = isset($data['timestamp']) ? $data['timestamp'] : null;
        $signature = isset($data['signature']) ? $data['signature'] : null;

        if (!class_exists('Crypt_RSA')) {
            include_once(dirname(__FILE__) . '/phpseclib/Crypt/RSA.php');
        }
        $rsa = new Crypt_RSA();
        global $fomofwc_public_key;
        $rsa->loadKey($fomofwc_public_key);
        if ($rsa->verify($timestamp, base64_decode($signature))) {
            $from = isset($data['from']) ? $data['from'] : null;
            $orders = get_posts(
                [
                    'post_type' => 'shop_order',
                    'post_status' => ['wc-completed'],
                    'numberposts' => 3000,
                    'posts_per_page' => 3000,
                    'date_query' => [
                        [
                            'after' => date('c', $from)
                        ]
                    ]
                ]
            );
            $results = [];
            foreach ($orders as $orderPost) {
                $order = new WC_Order();
                $order->populate($orderPost);
                $result = [
                    'order_id' => $order->id,
                    'shipping_data' => [
                        'firstname' => $order->billing_first_name != null ? $order->billing_first_name : $order->shipping_first_name,
                        'city' => $order->billing_city != null ? $order->billing_city : $order->shipping_city,
                        'province' => $order->billing_state != null ? $order->billing_state : $order->shipping_state,
                        'country' => $order->billing_country != null ? $order->billing_country : $order->shipping_country
                    ],
                    'created_at' => $orderPost->post_date_gmt,
                    'products' => []
                ];
                $items = $order->get_items();
                foreach ($items as $order_id => $item) {
                    $product_id = $item["item_meta"]["_product_id"][0];
                    $product = new WC_Product($product_id);
                    $result['products'][] = [
                        'product_name' => $product->get_title(),
                        'id' => $product_id,
                        'product_image_url' => wp_get_attachment_url($product->get_image_id()),
                        'product_url' => $product->get_permalink(),
                        'product_attributes' => $product->get_attributes()
                    ];
                }
                if (count($result['products']) > 0) {
                    $results[] = $result;
                }
            }
            return $results;
        } else {
            header('HTTP/1.0 401 Unauthorized');
            die();
        }
    }

    // Access via /wp-json/fomofwc/v1/orders
    add_action('rest_api_init', function () {
        register_rest_route('fomofwc', '/v1/orders/', [
            'methods' => 'POST',
            'callback' => 'fomofwc_rest_orders',
        ]);
    });

    function fomofwc_rest_product($request)
    {
        $data = json_decode($request->get_body(), true);
        if (count($data) == 0) {
            header('HTTP/1.0 401 Unauthorized');
            die();
        }

        $timestamp = isset($data['timestamp']) ? $data['timestamp'] : null;
        $signature = isset($data['signature']) ? $data['signature'] : null;
        $product_id = isset($data['product_id']) ? $data['product_id'] : null;

        if (!class_exists('Crypt_RSA')) {
            include_once(dirname(__FILE__) . '/phpseclib/Crypt/RSA.php');
        }
        $rsa = new Crypt_RSA();
        global $fomofwc_public_key;
        $rsa->loadKey($fomofwc_public_key);
        if ($rsa->verify($timestamp . $product_id, base64_decode($signature))) {
            $product = new WC_Product($product_id);
            $result = [
                'product_name' => $product->get_title(),
                'id' => $product_id,
                'product_image_url' => wp_get_attachment_url($product->get_image_id()),
                'product_url' => $product->get_permalink(),
                'product_attributes' => $product->get_attributes()
            ];
            return $result;
        } else {
            header('HTTP/1.0 401 Unauthorized');
            die();
        }
    }

    // Access via /wp-json/fomofwc/v1/product
    add_action('rest_api_init', function () {
        register_rest_route('fomofwc', '/v1/product/', [
            'methods' => 'POST',
            'callback' => 'fomofwc_rest_product',
        ]);
    });

    function fomofwc_remote_post($url, $http_args)
    {
        $response = wp_remote_post($url, $http_args);
        if (is_wp_error($response)) {
            if ($response->get_error_message() == 'cURL error 35: SSL connect error') {
                // try fallback with php direct call instead of WP
                $headers = '';
                if (isset($http_args['headers'])) {
                    foreach ($http_args['headers'] as $key => $value) {
                        $headers .= $key . ': ' . $value . "\r\n";
                    }
                }
                $opts = array('http' =>
                    array(
                        'method' => 'POST',
                        'header' => $headers,
                        'content' => $http_args['body']
                    )
                );
                $context = @stream_context_create($opts);
                $result = @file_get_contents($url, false, $context);
            }
            // error, CURL not installed, firewall blocked or Fomo server down
        } else {
            if ($response != null) {
                switch ($response['response']['code']) {
                    case 200:  # OK
                        break;
                    case 401:
                        // reinitialize key exchange
                        fomofwc_init(home_url());
                        break;
                    default:
                        // Unexpected HTTP code: $http_code
                        break;
                }
            }
        }
    }

    function fomofwc_process_new_order($order_id)
    {
        $order = new WC_Order($order_id);
        $result = [
            'order_id' => $order->id,
            'shipping_data' => [
                'firstname' => $order->billing_first_name != null ? $order->billing_first_name : $order->shipping_first_name,
                'city' => $order->billing_city != null ? $order->billing_city : $order->shipping_city,
                'province' => $order->billing_state != null ? $order->billing_state : $order->shipping_state,
                'country' => $order->billing_country != null ? $order->billing_country : $order->shipping_country
            ],
            'created_at' => $order->post->post_date_gmt,
            'products' => []
        ];
        $items = $order->get_items();
        foreach ($items as $order_id => $item) {
            $product_id = $item["item_meta"]["_product_id"][0];
            $product = new WC_Product($product_id);
            $result['products'][] = [
                'product_name' => $product->get_title(),
                'id' => $product_id,
                'product_image_url' => wp_get_attachment_url($product->get_image_id()),
                'product_url' => $product->get_permalink(),
                'product_attributes' => $product->get_attributes()
            ];
        }
        if (count($result['products']) > 0) {
            global $fomofwc_domain;
            $time = time();
            $data = json_encode($result);
            $url = $fomofwc_domain . '/woocommerce_webhook?url=' . urlencode(home_url()) .
                '&timestamp=' . $time . '&hmac=' . hash_hmac('sha256', $data, fomofwc_set_and_get_key());
            $http_args = array(
                'body' => $data,
                'headers' => array(
                    'Content-Type' => 'application/json'
                ),
                'httpversion' => '1.0',
                'timeout' => 15
            );

            fomofwc_remote_post($url, $http_args);
        }
    }

    add_action('woocommerce_checkout_order_processed', 'fomofwc_process_new_order');

    function fomofwc_init($shop_url)
    {
        global $fomofwc_domain, $fomofwc_version;
        $url = $fomofwc_domain . '/woocommerce_init?url=' . urlencode($shop_url) . '&timestamp=' . time() .
            "&version=" . $fomofwc_version;
        fomofwc_remote_get($url);
    }


    function fomofwc_check($shop_url, $key)
    {
        global $fomofwc_domain;
        $time = time();
        $url = $fomofwc_domain . '/woocommerce_check?url=' . urlencode($shop_url) . '&timestamp=' . $time .
            '&hmac=' . hash_hmac('sha256', $shop_url . $time, $key);
        return fomofwc_remote_get($url);
    }

    function fomofwc_remote_get($url)
    {
        $response = wp_remote_get($url);
        if (is_wp_error($response)) {
            if ($response->get_error_message() == 'cURL error 35: SSL connect error') {
                try {
                    return json_decode(file_get_contents($url), true);
                } catch (Exception $e) {
                    echo '<p class="warning">';
                    _e('Error accrued while contacting our server!');
                    echo '<br />';
                    _e('Error data: ');
                    echo $e->getMessage();
                    echo '</p>';
                }
            } else {
                echo '<p class="warning">';
                _e('Error accrued while contacting our server!');
                echo '<br />';
                _e('Error data: ');
                echo $response->get_error_data();
                echo '<br />';
                _e('Error message: ');
                echo $response->get_error_message();
                echo '<br />';
                _e('Error code: ');
                echo $response->get_error_code();
                echo "</p>";
            }
            // error, CURL not installed, firewall blocked or Fomo server down
        } else {
            if ($response != null && isset($response['body'])) {
                return json_decode($response['body'], true);
            }
        }
    }

    function fomofwc_uninstall()
    {
        $shop_url = home_url();
        $key = fomofwc_set_and_get_key();
        global $fomofwc_domain;
        $time = time();
        $url = $fomofwc_domain . '/woocommerce_uninstall?url=' . urlencode($shop_url) . '&timestamp=' . $time .
            '&hmac=' . hash_hmac('sha256', $shop_url . $time, $key);
        fomofwc_remote_get($url);
    }

    register_deactivation_hook(__FILE__, 'fomofwc_uninstall');

    function fomofwc_add_action_links($links)
    {
        $added_links = array(
            '<a href="' . admin_url('options-general.php?page=fomo') . '">' . __('Settings') . '</a>',
        );
        return array_merge($links, $added_links);
    }

    add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'fomofwc_add_action_links');
} else {
    function fomofwc_admin_notice_woocommerce_not_installed()
    {
        $class = 'notice notice-error';
        $message = __('<strong>Fomo for WooCommerce</strong> plugin requires to have <a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a> plugin installed.', 'woocommerce-plugin-fomo');

        printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message);
    }

    add_action('admin_notices', 'fomofwc_admin_notice_woocommerce_not_installed');
}
