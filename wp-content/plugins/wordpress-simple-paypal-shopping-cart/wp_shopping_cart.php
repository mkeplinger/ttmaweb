<?php
/*
Plugin Name: WP Simple Paypal Shopping cart
Version: v2.8.1
Plugin URI: http://www.tipsandtricks-hq.com/?p=768
Author: Ruhul Amin
Author URI: http://www.tipsandtricks-hq.com/
Description: Simple WordPress Shopping Cart Plugin, very easy to use and great for selling products and services from your blog!
*/

/*
    This program is free software; you can redistribute it
    under the terms of the GNU General Public License version 2,
    as published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

session_start();

$siteurl = get_option('siteurl');
define('WP_CART_FOLDER', dirname(plugin_basename(__FILE__)));
define('WP_CART_URL', get_option('siteurl').'/wp-content/plugins/' . WP_CART_FOLDER);

add_option('wp_cart_title', 'Your Shopping Cart');
add_option('wp_cart_empty_text', 'Your cart is empty');
add_option('cart_return_from_paypal_url', get_bloginfo('wpurl'));

function always_show_cart_handler($atts) 
{
	return print_wp_shopping_cart();
}

function show_wp_shopping_cart_handler()
{
    if (cart_not_empty())
    {
       	$output = print_wp_shopping_cart();
    }
    return $output;	
}

function shopping_cart_show($content)
{
	if (strpos($content, "<!--show-wp-shopping-cart-->") !== FALSE)
    {
    	if (cart_not_empty())
    	{
        	$content = preg_replace('/<p>\s*<!--(.*)-->\s*<\/p>/i', "<!--$1-->", $content);
        	$matchingText = '<!--show-wp-shopping-cart-->';
        	$replacementText = print_wp_shopping_cart();
        	$content = str_replace($matchingText, $replacementText, $content);
    	}
    }
    return $content;
}

// Reset the Cart as this is a returned customer from Paypal
$merchant_return_link = $_GET["merchant_return_link"];
if (!empty($merchant_return_link))
{
    reset_wp_cart();
}
$mc_gross = $_GET["mc_gross"];
if ($mc_gross > 0)
{
    reset_wp_cart();
}

function reset_wp_cart()
{
    $products = $_SESSION['simpleCart'];
    foreach ($products as $key => $item)
    {
        unset($products[$key]);
    }
    $_SESSION['simpleCart'] = $products;
    header('Location: ' . get_option('cart_return_from_paypal_url'));
}

if ($_POST['addcart'])
{
    $count = 1;    
    $products = $_SESSION['simpleCart'];
    
    if (is_array($products))
    {
        foreach ($products as $key => $item)
        {
            if ($item['name'] == stripslashes($_POST['product']))
            {
                $count += $item['quantity'];
                $item['quantity']++;
                unset($products[$key]);
                array_push($products, $item);
            }
        }
    }
    else
    {
        $products = array();
    }
        
    if ($count == 1)
    {
        if (!empty($_POST[$_POST['product']]))
            $price = $_POST[$_POST['product']];
        else
            $price = $_POST['price'];
        
        $product = array('name' => stripslashes($_POST['product']), 'price' => $price, 'quantity' => $count, 'shipping' => $_POST['shipping'], 'cartLink' => $_POST['cartLink'], 'item_number' => $_POST['item_number']);
        array_push($products, $product);
    }
    
    sort($products);
    $_SESSION['simpleCart'] = $products;
}
else if ($_POST['cquantity'])
{
    $products = $_SESSION['simpleCart'];
    foreach ($products as $key => $item)
    {
        if ((stripslashes($item['name']) == stripslashes($_POST['product'])) && $_POST['quantity'])
        {
            $item['quantity'] = $_POST['quantity'];
            unset($products[$key]);
            array_push($products, $item);
        }
        else if (($item['name'] == stripslashes($_POST['product'])) && !$_POST['quantity'])
            unset($products[$key]);
    }
    sort($products);
    $_SESSION['simpleCart'] = $products;
}
else if ($_POST['delcart'])
{
    $products = $_SESSION['simpleCart'];
    foreach ($products as $key => $item)
    {
        if ($item['name'] == stripslashes($_POST['product']))
            unset($products[$key]);
    }
    $_SESSION['simpleCart'] = $products;
}

function print_wp_shopping_cart()
{
	if (!cart_not_empty())
	{
	    $empty_cart_text = get_option('wp_cart_empty_text');
		if (!empty($empty_cart_text)) 
		{
			$output .= $empty_cart_text;
		}
		$cart_products_page_url = get_option('cart_products_page_url');
		if (!empty($cart_products_page_url))
		{
			$output .= '<br /><a rel="nofollow" href="'.$cart_products_page_url.'">Visit The Shop</a>';
		}		
		return $output;
	}
    $email = get_bloginfo('admin_email');
    $use_affiliate_platform = get_option('wp_use_aff_platform');   
    $defaultCurrency = get_option('cart_payment_currency');
    $defaultSymbol = get_option('cart_currency_symbol');
    $defaultEmail = get_option('cart_paypal_email');
    if (!empty($defaultCurrency))
        $paypal_currency = $defaultCurrency;
    else
        $paypal_currency = 'USD';
    if (!empty($defaultSymbol))
        $paypal_symbol = $defaultSymbol;
    else
        $paypal_symbol = '$';

    if (!empty($defaultEmail))
        $email = $defaultEmail;
     
    $decimal = '.';  
	$urls = '';
        
    $return = get_option('cart_return_from_paypal_url');
            
    if (!empty($return))
        $urls .= '<input type="hidden" name="return" value="'.$return.'" />';
	
	if ($use_affiliate_platform)  
	{
		if (function_exists('wp_aff_platform_install'))
		{
			$notify = WP_CART_URL.'/paypal.php';
			$urls .= '<input type="hidden" name="notify_url" value="'.$notify.'" />';
		}
	}
	$title = get_option('wp_cart_title');
	//if (empty($title)) $title = 'Your Shopping Cart';
    
    global $plugin_dir_name;
    $output .= '<div class="shopping_cart" style=" padding: 5px;">';
    if (!get_option('wp_shopping_cart_image_hide'))    
    {
    	$output .= "<input type='image' src='".WP_CART_URL."/images/shopping_cart_icon.png' value='Cart' title='Cart' />";
    }
    if(!empty($title))
    {
    	$output .= '<h2>';
    	$output .= $title;  
    	$output .= '</h2>';
    }
        
    $output .= '<br /><span id="pinfo" style="display: none; font-weight: bold; color: red;">Hit enter to submit new Quantity.</span>';
	$output .= '<table style="width: 100%;">';    
    
    $count = 1;
    $total_items = 0;
    $total = 0;
    $form = '';
    if ($_SESSION['simpleCart'] && is_array($_SESSION['simpleCart']))
    {   
        $output .= '
        <tr>
        <th style="text-align: left">Item Name</th><th>Quantity</th><th>Price</th>
        </tr>';
    
	    foreach ($_SESSION['simpleCart'] as $item)
	    {
	        $total += $item['price'] * $item['quantity'];
	        $item_total_shipping += $item['shipping'] * $item['quantity'];
	        $total_items +=  $item['quantity'];
	    }
	    $baseShipping = get_option('cart_base_shipping_cost');
	    $postage_cost = $item_total_shipping + $baseShipping;
	    
	    $cart_free_shipping_threshold = get_option('cart_free_shipping_threshold');
	    if (!empty($cart_free_shipping_threshold) && $total > $cart_free_shipping_threshold)
	    {
	    	$postage_cost = 0;
	    }

	    foreach ($_SESSION['simpleCart'] as $item)
	    {
	        $output .= "
	        <tr><td style='overflow: hidden;'><a href='".$item['cartLink']."'>".$item['name']."</a></td>
	        <td style='text-align: center'><form method=\"post\"  action=\"\" name='pcquantity' style='display: inline'>
                <input type=\"hidden\" name=\"product\" value=\"".$item['name']."\" />

	        <input type='hidden' name='cquantity' value='1' /><input type='text' name='quantity' value='".$item['quantity']."' size='1' onchange='document.pcquantity.submit();' onkeypress='document.getElementById(\"pinfo\").style.display = \"\";' /></form></td>
	        <td style='text-align: center'>".print_payment_currency(($item['price'] * $item['quantity']), $paypal_symbol, $decimal)."</td>
	        <td><form method=\"post\"  action=\"\">
	        <input type=\"hidden\" name=\"product\" value=\"".$item['name']."\" />
	        <input type='hidden' name='delcart' value='1' />
	        <input type='image' src='".WP_CART_URL."/images/Shoppingcart_delete.png' value='Remove' title='Remove' /></form></td></tr>
	        ";
	        
	        $form .= "
	            <input type=\"hidden\" name=\"item_name_$count\" value=\"".$item['name']."\" />
	            <input type=\"hidden\" name=\"amount_$count\" value='".$item['price']."' />
	            <input type=\"hidden\" name=\"quantity_$count\" value=\"".$item['quantity']."\" />
	            <input type='hidden' name='item_number' value='".$item['item_number']."' />
	        ";        
	        $count++;
	    }
	    if (!get_option('wp_shopping_cart_use_profile_shipping'))
	    {
	    	$form .= "<input type=\"hidden\" name=\"shipping_1\" value='".$postage_cost."' />";  
	    }
	    if (get_option('wp_shopping_cart_collect_address'))//force address collection
	    {
	    	$form .= "<input type=\"hidden\" name=\"no_shipping\" value=\"2\" />";  
	    }	    	    
    }
    
       	$count--;
       	
       	if ($count)
       	{
       		//$output .= '<tr><td></td><td></td><td></td></tr>';  

            if ($postage_cost != 0)
            {
                $output .= "
                <tr><td colspan='2' style='font-weight: bold; text-align: right;'>Subtotal: </td><td style='text-align: center'>".print_payment_currency($total, $paypal_symbol, $decimal)."</td><td></td></tr>
                <tr><td colspan='2' style='font-weight: bold; text-align: right;'>Shipping: </td><td style='text-align: center'>".print_payment_currency($postage_cost, $paypal_symbol, $decimal)."</td><td></td></tr>";
            }

            $output .= "
       		<tr><td colspan='2' style='font-weight: bold; text-align: right;'>Total: </td><td style='text-align: center'>".print_payment_currency(($total+$postage_cost), $paypal_symbol, $decimal)."</td><td></td></tr>
       		<tr><td colspan='4'>";
       
              	$output .= "<form action=\"https://www.paypal.com/us/cgi-bin/webscr\" method=\"post\">$form";
    			if ($count)
            		$output .= '<input type="image" src="'.WP_CART_URL.'/images/paypal_checkout.png" name="submit" class="wp_cart_checkout_button" alt="Make payments with PayPal - it\'s fast, free and secure!" />';
       
    			$output .= $urls.'
			    <input type="hidden" name="business" value="'.$email.'" />
			    <input type="hidden" name="currency_code" value="'.$paypal_currency.'" />
			    <input type="hidden" name="cmd" value="_cart" />
			    <input type="hidden" name="upload" value="1" />
			    <input type="hidden" name="mrb" value="3FWGC6LFTMTUG" />';
			    if ($use_affiliate_platform)
			    {
			    	$output .= wp_cart_add_custom_field();
			    }
			    $output .= '</form>';          
       	}       
       	$output .= "       
       	</td></tr>
    	</table></div>
    	";
    
    return $output;
}
// https://www.sandbox.paypal.com/cgi-bin/webscr (paypal testing site)
// https://www.paypal.com/us/cgi-bin/webscr (paypal live site )

function wp_cart_add_custom_field()
{
	if (function_exists('wp_aff_platform_install'))
	{
		$output = '';
		if (!empty($_SESSION['ap_id']))
		{
			$output = '<input type="hidden" name="custom" value="'.$_SESSION['ap_id'].'" id="wp_affiliate" />';
		}
		else if (isset($_COOKIE['ap_id']))
		{
			$output = '<input type="hidden" name="custom" value="'.$_COOKIE['ap_id'].'" id="wp_affiliate" />';
		}
		return 	$output;
	}
}

function print_wp_cart_button_new($content)
{
	//wp_cart_add_read_form_javascript();
        
        $addcart = get_option('addToCartButtonName');    
        if (!$addcart || ($addcart == '') )
            $addcart = 'Add to Cart';
            	
        $pattern = '#\[wp_cart:.+:price:.+:end]#';
        preg_match_all ($pattern, $content, $matches);

        foreach ($matches[0] as $match)
        {   
        	$var_output = '';
            $pos = strpos($match,":var1");
			if ($pos)
			{				
				$match_tmp = $match;
				// Variation control is used
				$pos2 = strpos($match,":var2");
				if ($pos2)
				{
					//echo '<br />'.$match_tmp.'<br />';
					$pattern = '#var2\[.*]:#';
				    preg_match_all ($pattern, $match_tmp, $matches3);
				    $match3 = $matches3[0][0];
				    //echo '<br />'.$match3.'<br />';
				    $match_tmp = str_replace ($match3, '', $match_tmp);
				    
				    $pattern = 'var2[';
				    $m3 = str_replace ($pattern, '', $match3);
				    $pattern = ']:';
				    $m3 = str_replace ($pattern, '', $m3);  
				    $pieces3 = explode('|',$m3);
			
				    $variation2_name = $pieces3[0];
				    $var_output .= $variation2_name." : ";
				    $var_output .= '<select name="variation2" onchange="ReadForm (this.form, false);">';
				    for ($i=1;$i<sizeof($pieces3); $i++)
				    {
				    	$var_output .= '<option value="'.$pieces3[$i].'">'.$pieces3[$i].'</option>';
				    }
				    $var_output .= '</select><br />';				    
				}				
			    
			    $pattern = '#var1\[.*]:#';
			    preg_match_all ($pattern, $match_tmp, $matches2);
			    $match2 = $matches2[0][0];

			    $match_tmp = str_replace ($match2, '', $match_tmp);

				    $pattern = 'var1[';
				    $m2 = str_replace ($pattern, '', $match2);
				    $pattern = ']:';
				    $m2 = str_replace ($pattern, '', $m2);  
				    $pieces2 = explode('|',$m2);
			
				    $variation_name = $pieces2[0];
				    $var_output .= $variation_name." : ";
				    $var_output .= '<select name="variation1" onchange="ReadForm (this.form, false);">';
				    for ($i=1;$i<sizeof($pieces2); $i++)
				    {
				    	$var_output .= '<option value="'.$pieces2[$i].'">'.$pieces2[$i].'</option>';
				    }
				    $var_output .= '</select><br />';				

			}

            $pattern = '[wp_cart:';
            $m = str_replace ($pattern, '', $match);
            
            $pattern = 'price:';
            $m = str_replace ($pattern, '', $m);
            $pattern = 'shipping:';
            $m = str_replace ($pattern, '', $m);
            $pattern = ':end]';
            $m = str_replace ($pattern, '', $m);

            $pieces = explode(':',$m);
    
                $replacement = '<object><form method="post"  action="" style="display:inline" onsubmit="return ReadForm(this, true);">';             
                if (!empty($var_output))
                {
                	$replacement .= $var_output;
                } 
				                
				if (preg_match("/http/", $addcart)) // Use the image as the 'add to cart' button
				{
				    $replacement .= '<input type="image" src="'.$addcart.'" class="wp_cart_button" alt="Add to Cart"/>';
				} 
				else 
				{
				    $replacement .= '<input type="submit" value="'.$addcart.'" />';
				} 

                $replacement .= '<input type="hidden" name="product" value="'.$pieces['0'].'" /><input type="hidden" name="price" value="'.$pieces['1'].'" />';
                $replacement .= '<input type="hidden" name="product_tmp" value="'.$pieces['0'].'" />';
                if (sizeof($pieces) >2 )
                {
                	//we have shipping
                	$replacement .= '<input type="hidden" name="shipping" value="'.$pieces['2'].'" />';
                }
                $replacement .= '<input type="hidden" name="cartLink" value="'.cart_current_page_url().'" />';
                $replacement .= '<input type="hidden" name="addcart" value="1" /></form></object>';
                $content = str_replace ($match, $replacement, $content);                
        }
        return $content;	
}

function wp_cart_add_read_form_javascript()
{
	echo '
	<script type="text/javascript">
	<!--
	//
	function ReadForm (obj1, tst) 
	{ 
	    // Read the user form
	    var i,j,pos;
	    val_total="";val_combo="";		
	
	    for (i=0; i<obj1.length; i++) 
	    {     
	        // run entire form
	        obj = obj1.elements[i];           // a form element
	
	        if (obj.type == "select-one") 
	        {   // just selects
	            if (obj.name == "quantity" ||
	                obj.name == "amount") continue;
		        pos = obj.selectedIndex;        // which option selected
		        val = obj.options[pos].value;   // selected value
		        val_combo = val_combo + "(" + val + ")";
	        }
	    }
		// Now summarize everything we have processed above
		val_total = obj1.product_tmp.value + val_combo;
		obj1.product.value = val_total;
	}
	//-->
	</script>';	
}
function print_wp_cart_button_for_product($name, $price, $shipping=0)
{
        $addcart = get_option('addToCartButtonName');
    
        if (!$addcart || ($addcart == '') )
            $addcart = 'Add to Cart';
                  

        $replacement = '<object><form method="post"  action="" style="display:inline">';
		if (preg_match("/http:/", $addcart)) // Use the image as the 'add to cart' button
		{
			$replacement .= '<input type="image" src="'.$addcart.'" class="wp_cart_button" alt="Add to Cart"/>';
		} 
		else 
		{
		    $replacement .= '<input type="submit" value="'.$addcart.'" />';
		}             	      

        $replacement .= '<input type="hidden" name="product" value="'.$name.'" /><input type="hidden" name="price" value="'.$price.'" /><input type="hidden" name="shipping" value="'.$shipping.'" /><input type="hidden" name="addcart" value="1" /><input type="hidden" name="cartLink" value="'.cart_current_page_url().'" /></form></object>';
                
        return $replacement;
}

function cart_not_empty()
{
        $count = 0;
        if (isset($_SESSION['simpleCart']) && is_array($_SESSION['simpleCart']))
        {
            foreach ($_SESSION['simpleCart'] as $item)
                $count++;
            return $count;
        }
        else
            return 0;
}

function print_payment_currency($price, $symbol, $decimal)
{
    return $symbol.number_format($price, 2, $decimal, ',');
}

function cart_current_page_url() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

function show_wp_cart_options_page () {	
	$wp_simple_paypal_shopping_cart_version = "2.8.1";
    if (isset($_POST['info_update']))
    {
        update_option('cart_payment_currency', (string)$_POST["cart_payment_currency"]);
        update_option('cart_currency_symbol', (string)$_POST["cart_currency_symbol"]);
        update_option('cart_base_shipping_cost', (string)$_POST["cart_base_shipping_cost"]);
        update_option('cart_free_shipping_threshold', (string)$_POST["cart_free_shipping_threshold"]);   
        update_option('wp_shopping_cart_collect_address', ($_POST['wp_shopping_cart_collect_address']!='') ? 'checked="checked"':'' );    
        update_option('wp_shopping_cart_use_profile_shipping', ($_POST['wp_shopping_cart_use_profile_shipping']!='') ? 'checked="checked"':'' );
                
        update_option('cart_paypal_email', (string)$_POST["cart_paypal_email"]);
        update_option('addToCartButtonName', (string)$_POST["addToCartButtonName"]);
        update_option('wp_cart_title', (string)$_POST["wp_cart_title"]);
        update_option('wp_cart_empty_text', (string)$_POST["wp_cart_empty_text"]);
        update_option('cart_return_from_paypal_url', (string)$_POST["cart_return_from_paypal_url"]);
        update_option('cart_products_page_url', (string)$_POST["cart_products_page_url"]);
        update_option('wp_shopping_cart_image_hide', ($_POST['wp_shopping_cart_image_hide']!='') ? 'checked="checked"':'' );
        update_option('wp_use_aff_platform', ($_POST['wp_use_aff_platform']!='') ? 'checked="checked"':'' );
        
        echo '<div id="message" class="updated fade">';
        echo '<p><strong>Options Updated!</strong></p></div>';
    }	
	
    $defaultCurrency = get_option('cart_payment_currency');    
    if (empty($defaultCurrency)) $defaultCurrency = 'USD';
    
    $defaultSymbol = get_option('cart_currency_symbol');
    if (empty($defaultSymbol)) $defaultSymbol = '$';

    $baseShipping = get_option('cart_base_shipping_cost');
    if (empty($baseShipping)) $baseShipping = 0;
    
    $cart_free_shipping_threshold = get_option('cart_free_shipping_threshold');

    $defaultEmail = get_option('cart_paypal_email');
    if (empty($defaultEmail)) $defaultEmail = get_bloginfo('admin_email');
    
    $return_url =  get_option('cart_return_from_paypal_url');

    $addcart = get_option('addToCartButtonName');
    if (empty($addcart)) $addcart = 'Add to Cart';           

	$title = get_option('wp_cart_title');
	//if (empty($title)) $title = 'Your Shopping Cart';
	
	$emptyCartText = get_option('wp_cart_empty_text');
	$cart_products_page_url = get_option('cart_products_page_url');	      
        	    
    if (get_option('wp_shopping_cart_collect_address'))
        $wp_shopping_cart_collect_address = 'checked="checked"';
    else
        $wp_shopping_cart_collect_address = '';
        
    if (get_option('wp_shopping_cart_use_profile_shipping'))
        $wp_shopping_cart_use_profile_shipping = 'checked="checked"';
    else
        $wp_shopping_cart_use_profile_shipping = '';
                	
    if (get_option('wp_shopping_cart_image_hide'))
        $wp_cart_image_hide = 'checked="checked"';
    else
        $wp_cart_image_hide = '';

    if (get_option('wp_use_aff_platform'))
        $wp_use_aff_platform = 'checked="checked"';
    else
        $wp_use_aff_platform = '';
                              
	?>
 	<h2>Simple Paypal Shopping Cart Settings v <?php echo $wp_simple_paypal_shopping_cart_version; ?></h2>
 	
 	<p>For information, updates and detailed documentation, please visit:<br />
    <a href="http://www.tipsandtricks-hq.com/?p=768">http://www.tipsandtricks-hq.com/?p=768</a></p>
    
     <fieldset class="options">
    <legend>Usage:</legend>

    <p>1. To add the 'Add to Cart' button simply add the trigger text <strong>[wp_cart:PRODUCT-NAME:price:PRODUCT-PRICE:end]</strong> to a post or page next to the product. Replace PRODUCT-NAME and PRODUCT-PRICE with the actual name and price. For example: [wp_cart:Test Product:price:15.00:end]</p>
	<p>2. To add the shopping cart to a post or page (eg. checkout page) simply add the shortcode <strong>[show_wp_shopping_cart]</strong> to a post or page or use the sidebar widget to add the shopping cart to the sidebar.</p> 
    </fieldset>

    <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
    <input type="hidden" name="info_update" id="info_update" value="true" />    
 	<?php
echo '
	<div class="postbox">
	<h3><label for="title">PayPal and Shopping Cart Settings</label></h3>
	<div class="inside">';

echo '
<table class="form-table">
<tr valign="top">
<th scope="row">Paypal Email Address</th>
<td><input type="text" name="cart_paypal_email" value="'.$defaultEmail.'" size="40" /></td>
</tr>
<tr valign="top">
<th scope="row">Shopping Cart title</th>
<td><input type="text" name="wp_cart_title" value="'.$title.'" size="40" /></td>
</tr>
<tr valign="top">
<th scope="row">Text to show when Cart empty</th>
<td><input type="text" name="wp_cart_empty_text" value="'.$emptyCartText.'" size="40" /></td>
</tr>
<tr valign="top">
<th scope="row">Currency</th>
<td><input type="text" name="cart_payment_currency" value="'.$defaultCurrency.'" size="6" /> (e.g. USD, EUR, GBP, AUD)</td>
</tr>
<tr valign="top">
<th scope="row">Currency Sybmol</th>
<td><input type="text" name="cart_currency_symbol" value="'.$defaultSymbol.'" size="2" style="width: 1.5em;" /> (e.g. $, &#163;, &#8364;) 
</td>
</tr>

<tr valign="top">
<th scope="row">Base Shipping Cost</th>
<td><input type="text" name="cart_base_shipping_cost" value="'.$baseShipping.'" size="5" /> <br />This is the base shipping cost that will be added to the total of individual products shipping cost. Put 0 if you do not want to charge shipping cost or use base shipping cost. <a href="http://www.tipsandtricks-hq.com/ecommerce/?p=297" target="_blank">Learn More on Shipping Calculation</a></td>
</tr>

<tr valign="top">
<th scope="row">Free Shipping for Orders Over</th>
<td><input type="text" name="cart_free_shipping_threshold" value="'.$cart_free_shipping_threshold.'" size="5" /> <br />When a customer orders more than this amount he/she will get free shipping. Leave empty if you do not want to use it.</td>
</tr>

<tr valign="top">
<th scope="row">Must Collect Shipping Address on PayPal</th>
<td><input type="checkbox" name="wp_shopping_cart_collect_address" value="1" '.$wp_shopping_cart_collect_address.' /><br />If checked the customer will be forced to enter a shipping address on PayPal when checking out.</td>
</tr>

<tr valign="top">
<th scope="row">Use PayPal Profile Based Shipping</th>
<td><input type="checkbox" name="wp_shopping_cart_use_profile_shipping" value="1" '.$wp_shopping_cart_use_profile_shipping.' /><br />Check this if you want to use <a href="https://cms.paypal.com/us/cgi-bin/?&cmd=_render-content&content_ID=developer/e_howto_html_ProfileAndTools#id08A9EF00IQY" target="_blank">PayPal profile based shipping</a>. Using this will ignore any other shipping options that you have specified in this plugin.</td>
</tr>
		
<tr valign="top">
<th scope="row">Add to Cart button text or Image</th>
<td><input type="text" name="addToCartButtonName" value="'.$addcart.'" size="100" /><br />To use a cusomized image as the button simply enter the URL of the image file. eg. http://www.tipsandtricks-hq.com/wp-content/plugins/wordpress-paypal-shopping-cart/images/buy_now_button.png</td>
</tr>

<tr valign="top">
<th scope="row">Return URL</th>
<td><input type="text" name="cart_return_from_paypal_url" value="'.$return_url.'" size="100" /><br />This is the URL the customer will be redirected to after a successful payment</td>
</tr>
		
<tr valign="top">
<th scope="row">Products Page URL</th>
<td><input type="text" name="cart_products_page_url" value="'.$cart_products_page_url.'" size="100" /><br />This is the URL of your products page if you have any. If used, the shopping cart widget will display a link to this page when cart is empty</td>
</tr>
</table>


<table class="form-table">
<tr valign="top">
<th scope="row">Hide Shopping Cart Image</th>
<td><input type="checkbox" name="wp_shopping_cart_image_hide" value="1" '.$wp_cart_image_hide.' /><br />If ticked the shopping cart image will no be shown.</td>
</tr>
</table>

<table class="form-table">
<tr valign="top">
<th scope="row">Use WP Affiliate Platform</th>
<td><input type="checkbox" name="wp_use_aff_platform" value="1" '.$wp_use_aff_platform.' /><br />Check this if using with the <a href="http://tipsandtricks-hq.com/?p=1474" target="_blank">WP Affiliate Platform plugin</a>.</td>
</tr>
</table>
</div></div>
    <div class="submit">
        <input type="submit" name="info_update" value="Update Options &raquo;" />
    </div>						
 </form>
 ';
    echo 'Like the Simple WordPress Shopping Cart Plugin? <a href="http://wordpress.org/extend/plugins/wordpress-simple-paypal-shopping-cart" target="_blank">Give it a good rating</a>'; 
}

function wp_cart_options()
{
     echo '<div class="wrap"><h2>WP Paypal Shopping Cart Options</h2>';
     echo '<div id="poststuff"><div id="post-body">';
     show_wp_cart_options_page();
     echo '</div></div>';
     echo '</div>';
}

// Display The Options Page
function wp_cart_options_page () 
{
     add_options_page('WP Paypal Shopping Cart', 'WP Shopping Cart', 'manage_options', __FILE__, 'wp_cart_options');  
}

function show_wp_paypal_shopping_cart_widget($args)
{
	extract($args);
	
	$cart_title = get_option('wp_cart_title');
	if (empty($cart_title)) $cart_title = 'Shopping Cart';
	
	echo $before_widget;
	echo $before_title . $cart_title . $after_title;
    echo print_wp_shopping_cart();
    echo $after_widget;
}

function wp_paypal_shopping_cart_widget_control()
{
    ?>
    <p>
    <? _e("Set the Plugin Settings from the Settings menu"); ?>
    </p>
    <?php
}

function widget_wp_paypal_shopping_cart_init()
{
    $widget_options = array('classname' => 'widget_wp_paypal_shopping_cart', 'description' => __( "Display WP Paypal Shopping Cart.") );
    wp_register_sidebar_widget('wp_paypal_shopping_cart_widgets', __('WP Paypal Shopping Cart'), 'show_wp_paypal_shopping_cart_widget', $widget_options);
    wp_register_widget_control('wp_paypal_shopping_cart_widgets', __('WP Paypal Shopping Cart'), 'wp_paypal_shopping_cart_widget_control' );
}

function wp_cart_css()
{
    echo '<link type="text/css" rel="stylesheet" href="'.WP_CART_URL.'/wp_shopping_cart_style.css" />'."\n";
}

// Insert the options page to the admin menu
add_action('admin_menu','wp_cart_options_page');
add_action('init', 'widget_wp_paypal_shopping_cart_init');
//add_filter('the_content', 'print_wp_cart_button',11);

add_filter('the_content', 'print_wp_cart_button_new',11);
add_filter('the_content', 'shopping_cart_show');

add_shortcode('show_wp_shopping_cart', 'show_wp_shopping_cart_handler');

add_shortcode('always_show_wp_shopping_cart', 'always_show_cart_handler');

add_action('wp_head', 'wp_cart_css');
add_action('wp_head', 'wp_cart_add_read_form_javascript');
?>