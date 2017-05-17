<?php 

	namespace page;

	use Rain\Tpl;

	class Page {
		private $tpl;
		private $options = [];
		private $defaults = [
			"data"=>[]
		];

		//Cria o objeto do Rain e monta o header da pagina
		public function __construct($opts = array()) {
			$this->options = array_merge($this->defaults, $opts);

			// config
			$config = array(
			    "tpl_dir"       => "views/",
			    "cache_dir"     => "views-cache/",
			    "debug"         => true, // set to false to improve the speed
			);

			Tpl::configure( $config );

			$this->tpl = new Tpl;

			$this->setData($this->options["data"]);

			$this->tpl->draw("header");
		}

		//Carrega os dados passados para o template
		private function setData($data = array()) {
			foreach ($data as $key => $value) {
				$this->tpl->assign($key, $value);
			}
		}

		//Carrega template na tela
		public function setTpl($name, $data = array(), $returnHtml = false) {
			$this->setData($data);

			return $this->tpl->draw($name, $returnHtml);
		}

		//Monta o footer da pagina
		public function __destruct() {
			$this->tpl->draw("footer");
		}

	}


 ?>