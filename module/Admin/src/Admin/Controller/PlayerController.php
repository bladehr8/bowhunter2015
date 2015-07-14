<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\Admin;          // <-- Add this import
use Admin\Form\AdminForm;       // <-- Add this import
use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\View\Model\JsonModel;
use Admin\Model\Player;
use Zend\Paginator\Paginator;  


class PlayerController extends AbstractActionController
{
	
	protected $playerTable;
	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }
	
	public function getPlayerTable()
    {
        if (!$this->playerTable) {
            $sm = $this->getServiceLocator();
            $this->playerTable = $sm->get('Admin\Model\PlayerTable');
        }
        return $this->playerTable;
    }

	
	///// Player list from API call 
	public function apiindexAction()
    { 	
	//print_r($_POST);
	$results = $this->getPlayerTable()->fetchAll();
        $data = array();
        foreach($results as $result) {
            $data[] = $result;
        }

		echo json_encode($data);
		exit();
	
	}
	
	
	///// Player add from API call 
	public function addAction()
	{
				
			$request = $this->getRequest();	
			if ($request->isPost()) {
            $player = new Player();
			$data=$_POST['data'];			
			
			$dataArr=json_decode($data,true);
		
			$player->exchangeArray($dataArr);
			$this->getPlayerTable()->savePlayer($player);
				
				exit();

        }
	
	}
	
    public function indexAction()
    { 
		$this->init();
		
		$paginator = $this->getPlayerTable()->fetchAll(true);
		 // set the current page to what has been passed in query string, or to 1 if none set
		$paginator->setCurrentPageNumber((int)$this->params()->fromQuery('page', 1));
		// set the number of items per page to 10
		$paginator->setItemCountPerPage(4);
		
			
		 return new ViewModel(array(
				'paginator' => $paginator,
			));
		
	}
	
	public function deleteAction()
	{
		$this->init();
		
		$id = (int) $this->params()->fromRoute('id', 0);
		echo $id . '#I am here !';
		exit;
		//$this->getPlayerTable()->deletePlayer($id);
	}
	
	
	public function detailsAction()
	{
		$this->init();
		
		$id = (int) $this->params()->fromRoute('id', 0);
		return new ViewModel(     array(
								'playerDetails' => $this->getPlayerTable()->getPlayer($id) ,
								)
		);
	
		
	}
	

}

