
<?php 

/**
 * Contao Open Source CMS
 *
 * @version   php 5
 * @package   con4gis
 * @author     Jürgen Witte <http://www.kuestenschmiede.de>
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

class C4GViaRoute extends Controller
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
				if (substr($key,0,3)=='loc')
					$key='loc';
				if (substr($key,0,4)=='hint')
					$key='hint';
				$param .= $key.'='.$value;
			}	
		}
		$url = '';
		if ($profileId) {
			$profile = $this->Database->prepare(
					"SELECT router,router_viaroute_url FROM tl_c4g_map_profiles ".
					"WHERE id=?")
					->execute($profileId);
			if ($profile->router) {
				$profileUrl = $profile->router_viaroute_url;
				if ($profileUrl) {
					$url = $profileUrl;
				} else {
					$url = 'http://router.project-osrm.org/viaroute';						
				}
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
		} else if ($url) {
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
		else {
			echo 'Error - Routing is not active in the map profile!';
		}	
		
	}
}
$objC4GViaRoute = new C4GViaRoute();
$objC4GViaRoute->run();

?>