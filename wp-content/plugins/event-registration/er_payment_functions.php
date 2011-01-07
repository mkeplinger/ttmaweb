<?php

/**
 * @author David Fleming
 * @copyright 2010
 */


if ($currency_format == "$" || $currency_format == "") {$currency_format = "USD";}



function er_paypal_pay(
$payment_vendor_id,$currency_format, $item_name, $event_name, $num_people, $price
){

//Paypal Payment Button
if ($currency_format == "$" || $currency_format == "") {$currency_format = "USD";}

if ($num_people == "1" ){$people_count = "Individual";}
if ($num_people >= "2" ){$people_count = "Group of ".$num_people;}

$paypal_id =  $payment_vendor_id;
$item_name= $item_name;
$item_description=$event_name;
$item_qty=$num_people;
$item_price=$cost;
$currency_type= $currency_format;


 ?>   	
<form action="https://www.paypal.com/cgi-bin/webscr" target="paypal" method="post">
<input type="hidden" name="bn" value="AMPPFPWZ.301"> 
<input type="hidden" name="cmd" value="_xclick"> 
<input type="hidden" name="business" value="<?php echo $payment_vendor_id;?>">
<input type="hidden" name="notify_url" value="<?php echo $notify_url;?>"> 
<input type="hidden" name="item_name" value="<?php echo $item_name;?>">
<input type="hidden" name="item_number" value="<?php echo $item_description."-".$people_count;?>"> 
<?php
if ($price == "0"){?>
Enter Amount $<input name="amount" type="text" value="10.00" />
<?php
}
else { ?>
<input type="hidden" name="amount" value="<?php echo $price;?>">
<?php
}
?>
 
<input type="hidden" name="currency_code" value="<?php echo $currency_format;?>"> 
<input type="hidden" name="quantity" value="<?php echo $num_people;?>"> 
<input type="hidden" name="custom" value=""> 
<input type="hidden" name="image_url" style="font-weight: 700"> 
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_paynowCC_LG.gif"	border="0" align='middle' name="submit"></p>
</form>
<?php        	
}





function er_google_pay(
$payment_vendor_id,$currency_format, $item_name, $event_name, $num_people, $price
){
//Google Payment Button
if ($currency_format == "$" || $currency_format == "") {$currency_format = "USD";}

if ($num_people == "1" ){$people_count = "Individual";}
if ($num_people >= "2" ){$people_count = "Group of ".$num_people;}

$google_id = $payment_vendor_id;
$item_name= $item_name;
$item_description=$event_name;
$item_qty=$num_people;
$item_price=$cost;
$currency_type= $currency_format;
?>

<form action="https://checkout.google.com/api/checkout/v2/checkoutForm/Merchant/<?php echo $google_id;?>" id="BB_BuyButtonForm" method="post" name="BB_BuyButtonForm" target="_top">
<input name="item_name_1" type="hidden" value="<?php echo $item_name;?>"/>
<input name="item_description_1" type="hidden" value="<?php echo $item_description." - ".$people_count;?>"/>
<input name="item_quantity_1" type="hidden" value="<?php echo $num_people;?>"/>
<?php
if ($price == "0"){?>
Enter Amount $<input name="item_price_1" type="text" value="10.00" />
<?php
}
else { ?>
<input name="item_price_1" type="hidden" value="<?php echo $price;?>"/>
<?php
}
?>
<input name="item_currency_1" type="hidden" value="<?php echo $currency_format;?>"/>
<input name="_charset_" type="hidden" value="utf-8"/>
<input alt="" src="https://checkout.google.com/buttons/buy.gif?merchant_id=<?php echo"google_id";?>&amp;w=117&amp;h=48&amp;style=trans&amp;variant=text&amp;loc=en_US" type="image"/>
</form>
                

<?php

}

