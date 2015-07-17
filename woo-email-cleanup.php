<?php
/*
Plugin Name: Woo Email Cleanup
Description: Hooks to some Woo Commerce Pretty Email filter & action to tweak an email or 2 better
Author: alex chousmith
Version: 1.3
Author URI: http://www.ninthlink.com
*/


function woo_email_cleanup_intro( $intro, $key, $order ) {
  $oot = $intro;
	if ( in_array( $key, array( 'customer-processing-order', 'customer-completed-order' ) ) ) {
    // totes override
    $orderid = $order->id;
    $fname = get_post_meta( $orderid, '_billing_first_name', true );
    if ( $fname != '' ) {
      $fname = ' '. $fname;
    }
    $oot = "Hi". $fname .",<br /><br />Thank you for your recent order on GoRoll.com. Your Order ID is ". $order->id .". Please refer to this number if you need to contact us. ";
    /*
    // check for BEACH coupon?!
    if( $order->get_used_coupons() ) {
      $beachd = false;
			$coupons_count = count( $order->get_used_coupons() );
      foreach( $order->get_used_coupons() as $coupon) {
        if ( $coupon == 'beach' ) {
          $beachd = true;
        }
      }
      if ( $beachd === true ) {
        $ctime = current_time( 'timestamp' );
        $cutoff = 1429056000; //2015-04-15
        if ( $ctime < $cutoff ) {
          $oot .= 'To pickup your purchase, stop by GoRoll\'s rest and relaxation tent at the Beach Soccer Championships in Oceanside, Calif. May 15-17.';
        }
      }
		} // endif get_used_coupons
    */

    $oot .= "<br /><br /><strong>Here are your order details:</strong>";
  }
  return $oot;
}
add_filter( 'woocommerce_email_mbc_cpo_intro_filter', 'woo_email_cleanup_intro', 1, 3 );
add_filter( 'woocommerce_email_mbc_cco_intro_filter', 'woo_email_cleanup_intro', 1, 3 );

function woo_email_cleanup_prefooter( $key ) {
	if ( in_array( $key, array( 'customer-processing-order', 'customer-completed-order' ) ) ) {
    echo '<p><br /><br /><strong>Refer-a-friend:</strong><br /><br />You have been awarded a savings code of [GR15PRESALE] to offer to a friend to receive a one-time 10% discount on a GoRoll.com order. Share this with a friend now!<br /><br /><strong>Customer care:</strong><br /><br />Our support team is always here for you. Just send us an email at Support@GoRoll.com with any questions about your order.<br /><br />We’re dedicated to developing products to inspire a balanced life, for a life well lived.  Thanks for choosing GoRoll to support you in your journey!<br /><br /><em>The Team at GoRoll</em></p>';
	}
}
add_action( 'woocommerce_email_footer', 'woo_email_cleanup_prefooter', 1, 1 );

/**
 * filter woocommerce_package_rates via http://businessbloomer.com/woocommerce-limit-shipping-one-state/
 * for no north carolina
 */
function woo_email_cleanup_nc( $rates, $package ) {
  global $woocommerce;
  $excluded_states = array( 'NC' );
  if( in_array( $woocommerce->customer->shipping_state, $excluded_states ) ) {
    $rates = array();
  }
  return $rates;
}
add_filter( 'woocommerce_package_rates', 'woo_email_cleanup_nc', 10, 2 );

/**
 * filter woocommerce_no_shipping_available_html
 * & woocommerce_cart_no_shipping_available_html
 * for no north carolina message
 */
function woo_email_cleanup_nonc_msg( $html ) {
  global $woocommerce;
  $excluded_states = array( 'NC' );
  if( in_array( $woocommerce->customer->shipping_state, $excluded_states ) ) {
    $html = '<p>' . __( 'Product not available for sale in North Carolina.', 'woocommerce' ) . '</p>';
  }
  return $html;
}
add_filter( 'woocommerce_cart_no_shipping_available_html', 'woo_email_cleanup_nonc_msg' );
add_filter( 'woocommerce_no_shipping_available_html', 'woo_email_cleanup_nonc_msg' );

function woo_email_cleanup_add_nc_msg() {
  $info_message = '<strong>'. __( 'Product not available for sale in North Carolina.', 'woocommerce' ) .'</strong>';
  wc_print_notice( $info_message, 'error' );
}
//add_action( 'woocommerce_before_checkout_form', 'woo_email_cleanup_add_nc_msg' );