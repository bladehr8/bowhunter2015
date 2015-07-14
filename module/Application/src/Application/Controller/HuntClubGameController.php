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
use  Application\Form\HuntClubGameForm;
use  Application\Model\HuntClubGame;

use Zend\View\Model\ViewModel;
use Zend\Session\Container;



class HuntClubGameController extends AbstractActionController
{
    
		protected $huntClubGameTable;
		public function init()
		{
				# For manually check for logged-in user
				$fb_session = new Container('fbloginbase');		
				$uId = $fb_session->offsetGet('uid'); 
				if(!$uId)
				return $this->redirect()->toRoute('application', array('controller' => 'index', 'action'=>'index'));
		}

		public function getTable()
		{
			if (!$this->huntClubGameTable) {
				$sm = $this->getServiceLocator();
				$this->huntClubGameTable = $sm->get('Application\Model\HuntClubGameTable');
			}
			return $this->huntClubGameTable;
		}
		
		
		
		
		
		

	
		public function indexAction()
		{ 
			$this->init();
			return new ViewModel( array( 
			'rows' => $this->getTable()->fetchAll() , 
			 'clubs' => $this->getTable()->huntClubsLists() ,
			 'lights' => $this->getTable()->lightConditionsLists() ,
			 'regions' => $this->getTable()->regionLists(),
			'flashMessages'  => $this->flashmessenger()->getMessages() 
			) );		

		}
		
		public function addAction()
		{
			//$this->init();
			$form = new HuntClubGameForm();
			$huntClubs 				= $this->getTable()->huntClubsLists();
			$lightConditions 		= $this->getTable()->lightConditionsLists();
			$regions 				= $this->getTable()->regionLists();
			
			
			$form->get('hunt_clubs_id')->setValueOptions($huntClubs);
			$form->get('light_conditions_id')->setValueOptions($lightConditions);
			$form->get('regions_id')->setValueOptions($regions);


			$request = $this->getRequest();
			if ($request->isPost())
			{
				$huntclub = new HuntClubGame();		       
				$form->setData($request->getPost());

				if ($form->isValid())
				{
					$dataArr['hunt_clubs_id']				=	$request->getPost('hunt_clubs_id');
					$dataArr['light_conditions_id']			=	$request->getPost('light_conditions_id');
					$dataArr['regions_id'] 					= 	$request->getPost('regions_id');
					$dataArr['number_of_animals'] 			= 	$request->getPost('number_of_animals');
					$dataArr['time_to_complete']			=	$request->getPost('time_to_complete');					
					$dataArr['created_on']					=	time();					
					$dataArr['active']						=	$request->getPost('active');
				
					$huntclub->exchangeArray($dataArr);	
					$return_id=$this->getTable()->saveData($huntclub);
					if($return_id) $this->flashMessenger()->addMessage('Hunt-Club has been created successfully .');		
					return $this->redirect()->toRoute('hunt-club-game', array('action'=>'index'));
				
				}

			}

			return new ViewModel( array('form' => $form,  'flashMessages'  => $this->flashmessenger()->getMessages() )  ) ; 
		}
		
		
		public function editAction()
		{
			//$this->init();
		
			$id = (int) $this->params()->fromRoute('id', 0);
			if (!$id)
			{
					throw new \Exception("Could not find row $id");
					//return $this->redirect()->toRoute('bank', array('action'=>'add'));
			}
			

			$row = $this->getTable()->getData($id);
			

			$form = new HuntClubGameForm();
			$form->bind($row);
			$huntClubs 				= $this->getTable()->huntClubsLists();
			$lightConditions 		= $this->getTable()->lightConditionsLists();
			$regions 				= $this->getTable()->regionLists();
			
			
			$form->get('hunt_clubs_id')->setValueOptions($huntClubs);
			$form->get('light_conditions_id')->setValueOptions($lightConditions);
			$form->get('regions_id')->setValueOptions($regions);


			$request = $this->getRequest();
			if ($request->isPost())
			{
				$huntClubGame = new HuntClubGame();
				$form->setInputFilter($row->getInputFilter());
				$form->setData($request->getPost());

				
				//$form->setData($request->getPost());

				if ($form->isValid())
				{
					$dataArr['id']							=	$request->getPost('id');
					$dataArr['hunt_clubs_id']				=	$request->getPost('hunt_clubs_id');
					$dataArr['light_conditions_id']			=	$request->getPost('light_conditions_id');
					$dataArr['regions_id'] 					= 	$request->getPost('regions_id');
					$dataArr['number_of_animals'] 			= 	$request->getPost('number_of_animals');
					$dataArr['time_to_complete']			=	$request->getPost('time_to_complete');					
					$dataArr['created_on']					=	time();					
					$dataArr['active']						=	$request->getPost('active');
				
					$huntClubGame->exchangeArray($dataArr);	
					$this->getTable()->saveData($row);
					$this->flashMessenger()->addMessage('Hunt-Club-Game has been updated successfully .');		
					return $this->redirect()->toRoute('hunt-club-game', array('action'=>'index'));
				
				}
				else
				{
					throw new \Exception("Error in form validation !!");
				}				

			}

			return new ViewModel( array( 'id' => $id, 'form' => $form,  'flashMessages'  => $this->flashmessenger()->getMessages() )  ) ; 
		}		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	
	
}
