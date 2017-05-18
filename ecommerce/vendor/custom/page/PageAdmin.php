<?php
	namespace page;

	class PageAdmin extends Page {

		//Cria o objeto do Rain e monta o header da pagina
		public function __construct($opts = array(), $tpl_dir = "views/admin") {
			parent::__construct($opts, $tpl_dir);
		}


	}

 ?>