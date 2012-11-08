<?php
/*
Plugin Name: DH Events Calendar
Plugin URI: http://wordpress.org/extend/plugins/dh-events-calendar
Description: Create, and manage a new type of content, the events, also setup a shortcodes for event calendar and a list year-month-day of events.
Version: 1.0
Author: Diego Jesus Hincapie Espinal
Author URI: http://diegojesushincapie.wordpress.com/
License:
Create, and manage a new type of content, the events, also setup a shortcodes for event calendar and a list year-month-day of events.
Copyright (C) 2011  Diego Jesus Hincapie Espinal

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/


if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']))
{
    die('You are not allowed to call this page directly.');
}


if (!class_exists('Dheventscalendar'))
{
    class Dheventscalendar
    {
        public function __construct()
        {
            if (is_admin())
            {
                add_action('admin_menu', array($this, 'dhc_admin_menu'));
                wp_enqueue_script('suggest');

                register_activation_hook(__FILE__, array($this, 'activation_plugin'));


                add_action('wp_ajax_autocomplete', array($this, 'autocomplete'));

            }
            
            wp_enqueue_script('jquery');
            wp_enqueue_script('jqueryui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js', array('jquery'));
            wp_register_style( 'jqueryuicss', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css' );
            wp_enqueue_style( 'jqueryuicss');
            wp_register_style('dheccss', plugins_url( 'css/dhecmain.css' , __FILE__ ));
            wp_enqueue_style('dheccss');
            add_shortcode('dhecdatepicker', array($this, 'dhecdatepicker_function'));
            add_shortcode('dheccalendar', array($this, 'dheccalendar_function'));

            add_filter( 'the_content', array($this, 'listdatestocontent'));
            add_action('widgets_init', array($this, 'register'));



            add_filter( 'tiny_mce_version', array($this, 'my_refresh_mce'));
            add_action('init', array($this, 'add_dhec_button'));
        }

        // Load the TinyMCE plugin : editor_plugin.js (wp2.5)
        public function add_dhec_tinymce_plugin($plugin_array) {
           $plugin_array['dhec'] = get_bloginfo('wpurl').'/wp-content/plugins/dh-events-calendar/js/editor_plugin.js';
           return $plugin_array;
        }

        public function register_dhec_button($buttons) {
           array_push($buttons, "|", "dhec");
           return $buttons;
        }

        public function add_dhec_button() {
           // Don't bother doing this stuff if the current user lacks permissions
           if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
             return;

           // Add only in Rich Editor mode
           if ( get_user_option('rich_editing') == 'true') {
             add_filter("mce_external_plugins", array($this, "add_dhec_tinymce_plugin"));
             add_filter('mce_buttons', array($this, 'register_dhec_button'));
           }
        }

        public function my_refresh_mce($ver) {
          $ver += 3;
          return $ver;
        }
        
        function control()
        {
            echo '';
        }

        public function register()
        {
            register_sidebar_widget('DH events calendar widget', array($this, 'dheccalendar_function_w'));
            register_widget_control('DH events calendar widget', array($this, 'control'));
        }

        public function listdatestocontent($content)
        {
            if($_POST['type']==1)
            {
                $content .= '<br /><br />'.$this -> eventlist($_POST['datehidden']);
            }
            return $content;
        }

        public function dheccalendar_function()
        {
            $cadena = '<div id="dhecdatepicker"></div>';
            $cadena .= '<script type="text/javascript">';
            $cadena .= 'jQuery(document).ready(function(){';
            $cadena .= 'jQuery("#dhecdatepicker").datepicker({dateFormat: \'yy-mm-dd\' ,onSelect: function(dateText, inst){';
            $cadena .= 'jQuery("#datehidden").val(dateText);';
            $cadena .= 'jQuery("#dhecdatepickerhform").submit();';
            $cadena .= '}});';
            $cadena .= '});';
            $cadena .= '</script>';
            $cadena .= '<form method="post" action="" id="dhecdatepickerhform">';
            $cadena .= '<input type="hidden" name="datehidden" id="datehidden" />';
            $cadena .= '<input type="hidden" name="type" id="type" value="1" />';
            $cadena .= '</form>';
            $cadena .= '</div>';
            return $cadena;
        }

        public function dheccalendar_function_w($args)
        {
            $cadena = $this ->dheccalendar_function();
            echo $cadena;
        }

        public function dhecdatepicker_function()
        {
            $cadena = '';
            $cadena .= '<div id="dheccontainer">';
            $cadena .= '<div id="dheceventlist">';

            if(isset($_POST['datehidden']))
            {
                $cadena .= $this -> eventlist($_POST['datehidden']);
            }
            else
            {
                $cadena .= __('Please, select a date');
            }

            $cadena .= '</div>';
            $cadena .= '<div id="dhecdatepicker"></div>';
            $cadena .= '<script type="text/javascript">';
            $cadena .= 'jQuery(document).ready(function(){';
            $cadena .= 'jQuery("#dhecdatepicker").datepicker({dateFormat: \'yy-mm-dd\' ,onSelect: function(dateText, inst){';
            $cadena .= 'jQuery("#datehidden").val(dateText);';
            $cadena .= 'jQuery("#dhecdatepickerhform").submit();';
            $cadena .= '}});';
            $cadena .= '});';
            $cadena .= '</script>';
            $cadena .= '<form method="post" action="" id="dhecdatepickerhform">';
            $cadena .= '<input type="hidden" name="datehidden" id="datehidden" />';
            $cadena .= '<input type="hidden" name="type" id="type" value="2" />';
            $cadena .= '</form>';
            $cadena .= '</div>';
            return $cadena;
        }

        public function eventlist($date)
        {
            global $wpdb;

            $table_name = $wpdb->prefix . "dheventcalendar";

            $content = '';


            $query = "SELECT id, sincedate, todate, name, post FROM ".$table_name." WHERE '".$date."' BETWEEN sincedate AND todate";
            $events = $wpdb->get_results($query, OBJECT);

            if(count($events)>0)
            {
                $content .= $this -> publiclistevent($events);
            }
            else
            {
                $content .= 'No events for select date';
            }

            return $content;
        }

        public function dhec_excerpt($text, $excerpt = false)
        {
            if ($excerpt) return $excerpt;

            $text = strip_shortcodes( $text );

            //$text = apply_filters('the_content', $text);
            $text = str_replace(']]>', ']]&gt;', $text);
            $text = strip_tags($text);
            $excerpt_length = apply_filters('excerpt_length', 55);
            $excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
            $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
            if ( count($words) > $excerpt_length ) {
                    array_pop($words);
                    $text = implode(' ', $words);
                    $text = $text . $excerpt_more;
            } else {
                    $text = implode(' ', $words);
            }

            return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
        }

        public function publiclistevent($events)
        {
            global $wpdb;

            $cadena = '';
            $cadena .= '<div id="dhecaccordion">';

            foreach($events as $event)
            {
                $cadena .= '<h3><a href="#">'.$event->name.'</a></h3>';
                $cadena .= '<div>';
                $cadena .= '<span>';
                $cadena .= __('Since').': ';
                $cadena .= $event->sincedate;
                $cadena .= '</span><br />';
                $cadena .= '<span>';
                $cadena .= __('To').': ';
                $cadena .= $event->todate;
                $cadena .= '</span><br />';

                $query_post = "SELECT ID, post_content FROM ".$wpdb->posts." WHERE ID=".$event->post;
                $post = $wpdb->get_results($query_post, OBJECT);

                $cadena .= '<span>';

                $cadena .= $this ->dhec_excerpt($post[0] -> post_content);

                $cadena .= '</span><br />';

                $cadena .= '<a href="'.get_permalink($post[0]->ID).'">'.__('View more').'</a>';
                $cadena .= '</div>';
            }
            $cadena .= '</div>';
            $cadena .= '<script type="text/javascript">';
            $cadena .= 'jQuery(document).ready(function(){';
            $cadena .= 'jQuery("#dhecaccordion").accordion();';
            $cadena .= '});';
            $cadena .= '</script>';
            return $cadena;
        }

        public function activation_plugin()
        {
            global $wpdb;

            $table_name = $wpdb->prefix . "dheventcalendar";

            $sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
                  id bigint(20) NOT NULL AUTO_INCREMENT,
                  sincedate date NOT NULL,
                  todate date NOT NULL,
                  name VARCHAR(255) NOT NULL,
                  post bigint(20) NOT NULL,
                  PRIMARY KEY (id)
                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }

        public function autocomplete()
        {
            global $wpdb;

            if(!isset($_GET['q']))
                {
                    $_GET['q'] = '';
                }
                $query = 'SELECT post_title, post_name, guid FROM '.$wpdb->posts.' WHERE post_status=\'publish\' AND post_type=\'post\' AND post_title LIKE \'%'.$_GET['q'].'%\' ORDER BY menu_order';

                $posts = $wpdb->get_results($query, OBJECT);

                if ($posts)
                {
                  foreach ($posts as $post)
                  {
                    echo $post->post_title. PHP_EOL;
                  }
                }
                die();
        }

        public function dhc_admin_menu()
        {
            add_menu_page('DH Events Calendar Admin', 'DH Events Calendar', 'edit_posts', 'dh-events-calendar', array($this, 'dhc_options_page'));
        }

        protected function updateform($title = 'New Event', $action = 'add', $post = '', $name = '', $sincedate = '', $todate = '', $error = false)
        {
            $cadena = '';

            $cadena .= '<div class="wrap">';
            $cadena .= '<div id="icon-edit" class="icon32">';
            $cadena .= '<br />';
            $cadena .= '</div>';
            $cadena .= '<h2>';
            $cadena .= __($title);
            $cadena .= '</h2>';
            $cadena .= '<br />';
            $filepath = admin_url()."admin.php?page=".$_GET["page"];
            $cadena .= '<form method="POST" action="'.$filepath.'&action='.$action.'&noheader=true" id="formeventnew">';
            $cadena .= '<table class="form-table">';
            $cadena .= '<tbody>';
            $cadena .= '<tr valign="top">';
            $cadena .= '<th scope="row">'.__('Post for info').'</th>';
            $cadena .= '<td><input type="text" name="postfi" id="postfi" value="'.$post.'" /></td>';
            $cadena .= '</tr>';
            $cadena .= '<tr valign="top">';
            $cadena .= '<th scope="row">'.__('Event name').'</th>';
            $cadena .= '<td><input type="text" name="name" id="name" value="'.$name.'" /></td>';
            $cadena .= '</tr>';
            $cadena .= '<tr valign="top">';
            $cadena .= '<th scope="row">'.__('Since date').'</th>';
            $cadena .= '<td><input type="text" name="sincedate" id="sincedate" value="'.$sincedate.'" /></td>';
            $cadena .= '</tr>';
            $cadena .= '<tr valign="top">';
            $cadena .= '<th scope="row">'.__('To date').'</th>';
            $cadena .= '<td><input type="text" name="todate" id="todate" value="'.$todate.'" /></td>';
            $cadena .= '</tr>';
            $cadena .= '</tbody></table>';
            $cadena .= '<div class="submit">';
            $cadena .= '<input id="submitbt" name="submitbt" value="'.__('Save event').'" type="button" class="button-primary" />';
            $cadena .= '</div>';
            $cadena .= '</form>';
            $cadena .= '</div>';

            $display = ' style="display: none;" ';

            if($error)
            {
                $display = '';
            }

            $cadena .= '<div class="error" '.$display.'>'.__($error).'</div>';
            $cadena .= '<script type="text/javascript">';

            $cadena .= 'function trim(str, charlist)
{
	var whitespace, l = 0,
        i = 0;
    str += \'\';

    if (!charlist) {
        // default list
        whitespace = " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
    } else {
        // preg_quote custom list
        charlist += \'\';
        whitespace = charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, \'$1\');
    }

    l = str.length;
    for (i = 0; i < l; i++) {
        if (whitespace.indexOf(str.charAt(i)) === -1) {
            str = str.substring(i);
            break;
        }
    }

    l = str.length;
    for (i = l - 1; i >= 0; i--) {
        if (whitespace.indexOf(str.charAt(i)) === -1) {
            str = str.substring(0, i + 1);
            break;
        }
    }

    return whitespace.indexOf(str.charAt(0)) === -1 ? str : \'\';
}';

            $cadena .= '
                jQuery(function($)
                        {
                            $("#postfi").suggest(ajaxurl + "?action=autocomplete", { delay: 1, minchars: 1 });
                        });';
            $cadena .= '
                jQuery(function($)
                        {
                            $("#sincedate").datepicker({ dateFormat: \'yy-mm-dd\'});
                        });';
            $cadena .= '
                jQuery(function($)
                        {
                            $("#todate").datepicker({ dateFormat: \'yy-mm-dd\'});
                        });';
            $cadena .= '
                jQuery("#submitbt").click(function(){';
            $cadena .= '
                if((trim(jQuery("#postfi").val())==\'\') || (trim(jQuery("#name").val())==\'\') || (trim(jQuery("#sincedate").val())==\'\') || (trim(jQuery("#todate").val())==\'\')){';
            $cadena .= '
                jQuery(".error").html(\''.__('An error has ocurred, please check your event info').'\');
                jQuery(".error").show();';
            $cadena .= '
                }';
            $cadena .= '
                else if(jQuery("#sincedate").val()>jQuery("#todate").val())
                {
                    jQuery(".error").html("'.__('An error has ocurred, please check your event info, since date can\'t be less than to date').'");
                    jQuery(".error").show();
                }
                ';
            $cadena .= '
                else {';
            $cadena .= '
                jQuery("#formeventnew").submit();';
            $cadena .= '
                }';
            $cadena .= '
                });';

            $cadena .= '</script>';

            return $cadena;
        }

        protected function listevents($events = false, $cant = 0, $pag = 1, $pages = 1)
        {
            global $wpdb;

            $cadena = '';

            $filepath = admin_url()."admin.php?page=".$_GET["page"];
            $cadena .= '<div class="wrap">';
            $cadena .= '<div id="icon-edit" class="icon32">';
            $cadena .= '<br />';
            $cadena .= '</div>';
            $cadena .= '<h2>';
            $cadena .= __('Event');
            $cadena .= '</h2>';
            $cadena .= '<br />';
            $cadena .= '<a href="'.$filepath.'&action=new">'.__('Create Event').'</a>';
            $cadena .= '<br />';

            if ($events)
            {
                $cadena .= '<br />';
                $cadena .= '<br />';
                $cadena .= '<form id="events-batch" method="post" action="'.$filepath.'&noheader=true">';
                $cadena .= '<div class="tablenav top">';
                $cadena .= '<div class="alignleft actions">';
                $cadena .= '<select name="action">';
                $cadena .= '<option selected="selected" value="-1">'.__('action batch').'</option>';
                $cadena .= '<option value="delete">'.__('delete').'</option>';
                $cadena .= '</select>';
                $cadena .= '<input type="submit" class="button-secondary" value="'.__('Apply').'" name="" />';
                $cadena .= '</div>';
                $cadena .= '</div>';
                $cadena .= '<table class="wp-list-table widefat" cellpadding="0">';
                $cadena .= '<thead>';
                $cadena .= '<tr>';
                $cadena .= '<th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>';
                $cadena .= '<th style="" class="manage-column column-sincedate" id="sincedate" scope="col">'.__('Since date').'</th>';
                $cadena .= '<th style="" class="manage-column column-todate" id="todate" scope="col">'.__('To date').'</th>';
                $cadena .= '<th style="" class="manage-column column-name" id="name" scope="col">'.__('Name').'</th>';
                $cadena .= '<th style="" class="manage-column column-post" id="post" scope="col">'.__('Event post').'</th>';
                $cadena .= '<th style="" class="manage-column column-post" id="action" scope="col">'.__('Action').'</th>';
                $cadena .= '</tr>';
                $cadena .= '</thead>';
                $cadena .= '<tfoot>';
                $cadena .= '<tr>';
                $cadena .= '<th style="" class="manage-column column-cb check-column" id="fcb" scope="col"><input type="checkbox"></th>';
                $cadena .= '<th style="" class="manage-column column-sincedate" id="fsincedate" scope="col">'.__('Since date').'</th>';
                $cadena .= '<th style="" class="manage-column column-todate" id="ftodate" scope="col">'.__('To date').'</th>';
                $cadena .= '<th style="" class="manage-column column-name" id="fname" scope="col">'.__('Name').'</th>';
                $cadena .= '<th style="" class="manage-column column-post" id="fpost" scope="col">'.__('Event post').'</th>';
                $cadena .= '<th style="" class="manage-column column-post" id="faction" scope="col">'.__('Action').'</th>';
                $cadena .= '</tr>';
                $cadena .= '</tfoot>';
                $cadena .= '<tbody id="the-list">';
                foreach ($events as $event)
                {
                    $cadena .= '<tr valign="top" class="alternate" id="post-'.$event->id.'">';
                    $cadena .= '<th class="check-column" scope="row">
                            <input type="checkbox" value="'.$event->id.'" name="post[]">
                        </th>';
                    $cadena .= '<td>'.$event->sincedate.'</td>';
                    $cadena .= '<td>'.$event->todate.'</td>';
                    $cadena .= '<td>'.$event->name.'</td>';

                    $query_post = "SELECT ID, post_title FROM ".$wpdb->posts." WHERE ID=".$event->post;

                    $post = $wpdb->get_results($query_post, OBJECT);

                    $cadena .= '<td><a href="'.  get_permalink($post[0]->ID).'" target="_blank">'.$post[0]->post_title.'</a></td>';
                    $cadena .= '<td><a href="'.$filepath.'&action=edit&event='.$event->id.'">'.__('Edit').'</a>&nbsp;|&nbsp;<a href="'.$filepath.'&action=delete&event='.$event->id.'&noheader=true" onclick="return confirm(\''.__('Are you sure?').'\');">'.__('Delete').'</a></td>';

                    $cadena .= '</tr>';
                }
                $cadena .= '</tbody>';
                $cadena .= '</table>';
                $cadena .= '<div class="tablenav bottom">';
                $cadena .= '<div class="alignleft actions">';
                $cadena .= '<select name="action2">';
                $cadena .= '<option selected="selected" value="-1">'.__('action batch').'</option>';
                $cadena .= '<option value="delete">'.__('delete').'</option>';
                $cadena .= '</select>';
                $cadena .= '<input type="submit" class="button-secondary" value="'.__('Apply').'" name="" />';
                $cadena .= '</div>';
                $cadena .= '<div class="tablenav-pages">';
                $cadena .= '<span class="displaying-num">';
                $cadena .= $cant.' ';
                $cadena .= __('elements');
                $cadena .= '</span>';
                $cadena .= '<span class="pagination-links">';
                $prevpag = $pag-1;
                $nextpag = $pag+1;
                $status_p_page_link = '';
                $status_n_page_link = '';
                if($pag==1)
                {
                    $prevpag = 1;
                    $status_p_page_link = ' disabled';
                }
                if($pag==$pages)
                {
                    $nextpag = $pag;
                    $status_n_page_link = ' disabled';
                }
                $cadena .= '<a href="'.$filepath.'&paged=1" title="'.__('go first page').'" class="first-page'.$status_p_page_link.'">«</a>';
                $cadena .= '<a href="'.$filepath.'&paged='.$prevpag.'" title="'.__('go last page').'" class="next-page'.$status_p_page_link.'">‹</a>';
                $cadena .= '<span class="paging-input">';
                $cadena .= $pag.' '.__('of').' <span>'.$pages.'</span>';
                $cadena .= '</span>';
                $cadena .= '<a href="'.$filepath.'&paged='.$nextpag.'" title="'.__('go next page').'" class="next-page'.$status_n_page_link.'">›</a>';
                $cadena .= '<a href="'.$filepath.'&paged='.$pages.'" title="'.__('go last page').'" class="last-page'.$status_n_page_link.'">»</a>';
                $cadena .= '</span>';
                $cadena .= '</span>';
                $cadena .= '</div>';
                $cadena .= '</div>';
                $cadena .= '</form>';
            }

            $cadena .= '</div>';

            return $cadena;
        }

        public function dhc_options_page()
        {
            if(isset($_GET['action']) && ($_GET['action']=='add'))
            {
                global $wpdb;

                if(!isset($_POST['postfi']) || !isset($_POST['name']) || !isset($_POST['sincedate']) || !isset($_POST['todate']) || (trim($_POST['postfi'])=='') || (trim($_POST['name'])=='') || (trim($_POST['sincedate'])=='') || (trim($_POST['todate'])==''))
                {
                    echo $this->updateform('New Event', 'add', '', '', '', '', 'An error has ocurred, please check your event info');
                }
                else
                {
                    $query = 'SELECT ID FROM '.$wpdb->posts.' WHERE post_status=\'publish\' AND post_type=\'post\' AND post_title=\''.$_POST['postfi'].'\' ORDER BY menu_order';

                    $posts = $wpdb->get_results($query, OBJECT);

                    if(count($posts)!=1)
                    {
                        echo $this->updateform('New Event', 'add', '', '', '', '', 'An error has ocurred, please check your event info');
                    }
                    else
                    {
                        $table_name = $wpdb->prefix . "dheventcalendar";
                        $query = "INSERT INTO ".$table_name." (sincedate, todate, name, post) VALUES ('".$_POST['sincedate']."', '".$_POST['todate']."', '".$_POST['name']."', ".$posts[0]->ID.");";
                        $posts = $wpdb->get_results($query, OBJECT);

                        $filepath = admin_url()."admin.php?page=".$_GET["page"];
                        wp_redirect($filepath);

                    }
                }
            }
            else if(isset($_GET['action']) && ($_GET['action']=='new'))
            {
                echo  $this -> updateform();
            }
            else if(isset($_GET['action']) && ($_GET['action']=='delete'))
            {
                global $wpdb;
                $filepath = admin_url()."admin.php?page=".$_GET["page"];

                $table_name = $wpdb->prefix . "dheventcalendar";

                $query_event = 'DELETE FROM '.$table_name." WHERE id=".$_GET['event'];

                $wpdb->get_results($query_event, OBJECT);

                wp_redirect($filepath);
            }
            else if(isset($_GET['action']) && ($_GET['action']=='saveedit'))
            {
                global $wpdb;
                if(!isset($_POST['postfi']) || !isset($_POST['name']) || !isset($_POST['sincedate']) || !isset($_POST['todate']) || (trim($_POST['postfi'])=='') || (trim($_POST['name'])=='') || (trim($_POST['sincedate'])=='') || (trim($_POST['todate'])==''))
                {

                    $table_name = $wpdb->prefix . "dheventcalendar";

                    $query_event = 'SELECT id, sincedate, todate, name, post FROM '.$table_name." WHERE id=".$_GET['event'];

                    $event = $wpdb->get_results($query_event, OBJECT);

                    $query_post = "SELECT id, sincedate, todate, name, post FROM ".$wpdb->posts." WHERE ID=".$event[0]->post;

                    $post = $wpdb->get_results($query_post, OBJECT);

                    echo $this->updateform('Edit Event', 'saveedit', $post[0]->post_title, $event[0]->name, $event[0]->sincedate, $event[0]->todate, 'An error has ocurred, please check your event info');
                }
                else
                {
                    $query = 'SELECT ID FROM '.$wpdb->posts.' WHERE post_status=\'publish\' AND post_type=\'post\' AND post_title=\''.$_POST['postfi'].'\' ORDER BY menu_order';

                    $posts = $wpdb->get_results($query, OBJECT);

                    $table_name = $wpdb->prefix . "dheventcalendar";
                    $query = "UPDATE ".$table_name." SET sincedate='".$_POST['sincedate']."', todate='".$_POST['todate']."', name='".$_POST['name']."', post=".$posts[0]->ID." WHERE id=".$_POST['event'].";";
                    $posts = $wpdb->get_results($query, OBJECT);

                    $filepath = admin_url()."admin.php?page=".$_GET["page"];
                    wp_redirect($filepath);
                }
            }
            else if(isset($_GET['action']) && ($_GET['action']=='edit'))
            {
                global $wpdb;

                $table_name = $wpdb->prefix . "dheventcalendar";

                $query_event = 'SELECT id, sincedate, todate, name, post FROM '.$table_name." WHERE id=".$_GET['event'];

                $event = $wpdb->get_results($query_event, OBJECT);

                $query_post = "SELECT ID, post_title FROM ".$wpdb->posts." WHERE ID=".$event[0]->post;

                $post = $wpdb->get_results($query_post, OBJECT);

                echo $this->updateform('Edit Event', 'saveedit', $post[0]->post_title, $event[0]->name, $event[0]->sincedate, $event[0]->todate);
            }
            else
            {
                global $wpdb;

                $table_name = $wpdb->prefix . "dheventcalendar";

                if(isset($_POST['action']) && ($_POST['action']=='delete'))
                {
                    if(isset($_POST['post']))
                    {
                        $in_id = implode(', ', $_POST['post']);
                        $query_delete = "DELETE FROM ".$table_name." WHERE id IN (".$in_id.");";

                        $wpdb->get_results($query_delete, OBJECT);
                    }

                    wp_redirect($filepath);
                }
                else if(isset($_POST['action2']) && ($_POST['action2']=='delete'))
                {
                    if(isset($_POST['post']))
                    {
                        $in_id = implode(', ', $_POST['post']);
                        $query_delete = "DELETE FROM ".$table_name." WHERE id IN (".$in_id.");";

                        $wpdb->get_results($query_delete, OBJECT);
                    }

                    wp_redirect($filepath);
                }

                $pag = $_GET['paged']?$_GET['paged']:1;
                $limit = 20;

                $from = ($pag-1)*$limit;

                $query_cant = 'SELECT COUNT(*) AS cant FROM '.$table_name;

                $cant_events = $wpdb->get_results($query_cant, OBJECT);

                $cant = $cant_events[0]->cant;
                $pages = ceil($cant/$limit);

                $query = 'SELECT id, sincedate, todate, name, post FROM '.$table_name.' ORDER BY id LIMIT '.$from.', '.$limit;

                $events = $wpdb->get_results($query, OBJECT);

                echo $this -> listevents($events, $cant, $pag, $pages);
            }
        }
    }

    $dheventscalendar = new Dheventscalendar();
}


?>