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

use Application\Model\Subscribe;
use Application\Model\CustomerSupport;    
use Application\Form\CustomerSupportForm;  
use Application\Form\SubscribeForm; 

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mail;

use Facebook; 
use Google_Client; 
use Google_PlusService;
use Google_Oauth2Service;


class IndexController extends AbstractActionController
{
	protected $othersGameTable;
	protected $pagesTable;
	protected $imgsliderTable;
	protected $screenShotsTable;
	protected $gameVideoTable;
	protected $interviewVideoTable;
	protected $sponsorTable;
	protected $offersTable;
	protected $newsTable;
	protected $tournamentTable;
	protected $short_title = 'about-game';	 // Default for home page
	
	public function getOffersTable()
    {
        if (!$this->offersTable) {
            $sm = $this->getServiceLocator();
            $this->offersTable = $sm->get('Admin\Model\OffersTable');
        }
        return $this->offersTable;
    }
	
	
	
	
	
	
	
	
	# Call OthersGame mOdel	
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

	public function getSponsorTable()
    {
        if (!$this->sponsorTable) {
            $sm = $this->getServiceLocator();
            $this->sponsorTable = $sm->get('Admin\Model\SponsorTable');
        }
        return $this->sponsorTable;
    }
	# get News Model
	public function getNewsTable()
    {
        if (!$this->newsTable) {
            $sm = $this->getServiceLocator();
            $this->newsTable = $sm->get('Admin\Model\NewsTable');
        }
        return $this->newsTable;
    }	


	
	# Call PageModel
	public function getPageTable()
    {
        if (!$this->pagesTable) {
            $sm = $this->getServiceLocator();
            $this->pagesTable = $sm->get('Admin\Model\PagesTable');
        }
        return $this->pagesTable;
    }
	
	# Call Scrren-Shots Model
	public function getScreenShotsTable()
    {
        if (!$this->screenShotsTable) {
            $sm = $this->getServiceLocator();
            $this->screenShotsTable = $sm->get('Admin\Model\ScreenShotsTable');
        }
        return $this->screenShotsTable;
    }	
	
	# Call Game-Video Model
	public function getGameVideoTable()
    {
        if (!$this->gameVideoTable) {
            $sm = $this->getServiceLocator();
            $this->gameVideoTable = $sm->get('Admin\Model\GameVideoTable');
        }
        return $this->gameVideoTable;
    }
	# Call Interview-Video Model
	public function getInterviewVideoTable()
    {
        if (!$this->interviewVideoTable) {
            $sm = $this->getServiceLocator();
            $this->interviewVideoTable = $sm->get('Admin\Model\InterviewVideoTable');
        }
        return $this->interviewVideoTable;
    }

	# Call Tournament Model
	public function getTournamentTable()
    {
        if (!$this->tournamentTable) {
            $sm = $this->getServiceLocator();
            $this->tournamentTable = $sm->get('Admin\Model\TournamentTable');
        }
        return $this->tournamentTable;
    }






	
	
