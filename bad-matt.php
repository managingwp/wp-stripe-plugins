<?php defined('ABSPATH') or die();
/*
 * Plugin Name:       Bad Matt
 * Plugin URI:        https://gschoppe.com
 * Description:       A protest plugin that removes (or replaces via filter) the Automattic-owned woocommerce stripe gateway partner id.
 * Version:           1.0.0
 * Requires at least: 4.0
 * Requires PHP:      7.2
 * Author:            Greg Schoppe
 * Author URI:        https://gschoppe.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Requires Plugins:  woocommerce, woocommerce-gateway-stripe
 */

add_filter( 'http_request_args', function( $args, $url ) {
  $stripe_api = "api.stripe.com";
  $host = parse_url( $url, PHP_URL_HOST );
  if( $host == $stripe_api ) {
    $headers = $args['headers'];
    // Set app info
    $app_info = [
      'name'       => get_bloginfo( 'name' ) . ' Stripe Gateway',
      'version'    => '1.0.0',
      'url'        => get_bloginfo( 'url' ),
      'partner_id' => apply_filters( 'stripe_gateway_partner_id', '', $url ),
    ];
    $app_info = apply_filters( 'stripe_gateway_app_info', $app_info, $url, $headers );
    // Change user agent
    $user_agent = $app_info['name'] . '/' . $app_info['version'] . ' (' . $app_info['url'] . ')';
    $headers['User-Agent'] = apply_filters( 'stripe_gateway_user_agent', $user_agent, $app_info, $url, $headers );
    // Change custom stripe header
    $stripe_header_content = [
      'lang'         => 'php',
      'lang_version' => phpversion(),
      'publisher'    => get_bloginfo( 'admin_email' ),
      'uname'        => function_exists( 'php_uname' ) ? php_uname() : PHP_OS,
      'application'  => $app_info,
    ];
    $stripe_header_content = apply_filters( 'stripe_gateway_client_user_agent', $stripe_header_content, $app_info, $url, $headers );
    $headers['X-Stripe-Client-User-Agent'] = wp_json_encode( $stripe_header_content );
    $args['headers'] = $headers;
  }

  return $args;
}, PHP_INT_MAX, 2 );