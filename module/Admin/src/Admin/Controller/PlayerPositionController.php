<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\PlayerPosition;          // <-- Add this import
use Admin\Form\PlayerPositionForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;



class PlayerPositionController extends AbstractActionController
{
	
	protected $playerpositionTable;
	
	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }
	
	public function getPlayerPositionTable()
    {
        if (!$this->playerpositionTable) {
            $sm = $this->getServiceLocator();
            $this->playerpositionTable = $sm->get('Admin\Model\PlayerPositionTable');
        }
        return $this->playerpositionTable;
    }
	
	public function indexAction()
	{ 
		$this->init();	
		//echo '<pre>'; print_r($this->getRegionTable()->fetchAll()); exit;
		return new ViewModel( array('rows' => $this->getPlayerPositionTable()->fetchAll(), 'flashMessages'  => $this->flashmessenger()->getMessages(), ));			

	}
	
	
	
	

	
	public function addAction()	{
		$this->init();
		$form = new PlayerPositionForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
	
				$playerposition = new PlayerPosition();
				$form->setInputFilter($playerposition->getInputFilter());
				$form->setData($request->getPost());

			   if ($form->isValid())
			   {							

					$name 					=  $request->getPost('name') ; 
					$active 				=  $request->getPost('active'); 					
					$dataArr['name']		=	$name;				
					$dataArr['created_on'] 	=	time() ;
					$dataArr['active']		= 	$active ;	

					//$this->getKillTypeTable()->isExistName($name);
					//exit;

					
					
					if( $this->getPlayerPositionTable()->isExistName($name) ) {	
					
						$this->flashMessenger()->addMessage("Duplicate entry into database !");
						return $this->redirect()->toRoute('player-position', array('action'=>'add'));
					}
					else
					{
						
							$playerposition->exchangeArray($dataArr);
							$this->getPlayerPositionTable()->savePlayerPosition($playerposition);
							$this->flashMessenger()->addMessage('Successfully added this content.');									
							return $this->redirect()->toRoute('player-position', array('action'=>'index'));

					}
			
			 }
		
        }
		
        return array('form' => $form, 'flashMessages'  => $this->flashmessenger()->getMessages());	
	
	}
	

	public function editAction() {
	
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			 return $this->redirect()->toRoute('kill-type', array('action'=>'add'));
		 }
		 
		$getData = $this->getPlayerPositionTable()->getPlayerPosition($id);	
		$form = new PlayerPositionForm();
		$form->bind($getData);
		//$form->get('submit')->setValue('Edit');
		$form->get('submit')->setAttribute('value', 'Update & Save');	
        $request = $this->getRequest();
		
        if ($request->isPost()) {	
           // $haircolor = new Haircolor();
            $form->setInputFilter($getData->getInputFilter());
            $form->setData($request->getPost());

			   if ($form->isValid()) {				

					$name 				=  $request->getPost('name') ; 
					$active 			=  $request->getPost('active'); 

					$dataArr['name']					=	trim($name);			
					$dataArr['active']					= 	$active ;	
					$dataArr['id']						= 	$request->getPost('id');	
					
					$getData->exchangeArray($dataArr);	
					$this->getPlayerPositionTable()->savePlayerPosition($getData);
					$this->flashMessenger()->addMessage('Successfully updated this content.');					
					return $this->redirect()->toRoute('player-position', array('action'=>'index'));			
			 }
		
        }

	
	 return array(
         'id' => $id,
         'form' => $form,
		 'getData' => $getData,
     );
	

	
	}
	
	public function deleteAction()
	{
		$this->init();	
		if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
		
		
		$id = (int) $this->params()->fromRoute('id', 0);
		if($id) { 
		$this->getPlayerPositionTable()->deletePlayerPosition($id);
		$this->flashMessenger()->addMessage('Successfully deleted this content.');
		return $this->redirect()->toRoute('player-position' , array('action' => 'index'));
		}
		
		
	}
	
	
	public function detailsAction()
	{
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		return new ViewModel( array('bankoptions' => $this->getRegionTable()->fetchRowsById($id) ,)
		);
		
	}
	
	
	

	
	

}