	public function init()
	{
		$others_game_lists = $this->getOthersGameTable()->fetchAll()  ;		
		$this->layout()->setVariable('others_game_lists', $others_game_lists);			
		$this->layout()->setVariable('banners', $this->getImageSliderTable()->fetchAll() );
		$this->layout()->setVariable('site_sponsor', $this->getSponsorTable()->fetchAll() );
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
		
		
		} else {

			$loginUrl = $facebook->getLoginUrl(array(	'scope'		=> 'email, read_stream, friends_likes', 'redirect_uri' => 'http://matrixmedia.redappletech.com/huntinggame/public/application/index/loginfb'));	
			$this->layout()->setVariable('fb_login_url', $loginUrl );	
		}		

		
	}






	

	
	

    public function indexAction()
    {
		$this->init();
		$this->checkLogin();		
		$this->googlePlusLogin();
		
		return new ViewModel(array(				
				'home_pages' 		=> $this->getPageTable()->getDataByShortCode($this->short_title),
				'screen_shots' 		=> $this->getScreenShotsTable()->fetchAll(),
				'game_video' 		=> $this->getGameVideoTable()->fetchAll()
							
			));
			
			
			
    }
	
	public function customerSupportAction()
	{
		$this->init();
		$this->checkLogin();		
		$this->googlePlusLogin();		
		$form = new CustomerSupportForm();
		$request = $this->getRequest();
		if ($request->isPost())
		{
				$customerSupport = new CustomerSupport();   

				$form->setInputFilter($customerSupport->getInputFilter());				
				$form->setData($request->getPost());				
				if ($form->isValid())
				{

					$name 			= $request->getPost('name');
					$email 			= $request->getPost('email');
					$contactno 		= $request->getPost('contactno');
					$subject 		= $request->getPost('subject');
					$short_note 	= $request->getPost('short_note');
				//	echo $name.'<br/>'.$email.'<br/>'.$contactno.'<br/>'.$subject.'<br/>'.$short_note;
                //    exit;
					$mail = new Mail\Message();
					$mail->setBody($short_note);
					$mail->setFrom($email, $name);
					$mail->addTo('arup@redappletech.com', 'Arup Roy');
					$mail->setSubject($subject);

					$transport = new Mail\Transport\Sendmail();
					$transport->send($mail);	
					
					$this->flashMessenger()->addMessage('Your mail has been delivered successfully');		
					return $this->redirect()->toRoute('index', array('action'=>'customer-support'));			
				
				
				
				
				}				

			
		}

		$this->layout()->setVariable('active_menu', 'customer-support');

		return new ViewModel(array(
								'form' => $form	,
								'flashMessages'  => $this->flashmessenger()->getMessages()	,								
			));
	}
	
	public function subscribeAction()
	{
		$this->init();
		$this->checkLogin();		
		$this->googlePlusLogin();		
		
		
		$form = new SubscribeForm();
		
		$request = $this->getRequest();
		if ($request->isPost())
		{
		
				$subscribe = new Subscribe();   

				$form->setInputFilter($subscribe->getInputFilter());				
				$form->setData($request->getPost());				
				if ($form->isValid())
				{		

					$name =  $request->getPost('name');
					$email = $request->getPost('email');				
					$Data = array();
					$Data['email'] 	= $email;
					$Data['name'] 	= $name	;
					$Data['created_on'] 	= date("Y-m-d")	;	
					$Data['active'] 	= 1	;											
					
					
					//$adapter = $this->tableGateway->getAdapter();
					$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
					$validator = new \Zend\Validator\Db\NoRecordExists(
																		array(
																			'table'   => 'subscriber_signup',
																			'field'   => 'email',
																			'adapter' => $dbAdapter
																		)
								);

						if ($validator->isValid($email)) {
							// email address appears to be valid
							
						$sql       = new Sql($dbAdapter);
						$insert    = $sql->insert('subscriber_signup');
						$insert->values($Data);

						$statement = $sql->prepareStatementForSqlObject($insert);				
						$results    = $statement->execute();			
						$return_id = $dbAdapter->getDriver()->getLastGeneratedValue();	
						if($return_id) $this->flashMessenger()->addMessage('Your subscription has been created !');		
						return $this->redirect()->toRoute('index', array('action'=>'subscribe'));
							
						} else {
									//$this->flashMessenger()->addMessage('A record matching the input was found!');
						$this->layout()->setVariable('message', $validator->getMessages() );									
							// email address is invalid; print the reasons
							//foreach ($validator->getMessages() as $message) {
								//echo "$message\n";
							//}
						}

				}
		
		}			
		
		
		
		
		
		return new ViewModel(array(
								'form' => $form	,
								'flashMessages'  => $this->flashmessenger()->getMessages()								
			));
	}	
	
	
	
	
	
	
	
	
	public function recentOffersAction()
	{
		$this->init();
		
		$this->checkLogin();		
		$this->googlePlusLogin();		
		//$form = new CustomerSupportForm();
		return new ViewModel(array(
								'recent_offers' => $this->getOffersTable()->recentOffers(),		
			));
	}


	public function newsAction()
	{
		$this->init();
		$this->checkLogin();		
		$this->googlePlusLogin();		
		
		
		return new ViewModel(array(
								'latest_news' => $this->getNewsTable()->fetchAll(),		
			));
		
	}
	
	public function tournamentsAction()
	{
	   exit;
		$this->init();
		$this->checkLogin();		
		$this->googlePlusLogin();		
		
		
		$this->layout()->setVariable('tournament-sidebar', 'active_menu' );
		$type =  $this->params()->fromQuery('type', 'Daily');
		//echo $type ; exit;
	return new ViewModel(array(
								'tournaments' => $this->getTournamentTable()->loadByType($type),		
			));
		
	}	
	
	
	
	
	
	
	
	public function sponsorsAction()
	{
		$this->init();
		$this->checkLogin();		
		$this->googlePlusLogin();		
		
		
		return new ViewModel(array(
								'game_sponsors' => $this->getSponsorTable()->fetchAll(),		
			));
		
	}

	public function interviewVideoAction()
	{
		$this->init();
		$this->checkLogin();		
		$this->googlePlusLogin();		
		
		
		return new ViewModel(array(
								'interview_video' => $this->getInterviewVideoTable()->fetchAll(),		
			));
		
	}

	
	
	
	public function logoutfbAction()
    {		
		$facebook = new Facebook(array(
		'appId'  => '386391428152834',   // Facebook App ID 
		'secret' => '498339cca36cc580d14e59a65485b3b8',  // Facebook App Secret
		'cookie' => true,	
		));
		//$user = $facebook->getUser();
		$facebook->destroySession();
		$fb_session_logout = new Container('fbloginbase');
		$fb_session_logout->getManager()->getStorage()->clear('fbloginbase');		
		return $this->redirect()->toRoute('application', array('controller' => 'index', 'action'=>'index'));
		exit();		
    }
	
	
	
	public function loginfbAction()
    {	
		
		$facebook = new Facebook(array(
		  'appId'  => '386391428152834',   // Facebook App ID 
		  'secret' => '498339cca36cc580d14e59a65485b3b8',  // Facebook App Secret
		  'cookie' => true,	
		));
		
		$facebook->destroySession();
		$user = $facebook->getUser();
         
		$access_token = $facebook->getAccessToken();
		
		
		
		if ($user) {
		  try {
				$user_profile = $facebook->api('/me');
				$fbid = $user_profile['id'];                 // To Get Facebook ID
				$fbuname = $user_profile['username'];  // To Get Facebook Username
				$fbfullname = $user_profile['name']; // To Get Facebook full name
				$femail = $user_profile['email'];    // To Get Facebook email ID
			/* ---- Session Variables -----*/
				$fbsession = new Container('fbloginbase');
				$fbsession->FBID = $fbid; 
				$fbsession->USERNAME = $fbuname;
				$fbsession->FULLNAME = $fbfullname;
				$fbsession->EMAIL = $femail;
				return $this->redirect()->toRoute('application', array('controller' => 'player', 'action'=>'player-profile'));
				exit();
			
		  } catch (FacebookApiException $e) {
			error_log($e);
		   $user = null;
		  }
		}
		if ($user) {
						$access_array = split("\|", $access_token);
						$session_key = $access_array[1];
		
		
					//return $this->redirect()->toRoute('application', array('controller' => 'player', 'action'=>'player-profile'));						
					$params = array( 'next' => 'http://matrixmedia.redappletech.com/huntinggame/public/application/player/logoutfb', 'session_key' => $session_key);		
					$logoutUrl = $facebook->getLogoutUrl($params);
	
		} else {
			$loginUrl = $facebook->getLoginUrl(array(	'scope'		=> 'email, read_stream, friends_likes', 'redirect_uri' => 'http://matrixmedia.redappletech.com/huntinggame/public/application/index/loginfb'));
			header("Location: ".$loginUrl);
		}
		exit();
        //return new ViewModel();
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
	  
	}		
		
		
		
	$this->layout()->setVariable('gplusAuthUrl', $authUrl);	
	$this->layout()->setVariable('gplusaccess_token', $gplussession->access_token	);	
	$this->layout()->setVariable('gplusuer', $gplussession->gplusuer);	
		
		
	}
	
	
	
	
	public function googleloginAction()
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
		return $this->redirect()->toRoute('application', array('controller' => 'index', 'action'=>'index'));
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

	if(isset($_GET['logout'])) {
	  unset($gplussession->access_token);
	  unset($gplussession->gplusuer);
	  return $this->redirect()->toRoute('application', array('controller' => 'index', 'action'=>'index'));
	  //session_destroy();
	 // header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']); // it will simply destroy the current seesion which you started before
	  #header('Location: https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
	  
	  /*NOTE: for logout and clear all the session direct goole jus un comment the above line an comment the first header function */
	}		
			
			
			
		$this->layout()->setVariable('gplusAuthUrl', $authUrl);			
		
		
	}
		
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
