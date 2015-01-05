<?php 

/**
 * Contao Open Source CMS
 *
 * @version   php 5
 * @package   con4gis
 * @author    Jürgen Witte <http://www.kuestenschmiede.de>
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2014
 * @link      https://www.kuestenschmiede.de
 * @filesource 
 */



/**
 * Initialize the system
 */
define('TL_MODE', 'FE');
require_once('../../initialize.php');

function fix_magic_quotes()
{
	// Strip magic quotes from request data.
	if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
	    // Create lamba style unescaping function (for portability)
	    $quotes_sybase = strtolower(ini_get('magic_quotes_sybase'));
	    $unescape_function = (empty($quotes_sybase) || $quotes_sybase === 'off') ? 'stripslashes($value)' : 'str_replace("\'\'","\'",$value)';
	    $stripslashes_deep = create_function('&$value, $fn', '
	        if (is_string($value)) {
	            $value = ' . $unescape_function . ';
	        } else if (is_array($value)) {
	            foreach ($value as &$v) $fn($v, $fn);
	        }
	    ');
	   
	    // Unescape data
	    $stripslashes_deep($_GET, $stripslashes_deep);
	}
}

class C4GOverpass extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->import('Database');
		fix_magic_quotes();
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
				$param .= $key.'='.urlencode($value);
			}	
		}
		$url = 'http://overpass-api.de/api/interpreter';
		if ($profileId) {
			$profileUrl = $this->Database->prepare(
					"SELECT overpass_url FROM tl_c4g_map_profiles ".
					"WHERE id=?")
					->execute($profileId)->overpass_url;
			if ($profileUrl) {
				$url = $profileUrl;
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
			$r->send($url.'?'.$param);		
			echo $r->response;
		}
				
	}
}
$objC4GOverpass = new C4GOverpass();
$objC4GOverpass->run();

?>