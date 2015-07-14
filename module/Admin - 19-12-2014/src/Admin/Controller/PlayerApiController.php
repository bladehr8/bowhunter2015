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


class PlayerApiController extends AbstractActionController
{
	
	protected $playerTable;
	
	public function getPlayerTable()
    {
        if (!$this->playerTable) {
            $sm = $this->getServiceLocator();
            $this->playerTable = $sm->get('Admin\Model\PlayerTable');
        }
        return $this->playerTable;
    }

	
	
	public function indexAction()
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
	
	public function addAction()
	{
			$request = $this->getRequest();	
			if ($request->isPost()) {			
            $player = new Player();
			$data=$_POST['data'];
			
			
			$dataArr=json_decode($data,true);
		
                $player->exchangeArray($dataArr);
				
                $this->getPlayerTable()->savePlayer($player);
		
				

        }
		exit();
	}
	
  
	
	
	
	

	
	

}

