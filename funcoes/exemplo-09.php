<?php 

	$hierarquia = array(
		array(
			//ceo
			'nome_cargo' => 'CEO',
			'subordinados' => array(
				//diretor comercial
				array(
					'nome_cargo' => 'Diretor comercial',
					'subordinados' => array(
						//gerente de vendas
						array(
							'nome_cargo' => 'Gerente de vendas'
						)
					)
				),
				//diretor financeiro 
				array(
					'nome_cargo' => 'Diretor financeiro',
					'subordinados' => array(
						//gerente de contas a pagar
						array(
							'nome_cargo' => 'Gerente de contas a pagar',
							'subordinados' => array(
								//supervisor de pagamentos
								array(
									'nome_cargo' => 'Supervisor de pagamentos'
								)
							)
						),
						//gerente de compras
						array(
							'nome_cargo' => 'Gerente de compras',
							'subordinados' => array(
								//supervisor de suprementos
								array(
									'nome_cargo' => 'Supervisor de suprementos'
								)
							)
						)
					)
				)
			)
		)
	);

	function exibe($cargos) {
		$html = "<ul>";

		foreach ($cargos as $cargo) {
			$html .= "<li>";

			$html .= $cargo['nome_cargo'];

			if (isset($cargo['subordinados']) && count($cargo['subordinados']) > 0) {
				$html .= exibe($cargo['subordinados']);
			}

			$html .= "</li>";
		}

		$html .= '</ul>';

		return $html;
	}

	echo exibe($hierarquia);

 ?>