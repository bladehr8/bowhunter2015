<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\DeerPosition;          // <-- Add this import
use Admin\Form\DeerPositionForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;



class DeerPositionController extends AbstractActionController
{
	
	protected $deerpositionTable;
	
	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }
	
	public function getTable()
    {
        if (!$this->deerpositionTable) {
            $sm = $this->getServiceLocator();
            $this->deerpositionTable = $sm->get('Admin\Model\DeerPositionTable');
        }
        return $this->deerpositionTable;
    }
	
	public function indexAction()
	{ 
		$this->init();	
		//echo '<pre>'; print_r($this->getRegionTable()->fetchAll()); exit;
		return new ViewModel( array('rows' => $this->getTable()->fetchAll(), 'flashMessages'  => $this->flashmessenger()->getMessages(), ));			

	}
	
	
	
	

	
	public function addAction()	{
		$this->init();
		$form = new DeerPositionForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
	
				$deerPosition = new DeerPosition();
				$form->setInputFilter($deerPosition->getInputFilter());
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

					
					
					if( $this->getTable()->isExistName($name) ) {	
					
						$this->flashMessenger()->addMessage("Duplicate entry into database !");
						return $this->redirect()->toRoute('deer-position', array('action'=>'add'));
					}
					else
					{
						
							$deerPosition->exchangeArray($dataArr);
							$this->getTable()->saveData($deerPosition);	
							$this->flashMessenger()->addMessage("Successfully added this content.");								
							return $this->redirect()->toRoute('deer-position', array('action'=>'index'));

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
		 
		$getData = $this->getTable()->getData($id);	
		$form = new DeerPositionForm();
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
					$this->getTable()->saveData($getData);
					$this->flashMessenger()->addMessage("Successfully updated this content.");					
					return $this->redirect()->toRoute('deer-position', array('action'=>'index'));			
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
		$this->getTable()->deleteData($id);
		$this->flashMessenger()->addMessage("Successfully deleted this ID  - $id");
		return $this->redirect()->toRoute('deer-position' , array('action' => 'index'));
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

