<?php
function register_attendees($event_single_id) {
 	global $wpdb, $events_lang,$events_lang_flag;
	$paypal_cur = get_option ( 'paypal_cur' );
	if ($event_single_id == ""){$event_id = $_REQUEST ['event_id'];}
	if ($event_single_id != ""){$event_id = $event_single_id;}	
	$events_listing_type = get_option ( 'events_listing_type' );
	$events_attendee_tbl = get_option ( 'events_attendee_tbl' );
	$events_detail_tbl = get_option ( 'events_detail_tbl' );
	$events_organization_tbl = get_option ( 'events_organization_tbl' );
	$events_listing_type = get_option ( 'events_listing_type' );
	$sql = "SELECT * FROM " . $events_organization_tbl . " WHERE id='1'";	 
    $result = mysql_query ( $sql );
    $ER_org_data = mysql_fetch_array($result) or die(mysql_error());
    $events_listing_type = $ER_org_data['events_listing_type'];
	
    //Query Database for Active event and get variable
	if ($event_id == "") {
		$sql = "SELECT * FROM " . $events_detail_tbl . " WHERE is_active='yes'";
	} else {
		$sql = "SELECT * FROM " . $events_detail_tbl . " WHERE id = $event_id";
	}
	$result = mysql_query ( $sql );
	while ( $row = mysql_fetch_assoc ( $result ) ) {
	                $event_id= $row['id'];
			        $event_name =  stripslashes($row ['event_name']);
					$event_identifier =  stripslashes($row ['event_identifier']);
					$event_desc =  stripslashes($row ['event_desc']);
					$image_link = $row ['image_link'];
					$header_image = $row ['header_image'];
					$display_desc = $row ['display_desc'];
					$event_location =  stripslashes($row ['event_location']);
					$more_info = $row ['more_info'];
					$reg_limit = $row ['reg_limit'];
					$event_cost = $row ['event_cost'];
					$custom_cur = $row ['custom_cur'];
					$multiple = $row ['multiple'];
					$allow_checks = $row ['allow_checks'];
					$is_active = $row ['is_active'];
					$start_month = $row ['start_month'];
					$start_day = $row ['start_day'];
					$start_year = $row ['start_year'];
					$end_month = $row ['end_month'];
					$end_day = $row ['end_day'];
					$end_year = $row ['end_year'];
					$start_time = $row ['start_time'];
					$end_time = $row ['end_time'];
					$conf_mail = stripslashes($row ['conf_mail']);
					$send_mail = $row ['send_mail'];
                    $use_coupon=$row ['use_coupon'];
            		$coupon_code=$row ['coupon_code'];
            		$coupon_code_price=$row ['coupon_code_price'];
            		$use_percentage=$row ['use_percentage'];
            		$event_category =  $row ['event_category'];
						if ($start_month == "Jan"){$month_no = '01';}
						if ($start_month == "Feb"){$month_no = '02';}
						if ($start_month == "Mar"){$month_no = '03';}
						if ($start_month == "Apr"){$month_no = '04';}
						if ($start_month == "May"){$month_no = '05';}
						if ($start_month == "Jun"){$month_no = '06';}
						if ($start_month == "Jul"){$month_no = '07';}
						if ($start_month == "Aug"){$month_no = '08';}
						if ($start_month == "Sep"){$month_no = '09';}
						if ($start_month == "Oct"){$month_no = '10';}
						if ($start_month == "Nov"){$month_no = '11';}
						if ($start_month == "Dec"){$month_no = '12';}
					$start_date = $start_year."-".$month_no."-".$start_day;
						if ($end_month == "Jan"){$end_month_no = '01';}
						if ($end_month == "Feb"){$end_month_no = '02';}
						if ($end_month == "Mar"){$end_month_no = '03';}
						if ($end_month == "Apr"){$end_month_no = '04';}
						if ($end_month == "May"){$end_month_no = '05';}
						if ($end_month == "Jun"){$end_month_no = '06';}
						if ($end_month == "Jul"){$end_month_no = '07';}
						if ($end_month == "Aug"){$end_month_no = '08';}
						if ($end_month == "Sep"){$end_month_no = '09';}
						if ($end_month == "Oct"){$end_month_no = '10';}
						if ($end_month == "Nov"){$end_month_no = '11';}
						if ($end_month == "Dec"){$end_month_no = '12';}
					$end_date = $end_year."-".$end_month_no."-".$end_day;
                    $reg_form_defaults = unserialize($row['reg_form_defaults']);
                    if ($reg_form_defaults !=""){
                        if (in_array("Address", $reg_form_defaults)) {$inc_address = "Y";}
                        if (in_array("City", $reg_form_defaults)) {$inc_city = "Y";}
                        if (in_array("State", $reg_form_defaults)) {$inc_state = "Y";}
                        if (in_array("Zip", $reg_form_defaults)) {$inc_zip = "Y";}
                        if (in_array("Phone", $reg_form_defaults)) {$inc_phone = "Y";}
                        }
   		            if ($reg_limit == ''){$reg_limit = 999;}
                    if ($event_cost == ''){$event_cost= 0;}
                    if ($coupon_code_price == ''){$coupon_code_price = 0;}
             }
			
            	$sql2= "SELECT SUM(num_people) FROM " . get_option('events_attendee_tbl') . " WHERE event_id='$event_id'";
				$result2 = mysql_query($sql2);
	
				while($row = mysql_fetch_array($result2)){
					$number_attendees =  $row['SUM(num_people)'];
				}
				
				if ($number_attendees == '' || $number_attendees == 0){
					$number_attendees = '0';
				}
				
			/*	if ($reg_limit == "" || $reg_limit == " " || $reg_limit == "999"){
					$reg_limit = "&#8734;";
				}
	       */
	
	update_option ( "current_event", $event_name );
	//Query Database for Event Organization Info to email registrant BHC
	//$sql  = "SELECT * FROM wp_events_organization WHERE id='1'"; 
	
    /*This code outmoded by $ER_org_data['array']
    $events_organization_tbl = get_option ( 'events_organization_tbl' );
	$sql = "SELECT * FROM " . $events_organization_tbl . " WHERE id='1'";
	$result = mysql_query ( $sql );
	
	while ( $row = mysql_fetch_assoc ( $result ) ) {
		$org_id = $row ['id'];
		$Organization = $row ['organization'];
		$Organization_street1 = $row ['organization_street1'];
		$Organization_street2 = $row ['organization_street2'];
		$Organization_city = $row ['organization_city'];
		$Organization_state = $row ['organization_state'];
		$Organization_zip = $row ['organization_zip'];
		$contact = $row ['contact_email'];
		$registrar = $row ['contact_email'];
		$payment_vendor_id = $row ['payment_vendor_id'];
		$currency_format = $row ['currency_format'];
		$events_listing_type = $row ['events_listing_type'];
		$message = $row ['message'];
	}
	*/
	//get attendee count	
	$events_attendee_tbl = get_option ( 'events_attendee_tbl' );

	$sql= "SELECT SUM(num_people) FROM " . $events_attendee_tbl . " WHERE event_id='$event_id'";
	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result)){
		$num =  $row['SUM(num_people)'];
		};
	
	
		
		?>
