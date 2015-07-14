<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\DeerFacing;          // <-- Add this import
use Admin\Form\DeerFacingForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;



class DeerFacingController extends AbstractActionController
{
	
	protected $deerfacingTable;
	
	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }
	
	public function getDeerFacingTable()
    {
        if (!$this->deerfacingTable) {
            $sm = $this->getServiceLocator();
            $this->deerfacingTable = $sm->get('Admin\Model\DeerFacingTable');
        }
        return $this->deerfacingTable;
    }
	
	public function indexAction()
	{ 
		$this->init();	
		//echo '<pre>'; print_r($this->getRegionTable()->fetchAll()); exit;
		return new ViewModel( array('rows' => $this->getDeerFacingTable()->fetchAll(), 'flashMessages'  => $this->flashmessenger()->getMessages(), ));			

	}
	
	
	
	

	
	public function addAction()	{
		$this->init();
		$form = new DeerFacingForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
	
				$deerFacing = new DeerFacing();
				$form->setInputFilter($deerFacing->getInputFilter());
				$form->setData($request->getPost());

			   if ($form->isValid())
			   {							

					$name 								=  $request->getPost('name') ; 									
					$dataArr['name']					=	trim($name);	
					$dataArr['exact_angle_range']		=	$request->getPost('exact_angle_range');						
					$dataArr['created_on'] 				=	time() ;
					$dataArr['active']					= 	$request->getPost('active'); 	

					if( $this->getDeerFacingTable()->isExistName($name) ) {	
					
						$this->flashMessenger()->addMessage("Duplicate entry into database !");
						return $this->redirect()->toRoute('deer-facing', array('action'=>'add'));
					}
					else
					{
						
						$deerFacing->exchangeArray($dataArr);
						$this->getDeerFacingTable()->saveData($deerFacing);
						$this->flashMessenger()->addMessage("Successfully added this content.");							
						return $this->redirect()->toRoute('deer-facing', array('action'=>'index'));

					}
			
			 }
		
        }
		
        return array('form' => $form, 'flashMessages'  => $this->flashmessenger()->getMessages());	
	
	}
	

	public function editAction() {
	
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			 return $this->redirect()->toRoute('deer-facing', array('action'=>'add'));
		 }
		 
		$getData = $this->getDeerFacingTable()->getData($id);	
		$form = new DeerFacingForm();
		$form->bind($getData);
		//$form->get('submit')->setValue('Edit');
		$form->get('submit')->setAttribute('value', 'Update & Save');	
        $request = $this->getRequest();
		
        if ($request->isPost()) {	
           
            $form->setInputFilter($getData->getInputFilter());
            $form->setData($request->getPost());

			   if ($form->isValid()) {			

					$dataArr['name']					=	$request->getPost('name');
					$dataArr['exact_angle_range']		=	$request->getPost('exact_angle_range');						
					$dataArr['active']					= 	$request->getPost('active');
					$dataArr['id']						= 	$request->getPost('id');	
					
					$getData->exchangeArray($dataArr);	
					$this->getDeerFacingTable()->saveData($getData);
					$this->flashMessenger()->addMessage("Successfully updated this content.");						
					return $this->redirect()->toRoute('deer-facing', array('action'=>'index'));			
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
		$this->getDeerFacingTable()->deleteData($id);
		$this->flashMessenger()->addMessage("Successfully deleted this ID - $id");
		return $this->redirect()->toRoute('deer-facing' , array('action' => 'index'));
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

