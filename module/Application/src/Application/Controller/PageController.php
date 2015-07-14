<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Zend\View\Model\JsonModel;

use Facebook;  
use Google_Client; 
use Google_PlusService;
use Google_Oauth2Service;

class PageController extends AbstractActionController
{
	protected $pagesTable;
	protected $othersGameTable;
	protected $imgsliderTable;
	protected $sponsorTable;
	protected $playerTable;
	
	public function getPlayerTable()
    {
        if (!$this->playerTable) {
            $sm = $this->getServiceLocator();
            $this->playerTable = $sm->get('Admin\Model\PlayerTable');
        }
        return $this->playerTable;
    }		
	
	
	
	
	
	
	
	
	
	
	public function getPageTable()
    {
        if (!$this->pagesTable) {
            $sm = $this->getServiceLocator();
            $this->pagesTable = $sm->get('Admin\Model\PagesTable');
        }
        return $this->pagesTable;
    }
	public function getSponsorTable()
    {
        if (!$this->sponsorTable) {
            $sm = $this->getServiceLocator();
            $this->sponsorTable = $sm->get('Admin\Model\SponsorTable');
        }
        return $this->sponsorTable;
    }	

	
	public function getOthersGameTable()
    {
        if (!$this->othersGameTable) {
            $sm = $this->getServiceLocator();
            $this->othersGameTable = $sm->get('Admin\Model\OthersGameTable');
        }
        return $this->othersGameTable;
    }	
	
	# Call ImageSlider Model
	public function getImageSliderTable()
    {
        if (!$this->imgsliderTable) {
            $sm = $this->getServiceLocator();
            $this->imgsliderTable = $sm->get('Admin\Model\ImageSliderTable');
        }
        return $this->imgsliderTable;
    }
	
	 public function getuserRankByExp($xp_point)
	 {
		
			$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
			$sql       = new Sql($dbAdapter);
			$select    = $sql->select('player_rank_system');
			$select->where->lessThan('min_exp', $xp_point);
			$select->where->greaterThan('max_exp', $xp_point);
			
			$statement = $sql->prepareStatementForSqlObject($select);				
			$rowset    = $statement->execute();			
			
			$resultSet = new ResultSet();
			$resultSet->initialize($rowset);
			return $resultSet; 

	 }
	


    public function indexAction()
    {

		$short_title = $this->params()->fromRoute('title');
        
		$this->layout()->setVariable('active_menu', $short_title);
		$others_game_lists = $this->getOthersGameTable()->fetchAll()  ;
		//$others_game_lists = $this->MyModePlugin()->getOthersGameTable()->fetchAll();
		$this->layout()->setVariable('others_game_lists', $others_game_lists);						
		$this->layout()->setVariable('banners', $this->getImageSliderTable()->fetchAll() );
		$this->layout()->setVariable('site_sponsor', $this->getSponsorTable()->fetchAll() );
		$this->checkLogin();
		$this->googlePlusLogin();		
		
		
		
		
      
		return new ViewModel( array( 
			'basic_pages' => $this->getPageTable()->getDataByShortCode($short_title)  
			) );	
		
		
    }
	