<?php //JavaScript for Registration Form Validation 

define ( "EVNT_RGR_PLUGINPATH", "/" . plugin_basename ( dirname ( __FILE__ ) ) . "/" );
define ( "EVNT_RGR_PLUGINFULLURL", WP_PLUGIN_URL . EVNT_RGR_PLUGINPATH );
$cap_url = EVNT_RGR_PLUGINFULLURL."/cimg/";
$md5_url = EVNT_RGR_PLUGINFULLURL."/md5.js";

//javascript captcha - adapted from	Jonathan Feaster (http://www.archreality.com/) Version: 2.0 
//Based on: Gimpy CAPTCHA Project at Carnegie Mellon University (http://www.captcha.net/) 
?>
<script type="text/javascript" src="<?php echo $md5_url;?>"></script>
<SCRIPT>

<?php if ($ER_org_data['captcha']=='Y'){?>

var imgdir = "<?php echo $cap_url;?>"; // identify directory where captcha images are located
var jfldid = "uword"; // identify word field id name
var jfldsz = 15; // identify word field size

function sjcap(jfldcls){
imgdir = encodeURIComponent(imgdir);
if (jfldcls == null){
jfldcls = "";
}
anum = (Math.floor(Math.random()*191))+1;
imgid = parseInt(anum);
cword = 
["60ee0bc62638fccf2d37ac27a634a9e9", "68e2d83709f317938b51e53f7552ed04", "f4c9385f1902f7334b00b9b4ecd164de",
 "df491a4de50739fa9cffdbd4e3f4b4bb", "ef56b0b0ddb93c2885892c06be830c68", "fe4c0f30aa359c41d9f9a5f69c8c4192",
 "cbf4e0b7971051760907c327e975f4e5", "ea9e801b0d806f2398bd0c7fe3f3f0cd", "609a8f6f218fdfe6f955e19f818ec050",
 "cbf4e0b7971051760907c327e975f4e5", "8cb554127837a4002338c10a299289fb", "28f9b1cae5ae23caa8471696342f6f0c",
 "74e04ddb55ce3825f65ebec374ef8f0d", "567904efe9e64d9faf3e41ef402cb568", "7edabf994b76a00cbc60c95af337db8f",
 "639849f6b368019778991b32434354fc", "7edabf994b76a00cbc60c95af337db8f", "dd8fc45d87f91c6f9a9f43a3f355a94a",
 "eb5c1399a871211c7e7ed732d15e3a8b", "8cb554127837a4002338c10a299289fb", "0b8263d341de01f741e4deadfb18f9eb",
 "87fa4eaaf3698e1b1e2caadabbc8ca60", "327a6c4304ad5938eaf0efb6cc3e53dc", "841a2d689ad86bd1611447453c22c6fc",
 "ceb20772e0c9d240c75eb26b0e37abee", "a3e2a6cbf4437e50816a60a64375490e", "bc8fba5b68a7babc05ec51771bf6be21",
 "68934a3e9455fa72420237eb05902327", "c9fab33e9458412c527c3fe8a13ee37d", "2fc01ec765ec0cb3dcc559126de20b30",
 "fcc790c72a86190de1b549d0ddc6f55c", "918b81db5e91d031548b963c93845e5b", "9dfc8dce7280fd49fc6e7bf0436ed325",
 "ea82410c7a9991816b5eeeebe195e20a", "fb81c91eb92d6cb64aeb64c3f37ef2c4", "8d45c85b51b27a04ad7fdfc3f126f9f8",
 "70dda5dfb8053dc6d1c492574bce9bfd", "b9b83bad6bd2b4f7c40109304cf580e1", "981c1e7b3795da18687613fbd66d4954",
 "e170e3a15923188224c1c2bd1477d451", "fb81c91eb92d6cb64aeb64c3f37ef2c4", "cb15e32f389b7af9b285a63ca1044651",
 "632a2406bbcbcd553eec45ac14b40a0a", "e7b95b49658278100801c88833a52522", "6d4db5ff0c117864a02827bad3c361b9",
 "8b373710bcf876edd91f281e50ed58ab", "508c75c8507a2ae5223dfd2faeb98122", "97f014516561ef487ec368d6158eb3f4",
 "23678db5efde9ab46bce8c23a6d91b50", "2d6b0cefb06fd579a62bf56f02b6c2b3", "f1bdf5ed1d7ad7ede4e3809bd35644b0",
 "3ddaeb82fbba964fb3461d4e4f1342eb", "c9507f538a6e79c9bd6229981d6e05a3", "9e925e9341b490bfd3b4c4ca3b0c1ef2",
 "125097a929a62998c06340ea9ef43d77", "a557264a7d6c783f6fb57fb7d0b9d6b0", "eba478647c77836e50de44b323564bdb",
 "45fe7e5529d283851d93b74536e095a0", "56609ab6ba04048adc2cbfafbe745e10", "d938ad5cbe68bec494fbbf4463ad031d",
 "9bbd993d9da7df60b3fd4a4ed721b082", "a6ab62e9da89b20d720c70602624bfc2", "51037a4a37730f52c8732586d3aaa316",
 "7c4f29407893c334a6cb7a87bf045c0d", "3b7770f7743e8f01f0fd807f304a21d0", "29d233ae0b83eff6e5fbd67134b88717",
 "8d45c85b51b27a04ad7fdfc3f126f9f8", "9aa91f81de7610b371dd0e6fe4168b01", "9f27410725ab8cc8854a2769c7a516b8",
 "6ee6a213cb02554a63b1867143572e70", "918b81db5e91d031548b963c93845e5b", "3767b450824877f2b8f284f7a5625440",
 "81513effdf5790b79549208838404407", "7aea2552dfe7eb84b9443b6fc9ba6e01", "d8735f7489c94f42f508d7eb1c249584",
 "fde27e470207e146b29b8906826589cb", "2a2d595e6ed9a0b24f027f2b63b134d6", "99e0d947e01bbc0a507a1127dc2135b1",
 "6758fcdc0da017540d11889c22bb5a6e", "ab1991b4286f7e79720fe0d4011789c8", "28f9b1cae5ae23caa8471696342f6f0c",
 "f5b75010ea8a54b96f8fe7dafac65c18", "2570c919f5ef1d7091f0f66d54dac974", "ada15bd1a5ddf0b790ae1dcfd05a1e70",
 "eb88d7636980738cd0522ea69e212905", "83ab982dd08483187289a75163dc50fe", "8ac20bf5803e6067a65165d9df51a8e7",
 "7c4f29407893c334a6cb7a87bf045c0d", "67942503875c1ae74e4b5b80a0dade01", "d74fdde2944f475adc4a85e349d4ee7b",
 "163ccb6353c3b5f4f03cda0f1c5225ba", "6b1628b016dff46e6fa35684be6acc96", "de1b2a7baf7850243db71c4abd4e5a39",
 "5eda0ea98768e91b815fa6667e4f0178", "23ec24c5ca59000543cee1dfded0cbea", "ea9e801b0d806f2398bd0c7fe3f3f0cd",
 "35393c24384b8862798716628f7bc6f4", "28b26be59c986170c572133aaace31c2", "c2bfd01762cfbe4e34cc97b9769b4238",
 "22811dd94d65037ef86535740b98dec8", "acaa16770db76c1ffb9cee51c3cabfcf", "7516c3b35580b3490248629cff5e498c",
 "b04ab37e571600800864f7a311e2a386", "7e25b972e192b01004b62346ee9975a5", "2764ca9d34e90313978d044f27ae433b",
 "660cb6fe7437d4b40e4a04b706b93f70", "87a429872c7faee7e8bc9268d5bf548e", "31c13f47ad87dd7baa2d558a91e0fbb9",
 "e6ec529ba185279aa0adcf93e645c7cd", "21a361d96e3e13f5f109748c2a9d2434", "85814ce7d88361ec8eb8e07294043bc3",
 "a5fdad9de7faf3a0492812b9cb818d85", "0b8263d341de01f741e4deadfb18f9eb", "0cb47aeb6e5f9323f0969e628c4e59f5",
 "23a58bf9274bedb19375e527a0744fa9", "7e25b972e192b01004b62346ee9975a5", "b9d27d6b3d1915aacd5226b9d702bdbb",
 "6758fcdc0da017540d11889c22bb5a6e", "e2704f30f596dbe4e22d1d443b10e004", "da4f0053a5c13882268852ae2da2e466",
 "1562eb3f6d9c5ac7e159c04a96ff4dfe", "a94aa000f9a94cc51775bd5eac97c926", "1e4483e833025ac10e6184e75cb2d19d",
 "a957a3153eb7126b1c5f8b6aac35de53", "731b886d80d2ea138da54d30f43b2005", "a850c17cba5eb16b0d3d40a106333bd5",
 "7516c3b35580b3490248629cff5e498c", "d508fe45cecaf653904a0e774084bb5c", "18ccf61d533b600bbf5a963359223fe4",
 "f4d3b5a1116ded3facefb8353d0bd5ba", "28b26be59c986170c572133aaace31c2", "d5ca322453f2986b752e58b11af83d96",
 "37b19816109a32106d109e83bbb3c97d", "0423fa423baf1ea8139f6662869faf2f", "8ab8a4dfab57b4618331ffc958ebb4ec",
 "85814ce7d88361ec8eb8e07294043bc3", "273b9ae535de53399c86a9b83148a8ed", "4c9184f37cff01bcdc32dc486ec36961",
 "8ee2027983915ec78acc45027d874316", "1cba77c39b4d0a81024a7aada3655a28", "de1b2a7baf7850243db71c4abd4e5a39",
 "608f0b988db4a96066af7dd8870de96c", "06a224da9e61bee19ec9eef88b95f934", "df55340f75b5da454e1c189d56d7f31b",
 "8c728e685ddde9f7fbbc452155e29639", "2570c919f5ef1d7091f0f66d54dac974", "dce7c4174ce9323904a934a486c41288",
 "573ce5969e9884d49d4fab77b09a306a", "d5ca322453f2986b752e58b11af83d96", "eb88d7636980738cd0522ea69e212905",
 "e7e94d9ef1edaf2c6c55e9966b551295", "762f8817ab6af0971fe330dbf46a359a", "d8a48e3f0e1322d53d401e3dcb3360db",
 "c1940aeeb9693a02e28c52eb85ce261c", "d74fdde2944f475adc4a85e349d4ee7b", "b6a5d96a4e99b63723ab54ddb471baad",
 "6b157916b43b09df5a22f658ccb92b64", "bec670e5a55424d840db8636ecc28828", "4a6cbcd66d270792b89f50771604d093",
 "07202a7e6cbfbabe27abba87989f807e", "d60db28d94d538bbb249dcc7f2273ab1", "123402c04dcfb6625f688f771a5fc05d",
 "cd69b4957f06cd818d7bf3d61980e291", "be1ab1632e4285edc3733b142935c60b", "2bda2998d9b0ee197da142a0447f6725",
 "ba535ef5a9f7b8bc875812bb081286bb", "e9f40e1f1d1658681dad2dac4ae0971e", "eabe04e738cfb621f819e4e8f9489234",
 "aa2d6e4f578eb0cfaba23beef76c2194", "126ac4b07f93bc4f7bed426f5e978c16", "f43dff9a0dc54f0643d0c6d7971635f0",
 "ccaaac957ec37bde4c9993a26a064730", "2feaaf89c21770ea5c21196bc33848dd", "07cf4f8f5d8b76282917320715dda2ad",
 "1ffd9e753c8054cc61456ac7fac1ac89", "6050ce63e4bce6764cb34cac51fb44d1", "327a6c4304ad5938eaf0efb6cc3e53dc",
 "b82c91e2103d0a495c099f0a12f66363", "41d1de28e96dc1cde568d3b068fa17bb", "cad1c068cb62b0681fe4c33d1db1bad6",
 "de1b2a7baf7850243db71c4abd4e5a39", "75e52a0ecfafeda17a34fc60111c1f0b", "fc7e987f23de5bd6562b7c0063cad659",
 "126ac4b07f93bc4f7bed426f5e978c16", "fcc790c72a86190de1b549d0ddc6f55c", "72792fa10d4ca61295194377da0bcc05",
 "821f03288846297c2cf43c34766a38f7", "faec47e96bfb066b7c4b8c502dc3f649", "78b6367af86e03f19809449e2c365ff5",
 "015f28b9df1bdd36427dd976fb73b29d", "755f85c2723bb39381c7379a604160d8"];

document.write("<p><input type=\"text\" id=\"" + jfldid + "\" name=\"" + jfldid + "\" class=\"" + jfldcls + "\" size=\"" +  jfldsz + "\"><\/p>");
document.write("<p><img src=\"" + decodeURIComponent(imgdir) + imgid + ".jpg\" width=\"290\" height=\"80\" alt=\"\"><\/p>");
}


function jcap(){
var uword = hex_md5(document.getElementById(jfldid).value);
if (uword==cword[anum-1]) {
return true;
}
else {
   return false;
  }
}

<?php } ?>

