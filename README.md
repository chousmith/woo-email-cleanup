# Woo Email Cleanup
Hooks to some Woo Commerce Pretty Email filter &amp; action to tweak an email or 2 better, mainly for goroll.com. Also follows http://businessbloomer.com/woocommerce-limit-shipping-one-state/ to add functionality to disallow shipping to the state of North Carolina. Here for reference &amp; example.

Installation Note

After the "WooCommerce Pretty Emails" was installed, had to edit the woocommerce-pretty-emails/emails/customer-processing-order.php file in 2 places :

Changed line 20 to

```
echo apply_filters( 'woocommerce_email_mbc_cpo_intro_filter', $intro, 'customer-processing-order', $order );
```

and changed line 61 to pass the argument in so it wouldnt add the extra text to the bottom of ALL emails...

```
<?php do_action( 'woocommerce_email_footer', 'customer-processing-order' ); ?>
```

