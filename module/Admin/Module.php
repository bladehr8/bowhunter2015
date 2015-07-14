<?php
namespace Admin;

// Add these import statements:
use Admin\Model\Admin;
use Admin\Model\AdminTable;
use Admin\Model\Light;
use Admin\Model\LightTable;

use Admin\Model\Hunttype;
use Admin\Model\HunttypeTable;
use Admin\Model\Suggestion;
use Admin\Model\SuggestionTable;
// Add CMS Pages Model
use Admin\Model\Pages;
use Admin\Model\PagesTable;

use Admin\Model\Subscribe;
use Admin\Model\SubscribeTable;

use Admin\Model\ScreenShots;
use Admin\Model\ScreenShotsTable;

use Admin\Model\GameVideo;
use Admin\Model\GameVideoTable;

use Admin\Model\InterviewVideo;
use Admin\Model\InterviewVideoTable;

use Admin\Model\Offers;
use Admin\Model\OffersTable;

use Admin\Model\OthersGame;
use Admin\Model\OthersGameTable;

use Admin\Model\PlayerLevelStatus;
use Admin\Model\PlayerLevelStatusTable;

use Admin\Model\News;
use Admin\Model\NewsTable;

use Admin\Model\Sponsor;
use Admin\Model\SponsorTable;

use Admin\Model\ImageSlider;
use Admin\Model\ImageSliderTable;


use Admin\Model\FaceColor;
use Admin\Model\FaceColorTable;

use Admin\Model\PlayerRank;
use Admin\Model\PlayerRankTable;


use Admin\Model\Advertisement;
use Admin\Model\AdvertisementTable;

use Admin\Model\AdReport;
use Admin\Model\AdReportTable;

use Admin\Model\BankReport;
use Admin\Model\BankReportTable;

use Admin\Model\KillType;
use Admin\Model\KillTypeTable;

use Admin\Model\ShorthandTarget;
use Admin\Model\ShorthandTargetTable;

use Admin\Model\PlayerPosition;
use Admin\Model\PlayerPositionTable;

use Admin\Model\Player;
use Admin\Model\PlayerTable;

use Admin\Model\DeerActivity;
use Admin\Model\DeerActivityTable;

use Admin\Model\DeerSize;
use Admin\Model\DeerSizeTable;

use Admin\Model\DeerPosition;
use Admin\Model\DeerPositionTable;

use Admin\Model\DeerFacing;
use Admin\Model\DeerFacingTable;

use Admin\Model\Haircolor;
use Admin\Model\HaircolorTable;

use Admin\Model\Skincolor;
use Admin\Model\SkincolorTable;

use Admin\Model\Attributeset;
use Admin\Model\AttributesetTable;

use Admin\Model\Attributefield;
use Admin\Model\AttributefieldTable;

use Admin\Model\Weapon;
use Admin\Model\WeaponTable;

use Admin\Model\Weaponattrubutevalue;
use Admin\Model\WeaponattrubutevalueTable;

use Admin\Model\Tournament;
use Admin\Model\TournamentTable;

use Admin\Model\Bank;
use Admin\Model\BankTable;

use Admin\Model\Region;
use Admin\Model\RegionTable;

use Admin\Model\Clothing;
use Admin\Model\ClothingTable;

use Admin\Model\Deerinfo;
use Admin\Model\DeerinfoTable;

use Admin\Model\Missions;
use Admin\Model\MissionsTable;


use Admin\Model\subscribersMail;
use Admin\Model\subscribersMailTable;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Session\Config\SessionConfig;
use Zend\Session\SessionManager;
use Zend\Session\Container;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;




class Module implements AutoloaderProviderInterface
{

		public function onDispatchError($e)
		{
			return $this->getJsonModelError($e);
		}

		public function onRenderError($e)
		{
			return $this->getJsonModelError($e);
		}