function checkInternationalPhone(strPhone){

// Declaring required variables
var digits = "0123456789";
// non-digit characters which are allowed in phone numbers
var phoneNumberDelimiters = "()- ";
// characters which are allowed in international phone numbers
// (a leading + is OK)
var validWorldPhoneChars = phoneNumberDelimiters + "+";
// Minimum no of digits in an international phone no.
var minDigitsInIPhoneNumber = 10;

function isInteger(s)
{   var i;
    for (i = 0; i < s.length; i++)
    {   
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}
function trim(s)
{   var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not a whitespace, append to returnString.
    for (i = 0; i < s.length; i++)
    {   
        // Check that current character isn't whitespace.
        var c = s.charAt(i);
        if (c != " ") returnString += c;
    }
    return returnString;
}
function stripCharsInBag(s, bag)
{   var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++)
    {   
        // Check that current character isn't whitespace.
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}

var bracket=3
strPhone=trim(strPhone)
if(strPhone.indexOf("+")>1) return false
if(strPhone.indexOf("-")!=-1)bracket=bracket+1
if(strPhone.indexOf("(")!=-1 && strPhone.indexOf("(")>bracket)return false
var brchr=strPhone.indexOf("(")
if(strPhone.indexOf("(")!=-1 && strPhone.charAt(brchr+2)!=")")return false
if(strPhone.indexOf("(")==-1 && strPhone.indexOf(")")!=-1)return false
s=stripCharsInBag(strPhone,validWorldPhoneChars);
return (isInteger(s) && s.length >= minDigitsInIPhoneNumber);
}

function echeck(str) {
		var at="@"
		var dot="."
		var em = ""
		var lat=str.indexOf(at)
		var lstr=str.length
		var ldot=str.indexOf(dot)
		if (str.indexOf(at)==-1){
		    return false;
		    }

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		    return false;
		    
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		     return false;
		}

		 if (str.indexOf(at,(lat+1))!=-1){
		      return false;
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		     return false;
		 }

		 if (str.indexOf(dot,(lat+2))==-1){
		    return false;
		 }
		
		 if (str.indexOf(" ")!=-1){
		    return false;
		 }

 		 return true;					
}

