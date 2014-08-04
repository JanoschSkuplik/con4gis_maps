<?php

class LayerApi extends \Frontend
{
	/**
	 * generates the module
	 * @param  array $arrInput [description]
	 * @return mixed           [description]
	 */
	public function generate($arrInput)
	{
		// $this->import('FrontendUser', 'User');
		// $this->User->authenticate();

		$id = $arrInput[0]?:null;
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'GET':
				return $this->get( $id );
			case 'PUT':
			case 'POST':
			default:
				header('HTTP/1.1 405 Method Not Allowed');
				die;
		}
	}

	//comment
	private function get( $id = null ) 
	{
		if (!isset( $id )) {
			#pass
		} elseif (is_numeric( $id )) {
			#pass
		} else {
			header('HTTP/1.1 400 Bad Request');
			die;
		}
	}

}
