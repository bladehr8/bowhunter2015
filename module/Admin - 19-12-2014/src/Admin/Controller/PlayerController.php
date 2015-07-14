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


class PlayerController extends AbstractActionController
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

	
	
	public function apiindexAction()
    { 
	
	print_r($_POST);
	$results = $this->getPlayerTable()->fetchAll();
        $data = array();
        foreach($results as $result) {
            $data[] = $result;
        }

       /* return new JsonModel(array(
            'data' => $data,
        )); */
	
	//$data=$this->getPlayerTable()->fetchAll();
	
	//echo "<pre>";
	//print_r($data);
	echo json_encode($data);
	exit();
	
		//$table = $this->getServiceLocator()->get('Admin\Model\PlayerTable');
			
		/* return new ViewModel(array(
				'players' => $this->getPlayerTable()->fetchAll(),
			)); */
		
	}
	
	public function addAction()
	{
				//$album->exchangeArray($form->getData());
                //$this->getAlbumTable()->saveAlbum($album);
				
			$request = $this->getRequest();	
			if ($request->isPost()) {
            $player = new Player();
			$data=$_POST['data'];
			
			
			$dataArr=json_decode($data,true);
			
			
			



            //$form->setInputFilter($player->getInputFilter());
            //$form->setData($request->getPost());

            //if ($form->isValid()) {

                $player->exchangeArray($dataArr);
                $this->getPlayerTable()->savePlayer($player);
				
				exit();

                // Redirect to list of albums
                //return $this->redirect()->toRoute('album');
           // }
        }
	
	}
	
    public function indexAction()
    { 
		if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
		//$table = $this->getServiceLocator()->get('Admin\Model\PlayerTable');
			
		 return new ViewModel(array(
				'players' => $this->getPlayerTable()->fetchAll(),
			));
		
	}
	
	public function deleteAction()
	{
		if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
		$id = (int) $this->params()->fromRoute('id', 0);
		echo $id . '#I am here !';
		exit;
		//$this->getPlayerTable()->deletePlayer($id);
	}
	
	
	public function detailsAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		echo $id . '#I am here !';
		exit;
	}
	
	
	

	
	

}

