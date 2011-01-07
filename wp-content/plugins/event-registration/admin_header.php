<?php
//Build the header for the plugin
function admin_register_head(){ ?>
<link href="<?php echo ER_PLUGINFULLURL;?>styles.css" rel="stylesheet" type="text/css">

<script language="javascript"> 
<!-- 
var state = 'none'; 

function showhide(layer_ref) { 

if (state == 'block') { 
state = 'none'; 
} 
else { 
state = 'block'; 
} 
if (document.all) { //IS IE 4 or 5 (or 6 beta) 
eval( "document.all." + layer_ref + ".style.display = state"); 
} 
if (document.layers) { //IS NETSCAPE 4 or below 
document.layers[layer_ref].display = state; 
} 
if (document.getElementById &&!document.all) { 
hza = document.getElementById(layer_ref); 
hza.style.display = state; 
} 
} 
//--> 
</script> 
<script type="text/javascript">
function doHide(clientId) { 
 document.getElementById(clientId + '_ex').style.display='none'; 
 document.getElementById('m_'+clientId).style.display='inline'; 
 return false; 
}
function doMore(clientId) { 
 document.getElementById(clientId + '_ex').style.display='inline'; 
 document.getElementById('m_'+clientId).style.display='none'; 
 return false; 
}
</script> 




<?php 
}
?>