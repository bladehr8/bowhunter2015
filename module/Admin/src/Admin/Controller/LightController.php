<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\Light;          // <-- Add this import
use Admin\Form\LightForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;



class LightController extends AbstractActionController
{
	
	protected $lightTable;
	
	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }
	
	public function getLightTable()
    {
        if (!$this->lightTable) {
            $sm = $this->getServiceLocator();
            $this->lightTable = $sm->get('Admin\Model\LightTable');
        }
        return $this->lightTable;
    }
	
	public function indexAction()
	{ 
		$this->init();	
		//echo '<pre>'; print_r($this->getRegionTable()->fetchAll()); exit;

		return new ViewModel( array(
		'results' => $this->getLightTable()->fetchAll(),
		'flashMessages'  => $this->flashmessenger()->getMessages(),

		));			

	}
	
	
	
	

	
	public function addAction()	{
		$this->init();
		$form = new LightForm();
        $form->get('submit')->setValue('Save & Continue');
		
		//$id = (int) $this->params()->fromRoute('id', 0);

		//if($id) {
		//$form->get('id')->setValue($id );
		//$bank = $this->getBankTable()->getBank($id);
		//}
		

        $request = $this->getRequest();
        if ($request->isPost()) {
	
				$light = new Light();
				$form->setInputFilter($light->getInputFilter());
				$form->setData($request->getPost());

			   if ($form->isValid())
			   {							

					$name 		=  $request->getPost('name') ; 
					$active 	=  $request->getPost('active'); 
					
					$dataArr['name']		=	$name;			
					$dataArr['created_on'] 	=	time() ;
					$dataArr['active']		= 	$active ;		
					
					$light->exchangeArray($dataArr);
					$this->getLightTable()->saveLight($light);
					$this->flashMessenger()->addMessage('Successfully added this content.');					
					return $this->redirect()->toRoute('light', array('action'=>'index'));
			
			 }
		
        }
		
        return array('form' => $form, 'flashMessages'  => $this->flashmessenger()->getMessages());	
	
	}
	

	public function editAction() {
	
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			 return $this->redirect()->toRoute('light', array('action'=>'add'));
		 }
		 
		$light = $this->getLightTable()->getLight($id);
		//echo '<pre>'; print_r($bank);	
		 //echo $bank->name;
		// exit;	
		$form = new LightForm();
		$form->bind($light);
		//$form->get('submit')->setValue('Edit');
		$form->get('submit')->setAttribute('value', 'Update & Save');	
        $request = $this->getRequest();
		
        if ($request->isPost()) {	
           // $haircolor = new Haircolor();
            $form->setInputFilter($light->getInputFilter());
            $form->setData($request->getPost());

			   if ($form->isValid()) {				

					$name 				=  $request->getPost('name') ; 
					$active 			=  $request->getPost('active'); 

					$dataArr['name']					=	$name;			
					$dataArr['active']					= 	$active ;	
					$dataArr['id']						= 	$request->getPost('id');	
					
					$light->exchangeArray($dataArr);
					$this->getLightTable()->updateLight($light);	
					$this->flashMessenger()->addMessage('Successfully updated this content.');	
					return $this->redirect()->toRoute('light', array('action'=>'index'));			
			 }
		
        }

	
	 return array(
         'id' => $id,
         'form' => $form,
		 'light' => $light,
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
		$this->getLightTable()->deleteLight($id);
		$this->flashMessenger()->addMessage('Successfully deleted this content.');			
		return $this->redirect()->toRoute('light' , array('action' => 'index'));
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

