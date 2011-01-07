<?php
//Install/update data tables in the Wordpress database

function add_non_existent_column($db, $column, $column_attr = "VARCHAR( 255 ) NULL" ){
			$exists = false;
			$columns = mysql_query("show columns from $db");
			while($c = mysql_fetch_assoc($columns)){
				if($c['Field'] == $column){
					$exists = true;
					break;
				}
			}
			if(!$exists){
				mysql_query("ALTER TABLE `$db` ADD `$column`  $column_attr");
			}
		}
        
//Function to install/update data tables

function events_data_tables_install () {
 
    require_once ('er_db_attendee.inc.php');
    require_once ('er_db_event.inc.php');
    require_once ('er_db_organization.inc.php');
    require_once ('er_db_questions.inc.php');
    require_once ('er_db_categories.inc.php');
    require_once ('er_db_payment_txn.inc.php');



events_cat_detail_tbl_install();
events_attendee_tbl_install();
events_detail_tbl_install();
events_organization_tbl_install();
events_question_tbl_install();
events_answer_tbl_install();
events_payment_transactions_tbl_install();
update_option( 'awr_form_token', "387");
}
?>