		public function getJsonModelError($e)
		{
			$error = $e->getError();
			if (!$error) {
				return;
			}

			$response = $e->getResponse();
			$exception = $e->getParam('exception');
			$exceptionJson = array();
			if ($exception) {
				$exceptionJson = array(
					'class' => get_class($exception),
					'file' => $exception->getFile(),
					'line' => $exception->getLine(),
					'message' => $exception->getMessage(),
					'stacktrace' => $exception->getTraceAsString()
				);
			}

			$errorJson = array(
				'message'   => 'An error occurred during execution; please try again later.',
				'error'     => $error,
				'exception' => $exceptionJson,
			);
			if($error == 'error-controller-not-found' ) {
				$errorJson['message'] =  'The requested controller could not be mapped to an existing controller class.';
			}
			if($error ==  'error-controller-invalid' ) {
				$errorJson['message'] = 'The requested controller was not dispatchable.';
			}

			if ($error == 'error-router-no-match') {
				$errorJson['message'] = 'The requested URL could not be matched by routing.';
			}

			$model = new JsonModel(array('errors' => array($errorJson)));

			$e->setResult($model);

			return $model;
		}
















    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
	
	    // Add this method:
    public function getServiceConfig()
    {
	
	        
        
        return array(
            'factories' => array(
			
                'Admin\Model\AdminTable' =>  function($sm) {
                    $tableGateway = $sm->get('AdminTableGateway');
                    $table = new AdminTable($tableGateway);
                    return $table;
                },
                'AdminTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Admin());
                    return new TableGateway('admin', $dbAdapter, null, $resultSetPrototype);
                },
				
                'Admin\Model\PlayerLevelStatusTable' =>  function($sm) {
                    $tableGateway = $sm->get('PlayerLevelStatusTableGateway');
                    $table = new PlayerLevelStatusTable($tableGateway);
                    return $table;
                },
                'PlayerLevelStatusTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new PlayerLevelStatus());
                    return new TableGateway('player_level_staus', $dbAdapter, null, $resultSetPrototype);
                },					
				
				
				
				
				
				
				
				
				
				
                'Admin\Model\PagesTable' =>  function($sm) {
                    $tableGateway = $sm->get('PagesTableGateway');
                    $table = new PagesTable($tableGateway);
                    return $table;
                },
                'PagesTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Pages());
                    return new TableGateway('pages', $dbAdapter, null, $resultSetPrototype);
                },	
				
				
                'Admin\Model\SubscribeTable' =>  function($sm) {
                    $tableGateway = $sm->get('SubscribeTableGateway');
                    $table = new SubscribeTable($tableGateway);
                    return $table;
                },
                'SubscribeTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Subscribe());
                    return new TableGateway('subscriber_signup', $dbAdapter, null, $resultSetPrototype);
                },				
				
				
				
				
				
				
				
				
				
                'Admin\Model\GameVideoTable' =>  function($sm) {
                    $tableGateway = $sm->get('GameVideoTableGateway');
                    $table = new GameVideoTable($tableGateway);
                    return $table;
                },
                'GameVideoTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new GameVideo());
                    return new TableGateway('game_video', $dbAdapter, null, $resultSetPrototype);
                },

                'Admin\Model\InterviewVideoTable' =>  function($sm) {
                    $tableGateway = $sm->get('InterviewVideoTableGateway');
                    $table = new InterviewVideoTable($tableGateway);
                    return $table;
                },
                'InterviewVideoTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new InterviewVideo());
                    return new TableGateway('interview_video', $dbAdapter, null, $resultSetPrototype);
                },



				
				
				
                'Admin\Model\OffersTable' =>  function($sm) {
                    $tableGateway = $sm->get('OffersTableGateway');
                    $table = new OffersTable($tableGateway);
                    return $table;
                },
                'OffersTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Offers());
                    return new TableGateway('offers', $dbAdapter, null, $resultSetPrototype);
                },

                'Admin\Model\OthersGameTable' =>  function($sm) {
                    $tableGateway = $sm->get('OthersGameTableGateway');
                    $table = new OthersGameTable($tableGateway);
                    return $table;
                },
                'OthersGameTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new OthersGame());
                    return new TableGateway('others_game', $dbAdapter, null, $resultSetPrototype);
                },



				
								
                'Admin\Model\NewsTable' =>  function($sm) {
                    $tableGateway = $sm->get('NewsTableGateway');
                    $table = new NewsTable($tableGateway);
                    return $table;
                },
                'NewsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new News());
                    return new TableGateway('news', $dbAdapter, null, $resultSetPrototype);
                },					
				
                'Admin\Model\SponsorTable' =>  function($sm) {
                    $tableGateway = $sm->get('SponsorTableGateway');
                    $table = new SponsorTable($tableGateway);
                    return $table;
                },
                'SponsorTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Sponsor());
                    return new TableGateway('sponsors', $dbAdapter, null, $resultSetPrototype);
                },				
				
				
				
				
				
				
                'Admin\Model\ScreenShotsTable' =>  function($sm) {
                    $tableGateway = $sm->get('ScreenShotsTableGateway');
                    $table = new ScreenShotsTable($tableGateway);
                    return $table;
                },
                'ScreenShotsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new ScreenShots());
                    return new TableGateway('screen_shots', $dbAdapter, null, $resultSetPrototype);
                },					
				
				
                'Admin\Model\ImageSliderTable' =>  function($sm) {
                    $tableGateway = $sm->get('ImageSliderTableGateway');
                    $table = new ImageSliderTable($tableGateway);
                    return $table;
                },
                'ImageSliderTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new ImageSlider());
                    return new TableGateway('img_sliding_pool', $dbAdapter, null, $resultSetPrototype);
                },					
								
				
				
				
				
				
				
				
				
				

                'Admin\Model\FaceColorTable' =>  function($sm) {
                    $tableGateway = $sm->get('FaceColorTableGateway');
                    $table = new FaceColorTable($tableGateway);
                    return $table;
                },
                'FaceColorTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new FaceColor());
                    return new TableGateway('facecolor', $dbAdapter, null, $resultSetPrototype);
                },	


                'Admin\Model\PlayerRankTable' =>  function($sm) {
                    $tableGateway = $sm->get('PlayerRankTableGateway');
                    $table = new PlayerRankTable($tableGateway);
                    return $table;
                },
                'PlayerRankTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new PlayerRank());
                    return new TableGateway('player_rank_system', $dbAdapter, null, $resultSetPrototype);
                },	









				
				
				
				
				
				
			 'Admin\Model\AdReportTable' =>  function($sm) {
                    $tableGateway = $sm->get('AdReportTableGateway');
                    $table = new AdReportTable($tableGateway);
                    return $table;
                },
                'AdReportTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new AdReport());
                    return new TableGateway('advertisement_logs', $dbAdapter, null, $resultSetPrototype);
                },
				
			 'Admin\Model\BankReportTable' =>  function($sm) {
                    $tableGateway = $sm->get('BankReportTableGateway');
                    $table = new BankReportTable($tableGateway);
                    return $table;
                },
                'BankReportTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new BankReport());
                    return new TableGateway('payments_history', $dbAdapter, null, $resultSetPrototype);
                },				
				
				
				
				
				
				'Admin\Model\PlayerTable' =>  function($sm) {
                    $tableGateway = $sm->get('PlayerTableGateway');
                    $table = new PlayerTable($tableGateway);
                    return $table;
                },
                'PlayerTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Player());
                    return new TableGateway('player', $dbAdapter, null, $resultSetPrototype);
                },
				
				'Admin\Model\AdvertisementTable' =>  function($sm) {
                    $tableGateway = $sm->get('AdvertisementTableGateway');
                    $table = new AdvertisementTable($tableGateway);
                    return $table;
                },
                'AdvertisementTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Advertisement());
                    return new TableGateway('advertisement', $dbAdapter, null, $resultSetPrototype);
                },				
				
				
				
				
				
				
				'Admin\Model\HaircolorTable' =>  function($sm) {
                    $tableGateway = $sm->get('HaircolorTableGateway');
                    $table = new HaircolorTable($tableGateway);
                    return $table;
                },
                'HaircolorTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Haircolor());
                    return new TableGateway('haircolor', $dbAdapter, null, $resultSetPrototype);
                },
				'Admin\Model\SkincolorTable' =>  function($sm) {
                    $tableGateway = $sm->get('SkincolorTableGateway');
                    $table = new SkincolorTable($tableGateway);
                    return $table;
                },
                'SkincolorTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Skincolor());
                    return new TableGateway('skincolor', $dbAdapter, null, $resultSetPrototype);
                },
				'Admin\Model\AttributesetTable' =>  function($sm) {
                    $tableGateway = $sm->get('AttributesetTableGateway');
                    $table = new AttributesetTable($tableGateway);
                    return $table;
                },
                'AttributesetTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Attributeset());
                    return new TableGateway('attributeset', $dbAdapter, null, $resultSetPrototype);
                },
				'Admin\Model\AttributefieldTable' =>  function($sm) {
                    $tableGateway = $sm->get('AttributefieldTableGateway');
                    $table = new AttributefieldTable($tableGateway);
                    return $table;
                },
                'AttributefieldTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Attributefield());
                    return new TableGateway('attributefield', $dbAdapter, null, $resultSetPrototype);
                },
				'Admin\Model\WeaponTable' =>  function($sm) {
                    $tableGateway = $sm->get('WeaponTableGateway');
                    $table = new WeaponTable($tableGateway);
                    return $table;
                },
                'WeaponTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Weapon());
                    return new TableGateway('weapon', $dbAdapter, null, $resultSetPrototype);
                },
				'Admin\Model\WeaponattrubutevalueTable' =>  function($sm) {
                    $tableGateway = $sm->get('WeaponattrubutevalueTableGateway');
                    $table = new WeaponattrubutevalueTable($tableGateway);
                    return $table;
                },
                'WeaponattrubutevalueTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Weaponattrubutevalue());
                    return new TableGateway('weapon_attribute_value', $dbAdapter, null, $resultSetPrototype);
                },
				'Admin\Model\TournamentTable' =>  function($sm) {
                    $tableGateway = $sm->get('TournamentTableGateway');
                    $table = new TournamentTable($tableGateway);
                    return $table;
                },
                'TournamentTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Tournament());
                    return new TableGateway('tournament', $dbAdapter, null, $resultSetPrototype);
                },
				
				'Admin\Model\MissionsTable' =>  function($sm) {
                    $tableGateway = $sm->get('MissionsTableGateway');
                    $table = new MissionsTable($tableGateway);
                    return $table;
                },
                'MissionsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Missions());
                    return new TableGateway('missions', $dbAdapter, null, $resultSetPrototype);
                },				
				
				
				
				
				
				
				
				
				'Admin\Model\BankTable' =>  function($sm) {
                    $tableGateway = $sm->get('BankTableGateway');
                    $table = new BankTable($tableGateway);
                    return $table;
                },
                'BankTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Bank());
                    return new TableGateway('bank', $dbAdapter, null, $resultSetPrototype);
                },
				
				'Admin\Model\RegionTable' =>  function($sm) {
                    $tableGateway = $sm->get('RegionTableGateway');
                    $table = new RegionTable($tableGateway);
                    return $table;
                },
                'RegionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Region());
                    return new TableGateway('region', $dbAdapter, null, $resultSetPrototype);
                },
				'Admin\Model\ClothingTable' =>  function($sm) {
                    $tableGateway = $sm->get('ClothingTableGateway');
                    $table = new ClothingTable($tableGateway);
                    return $table;
                },
                'ClothingTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Clothing());
                    return new TableGateway('clothing', $dbAdapter, null, $resultSetPrototype);
                },
				
				'Admin\Model\DeerinfoTable' =>  function($sm) {
                    $tableGateway = $sm->get('DeerinfoTableGateway');
                    $table = new DeerinfoTable($tableGateway);
                    return $table;
                },
                'DeerinfoTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Deerinfo());
                    return new TableGateway('deer_information', $dbAdapter, null, $resultSetPrototype);
                },				
				
				'Admin\Model\LightTable' =>  function($sm) {
                    $tableGateway = $sm->get('LightTableGateway');
                    $table = new LightTable($tableGateway);
                    return $table;
                },
                'LightTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Light());
                    return new TableGateway('light_conditions', $dbAdapter, null, $resultSetPrototype);
                },		
				
				
				'Admin\Model\HunttypeTable' =>  function($sm) {
                    $tableGateway = $sm->get('HunttypeTableGateway');
                    $table = new HunttypeTable($tableGateway);
                    return $table;
                },
                'HunttypeTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Hunttype());
                    return new TableGateway('hunt_type', $dbAdapter, null, $resultSetPrototype);
                },					
				
				
				
				'Admin\Model\SuggestionTable' =>  function($sm) {
                    $tableGateway = $sm->get('SuggestionTableGateway');
                    $table = new SuggestionTable($tableGateway);
                    return $table;
                },
                'SuggestionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Suggestion());
                    return new TableGateway('hunt_suggestion', $dbAdapter, null, $resultSetPrototype);
                },					
				
				
				'Admin\Model\KillTypeTable' =>  function($sm) {
                    $tableGateway = $sm->get('KillTypeTableGateway');
                    $table = new KillTypeTable($tableGateway);
                    return $table;
                },
                'KillTypeTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new KillType());
                    return new TableGateway('kill_type', $dbAdapter, null, $resultSetPrototype);
                },					
				
				
				'Admin\Model\ShorthandTargetTable' =>  function($sm) {
                    $tableGateway = $sm->get('ShorthandTargetTableGateway');
                    $table = new ShorthandTargetTable($tableGateway);
                    return $table;
                },
                'ShorthandTargetTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new ShorthandTarget());
                    return new TableGateway('shorthand_target_type', $dbAdapter, null, $resultSetPrototype);
                },				
				
				'Admin\Model\PlayerPositionTable' =>  function($sm) {
                    $tableGateway = $sm->get('PlayerPositionTableGateway');
                    $table = new PlayerPositionTable($tableGateway);
                    return $table;
                },
                'PlayerPositionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new PlayerPosition());
                    return new TableGateway('player_position', $dbAdapter, null, $resultSetPrototype);
                },				
				
				
				'Admin\Model\DeerActivityTable' =>  function($sm) {
                    $tableGateway = $sm->get('DeerActivityTableGateway');
                    $table = new DeerActivityTable($tableGateway);
                    return $table;
                },
                'DeerActivityTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new DeerActivity());
                    return new TableGateway('deer_activity', $dbAdapter, null, $resultSetPrototype);
                },	

				'Admin\Model\DeerSizeTable' =>  function($sm) {
                    $tableGateway = $sm->get('DeerSizeTableGateway');
                    $table = new DeerSizeTable($tableGateway);
                    return $table;
                },
                'DeerSizeTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new DeerSize());
                    return new TableGateway('deer_size', $dbAdapter, null, $resultSetPrototype);
                },	


				'Admin\Model\DeerFacingTable' =>  function($sm) {
                    $tableGateway = $sm->get('DeerFacingTableGateway');
                    $table = new DeerFacingTable($tableGateway);
                    return $table;
                },
                'DeerFacingTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new DeerFacing());
                    return new TableGateway('deer_facing', $dbAdapter, null, $resultSetPrototype);
                },	


				'Admin\Model\DeerPositionTable' =>  function($sm) {
                    $tableGateway = $sm->get('DeerPositionTableGateway');
                    $table = new DeerPositionTable($tableGateway);
                    return $table;
                },
                'DeerPositionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new DeerPosition());
                    return new TableGateway('deer_position', $dbAdapter, null, $resultSetPrototype);
                },	


				
				
				
				 'Admin\Model\subscribersMailTable' =>  function($sm) {
                    $tableGateway = $sm->get('subscribersMailTableGateway');
                    $table = new subscribersMailTable($tableGateway);
                    return $table;
                },
                'subscribersMailTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new subscribersMail());
                    return new TableGateway('subscribersMail', $dbAdapter, null, $resultSetPrototype);
                },					
				
				


				
				'Admin\Model\MyAuthStorage' => function($sm){
					return new \Admin\Model\MyAuthStorage('adminstore'); 
				},
				
				'AuthService' => function($sm) {
									//My assumption, you've alredy set dbAdapter
									//and has users table with columns : user_name and pass_word
									//that password hashed with md5
									$dbAdapter           = $sm->get('Zend\Db\Adapter\Adapter');
									$dbTableAuthAdapter  = new DbTableAuthAdapter($dbAdapter,    'admin','email','password', 'MD5(?)');                                          
									$authService = new AuthenticationService();
									$authService->setAdapter($dbTableAuthAdapter);
									$authService->setStorage($sm->get('Admin\Model\MyAuthStorage'));              
									return $authService;
								},
				
				
				
            ),
			
        );
    }
	
		public function initSession($config)
		{
			$sessionConfig = new SessionConfig();
			$sessionConfig->setOptions($config);
			$sessionManager = new SessionManager($sessionConfig);
			$sessionManager->start();
			Container::setDefaultManager($sessionManager);
		}

	
		public function onBootstrap(MvcEvent $e)
		{
			$eventManager        = $e->getApplication()->getEventManager();
			$moduleRouteListener = new ModuleRouteListener();
			$moduleRouteListener->attach($eventManager);

			//$eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'onDispatchError'), 0);
			//$eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, array($this, 'onRenderError'), 0);
		
		
		
		
		
		
		
		
		
		
		
			$this->initSession(
									array(
											'remember_me_seconds' 	=> 180,
											'use_cookies' 			=> true,
											'cookie_httponly' 		=> true,
									)
			);


			$e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function($e) {
			$controller = $e->getTarget();
			$controllerClass = get_class($controller);
			$moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
			$controller->layout($moduleNamespace . '/layout');
			}, 100);

		} 

}
