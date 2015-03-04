<?php
/*
Plugin Name: Woo Email Cleanup
Description: Hooks to some Woo Commerce Pretty Email filter & action to tweak an email or 2 better
Author: alex chousmith
Version: 1.0
Author URI: http://www.ninthlink.com
*/


function woo_email_cleanup_intro( $intro, $key, $order ) {
  $oot = $intro;
  if ( $key == 'customer-processing-order' ) {
    // totes override
    $orderid = $order->id;
    $fname = get_post_meta( $orderid, '_billing_first_name', true );
    if ( $fname != '' ) {
      $fname = ' '. $fname;
    }
    $oot = "Hi". $fname .",<br /><br />Thank you for your recent order on GoRoll.com. Your Order ID is ". $order->id .". Please refer to this number if you need to contact us.<br /><br /><strong>Here are your order details:</strong>";
  }
  return $oot;
}
add_filter( 'woocommerce_email_mbc_cpo_intro_filter', 'woo_email_cleanup_intro', 1, 3 );

function woo_email_cleanup_prefooter( $key ) {
	if ( $key == 'customer-processing-order' ) {
    echo '<p><br /><br /><strong>Refer-a-friend:</strong><br /><br />You have been awarded a savings code of [GR15PRESALE] to offer to a friend to receive a one-time 10% discount on a GoRoll.com pre-sale order. Share this with a friend now! Offer expires on March 31, 2015.<br /><br /><strong>Customer care:</strong><br /><br />Our support team is always here for you. Just send us an email at Support@GoRoll.com with any questions about your order.<br /><br />We’re dedicated to developing products to inspire a balanced life, for a life well lived.  Thanks for choosing GoRoll to support you in your journey!<br /><br /><em>The Team at GoRoll</em></p>';
	}
}
add_action( 'woocommerce_email_footer', 'woo_email_cleanup_prefooter', 1, 1 );

