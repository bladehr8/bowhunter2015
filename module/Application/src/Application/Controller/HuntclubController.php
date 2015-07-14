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

use  Application\Form\HuntClubForm;
use  Application\Model\HuntClub;
use  Application\Model\HuntClubTable;

use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class HuntClubController extends AbstractActionController
{
    
		protected $huntclubTable;
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
			if (!$this->huntclubTable) {
				$sm = $this->getServiceLocator();
				$this->huntclubTable = $sm->get('Application\Model\HuntClubTable');
			}
			return $this->huntclubTable;
		}

	
		public function indexAction()
		{ 
			$this->init();
			return new ViewModel( array( 
			'rows' => $this->getTable()->fetchAll() , 
			'flashMessages'  => $this->flashmessenger()->getMessages() 
			) );		

		}
		
		public function addAction()
		{
			//$this->init();
			$form = new HuntClubForm();
			
			$request = $this->getRequest();
			if ($request->isPost())
			{
				$huntclub = new HuntClub();		       
				
				$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
				$huntclub->setDbAdapter($dbAdapter);
				$form->setInputFilter($huntclub->getInputFilter());
				
				$form->setData($request->getPost());
				
				if ($form->isValid())
				{
					$dataArr['name']				=	$request->getPost('name');
					$dataArr['player_id']			=	4; // Use it static - later it will be dynamic for sign-in user
					$dataArr['created_on']			=	time();
					$dataArr['modified_on']			=	time();
					$dataArr['active']				=	$request->getPost('active');
				
					$huntclub->exchangeArray($dataArr);	
					$return_id=$this->getTable()->saveData($huntclub);
					if($return_id) $this->flashMessenger()->addMessage('Hunt-Club has been created successfully .');		
					return $this->redirect()->toRoute('hunt-club', array('action'=>'index'));
				
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
			
			//$this->init();
			$row = $this->getTable()->getData($id);
			//echo '<pre>'; print_r($huntclub); exit;
			
			$form = new HuntClubForm();
			$form->bind($row);
			
			$request = $this->getRequest();
			if ($request->isPost())
			{
				//echo '<pre>'; print_r($request->getPost()); exit;
				$huntclub = new HuntClub();		       
				//$form->setData($request->getPost());
				//$form->setInputFilter($huntclub->getInputFilter());
				//$form->setData($request->getPost());
				$input = $form->getInputFilter();
				$e_filter = $input->get('name');
				$e_filter->setRequired(false);
				
				
	
				if ($form->isValid())
				{
					//echo '<pre>'; print_r($request->getPost()); exit;
					$dataArr['id']					=	$request->getPost('id');
					$dataArr['name']				=	$request->getPost('name');
					$dataArr['player_id']			=	4; // Use it static - later it will be dynamic for sign-in user
					$dataArr['created_on']			=	time();
					$dataArr['modified_on']			=	time();
					$dataArr['active']				=	$request->getPost('active');
					
				
					$huntclub->exchangeArray($dataArr);	
					$this->getTable()->saveData($huntclub);
					$this->flashMessenger()->addMessage('Hunt-Club has been updated successfully .');		
					return $this->redirect()->toRoute('hunt-club', array('action'=>'index'));
				
				}
				else
				{
					throw new \Exception("Error in form validation !!");
				}

			}

			return new ViewModel( array( 'id' => $id, 'form' => $form,  'getData' => $row, 'flashMessages'  => $this->flashmessenger()->getMessages() )  ) ; 			


		}
		
	public function deleteAction()
	{
		//$this->init();	

		
		$id = (int) $this->params()->fromRoute('id', 0);
		if($id) { 
		$this->getTable()->deleteClub($id);
		$this->flashMessenger()->addMessage('Hunt-Club has been deleted successfully .');		
		return $this->redirect()->toRoute('hunt-club' , array('action' => 'index'));
		}
	}
	
	
}