function testIsValidObject(objToTest) {
if (objToTest == null || objToTest == undefined) {
return false;
}
return true;
}

function jcap(){

var uword = hex_md5(document.getElementById(jfldid).value);

if (uword==cword[anum-1]) {
return true;
}

else {
return false;
}
}

function validateForm(form) { 
	
var msg = "";

if (form.fname.value == "") {  msg += "\n " +"Please enter your first name."; 
   		form.fname.focus( ); 
   	 }
if (form.lname.value == "") {  msg += "\n " +"Please enter your last name."; 
   		form.lname.focus( ); 
   		}
	
if (echeck(form.email.value)==false){
		msg += "\n " + "Email format not correct!";
		}

if(form.phone) {
	if (form.phone.value == "" || form.phone.value==null) {  msg += "\n " +"Please enter your phone number."; 
   		form.phone.focus( ); 
   		}
    if (checkInternationalPhone(form.phone.value)==false){
		msg += "\n " +"Please use correct format for your phone number."; 
		form.value=""
		form.phone.focus()
        }
}
	
if(form.address) {
if (form.address.value == "") {  msg += "\n " +"Please enter your address."; 
   		form.address.focus( ); 
   		}
        }
if(form.city) {
if (form.city.value == "") {  msg += "\n " +"Please enter your city."; 
   		form.city.focus( ); 
   		}  }
if(form.state) {
if (form.state.value == "") { msg += "\n " + "Please enter your state."; 
   		form.state.focus( ); 
   	 }
     }

if(form.zip) {   	    
if (form.zip.value == "") {  msg += "\n " +"Please enter your zip code."; 
   		form.zip.focus( ); 
   		 }
         }
    
//Validate Extra Questions
function trim(s) {if (s) {return s.replace(/^\s*|\s*$/g,"");} return null;}
				
	var inputs = form.getElementsByTagName("input");
	var e;

//Start Extra Questions Check
	for( var i = 0, e; e = inputs[i]; i++ )
	{
		var value = e.value ? trim(e.value) : null;
	
		if (e.type == "text" && e.title && !value && e.className == "r")
		{msg += "\n " + e.title;}
		
	
	if ((e.type == "radio" || e.type == "checkbox") && e.className == "r") {
				var rd =""
				var controls = form.elements;
				function getSelectedControl(group) 
					{
					for (var i = 0, n = group.length; i < n; ++i)
						if (group[i].checked) return group[i];
						return null;
					}
				if (!getSelectedControl(controls[e.name]))
								{msg += "\n " + e.title;}
			} 
			

	}

	var inputs = form.getElementsByTagName("textarea");
	var e;
	
	//Start Extra TextArea Questions Check
	for( var i = 0, e; e = inputs[i]; i++ )
	{
		var value = e.value ? trim(e.value) : null;
		if (!value && e.className == "r")
		{msg += "\n " + e.title;}
	}
	var inputs = form.getElementsByTagName("select");
	var e;
	
	//Start Extra TextArea Questions Check
	for( var i = 0, e; e = inputs[i]; i++ )
	{
		var value = e.value ? trim(e.value) : null;
		if ((!value || value =='') && e.className == "r")
		{msg += "\n " + e.title;}
	}
<?php if ($ER_org_data['captcha']=='Y'){?>
//Check Captcha
if (jcap() == false){
		msg += "\n " +"ERROR: Invalid Security Code."; 
        }
<?php } ?>
     if (msg.length > 0) {
			msg = "The following fields need to be completed before you can submit.\n\n" + msg;
			alert(msg);
            if (document.getElementById("mySubmit").disabled==true){
                document.getElementById("mySubmit").disabled=false;} 
                document.getElementById("mySubmit").focus( );
            return false;
            
		}
	
	return true;   

}

