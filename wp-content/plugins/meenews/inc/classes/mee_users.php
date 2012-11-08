<?php
##################################################################
class MeeUsers extends MeenewsManager{
##################################################################

	var $options; 		
	var $pageinfo;		
	var $db_tables;
        var $search;
        var $users;
        var $total;

	//constructor
	function MeeUsers($options, $pageinfo)
	{
		// set options and page variables
                $this->getActions();
		$this->options = $options;
		$this->pageinfo = $pageinfo;
		$this->grouped = false;
		$this->make_data_available();


                $this->tplPath = MEENEWS_TPL_SOURCES;
		
		global $option_pagecount;
		$option_pagecount = isset($option_pagecount) ? $option_pagecount + 1 : 1;

		
		$priority = $option_pagecount;
		if(!$this->pageinfo['child']) $priority = 1;

		add_action('admin_menu', array(&$this, 'add_admin_menu'), $priority);


	}

        function getActions(){
            global $_POST;
                $accion = $_POST['acc'];
                $this->page = $_GET['npage'];
                if (!$this->page)$this->page = 1;
                if($accion != ''){
                      switch ($accion)
                      {
                        case 'add_list':
                           $this->addList();
                        break;
                        case 'search':
                           if ($_POST['srch_user']){
                               $this->users = $this->searchUser($_POST['srch_user']);
                           }else{
                               if($_POST['srch_list']!= "false"){
                                    $datas['list'] = $_POST['srch_list'];
                               }
                               if($_POST['srch_status'] != "0"){
                                    $datas['state'] = $_POST['srch_status'];
                               }

                               $this->users = $this->getUsers($datas,$this->page);
                           }

                           $this->search = true;
                        break;
                        case 'bulk':
                                  $action = $_POST['accion'];
                                  $ids = $_POST['ids'];
                                  $ids = explode(",",$ids);
                                  foreach ($ids as $id){
                                         if ($action == "move_users"){
                                              $datas = array(
                                              "id_categoria" =>$_POST['move_to']);
                                              $this->modSubscriptor($datas,$id);
                                         }else if ($action == "delete_users"){
                                             $this->removeSusbscriptorList($id);
                                         }
                                  }

                        break;
                        case 'mod_user':
                             $id = $_POST['id_user'];
                             $datas = array("email" => $_POST['email'],
                                  "name" => $_POST['name_mod'],
                                  "id_categoria" =>$_POST['mod_list'],
                                  "email"=>$_POST['email_mod'],
                                  "direction" => $_POST['address_mod'],
                                  "enterprise" => $_POST['company_mod'],
                                  "country"=>$_POST['country_mod']);

                             $this->modSubscriptor($datas,$id);
                        break;
                        case 'add_subscriber':
                            $confirmation = $_POST["confirmacion"];
                            $datas = array("email" => $_POST['email'],
                                  "name" => $_POST['name'],
                                  "id_categoria" =>$_POST['list'],
                                  "direction" => $_POST['direction'],
                                  "enterprise" => $_POST['company'],
                                  "country"=>$_POST['country']);

                                  $this->addSubscriptor($datas,$confirmation);
                        break;

                        case 'import_csv':
                                $row = 1;
                                $lista = $_POST["list"];
                                $confirmation = $_POST["confirmacion"];
                                $separator = $_POST["separator"];
                                $enclose = $_POST["enclose"];
                                $escape = $_POST["escape"];
                                $file = basename($_FILES['file']['name']);
                                $ext = explode('.', $file);
                                $uploaddir = MEENEWS."files/";
                                $uploadfile = $uploaddir. basename($_FILES['file']['name']);
                                $i = 1;
                                    if($ext[1] == 'csv' || $ext[1] == 'txt'){

                                        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                                            $handle = fopen ($uploadfile,"r");
                                            while ($data = fgetcsv ($handle, 1000, $separator)) {

                                                $num = count ($data);
                                                $row ;
                                                $datas = array("email" => $data[0],
                                                  "name" => $data[1],
                                                  "id_categoria" =>$lista,
                                                  "direction" => $data[2],
                                                  "enterprise" => $data[3],
                                                  "country"=>$data[4]);

                                                $this->addSubscriptor($datas,$confirmation);

                                                $i ++;
                                           }
                                           $this->message =  __('File import ok', 'meenews')." ".$i;
                                           fclose ($handle);
                                      }else{
                                          $this->message = __('Has been problem uploading file, are you sure that customimage folder has 777 permision?', 'meenews');
                                      }
                                  }else{
                                       $this->message = __('Has been problem uploading file, are you sure that extension files be csv or txt', 'meenews');;
                                  }
                                 unlink($uploadfile) ;

                        break;
                        case 'export_csv':
                              ob_start();
                              $row = 1;
                              $lista = $_POST["listSuscribes"];
                              $this->export_subscribers($lista);
                        break;
                      }
                }
                if ($_GET['acc'] == "act_user"){
                    $confkey = $_GET['id'];
                    $id =$this->isConfirmation($confkey);
                    if ($id){
                        $this->activateSubscriptor($id->id);
                        $this->message = __("Subscriptor activated","meenews");
                    }else{
                        $this->message = __("Id subscriptor error","meenews");
                    }
                }else if($_GET['acc'] == "del_list"){
                    $list = $_GET['id'];
                    if ($list){
                        $this->removeList($list);
                        $this->message = __("List Removed","meenews");
                    }else{
                        $this->message = __("Id List error","meenews");
                    }

                }else if($_GET['acc'] == "delete_user"){
                    $confkey = $_GET['id'];
                    $id = $this->isConfirmation($confkey);
                    if ($id){
                        $this->removeSusbscriptorList($id->id);
                        $this->message = __("Subscriptor Removed","meenews");
                    }else{
                        $this->message = __("Id subscriptor error","meenews");
                    }
                }
            
        }

        function addList(){
            global $_POST,$wpdb;
            $data["categoria"] = $_POST['cat_name'];
            foreach ($data as $k => $v ) $data[$k] = $this->escape($v);
            $wpdb->insert( MEENEWS_CATEGORY, $data  );
            return true;
        }

        function addMember($datas){
		global $wpdb;

		foreach ($datas as $k => $v ) $data[$k] = $this->escape($v);
                $wpdb->insert( MEENEWS_USERS, $data  );
                return true;
	}
        
        function searchUser($email){
            global $wpdb;
            $query = "SELECT * FROM " .MEENEWS_USERS;
            $query .= " WHERE email LIKE '%$email%'";

            $results = $wpdb->get_results( $query );

            return $results;
            
        }
        
        function listform(){
             $content = '<form id="settings" name="settings" action="?page=users_manager.php&acc=add_list" method="post">
				<p>'. __("List Name:", 'meenews').'</p>
				<p><input type="text" style="width:95%" name="cat_name" id="vat_name" value="" /></p>
				<p><div class="submit">
					<input name="send" type="submit" value="'. __("New List", 'meenews').'" />

                                        <input type="hidden" name="acc" value="add_list" />
				</div></p>
                                <p>'.__("Lists","meenews").'</p>
                                '.$this->actualList().'
                                <p>&nbsp;</p>
			</form>';

           return $content;
        }

        function actualList(){
            $results = $this->getMeLists();
            if (!$results){
                $list = __("There aren't any list created","meenews");
            }else{
                $list = "";
                foreach($results as $result){
                    $list .= "<p style='width:95%;border-bottom:1px solid silver;padding-bottom:2px'>".$result->categoria."<a style='float:right' href = '?page=users_manager.php&amp;acc=del_list&amp;id=$result->id'>".__("Delete","meenews")."</a></p>";
                }
            }
            return $list;
        }
        function formSubscriber(){
             $content = '
			<form id="users" name="users" action="?page=users_manager.php" method="post">
				<p>'.__("E-mail", 'meenews').'</p>
				<p><input type="text" style="width:95%;" name="email" id="email" value="" /></p>
				<p>'.__("Name", 'meenews').'</p>
				<p><input type="text" style="width:95%" name="name" id="name" value="" /></p>
				<p>'. __("List to subscribe", 'meenews').'</p>
				<p><select name="list">
				<option value="false">'.__('Select List',"meenews").'</option>
				'.$this->makeComboList().'
				</select>
				</p>
				</p>'. __("Do you want to send confirmation?", 'meenews').'</p>
				<p><input checked="CHECKED" type="radio" id="confirmacion" name="confirmacion" value="true" /> '. __("Yes", 'meenews').' <input  type="radio" id="confirmacion" name="confirmacion" value="false" />'.__("No", 'meenews').'</p>
				<p class="submit">
				<input name="send" type="submit" value="'.__('New Subscriptor', 'meenews').'" />
				<input type="hidden" name="acc" value="add_subscriber" />
				</p>				
			</form>';
             return $content;
        }
        function formExport(){
            $content = '
			<form id="users" name="users" action="?page=users_manager.php" method="post"  enctype="multipart/form-data">

			    <p>'.__("Select list to export subscribers:", 'meenews').'</p>

                            <p><select name="list">
                            <option value="all">'.__('All',"meenews").'</option>
                            '.$this->makeComboList().'
                          </select></p>

		<div class="submit">
		    <input name="send" type="submit" value="'.__("Export Csv", 'meenews').'" />
                    <input type="hidden" name="acc" value="export_csv" />
		</div>
			</form>';
   return $content;
        }

        function formImport(){
             $content = '
				<form id="users" name="users" action="?page=users_manager.php" method="post"  enctype="multipart/form-data">
				<p>'.__("Csv File", 'meenews').'</p>
				<p><input type="file" style="width:95%;" name="file" id="file" value="" /></p>
                                <p>'.__("Separate Char", 'meenews').'</p>
                                <p><input type="text" name="separator" id="separator" class="edt_30"  maxlength="1" value="," /></p>
                                <p>'.__("Enclose Char", 'meenews').'</p>
                                <p><input type="text" name="enclose" id="enclose" class="edt_30"  maxlength="1" value=\'"\' /></p>
                                <p>'.__("Escape Char", 'meenews').'</p>
                                <p><input type="text" name="escape" id="escape" class="edt_30"  maxlength="1" value="\\" /></p>
				<p><select name="list">
				'.$this->makeComboList().'
				</select></p>
				</p>'. __("Do you want to send confirmation?", 'meenews').'</p>
				<p><input type="radio" id="confirmacion" name="confirmacion" value="true" /> '. __("Yes", 'meenews').' <input  checked="CHECKED" type="radio" id="confirmacion" name="confirmacion" value="false" /> '.__("No", 'meenews').'</p>
				<p class="submit">
				<input name="send" type="submit" value="'.__('Submit', 'meenews').'" />
				<input type="hidden" name="acc" value="import_csv" /></p>
			</form>';
             return $content;
        }
        function printList($datas){

            global $_POST;
            $this->page = $_GET['npage'];
            if (!$this->page)$this->page = 1;
            if($_POST['acc'] != "search")$this->users = $this->getUsers($datas);
            
            $list .= '
<link rel="stylesheet" media="screen" type="text/css" href="'.MEENEWS_URI.'inc/css/mee_admin.css" />
           <div style="width:100%;overflow:hidden">
            <table width="100%">
              <tr>
                <td><form method="post" action="" name="ordersearch_frm">
                    <table>
                      <tr>
                        <td valign="middle"><strong>'.__('Search',"meenews").'</strong></td>
                        <td valign="top">
                          <input type="text" value="" name="srch_user" id="srch_user"    /></td>
                        <td valign="top">
                          <select name="srch_list">
                            <option value="false">'.__('Select List',"meenews").'</option>
                            '.$this->makeComboList().'
                          </select></td>
                        <td valign="top">&nbsp;</td>
                        <td valign="top"><strong>'.__('Select state:',"meenews").'</strong>
                          <select name="srch_status">
                            <option value="0">'.__('Select state',"meenews").'</option>
                            <option value="2">'.__('Active',"meenews").'</option>
                            <option value="1">'.__('Unactive',"meenews").'</option>
                          </select></td>
                        <td valign="top">&nbsp;&nbsp;
                          <input type="submit" name="Search" value="'.__('Search',"meenews").'" class="button-secondary action" onclick="" />
                          &nbsp;

                        </td>
                        <td valign="top"></td>
                      </tr>
                      <tr>
                        <td height="2" valign="top"></td>
                        <td height="2" valign="top"></td>
                        <td height="2" valign="top"></td>
                        <td height="2" valign="top"></td>
                        <td height="2" valign="top"></td>
                      </tr>
                    </table>
                    <input type="hidden" name="acc" value="search" />
                  </form></td>
              </tr>
              <tr>
                <form method="post" action="" name="change_masive">
                    <table width="100%">
                    <tr>
                       
                        <td valign:="top">
                        <span>
                          <select name="bulk_action" id="bulk_action" style="float:left">
                            <option value="false">'.__('Bulk Options',"meenews").'</option>
                            <option value="0">'.__('Move List',"meenews").'</option>
                            <option value="2">'.__('Delete',"meenews").'</option>                 
                          </select></div>
                          <div id="moveList" style="display:none;float:left"><select name="move_to" id="move_to">
                            <option value="false">'.__('Select List',"meenews").'</option>
                            '.$this->makeComboList().'
                          </select></div>
                          <input type="submit" name="actions" id="baction" value="'.__('Do Action',"meenews").'" class="button-secondary action" onclick="" />
                          &nbsp;</td>

                      
                        <td valign="top"></td>
                      </tr>
                      <input type="hidden" id="accion" name="accion" value="">
                      <input type="hidden" id="acc" name="acc" value="bulk">
                      <input type="hidden" id="ids"name="ids" value="">
                      </form>
                </table>
              </tr>
              <tr>
                <td>';
         if (count($this->users) > 0){
                    $list .='
                  <form name="frmContentList1" action="" method="post">
                    <table width="100%" cellpadding="5"  class="widefat post fixed" >
                      <thead>
                        <tr>
                          <th width="10%" ><input name="check" onClick="return selectCheckBox();" id="check" type="checkbox"></th>
                          <th width="30%" ><strong>'.__('Name',"meenews").'</strong></th>
                          <th width="39%" ><strong>'.__('Email',"meenews").'</strong></th>
                          <th width="10%" ><strong>'.__('List',"meenews").'</strong></th>
                          <th width="10%" ><strong>'.__('Active',"meenews").'</strong></th>
                          <th width="1%" >&nbsp;</th>
                        </tr>';
                  
                foreach ($this->users as $user){
                 $listSubscriber = $this->getCategoryMemberName($user->id_categoria);
                 if ($user->state == "2"){
                     $link = "<a href='?page=users_manager.php&amp;acc=delete_user&amp;id=$user->confkey' onclick='return confirm(\"Delete?\")'><img src='".MEENEWS_URI."inc/img/ico_del.gif' class='ico' /></a>";
                 }else{
                     $link = "<a href = '?page=users_manager.php&amp;acc=act_user&amp;id=$user->confkey'>".__("Act", "meenews")."</a>";
                 }
                $list .='
                    <tr>
                      <td align="center"><input name="list_users[]" class="selusers" id="check_'.$user->id.'" value="'.$user->id.'" type="checkbox"></td>
                      <td><a href="">'.$user->name.'</a></td>
                      <td><a href="">'.$user->email.'</a></td>
                      <td>'.$listSubscriber.'</td>
                      <td><a href="#" class="mod" rel="'.$user->id.'"><img src="'.MEENEWS_URI.'inc/img/ico_edit.gif" class="ico" /></a> '.$link.' </td>
                      <td>&nbsp;</td>
                    </tr>';
                }

            $list .='
            <tr>
              <td colspan="6">'.$this->paginaUsers().'</td>
            </tr>

          </thead>
        </table>
      </form>';

         }else{
             $list .= __("No subscribers found");
         }
$list.='

    </td>
  </tr>
</table> </div><div id="designbox">

    <div class="box two" >

    <a href="#" id="closedesignbox">'. __("Close","meenews").'</a>

    <h3>'. __("Manager User","meenews").'</h3>

     <form id="users_mod" name="users_mod" action="?page=users_manager.php" method="post"  enctype="multipart/form-data">
    <div class="content corner" id="designs_content">

        <div id="list_mod">
        <select name="mod_list" id="mod_list">
              <option value="false">'.__('Select List',"meenews").'</option>
              '.$this->makeComboList().'
        </select>
        </div>
       <div class="mod_input">
        <label>Name:</label>
        <input type="text" name="name_mod" id="name_mod" value="name">
       </div>
      <div class="mod_input">
        <label>Email:</label>
        <input type="text" name="email_mod" id="email_mod" value="email">
       </div>
        <div class="mod_input">
        <label>Company:</label>
        <input type="text" name="company_mod" id="company_mod" value="company">
       </div>
       <div class="mod_input">
        <label>Address:</label>
        <input type="text" name="address_mod" id="address_mod" value="address">
       </div>
    </div> <!--/ content -->
    <input type="hidden" name="acc" value="mod_user">
    <input type="hidden" name="id_user" id="id_user" value="">
    </form>
    <div class="footerbox">
    <a href="#" class="corner" id="cancel_us">'.__("Cancel","meenews").'</a> <a href="#" class="corner black" id="mod_subs">'. __("Modify","meenews").'</a>
    </div>

    </div> <!--/ box-->

</div> <!--/ designbox -->
<script>
    jQuery("#closedesignbox").click(function(){
                            jQuery("#designbox").css("display", "none");
                    });
     jQuery("#cancel_us").click(function(){
                            jQuery("#designbox").css("display", "none");
                    });
     jQuery("#bulk_action").change(function(){
                        jQuery(".selusers:checked").each(function(){  });
                        if(jQuery(this).val() == 0){
                            jQuery("#moveList").css("display", "block");
                            jQuery("#accion").val("move_users");
                        }else if(jQuery(this).val() == 2){
                            jQuery("#accion").val("delete_users");
                        }else{
                            jQuery("#moveList").css("display", "none");
                            jQuery("#accion").val("");
                        }
                    });
    jQuery("#baction").click(function(){
            var selected = "";
                            jQuery(".selusers:checked").each(function(){ selected = selected + jQuery(this).val() + "," });
                            jQuery("#ids").val(selected);
    });
    jQuery("#mod_subs").click(function(){
            jQuery("#users_mod").submit();
    });
    jQuery(".mod").click(function(){
            jQuery("#designbox").css("display","block");
            var id = jQuery(this).attr("rel");
                jQuery( function($) {
                   var url = "'.MEENEWS_AJAX_FILE .'";
                       $.ajax({
                            type: "POST",
                            url: url,
                            data: "acc=showUser&id="+id,

                            success: function(datos){
                                    var datos = datos.split(",");
                                    jQuery("#name_mod").val(datos[0]);
                                    jQuery("#id_user").val(id);
                                    jQuery("#email_mod").val(datos[1]);
                                    jQuery("#company_mod").val(datos[2]);
                                    jQuery("#address_mod").val(datos[3]);
                                    jQuery("#mod_list option[@selected=\'selected\']").removeAttr("selected");
                                    jQuery("#mod_list option[@value=\'+datos[4]+\']").attr("selected","selected");
                            }
                    });
               });

               
    });
</script>';
            
            return $list;
        }

        function getAllMembers($send){
            global $wpdb;

            if($send['list']!= "0")$filter =" AND id_categoria='".$send['list']."'";
            $query = "SELECT * FROM " .MEENEWS_USERS ." WHERE state='2' $filter order by id ASC ;"  ;

            $results = $wpdb->get_results( $query );

	    return $results;
        }

        function getRangeMembers($send){
            global $wpdb;
            if($send['list']!= "0")$filter =" AND id_categoria='".$send['list']."'";
            $query = "SELECT * FROM " .MEENEWS_USERS ." WHERE state='2' $filter order by id ASC limit ".$send['to'].",".$send['until'].";" ;

            $results = $wpdb->get_results( $query );

	    return $results;
        }
        
        function getUsers($datas){


            global $wpdb,$_POST;

            
            
            $defaults = array(
            "state" => null,
            "list" => '0',
            "order_by" => 'id DESC',
            "from" => null,
            "until" => '50');

            $datas = array_merge((array)$defaults,(array)$datas);


            if($datas['state'] == null){

                if($datas['list']!= "0")$filter = " WHERE id_categoria = ".$datas['list'];

            }else{
                $filter =" WHERE state = ".$datas['state'];
                if($datas['list']!= "0")$filter .= " AND id_categoria = ".$datas['list'];

            }
            
            $filter .= " Order by ".$datas['order_by'];

            $this->total =  $wpdb->get_var( "SELECT COUNT(*) FROM " .MEENEWS_USERS ." $filter ;"  );
            if ($this->page != null){
                $from = ($this->page-1) * $datas['until'];
                $filter .= " limit ".$from.",".$datas['until'];
            }
            
            if ($datas['from'] != null) $filter .= " limit ".$datas['from'].",".$datas['until'];

            $query = "SELECT * FROM " .MEENEWS_USERS ." $filter ;"  ;

            $results = $wpdb->get_results( $query );

	    return $results;
        }

       function getMeLists(){
            global $wpdb;

            $query = "SELECT * FROM " .MEENEWS_CATEGORY ;
            $query .= " ;";
            $results = $wpdb->get_results( $query );

            return $results;
       }

       function makeComboList(){

            $results = $this->getMeLists();
            foreach ($results as $result){
                $combo .="<option value='$result->id'>$result->categoria</option>";
            }

            return $combo;
       }

       function escape($str) {
            global $wpdb;
            $str = get_magic_quotes_gpc()?stripslashes($str):$str;
            $str = $wpdb->escape($str);
            return $str;
       }
       function modSubscriptor($data,$id){
           global $wpdb;
           if ($id > 0){
               foreach ($data as $k => $v ) $data[$k] = $this->escape($v);
               $wpdb->update( MEENEWS_USERS, $data , array( 'id' => $id ) );
               $this->message=__("User has been modify","meenews");
           }else{

               $this->message=__("Id User Error","meenews");
           }
       }
       function addSubscriptor($datas,$confirmation = "true"){
             global $meenews_datas;
             $date = date("Y-m-d H:i:s");
             $defaults = array("email" => '',
                              "name" => '',
                              "id_categoria" => 1,
                              "direction" => false,
                              "enterprise" => null,
                              "country"=>null,
                              "direction" => null,
                              "state" => 1,
                              "joined" => $date);

                
                $datas = array_merge((array)$defaults,(array)$datas);
                
		$returnVal = array();
		if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", trim($datas['email']))){
			$returnVal['result']=false;
			 $this->message =$meenews_datas['newsletter']['invalid_email'];
			return $returnVal;
		}

		$estado = $this->getStateClient($datas['email']);

		if($estado->state != "2"){ // 2 activo
                        $sender = new MeeSender();
			if($estado->state == ""){
                               $confkey =md5(uniqid(rand(),1));
                               $member = array("name"=>$datas['name'],"email"=>$datas['email'],"confkey"=>$confkey);
                               $datas['confkey'] = $confkey;
                               if($confirmation == "true"){
                                     
                                     $send = array("member" => $member,"message"=>"confirm");
                                     if($sender->sendMessage($send)){
                                        if($this->addMember($datas)){
                                             $returnVal['result']=true;
                                             $this->message = $meenews_datas['newsletter']['email_sent'];
                                             return $returnVal;
                                        }else{
                                            $returnVal['result'] = false;
                                             $this->message = $meenews_datas['newsletter']['data_error'];
                                            return $returnVal;
                                        }
                                     }
                                     $returnVal['result'] = false;
                                      $this->message = $meenews_datas['newsletter']['data_error'];
                                            return $returnVal;
                                     }else{
                                        $datas['state'] = "2";
                                        if($this->addMember($datas)){
                                                $returnVal['result'] = true;
                                                 $this->message = __("Subscribers import ok", 'meenews');
                                                return $returnVal;
                                        }else{
                                                $returnVal['result'] = false;
                                                 $this->message = $meenews_datas['newsletter']['data_error'];
                                                return $returnVal;
                                        }
                                    }
			 }else{
                             if($confirmation == "true"){
                                $member = array("name"=>$datas['name'],"email"=>$datas['email'],"confkey"=>$estado->confkey);
                                $send = array("member" => $member,"message"=>"confirm");
			        if($sender->sendMessage($send)){
					$returnVal['result'] = true;
                                        $this->message = $meenews_datas['newsletter']['email_sent'];
                                        return $returnVal;
				}else{
					 $returnVal['result'] = false;
					 $this->message = $meenews_datas['newsletter']['data_error'];
                                         return $returnVal;
                                }
                             }
			}
		}else{
			$returnVal['result'] = false;
			 $this->message = $meenews_datas['newsletter']['already_subscribed'];
			return $returnVal;
		}
   }
	
   function getStateClient($email){
		global $wpdb;

		$email = addslashes( $email );
		return $wpdb->get_row("SELECT state,confkey FROM ".MEENEWS_USERS." WHERE email = '$email'");
   }

   function getCategoryMemberName ($category = 1){
            global $wpdb;

            $query = "SELECT * FROM " .MEENEWS_CATEGORY ;
            $query .= " where id='$category' limit 1;";
            $results = $wpdb->get_results( $query );
            foreach($results as $result)
            return $result->categoria;
   }

   function paginaUsers(){
        global $wpdb;
        $result =  ceil($this->total /50) ;

        $paginas = $result;

        $paginacion = "<p>".$this->page.__(" of ", "meenews"). $result. " Pages  <ul style='width:90%'> ";
        for ($i = 1; $i <= $paginas; $i ++){

            if ( ($i == 1) || (($i >= $this->page-3) && ($i <= $this->page+3)) || ($i == $paginas) ){
                if ($i == $this->page){
                    $paginacion .="<li style='float:left; margin-right:3px'><p style='color:red'>$i | </p>  </li>";
                }else{
                    $filter = str_replace("'", "",  $filter);
                    $paginacion .="<li style='float:left; margin-right:3px'> <a href='?page=users_manager.php&amp;npage=$i&amp;filter=$filter#tab2'>$i</a> | </li>";
                }
            }

        }
        $paginacion .="</ul>";

        return $paginacion;
   }

   function removeList($id){
		global $wpdb;

		$query = "DELETE FROM ".MEENEWS_CATEGORY."  WHERE id='$id';";
		$results = $wpdb->query( $query );
		return true;
   }

   function removeSusbscriptorList($id){
		global $wpdb;

		$query = "DELETE FROM ".MEENEWS_USERS."  WHERE id='$id';";
		$results = $wpdb->query( $query );
		return true;
   }

   function isConfirmation($confKey){
		global $wpdb;
		$results = $wpdb->get_row("SELECT * FROM ".MEENEWS_USERS." WHERE confkey = '$confKey';");
                
                if($results){
                    return $results;
                }

                return false;

    }
    function getUser($id){
		global $wpdb;
		$results = $wpdb->get_row("SELECT * FROM ".MEENEWS_USERS." WHERE id = '$id';");

                if($results){
                    return $results;
                }

                return false;

    }
    function activateSubscriptor($id){
		global  $wpdb;

		$query = "UPDATE ".MEENEWS_USERS." Set state = '2' WHERE id='$id';";
		$results = $wpdb->query( $query );
    }

    function getComboListSuscribes ($isFilter = false, $todos = false){
        $categories = MeeUsers::getListSuscribes();
        if ($isFilter){
            $combo = "<select name='listSuscribes' onchange='javascript:filterUserList(this.value)'><option value=''>".__("All", 'meenews')."</option>";
        }else{
            if ($todos){
                $combo = "<select name='listSuscribes' id ='listSuscribes' ><option value='all'>".__("All", 'meenews')."</option>";
            }else{
             $combo = "<select name='listSuscribes' >";
            }
        }
        foreach($categories as $category){
              $combo .="<option value='$category->id'>$category->categoria</option>";
        }
        $combo .= "</select>";
		return $combo;
    }

    function getListSuscribes (){
        global $wpdb;

        $query = "SELECT * FROM " .MEENEWS_CATEGORY ;
        $query .= " ;";
        $results = $wpdb->get_results( $query );

        return $results;
    }
    
    function howUserHaveInList($list){
        global $wpdb;
        if ($list == "all"){
            $query = "SELECT COUNT(*) from ".MEENEWS_USERS;
        }else{
            $query = "SELECT COUNT(*) from ".MEENEWS_USERS." WHERE id_categoria='$list'";
        }
        
        return $wpdb->get_var( $query );
    }
    
    function export_subscribers($list = "all"){

        global $wpdb;
        ob_start();
        
        header( "Content-Type: application/octet-stream");
        header( "Content-Disposition: attachment; filename=export.csv");
        
        if ($list != "all")$filter = " WHERE id_categoria = '$list'";
        $query = "SELECT * FROM " .MEENEWS_USERS. $filter;
        $results = $wpdb->get_results( $query );

        foreach ($results as $result){
            echo "$result->email , $result->name , $result->enterprise , $result->country , $result->direction";
        }

    }
        
     function getConfirmationId($confKey){
		global $wpdb;
		return $wpdb->get_var("SELECT * FROM ".MEENEWS_USERS." WHERE confkey = '$confKey';");
	}
##################################################################
} # end class
##################################################################
