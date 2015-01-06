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

class C4GNominatim extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->import('Database');
	}
	public function run()
	{

		$param = '';
		$engine = 1;
		$url = '';
		$profileId = 0;
		foreach ($_GET as $key=>$value) {
			if ($key=='engine') {
				$engine = $value;
			// } else if ($key=='url') {
			// 	$url = $value;
			} else if ($key=='profile') {
				$profileId = $value;
			} else if ($key=='token') {
				$token = $value;
			} else {
				if ($param)
					$param .= '&';
				$param .= $key.'='.urlencode($value);
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

			switch ($engine) {
				case '3':
					$url = $this->Database->prepare(
						"SELECT geosearch_customengine_url FROM tl_c4g_map_profiles WHERE id=?")
					->execute($profileId)->geosearch_customengine_url;
					break;
				case '2':
					$url = 'http://open.mapquestapi.com/nominatim/v1/search.php';
					break;
				case '1':
				default:
					$url = 'http://nominatim.openstreetmap.org/search';
					break;
			}

			$r->send($url.'?'.$param);

			echo $r->response;
		}

	}
}
$objC4GNominatim = new C4GNominatim();
$objC4GNominatim->run();

?>