</SCRIPT>
<?php 
	
if ($event_cost == ""||$event_cost =="0"||$event_cost=="0.00"){$event_cost = "FREE";}

if ($header_image != ""){echo "<p align='center'><img src='".$header_image."'  width='450' align='center'></p>";}
    	if ( $reg_limit > "$num" || $reg_limit == "") {
		echo "<p align='center'><b>".$events_lang['eventFormHeader'] . $event_name .  " - ".$event_identifier."</b></p>";
		echo "<table width='100%'><td>";
		if ($display_desc == "Y") {
			echo "<td span='2'>" . $event_desc ."</td>";
		}
		echo "</table>";
		echo "<table width='500'><td>";
		if ($custom_cur == ""){if ($currency_format == "USD" || $currency_format == "") {$currency_format = "$";}			}
		if ($custom_cur != "" || $custom_cur != "USD"){$currency_format = $custom_cur;}
		if ($custom_cur == "USD") {$currency_format = "$";}
			
		if ($event_cost == "FREE"){
		  echo "<b>" . $event_name . " - FREE EVENT </b></p></p>";
          }
          else if ($event_cost != "") {if ($events_lang_flag=='de')
				echo "<b>" . $event_name . " - Kosten " .$event_cost . " " .  $currency_format . "</b></p></p>";
      		else
			  	echo "<b>" . $event_name . " - Cost " . $currency_format . " " . $event_cost . "</b></p></p>";
			}
            
?>

</td>
<tr>
	<td>
	<form method="post" action="<?php echo $_SERVER ['REQUEST_URI'];?>" onSubmit="mySubmit.disabled=true;return validateForm(this)">
	<p align="left"><b><?php
		echo $events_lang ['firstName'];
		?>: <br />
	<input tabIndex="1" maxLength="40" size="47" name="fname"></b></p>
	<p align="left"><b><?php
		echo $events_lang ['lastName'];
		?>:<br />
	<input tabIndex="2" maxLength="40" size="47" name="lname"></b></p>
	<p align="left"><b><?php
		echo $events_lang ['email'];
		?>:<br />
	<input tabIndex="3" maxLength="40" size="47" name="email"></b></p>
	
        
<?php if ($inc_phone == "Y"){ ?>
	<p align="left"><b><?php
		echo $events_lang ['phone'];
		?>:<br />
  <input tabIndex="4" maxLength="20" size="25" name="phone"></b></p>
<?php } 
if ($inc_address == "Y"){  ?> 
        <p align="left"><b>
     <?php  echo $events_lang ['address'];	?>:<br />
       	<input tabIndex="5" maxLength="35" size="49" name="address"></b></p>
<?php } 

if ($inc_city == "Y"){ ?>
        <p align="left"><b>
    <?php echo $events_lang ['city'];?>:<br />
        <input tabIndex="6" maxLength="25" size="35" name="city"> </b></p>
<?php } 

if ($inc_state == "Y"){ ?>
    <?php //no state necessary in germany
      if ($events_lang_flag!="de")
      {  ?>  
      <p align="left"><b>
    <?php  echo $events_lang ['state'];}	?>:<br />
    	<input tabIndex="7" maxLength="20" size="18" name="state"></b></p>
<?php } 

if ($inc_zip == "Y"){	?>
	<p align="left"><b>
<?php echo $events_lang ['zip'];?>:<br />
	<input tabIndex="8" maxLength="10" size="15" name="zip"></b></p>
<?php } 

if ($multiple == "Y"){?>			
			
<p align="left"><b>	Additional attendees?
      <select name="num_people" style="width:70px;margin-top:4px">
        <option value="1" selected>None</option>
        <option value="2">1</option>
        <option value="3">2</option>
        <option value="4">3</option>
        <option value="5">4</option>
        <option value="6">5</option>
      </select>		
      </b></p>
      
      <?php
	  }
if ($multiple == "N"){?>
<input type="hidden" name="num_people" value="1"> 
<?php
}
		/*
			<p align="left"><b>How did you hear about this event?</b><br /><select tabIndex="9" size="1" name="hear">
			<option value="pick one" selected>pick one</option>
			<option value="Website">Website</option>
			<option value="A Friend">A Friend</option>
			<option value="Brochure">A Brochure</option>
			<option value="Announcment">An Announcment</option>
			<option value="Other">Other</option>
			</select></p>
			*/
		
			/* TODO IJ not for everyone nesseccary...
			if ($event_cost != "") {
			?>
			<p align="left">
			<b><?php echo $events_lang['payingPlan'];?></b><br />
	    <select tabIndex="10" size="1" name="payment">
		  <option value="pickone" selected><?php echo $events_lang['pickone']; ?></option>
			<?php
			if ($payment_vendor_id != "") {
				echo "<option value=\"Paypal\">$events_lang[paypal]</option>";
			}
			
			echo "<option value=\"Cash\">$events_lang[cash]</option>";
			
			if ($checks == "yes" && $events_lang_flag!='de') {  //very unusual in germany
        echo "<option value=\"Check\">$events_lang[check]</option>";
			}
			?>
			</select></font></p>
			<?php
		} else {
			?><input type="hidden" name="payment" value="free event"><?
		}
*/
			
if ($use_coupon =="Y"){
    echo "<p align='left'><b>Please enter coupon code for discount?".
    	"<input maxLength='10' size='12' name='coupon'></b></p>";
}
		$events_question_tbl = get_option ( 'events_question_tbl' );
		$questions = $wpdb->get_results ( "SELECT * from `$events_question_tbl` where event_id = '$event_id' order by sequence" );
		if ($questions) {
			foreach ( $questions as $question ) {
				
				echo "<p align='left'><b>" . $question->question . BR;
				event_form_build ( $question );
				echo "</b></p>";
			}
		}
		
		?>

<hr />
<?php if ($ER_org_data['captcha']=='Y'){?>
<p>Enter the security code as it is shown (required):<script type="text/javascript">sjcap("altTextField");</script>
		<noscript><p>[This resource requires a Javascript enabled browser.]</p></noscript>
<?php } ?>
		<input type="hidden" name="regevent_action" value="post_attendee"> 
        <input type="hidden" name="submitted_token" value="<?php $raw_key = get_option('awr_form_token');
        $submit_key = $raw_key +'20';
        echo $submit_key ;?>" />
        <input type="hidden" name="event_id" value="<?php echo $event_id;?>">
	<p align="center"><input type="submit" id="mySubmit" name="mySubmit" value="<?php echo $events_lang['submit']; ?>"/> <font color="#FF0000"><b><?php echo $events_lang['submitHint'];?></b></font>
	
	</form>
	</td>
</tr>
</table>
</body>
<?php
	} else {
		echo $events_lang ['maxAttendeesInfo'];
		echo "<p>Current Number of Attendees: " . $num . "</p>";
	}


}

