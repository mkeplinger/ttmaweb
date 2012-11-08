<?php
##################################################################
class MeeStats extends MeenewsManager{
##################################################################

	var $options;
	var $pageinfo;
	var $db_tables;
        var $search;
        var $users;
        var $total;

	//constructor
	function MeeStats($options, $pageinfo)
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


        function initialize()
	{
            global $_POST;

            $this->get_save_options();

            $this->getTpl();
            if ($this->message != ""){
               $this->message = "<div id='akismet-warning' class='updated fade'><p>".$this->message."</p></div> ";
               $this->tpl->assign("MESSAGE", $this->message);
            }
            print($this->page_html);


            include(MEENEWS_TPL_SOURCES.'mee_stats.php');

	}

        function getActions(){
            global $_POST;



        }
 
##################################################################
} # end class
##################################################################