function er_authorize_pay(
$payment_vendor_id,$txn_key, $currency_format, $item_name, $event_name, $num_people, $price){

//Authorize.Net Payment 
// This sample code requires the mhash library for PHP versions older than
// 5.1.2 - http://hmhash.sourceforge.net/
	
// the parameters for the payment can be configured here
// the API Login ID and Transaction Key must be replaced with valid values
$loginID		= $payment_vendor_id;
$transactionKey = $txn_key;
$amount 		= $price;
$description 	= $item_name;
$label 			= "Submit Payment"; // The is the label on the 'submit' button
$testMode		= "false";
// By default, this sample code is designed to post to our test server for
// developer accounts: https://test.authorize.net/gateway/transact.dll
// for real accounts (even in test mode), please make sure that you are
// posting to: https://secure.authorize.net/gateway/transact.dll
$url			= "https://secure.authorize.net/gateway/transact.dll";

// If an amount or description were posted to this page, the defaults are overidden
if ($_REQUEST["amount"])
	{ $amount = $_REQUEST["amount"]; }
if ($_REQUEST["description"])
	{ $description = $_REQUEST["description"]; }

// an invoice is generated using the date and time
$invoice	= date(YmdHis);
// a sequence number is randomly generated
$sequence	= rand(1, 1000);
// a timestamp is generated
$timeStamp	= time ();

// The following lines generate the SIM fingerprint.  PHP versions 5.1.2 and
// newer have the necessary hmac function built in.  For older versions, it
// will try to use the mhash library.
if( phpversion() >= '5.1.2' )
{	$fingerprint = hash_hmac("md5", $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^", $transactionKey); }
else 
{ $fingerprint = bin2hex(mhash(MHASH_MD5, $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^", $transactionKey)); }

// Print the Amount and Description to the screen.
echo "Amount: $amount <br />";
echo "Description: $description <br />";

// Create the HTML form containing necessary SIM post values
echo "<FORM method='post' action='$url' >";
// Additional fields can be added here as outlined in the SIM integration guide
// at: http://developer.authorize.net
echo "	<INPUT type='hidden' name='x_login' value='$loginID' />";
if ($price == "0"){echo "Enter Amount $<INPUT type='text' name='x_amount' value='10.00' />";}
else { echo "	<INPUT type='hidden' name='x_amount' value='$amount' />";}
echo "	<INPUT type='hidden' name='x_description' value='$description' />";
echo "	<INPUT type='hidden' name='x_invoice_num' value='$invoice' />";
echo "	<INPUT type='hidden' name='x_fp_sequence' value='$sequence' />";
echo "	<INPUT type='hidden' name='x_fp_timestamp' value='$timeStamp' />";
echo "	<INPUT type='hidden' name='x_fp_hash' value='$fingerprint' />";
echo "	<INPUT type='hidden' name='x_test_request' value='$testMode' />";
echo "	<INPUT type='hidden' name='x_show_form' value='PAYMENT_FORM' />";
echo "	<input type='submit' value='$label' />";
echo "</FORM>";

// This is the end of the code generating the "submit payment" button.    -->
}




function er_monster_pay($payment_vendor_id,$currency_format, $item_name, $event_name, $num_people, $price){

if ($currency_format == "$" || $currency_format == "") {$currency_format = "USD";}

if ($num_people == "1" ){$people_count = "Individual";}
if ($num_people >= "2" ){$people_count = "Group of ".$num_people;}


$item_name= $item_name;
$event_name=$event_name;
$item_qty=$num_people;
$item_price=$price;
$currency_type= $currency_format;
?>    
<form action="https://www.monsterpay.com/secure/index.cfm" method="POST" enctype="APPLICATION/X-WWW-FORM-URLENCODED" target="_BLANK">
<input type="hidden" name="ButtonAction" value="buynow">
<input type="hidden" name="MerchantIdentifier" value="<?php echo $payment_vendor_id;?>">
<input type="hidden" name="LIDDesc" value="<?php echo $event_name."-".$people_count;?>">
<input type="hidden" name="LIDSKU" value="<?php echo $event_name;?>">
<input type="hidden" name="LIDPrice" value="<?php echo $item_price;?>">
<input type="hidden" name="LIDQty" value="<?php echo $item_qty;?>">
<input type="hidden" name="CurrencyAlphaCode" value="<?php echo $currency_format;?>">
<input type="hidden" name="ShippingRequired" value="0">
<input type="hidden" name="MerchRef" value="">
<input type="submit" value="Buy Now" style="background-color: #DCDCDC; font-family: Arial; font-size: 11px; color: #000000; font-weight: bold; border: 1px groove #000000;">
</form> 
<?php   
}

function er_custom_pay(){}

?>