function add_attendees_to_db() {
	global $wpdb, $events_lang;
    
    $current_event = get_option ( 'current_event' );
	$registrar = get_option ( 'registrar' );
	$events_attendee_tbl = get_option ( 'events_attendee_tbl' );
	
	$fname = $_POST ['fname'];
	$lname = $_POST ['lname'];
	$address = $_POST ['address'];
	$city = $_POST ['city'];
	$state = $_POST ['state'];
	$zip = $_POST ['zip'];
	$phone = $_POST ['phone'];
	$email = $_POST ['email'];
	$hear = $_POST ['hear'];
	$num_people = $_POST ['num_people'];
    $coupon = $_POST['coupon'];
	$event_id = $_POST ['event_id'];
	$payment = $_POST ['payment'];
	$custom_1 = $_POST ['custom_1'];
	$custom_2 = $_POST ['custom_2'];
	$custom_3 = $_POST ['custom_3'];
	$custom_4 = $_POST ['custom_4'];
	update_option ( "attendee_first", $fname );
	update_option ( "attendee_last", $lname );
	update_option ( "attendee_name", $fname . " " . $lname );
	update_option ( "attendee_email", $email );
	
$sql = "INSERT INTO " . $events_attendee_tbl . " (lname ,fname ,address ,city ,state ,zip ,email ,phone ,hear ,coupon,num_people, payment, event_id) VALUES ('$lname', '$fname', '$address', '$city', '$state', '$zip', '$email', '$phone', '$hear', '$coupon','$num_people', '$payment', '$event_id')";

	$wpdb->query ( $sql );
	

	
	// Insert Extra From Post Here
	$events_question_tbl = get_option ( 'events_question_tbl' );
	$events_answer_tbl = get_option ( 'events_answer_tbl' );
	$reg_id = $wpdb->get_var ( "SELECT LAST_INSERT_ID()" );
	
	$questions = $wpdb->get_results ( "SELECT * from `$events_question_tbl` where event_id = '$event_id'" );
	if ($questions) {
		foreach ( $questions as $question ) {
			switch ($question->question_type) {
				case "TEXT" :
				case "TEXTAREA" :
				case "DROPDOWN" :
					$post_val = $_POST [$question->question_type . '_' . $question->id];
					$wpdb->query ( "INSERT into `$events_answer_tbl` (registration_id, question_id, answer)
					values ('$reg_id', '$question->id', '$post_val')" );
					break;
				case "SINGLE" :
					$post_val = $_POST [$question->question_type . '_' . $question->id];
					$wpdb->query ( "INSERT into `$events_answer_tbl` (registration_id, question_id, answer)
					values ('$reg_id', '$question->id', '$post_val')" );
					break;
				case "MULTIPLE" :
					$value_string = '';
					for ($i=0; $i<count($_POST[$question->question_type.'_'.$question->id]); $i++){ 
					//$value_string = $value_string +","+ ($_POST[$question->question_type.'_'.$question->id][$i]); 
					$value_string .= $_POST[$question->question_type.'_'.$question->id][$i].","; 
					}
					//echo "Value String - ".$value_string;
					/*$values = explode ( ",", $question->response );
					$value_string = '';
					foreach ( $values as $key => $value ) {
						$post_val = $_POST [$question->question_type . '_' . $question->id . '_' . $key];
						if ($key > 0 && ! empty ( $post_val )) $value_string .= ',';
						$value_string .= $post_val;
					}*/
					$wpdb->query ( "INSERT into `$events_answer_tbl` (registration_id, question_id, answer)
					values ('$reg_id', '$question->id', '$value_string')" );
					break;
			}
		}
	}
	
	
	//Added by IJ: get the attendee-number and add to subject of email for having a unique attendee-number
	$sql = "select max(id) as attnum from $events_attendee_tbl ";
	$result = mysql_query ( $sql );
	$row = mysql_fetch_array ( $result );
	$attnum = $row ['attnum'];	
	
	
	//Query Database for Event Organization Info to email registrant BHC
	$events_organization_tbl = get_option ( 'events_organization_tbl' );
	$sql = "SELECT * FROM " . $events_organization_tbl . " WHERE id='1'";
	// $sql  = "SELECT * FROM wp_events_organization WHERE id='1'"; 
	

	$result = mysql_query ( $sql );
	while ( $row = mysql_fetch_assoc ( $result ) ) {
		$org_id = $row ['id'];
		$Organization = $row ['organization'];
		$Organization_street1 = $row ['organization_street1'];
		$Organization_street2 = $row ['organization_street2'];
		$Organization_city = $row ['organization_city'];
		$Organization_state = $row ['organization_state'];
		$Organization_zip = $row ['organization_zip'];
		$contact = $row ['contact_email'];
		$registrar = $row ['contact_email'];
		$payment_vendor_id = $row ['payment_vendor_id'];
		$currency_format = $row ['currency_format'];
		$return_url = $row ['return_url'];
        $events_listing_type = $row ['events_listing_type'];
		$default_mail = $row ['default_mail'];
		$conf_message = $row ['message'];
	}
	
	$events_detail_tbl = get_option ( 'events_detail_tbl' );
	
	$sql = "SELECT * FROM " . $events_detail_tbl . " WHERE id='" . $event_id . "'";
	$result = mysql_query ( $sql );
	while ( $row = mysql_fetch_assoc ( $result ) ) {
		$event_name = $row ['event_name'];
		$event_desc = $row ['event_desc']; // BHC
		$display_desc = $row ['display_desc'];
		$image = $row ['image_link'];
		$identifier = $row ['event_identifier'];
		$reg_limit = $row ['reg_limit'];
		$cost = $row ['event_cost'];
		$start_month = $row ['start_month'];
		$start_day = $row ['start_day'];
		$start_year = $row ['start_year'];
		$multiple = $row ['multiple'];
		$end_month = $row ['end_month'];
		$end_day = $row ['end_day'];
		$end_year = $row ['end_year'];
		$start_time = $row ['start_time'];
		$end_time = $row ['end_time'];
		$checks = $row ['allow_checks'];
		$active = $row ['is_active'];
		$question1 = $row ['question1'];
		$question2 = $row ['question2'];
		$question3 = $row ['question3'];
		$question4 = $row ['question4'];
		$send_mail = $row ['send_mail'];
		$conf_mail = $row ['conf_mail'];
				$event_location = $row ['event_location'];
		$more_info = $row ['more_info'];
		$custom_cur = $row ['custom_cur'];
		$start_date = $start_month . " " . $start_day . ", " . $start_year;
		$end_date = $end_month . " " . $end_day . ", " . $end_year;
	}
	
	// Email Confirmation to Registrar
	
	$event_name = $current_event;
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/plain; charset=UTF-8\r\n";
    $headers .= 'From: "' . $Organization . '" <' . $registrar . ">\r\n";
	
	$distro = $registrar;
	$message = ("$fname $lname  has signed up on-line for $event_name.\n\nMy email address is  $email.");
	
	wp_mail ( $distro, $event_name . " Number: $attnum", $message, $headers );
  
	
	//Email Confirmation to Attendee
	$query = "SELECT * FROM $events_attendee_tbl WHERE fname='$fname' AND lname='$lname' AND email='$email'";
	$result = mysql_query ( $query ) or die ( 'Error : ' . mysql_error () );
	while ( $row = mysql_fetch_assoc ( $result ) ) {
		$id = $row ['id'];
	}
	
   $arr_params = array ('id' => $id);
   $payment_link =  add_query_arg($arr_params, $return_url);
    //$payment_link = $return_url . "?id=" . $id;
	
	//Email Confirmation to Attendee
	$SearchValues = array ("[fname]", "[lname]", "[phone]", "[event]", "[description]", "[cost]", "[currency]", "[qst1]", "[qst2]", "[qst3]", "[qst4]", "[contact]", "[company]", "[co_add1]", "[co_add2]", "[co_city]", "[co_state]", "[co_zip]", "[payment_url]", "[start_date]", "[start_time]", "[end_date]", "[end_time]","[snum]", "[num_people]" );
	
	$ReplaceValues = array ($fname, $lname, $phone, $event_name, $event_desc, $cost, $custom_cur, $question1, $question2, $question3, $question4, $contact, $Organization, $Organization_street1, $Organization_street2, $Organization_city, $Organization_state, $Organization_zip, $payment_link, $start_date, $start_time, $end_date, $end_time, $attnum, $num_people);
	
	$custom = str_replace ( $SearchValues, $ReplaceValues, $conf_mail );
	$default_replaced = str_replace ( $SearchValues, $ReplaceValues, $conf_message );
	
	$distro = $email;
	
	if ($default_mail == 'Y') {
		if ($send_mail == 'Y') {
			wp_mail ( $distro, $event_name, $custom, $headers );
		}
	}
	
	if ($default_mail == 'Y') {
		if ($send_mail == 'N') {
			wp_mail ( $distro, $event_name, $default_replaced, $headers );
		}
	}
	
	//Get registrars id from the data table .
	

	$query = "SELECT * FROM $events_attendee_tbl WHERE fname='$fname' AND lname='$lname' AND email='$email'";
	$result = mysql_query ( $query ) or die ( 'Error : ' . mysql_error () );
	while ( $row = mysql_fetch_assoc ( $result ) ) {
		$id = $row ['id'];
		$lname = $row ['lname'];
		$fname = $row ['fname'];
		$address = $row ['address'];
		$city = $row ['city'];
		$state = $row ['state'];
		$zip = $row ['zip'];
		$email = $row ['email'];
		$num_people = $row ['num_people'];
		$phone = $row ['phone'];
		$date = $row ['date'];
		$paystatus = $row ['paystatus'];
		$txn_type = $row ['txn_type'];
		$amt_pd = $row ['amount_pd'];
		$date_pd = $row ['paydate'];
		$event_id = $row ['event_id'];
		$custom1 = $row ['custom_1'];
		$custom2 = $row ['custom_2'];
		$custom3 = $row ['custom_3'];
		$custom4 = $row ['custom_4'];
	}
	
	update_option ( "attendee_id", $id );
	
	//Send screen confirmation & forward to paypal if selected.
	

	echo $events_lang ['registrationConfirm'];
	
	events_payment_page ( $event_id );
}

function manually_add_attendees_to_db() {
	global $wpdb, $events_lang;
	$current_event = get_option ( 'current_event' );
	$registrar = get_option ( 'registrar' );
	$events_attendee_tbl = get_option ( 'events_attendee_tbl' );
	
	$fname = $_POST ['fname'];
	$lname = $_POST ['lname'];
	$address = $_POST ['address'];
	$city = $_POST ['city'];
	$state = $_POST ['state'];
	$zip = $_POST ['zip'];
	$phone = $_POST ['phone'];
	$email = $_POST ['email'];
	$hear = $_POST ['hear'];
	$num_people = $_POST ['num_people'];
    $coupon = $_POST['coupon'];
	$event_id = $_POST ['event_id'];
	$payment = $_POST ['payment'];
	$custom_1 = $_POST ['custom_1'];
	$custom_2 = $_POST ['custom_2'];
	$custom_3 = $_POST ['custom_3'];
	$custom_4 = $_POST ['custom_4'];
	update_option ( "attendee_first", $fname );
	update_option ( "attendee_last", $lname );
	update_option ( "attendee_name", $fname . " " . $lname );
	update_option ( "attendee_email", $email );
	
$sql = "INSERT INTO " . $events_attendee_tbl . " (lname ,fname ,address ,city ,state ,zip ,email ,phone ,hear ,coupon,num_people, payment, event_id) VALUES ('$lname', '$fname', '$address', '$city', '$state', '$zip', '$email', '$phone', '$hear', '$coupon','$num_people', '$payment', '$event_id')";

	$wpdb->query ( $sql );
	

	
	// Insert Extra From Post Here
	$events_question_tbl = get_option ( 'events_question_tbl' );
	$events_answer_tbl = get_option ( 'events_answer_tbl' );
	$reg_id = $wpdb->get_var ( "SELECT LAST_INSERT_ID()" );
	
	$questions = $wpdb->get_results ( "SELECT * from `$events_question_tbl` where event_id = '$event_id'" );
	if ($questions) {
		foreach ( $questions as $question ) {
			switch ($question->question_type) {
				case "TEXT" :
				case "TEXTAREA" :
				case "DROPDOWN" :
					$post_val = $_POST [$question->question_type . '_' . $question->id];
					$wpdb->query ( "INSERT into `$events_answer_tbl` (registration_id, question_id, answer)
					values ('$reg_id', '$question->id', '$post_val')" );
					break;
				case "SINGLE" :
					$post_val = $_POST [$question->question_type . '_' . $question->id];
					$wpdb->query ( "INSERT into `$events_answer_tbl` (registration_id, question_id, answer)
					values ('$reg_id', '$question->id', '$post_val')" );
					break;
				case "MULTIPLE" :
					$value_string = '';
					for ($i=0; $i<count($_POST[$question->question_type.'_'.$question->id]); $i++){ 
					//$value_string = $value_string +","+ ($_POST[$question->question_type.'_'.$question->id][$i]); 
					$value_string .= $_POST[$question->question_type.'_'.$question->id][$i].","; 
					}
					echo "Value String - ".$value_string;
					/*$values = explode ( ",", $question->response );
					$value_string = '';
					foreach ( $values as $key => $value ) {
						$post_val = $_POST [$question->question_type . '_' . $question->id . '_' . $key];
						if ($key > 0 && ! empty ( $post_val )) $value_string .= ',';
						$value_string .= $post_val;
					}*/
					$wpdb->query ( "INSERT into `$events_answer_tbl` (registration_id, question_id, answer)
					values ('$reg_id', '$question->id', '$value_string')" );
					break;
			}
		}
	}
	
	
	//Added by IJ: get the attendee-number and add to subject of email for having a unique attendee-number
	$sql = "select max(id) as attnum from $events_attendee_tbl ";
	$result = mysql_query ( $sql );
	$row = mysql_fetch_array ( $result );
	$attnum = $row ['attnum'];	
	
	
	//Query Database for Event Organization Info to email registrant BHC
	$events_organization_tbl = get_option ( 'events_organization_tbl' );
	$sql = "SELECT * FROM " . $events_organization_tbl . " WHERE id='1'";
	// $sql  = "SELECT * FROM wp_events_organization WHERE id='1'"; 
	

	$result = mysql_query ( $sql );
	while ( $row = mysql_fetch_assoc ( $result ) ) {
		$org_id = $row ['id'];
		$Organization = $row ['organization'];
		$Organization_street1 = $row ['organization_street1'];
		$Organization_street2 = $row ['organization_street2'];
		$Organization_city = $row ['organization_city'];
		$Organization_state = $row ['organization_state'];
		$Organization_zip = $row ['organization_zip'];
		$contact = $row ['contact_email'];
		$registrar = $row ['contact_email'];
		$payment_vendor_id = $row ['payment_vendor_id'];
		$currency_format = $row ['currency_format'];
		$return_url = $row ['return_url'];
        $events_listing_type = $row ['events_listing_type'];
		$default_mail = $row ['default_mail'];
		$conf_message = $row ['message'];
	}
	
	$events_detail_tbl = get_option ( 'events_detail_tbl' );
	
	$sql = "SELECT * FROM " . $events_detail_tbl . " WHERE id='" . $event_id . "'";
	$result = mysql_query ( $sql );
	while ( $row = mysql_fetch_assoc ( $result ) ) {
		$event_name = $row ['event_name'];
		$event_desc = $row ['event_desc']; // BHC
		$display_desc = $row ['display_desc'];
		$image = $row ['image_link'];
		$identifier = $row ['event_identifier'];
		$reg_limit = $row ['reg_limit'];
		$cost = $row ['event_cost'];
		$start_month = $row ['start_month'];
		$start_day = $row ['start_day'];
		$start_year = $row ['start_year'];
		$multiple = $row ['multiple'];
		$end_month = $row ['end_month'];
		$end_day = $row ['end_day'];
		$end_year = $row ['end_year'];
		$start_time = $row ['start_time'];
		$end_time = $row ['end_time'];
		$checks = $row ['allow_checks'];
		$active = $row ['is_active'];
		$question1 = $row ['question1'];
		$question2 = $row ['question2'];
		$question3 = $row ['question3'];
		$question4 = $row ['question4'];
		$send_mail = $row ['send_mail'];
		$conf_mail = $row ['conf_mail'];
				$event_location = $row ['event_location'];
		$more_info = $row ['more_info'];
		$custom_cur = $row ['custom_cur'];
		$start_date = $start_month . " " . $start_day . ", " . $start_year;
		$end_date = $end_month . " " . $end_day . ", " . $end_year;
	}
	
	// Email Confirmation to Registrar
	
	$event_name = $current_event;
	
	$distro = $registrar;
	$message = ("$fname $lname  has signed up on-line for $event_name.\n\nMy email address is  $email.");
	
	wp_mail ( $distro, $event_name . " Number: $attnum", $message );
  
	
	//Email Confirmation to Attendee
	$query = "SELECT * FROM $events_attendee_tbl WHERE fname='$fname' AND lname='$lname' AND email='$email'";
	$result = mysql_query ( $query ) or die ( 'Error : ' . mysql_error () );
	while ( $row = mysql_fetch_assoc ( $result ) ) {
		$id = $row ['id'];
	}
	
	$payment_link = $return_url . "?id=" . $id;
	
	//Email Confirmation to Attendee
	$SearchValues = array ("[fname]", "[lname]", "[phone]", "[event]", "[description]", "[cost]", "[currency]", "[qst1]", "[qst2]", "[qst3]", "[qst4]", "[contact]", "[company]", "[co_add1]", "[co_add2]", "[co_city]", "[co_state]", "[co_zip]", "[payment_url]", "[start_date]", "[start_time]", "[end_date]", "[end_time]","[snum]", "[num_people]" );
	
	$ReplaceValues = array ($fname, $lname, $phone, $event_name, $event_desc, $cost, $custom_cur, $question1, $question2, $question3, $question4, $contact, $Organization, $Organization_street1, $Organization_street2, $Organization_city, $Organization_state, $Organization_zip, $payment_link, $start_date, $start_time, $end_date, $end_time, $attnum, $num_people);
	
	$custom = str_replace ( $SearchValues, $ReplaceValues, $conf_mail );
	$default_replaced = str_replace ( $SearchValues, $ReplaceValues, $conf_message );
	
	$distro = $email;
	
	if ($default_mail == 'Y') {
		if ($send_mail == 'Y') {
			wp_mail ( $distro, $event_name, $custom );
		}
	}
	
	if ($default_mail == 'Y') {
		if ($send_mail == 'N') {
			wp_mail ( $distro, $event_name, $default_replaced );
		}
	}
	
	//Get registrars id from the data table .
	

}
?>