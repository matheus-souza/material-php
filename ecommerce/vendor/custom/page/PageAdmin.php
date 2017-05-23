<?php

	namespace page;

	require_once("Page.php");

	class PageAdmin extends Page {


		public function __construct($opts = array(), $tpl_dir = "views/admin/") {
			parent::__construct($opts, $tpl_dir);
		}


	}

 ?>