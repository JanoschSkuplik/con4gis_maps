
<?php

/**
 * Contao Open Source CMS
 *
 * @version   php 5
 * @package   con4gis
 * @author    Jürgen Witte <http://www.kuestenschmiede.de>
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2014 - 2015
 * @link      https://www.kuestenschmiede.de
 * @filesource
 */



/**
 * Initialize the system
 */
define('TL_MODE', 'FE');
require_once('../../initialize.php');

class C4GReverse extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->import('Database');
	}
	public function run()
	{

		$param = '';
		$profileId = 0;
		foreach ($_GET as $key=>$value) {
			if ($key=='profile') {
				$profileId = $value;
			} else if ($key=='token') {
				$token = $value;
			} else {
				if ($param)
					$param .= '&';
				$param .= $key.'='.$value;
			}
		}
		if (version_compare(VERSION,'3','<')) {
			$objToken = RequestToken::getInstance();
			$compToken = $objToken->get();
		} else {
			$compToken = RequestToken::get();
		}
		if ($token <> $compToken) {
			echo 'Error - BAD REQUEST TOKEN';
		} else {
			$r = new Request();
			if ($_SERVER['HTTP_REFERER']) {
				$r->setHeader('Referer', $_SERVER['HTTP_REFERER']);
			}
			if ($_SERVER['HTTP_USER_AGENT']) {
				$r->setHeader('User-Agent', $_SERVER['HTTP_USER_AGENT']);
			}
			//$url = 'http://nominatim.openstreetmap.org/reverse';
			$url = 'http://open.mapquestapi.com/nominatim/v1/reverse';

			$r->send($url.'?'.$param);

			echo $r->response;
		}

	}
}
$objC4GReverse = new C4GReverse();
$objC4GReverse->run();

?>