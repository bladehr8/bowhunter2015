<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Admin' => 'Admin\Controller\AdminController',
			'Admin\Controller\Player' => 'Admin\Controller\PlayerController',
			'Admin\Controller\PlayerApi' => 'Admin\Controller\PlayerApiController',
			'Admin\Controller\Haircolor' => 'Admin\Controller\HaircolorController',
			'Admin\Controller\Skincolor' => 'Admin\Controller\SkincolorController',
			'Admin\Controller\Attributeset' => 'Admin\Controller\AttributesetController',
			'Admin\Controller\Attributefield' => 'Admin\Controller\AttributefieldController',
			'Admin\Controller\Weapon' => 'Admin\Controller\WeaponController',
			'Admin\Controller\Tournament' => 'Admin\Controller\TournamentController',
			'Admin\Controller\Missions' => 'Admin\Controller\MissionsController',
			'Admin\Controller\Bank' => 'Admin\Controller\BankController',
			'Admin\Controller\ApiRest' => 'Admin\Controller\ApiRestController',
			'Admin\Controller\ApiPlayer' => 'Admin\Controller\ApiPlayerController',
			'Admin\Controller\Region' => 'Admin\Controller\RegionController',
			'Admin\Controller\Clothing' => 'Admin\Controller\ClothingController',
			'Admin\Controller\Deerinfo' => 'Admin\Controller\DeerinfoController',
			'Admin\Controller\Light' => 'Admin\Controller\LightController',
			'Admin\Controller\Hunttype' => 'Admin\Controller\HunttypeController',
			'Admin\Controller\Suggestion' => 'Admin\Controller\SuggestionController',
			'Admin\Controller\ApiRegion' => 'Admin\Controller\ApiRegionController',
			'Admin\Controller\ApiLight' => 'Admin\Controller\ApiLightController',
			'Admin\Controller\ApiHunttype' => 'Admin\Controller\ApiHunttypeController',
			'Admin\Controller\ApiDeerinfo' => 'Admin\Controller\ApiDeerinfoController',
			'Admin\Controller\ApiSuggestion' => 'Admin\Controller\ApiSuggestionController',
			'Admin\Controller\ApiTournament' => 'Admin\Controller\ApiTournamentController',
			'Admin\Controller\KillType' 	 		=> 'Admin\Controller\KillTypeController',
			'Admin\Controller\ShorthandTarget' 	 	=> 'Admin\Controller\ShorthandTargetController',
			'Admin\Controller\PlayerPosition' 	 	=> 'Admin\Controller\PlayerPositionController',			
			'Admin\Controller\DeerActivity' 	 	=> 'Admin\Controller\DeerActivityController',
			'Admin\Controller\DeerSize' 	 		=> 'Admin\Controller\DeerSizeController',
			'Admin\Controller\DeerFacing' 		 	=> 'Admin\Controller\DeerFacingController',
			'Admin\Controller\DeerPosition' 	 	=> 'Admin\Controller\DeerPositionController',
			
			'Admin\Controller\ApiDeerSize' 			=> 'Admin\Controller\ApiDeerSizeController',
			'Admin\Controller\ApiDeerActivity' 		=> 'Admin\Controller\ApiDeerActivityController',
			'Admin\Controller\ApiDeerPosition' 		=> 'Admin\Controller\ApiDeerPositionController',
			'Admin\Controller\ApiDeerFacing' 		=> 'Admin\Controller\ApiDeerFacingController',
			
			'Admin\Controller\Advertisement' 		=> 'Admin\Controller\AdvertisementController',
			
			'Admin\Controller\ApiKillType' 			=> 'Admin\Controller\ApiKillTypeController',
			'Admin\Controller\ApiPlayerPosition' 	=> 'Admin\Controller\ApiPlayerPositionController',
			'Admin\Controller\ApiShortTarget' 		=> 'Admin\Controller\ApiShortTargetController',
			'Admin\Controller\ApiAdvertisement' 	=> 'Admin\Controller\ApiAdvertisementController',
			'Admin\Controller\AdReport' 			=> 'Admin\Controller\AdReportController',
			'Admin\Controller\BankReport' 			=> 'Admin\Controller\BankReportController',
			'Admin\Controller\Pages' 				=> 'Admin\Controller\PagesController',
			'Admin\Controller\FaceColor' 			=> 'Admin\Controller\FaceColorController',
			'Admin\Controller\PlayerRank' 			=> 'Admin\Controller\PlayerRankController',
			'Admin\Controller\ScreenShots' 			=> 'Admin\Controller\ScreenShotsController',
			'Admin\Controller\GameVideo' 			=> 'Admin\Controller\GameVideoController',
			'Admin\Controller\Offers' 				=> 'Admin\Controller\OffersController',
			'Admin\Controller\News' 				=> 'Admin\Controller\NewsController',
			'Admin\Controller\Sponsor' 				=> 'Admin\Controller\SponsorController',
			'Admin\Controller\ImageSlider' 			=> 'Admin\Controller\ImageSliderController',
			'Admin\Controller\OthersGame' 			=> 'Admin\Controller\OthersGameController',
			'Admin\Controller\InterviewVideo' 		=> 'Admin\Controller\InterviewVideoController',
			'Admin\Controller\Subscribe' 			=> 'Admin\Controller\SubscribeController',
			'Admin\Controller\ApiPlayerLevelStatus' => 'Admin\Controller\ApiPlayerLevelStatusController',
        ),
    ),
	
	'controller_plugins' => array(
									'invokables' => array(
															'MyFirstPlugin' => 'Admin\Controller\Plugin\MyFirstPlugin',
														)
	),
	
	
	

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
           /* 'admin' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Admin',
                        'action'        => 'index',
                    ),
                ),
				'may_terminate' => true,
				 'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action][/:id]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id' => '[a-zA-Z0-9]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ), */
			
			
            'admin' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Admin',
                        'action'     => 'index',
                    ),
                ),
            ),
			
			
			'hunttype' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/hunttype[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Hunttype',
                        'action'     => 'index',
                    ),
                ),
            ),
			
			'pages' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/pages[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Pages',
                        'action'     => 'index',
                    ),
                ),
            ),

			'subscribe' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/subscribe[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Subscribe',
                        'action'     => 'index',
                    ),
                ),
            ),	



			
			
			'screen-shots' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/screen-shots[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\ScreenShots',
                        'action'     => 'index',
                    ),
                ),
            ),			
			
			'game-video' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/game-video[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\GameVideo',
                        'action'     => 'index',
                    ),
                ),
            ),
			
			'interview-video' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/interview-video[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\InterviewVideo',
                        'action'     => 'index',
                    ),
                ),
            ),			
			
			
			
			
			
			
			
			
			
			
			

			'offers' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/offers[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Offers',
                        'action'     => 'index',
                    ),
                ),
            ),
			
			'others-game' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/others-game[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\OthersGame',
                        'action'     => 'index',
                    ),
                ),
            ),			
			
			
			
			
			
			
			
			
			
			
			

			'news' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/news[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\News',
                        'action'     => 'index',
                    ),
                ),
            ),
			
			
			'sponsor' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/sponsor[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Sponsor',
                        'action'     => 'index',
                    ),
                ),
            ),			
			
			'img-slider' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/img-slider[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\ImageSlider',
                        'action'     => 'index',
                    ),
                ),
            ),				
			
			
			
			


			
			
			
			

			'advertisement' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/advertisement[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Advertisement',
                        'action'     => 'index',
                    ),
                ),
            ),












			
			
			
			'suggestion' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/suggestion[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Suggestion',
                        'action'     => 'index',
                    ),
                ),
            ),			
						
			'kill-type' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/kill-type[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\KillType',
                        'action'     => 'index',
                    ),
                ),
            ),			

			'shorthand-target' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/shorthand-target[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\ShorthandTarget',
                        'action'     => 'index',
                    ),
                ),
            ),	

			'player-position' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/player-position[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\PlayerPosition',
                        'action'     => 'index',
                    ),
                ),
            ),
			
			
			'deer-activity' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/deer-activity[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\DeerActivity',
                        'action'     => 'index',
                    ),
                ),
            ),			
			
			'deer-size' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/deer-size[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\DeerSize',
                        'action'     => 'index',
                    ),
                ),
            ),				
			
			'deer-facing' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/deer-facing[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\DeerFacing',
                        'action'     => 'index',
                    ),
                ),
            ),			

			'deer-position' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/deer-position[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\DeerPosition',
                        'action'     => 'index',
                    ),
                ),
            ),			
		
			
			
			
			
			
			'player' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/player[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Player',
                        'action'     => 'index',
                    ),
                ),
            ),	

			
			'haircolor' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/haircolor[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Haircolor',
                        'action'     => 'index',
                    ),
                ),
            ),
			
			'face-color' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/face-color[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\FaceColor',
                        'action'     => 'index',
                    ),
                ),
            ),			
			
				'player-rank' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/player-rank[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\PlayerRank',
                        'action'     => 'index',
                    ),
                ),
            ),			
					
			
			
			
			
			
			
			
			
			
			
			
			
			
		'ad-report' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/ad-report[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\AdReport',
                        'action'     => 'index',
                    ),
                ),
            ),		
			
			'bank-report' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/bank-report[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\BankReport',
                        'action'     => 'index',
                    ),
                ),
            ),			
			
			
			
			
			
			
			'skincolor' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/skincolor[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Skincolor',
                        'action'     => 'index',
                    ),
                ),
            ),
			
			'light' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/light[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Light',
                        'action'     => 'index',
                    ),
                ),
            ),			
			
			
			
			
			
			'attributeset' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/attributeset[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Attributeset',
                        'action'     => 'index',
                    ),
                ),
            ),
			'attributefield' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/attributefield[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Attributefield',
                        'action'     => 'index',
                    ),
                ),
            ),
			
			'weapon' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/weapon[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Weapon',
                        'action'     => 'index',
                    ),
                ),
            ),
			'tournament' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/tournament[/:action][/:id][/:count]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
						'count'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Tournament',
                        'action'     => 'index',
                    ),
                ),
            ),
			
			'missions' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/missions[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
						
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Missions',
                        'action'     => 'index',
                    ),
                ),
            ),			
			
			
			
			
			
			
			
			
			
			
			
			
			
			'playerapi' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/api/playerapi[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\PlayerApi',
                        'action'     => 'index',
                    ),
                ),
            ),
        
			'bank' => array(
						'type'    => 'segment',
						'options' => array(
							'route'    => '/admin/bank[/:action][/:id]',
							'constraints' => array(
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id'     => '[0-9]+',
												),
							'defaults' => array(
											'controller' => 'Admin\Controller\Bank',
											'action'     => 'index',
											),
						),
			),
			
			
			
			'api-kill-type' => array(
					'type'    => 'segment',
					'options' => array(
								'route'    		=> '/admin/api-kill-type[/:id]',
								'constraints' 	=> array(								
															'id'     => '[0-9]+',
														),
					'defaults' 		=> array(
												'controller' 	=> 'Admin\Controller\ApiKillType',
					
											),
					),
			),	

			'api-advertisement' => array(
					'type'    => 'segment',
					'options' => array(
								'route'    		=> '/admin/api-advertisement[/:action][/:id]',
								'constraints' 	=> array(	
															'action' => '[a-zA-Z][a-zA-Z0-9_-]*',	
															'id'     => '[0-9]+',
														),
					'defaults' 		=> array(
												'controller' 	=> 'Admin\Controller\ApiAdvertisement',
					
											),
					),
			),	

			'api-player-level-status' => array(
					'type'    => 'segment',
					'options' => array(
								'route'    		=> '/admin/api-player-level-status[/:action][/:id]',
								'constraints' 	=> array(	
															'action' => '[a-zA-Z][a-zA-Z0-9_-]*',	
															'id'     => '[0-9]+',
														),
					'defaults' 		=> array(
												'controller' 	=> 'Admin\Controller\ApiPlayerLevelStatus',					
											),
					),
			),	

			
			'api-player-position' => array(
					'type'    => 'segment',
					'options' => array(
								'route'    		=> '/admin/api-player-position[/:id]',
								'constraints' 	=> array(								
															'id'     => '[0-9]+',
														),
					'defaults' 		=> array(
												'controller' 	=> 'Admin\Controller\ApiPlayerPosition',
					
											),
					),
			),				
			

			'api-short-target' => array(
					'type'    => 'segment',
					'options' => array(
								'route'    		=> '/admin/api-short-target[/:id]',
								'constraints' 	=> array(								
															'id'     => '[0-9]+',
														),
					'defaults' 		=> array(
												'controller' 	=> 'Admin\Controller\ApiShortTarget',
					
											),
					),
			),	





			
			
			
			
			
			'api-rest' => array(
					'type'    => 'segment',
					'options' => array(
								'route'    		=> '/admin/api-rest[/:id]',
								'constraints' 	=> array(								
												'id'     => '[0-9]+',
												),
					'defaults' 		=> array(
												'controller' 	=> 'Admin\Controller\ApiRest',
					
											),
					),
			),
			
			'api-player' => array(
					'type'    => 'segment',
					'options' => array(
								'route'    		=> '/admin/api-player[/:action][/:id]',
								'constraints' 	=> array(
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',								
												'id'     => '[0-9]+',
												),
					'defaults' 		=> array(
												'controller' 	=> 'Admin\Controller\ApiPlayer',
					
											),
					),
			),
			
			'api-region' => array(
					'type'    => 'segment',
					'options' => array(
								'route'    		=> '/admin/api-region[/:id]',
								'constraints' 	=> array(								
												'id'     => '[0-9]+',
												),
					'defaults' 		=> array(
												'controller' 	=> 'Admin\Controller\ApiRegion',
					
											),
					),
			),			
			
				'api-light' => array(
					'type'    => 'segment',
					'options' => array(
								'route'    		=> '/admin/api-light[/:id]',
								'constraints' 	=> array(								
												'id'     => '[0-9]+',
												),
					'defaults' 		=> array(
												'controller' 	=> 'Admin\Controller\ApiLight',
					
											),
					),
			),			
			
			'api-hunttype' => array(
					'type'    => 'segment',
					'options' => array(
								'route'    		=> '/admin/api-hunttype[/:id]',
								'constraints' 	=> array(								
												'id'     => '[0-9]+',
												),
					'defaults' 		=> array(
												'controller' 	=> 'Admin\Controller\ApiHunttype',
					
											),
					),
			),			
			
			
			'api-deerinfo' => array(
					'type'    => 'segment',
					'options' => array(
								'route'    		=> '/admin/api-deerinfo[/:id]',
								'constraints' 	=> array(								
												'id'     => '[0-9]+',
												),
					'defaults' 		=> array(
												'controller' 	=> 'Admin\Controller\ApiDeerinfo',
					
											),
					),
			),				
			
			
			'api-suggestion' => array(
					'type'    => 'segment',
					'options' => array(
								'route'    		=> '/admin/api-suggestion[/:id]',
								'constraints' 	=> array(								
												'id'     => '[0-9]+',
												),
					'defaults' 		=> array(
												'controller' 	=> 'Admin\Controller\ApiSuggestion',
					
											),
					),
			),				
			
			'api-tournament' => array(
					'type'    => 'segment',
					'options' => array(
								'route'    		=> '/admin/api-tournament[/:id]',
								'constraints' 	=> array(								
															'id'     => '[0-9]+',
														),
					'defaults' 		=> array(
												'controller' 	=> 'Admin\Controller\ApiTournament',
					
											),
					),
			),				
			
			
			'api-deersize' => array(
					'type'    => 'segment',
					'options' => array(
								'route'    		=> '/admin/api-deersize[/:id]',
								'constraints' 	=> array(								
															'id'     => '[0-9]+',
														),
					'defaults' 		=> array(
												'controller' 	=> 'Admin\Controller\ApiDeerSize',
					
											),
					),
			),				
						
			
			'api-deer-activity' => array(
					'type'    => 'segment',
					'options' => array(
								'route'    		=> '/admin/api-deer-activity[/:id]',
								'constraints' 	=> array(								
															'id'     => '[0-9]+',
														),
					'defaults' 		=> array(
												'controller' 	=> 'Admin\Controller\ApiDeerActivity',
					
											),
					),
			),			
			
			'api-deer-facing' => array(
					'type'    => 'segment',
					'options' => array(
								'route'    		=> '/admin/api-deer-facing[/:id]',
								'constraints' 	=> array(								
															'id'     => '[0-9]+',
														),
					'defaults' 		=> array(
												'controller' 	=> 'Admin\Controller\ApiDeerFacing',
					
											),
					),
			),				
			
			'api-deer-position' => array(
					'type'    => 'segment',
					'options' => array(
								'route'    		=> '/admin/api-deer-position[/:id]',
								'constraints' 	=> array(								
															'id'     => '[0-9]+',
														),
					'defaults' 		=> array(
												'controller' 	=> 'Admin\Controller\ApiDeerPosition',
					
											),
					),
			),				
			
			
			
			
			
			
			
			
			
			'region' => array(
						'type'    => 'segment',
						'options' => array(
							'route'    => '/admin/region[/:action][/:id]',
							'constraints' => array(
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id'     => '[0-9]+',
												),
							'defaults' => array(
											'controller' => 'Admin\Controller\Region',
											'action'     => 'index',
											),
						),
			),
			
			'clothing' => array(
						'type'    => 'segment',
						'options' => array(
							'route'    => '/admin/clothing[/:action][/:id]',
							'constraints' => array(
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id'     => '[0-9]+',
												),
							'defaults' => array(
											'controller' => 'Admin\Controller\Clothing',
											'action'     => 'index',
											),
						),
			),			
			
			'deerinfo' => array(
						'type'    => 'segment',
						'options' => array(
							'route'    => '/admin/deerinfo[/:action][/:id]',
							'constraints' => array(
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id'     => '[0-9]+',
												),
							'defaults' => array(
											'controller' => 'Admin\Controller\Deerinfo',
											'action'     => 'index',
											),
						),
			),
			
			
			/*
			
			'player' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/player[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9]*',
                    ),
                    'defaults' => array(
						'__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'player',
                        'action' => 'index',
                    ),
                ),
                
            ),
			
			*/
			
			
			
			
			
			
			
			
        ),
    ),	
	/*'view_manager' => array(
        'template_path_stack' => array(
            'SanAuth' => __DIR__ . '/../view',
        ),
    ),*/
	
	
	 'view_manager' => array(
		  'strategies' => array(
				'ViewJsonStrategy',
		),
	 
	 
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'Admin/layout'           => __DIR__ . '/../view/layout/layout.phtml',            
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
	
	'offers_thumb_img' => array('thumbWidth' => 200 , 'thumbHeight' => 300),
	
);