	public function checkLogin()
	{
		$facebook = new Facebook(array(
		'appId'  => '386391428152834',   
		'secret' => '498339cca36cc580d14e59a65485b3b8',  
		'cookie' => true,	
		));	
		
		$user = $facebook->getUser();
		$access_token = $facebook->getAccessToken();
		if ($user) {
				$user_profile = $facebook->api('/me');
				$fbid = $user_profile['id'];                 // To Get Facebook ID
				$fbuname = $user_profile['username'];  // To Get Facebook Username
				$fbfullname = $user_profile['name']; // To Get Facebook full name
				$femail = $user_profile['email'];    // To Get Facebook email ID
				/* ---- Session Variables -----*/
				$fbsession = new Container('fbloginbase');
				$fbsession->fbuserprofile = $user_profile ; 
				$fbsession->FBID = $fbid; 
				$fbsession->USERNAME = $fbuname;
				$fbsession->FULLNAME = $fbfullname;
				$fbsession->EMAIL = $femail;

				//echo $access_token; exit;
				if(!empty($access_token )):
				if(strpos($access_token, '|') !== false) {
					$access_array = split("\|", $access_token);
					$session_key = $access_array[1];
				} else $session_key = $access_token;
				endif; 
				$params = array( 'next' => 'http://matrixmedia.redappletech.com/huntinggame/public/application/player/logoutfb', 'session_key' => $session_key);		
				$logoutUrl = $facebook->getLogoutUrl($params);					
					
				$this->layout()->setVariable('fb_logout_url', $logoutUrl );		

				$this->layout()->setVariable('fb_session_email', $fbsession->EMAIL );
				$this->layout()->setVariable('fb_session_fbid', $fbsession->FBID );
				$this->layout()->setVariable('fbuserprofile', $fbsession->fbuserprofile );
				
				//$playerDetails = $this->getPlayerTable()->fetchByEmail($femail);
				//echo $playerDetails['xpPoint'];
				//$palyerRank = $this->getuserRankByExp($playerDetails['xpPoint']);
				//$palyerRankArray = $palyerRank->toArray();				
				
				
		
		
		} else {

			$loginUrl = $facebook->getLoginUrl(array(	'scope'		=> 'email, read_stream, friends_likes', 'redirect_uri' => 'http://matrixmedia.redappletech.com/huntinggame/public/application/index/loginfb'));	
			$this->layout()->setVariable('fb_login_url', $loginUrl );	
		}		

		
	}	
		
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function googlePlusLogin()
	{
		$gplussession = new Container('gplussession');
		$client = new \Google_Client();
		$client->setApplicationName("RedApple"); // Set your applicatio name
		$client->setScopes(array('https://www.googleapis.com/auth/plus.login', 'https://www.googleapis.com/auth/userinfo.email', 'https://www.googleapis.com/auth/plus.me', 'https://www.googleapis.com/auth/userinfo.profile')); // set scope during user login
		$client->setClientId('650150261167-9533q5q600tj676llbjoflomcjsdffji.apps.googleusercontent.com'); // paste the client id which you get from google API Console
		$client->setClientSecret('Zg6l1TE9nsUyMDEwGJhA8vg4'); // set the client secret
		$client->setRedirectUri('http://matrixmedia.redappletech.com/huntinggame/public/application/index/googlelogin'); 
		$client->setDeveloperKey('AIzaSyBFfz3E2pbqM_RVg-u5xIjV8U_c59DP37o'); // Developer key
		$plus 		= new \Google_PlusService($client);
		$oauth2 	= new Google_Oauth2Service($client); // Call the OAuth2 class for get email address		
		
	if(isset($_GET['code'])) {
		$client->authenticate(); // Authenticate
		//$_SESSION['access_token'] = $client->getAccessToken(); // get the access token here
		$gplussession->access_token = $client->getAccessToken();
		//header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
	}

	if(isset($gplussession->access_token)) {
		$client->setAccessToken($gplussession->access_token);
	}

	if ($client->getAccessToken()) {
	  $user 		= $oauth2->userinfo->get();
	  $me 			= $plus->people->get('me');
	  $optParams 	= array('maxResults' => 100);
	  $activities 	= $plus->activities->listActivities('me', 'public',$optParams);
	  // The access token may have been updated lazily.
	  $gplussession->access_token		= $client->getAccessToken();
	  $email 							= filter_var($user['email'], FILTER_SANITIZE_EMAIL); // get the USER EMAIL ADDRESS using OAuth2
	} else {
		$authUrl = $client->createAuthUrl();
	}

	if(isset($me)){ 
		//$_SESSION['gplusuer'] = $me; // start the session
		$gplussession->gplusuer = $me;
	}

	if(isset($_GET['logout']) && $_GET['logout'] == 'true' ) {

	  unset($gplussession->access_token);
	  unset($gplussession->gplusuer);
	  //session_destroy();
	 // header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']); // it will simply destroy the current seesion which you started before
	 header('Location: https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=http://matrixmedia.redappletech.com/huntinggame/public/');  
	  /*NOTE: for logout and clear all the session direct goole jus un comment the above line an comment the first header function */
	}		
			
			
			
	$this->layout()->setVariable('gplusAuthUrl', $authUrl);	
	$this->layout()->setVariable('gplusaccess_token', $gplussession->access_token	);	
	$this->layout()->setVariable('gplusuer', $gplussession->gplusuer);		
		
		
	}	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

	
}
