<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\ShorthandTarget;          // <-- Add this import
use Admin\Form\ShorthandTargetForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;



class ShorthandTargetController extends AbstractActionController
{
	
	protected $shorthandtargetTable;
	
	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }
	
	public function getShorthandTargetTable()
    {
        if (!$this->shorthandtargetTable) {
            $sm = $this->getServiceLocator();
            $this->shorthandtargetTable = $sm->get('Admin\Model\ShorthandTargetTable');
        }
        return $this->shorthandtargetTable;
    }
	
	public function indexAction()
	{ 
		$this->init();			
		return new ViewModel( array('rows' => $this->getShorthandTargetTable()->fetchAll(), 'flashMessages'  => $this->flashmessenger()->getMessages(), ));			

	}
	
	
	
	

	
	public function addAction()	{
		$this->init();
		$form = new ShorthandTargetForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
	
				$shorthand = new ShorthandTarget();
				$form->setInputFilter($shorthand->getInputFilter());
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

					
					
					if( $this->getShorthandTargetTable()->isExistName($name) ) {	
					
						$this->flashMessenger()->addMessage("Duplicate entry into database !");
						return $this->redirect()->toRoute('shorthand-target', array('action'=>'add'));
					}
					else
					{
						
							$shorthand->exchangeArray($dataArr);
							$this->getShorthandTargetTable()->saveShorthandTarget($shorthand);			
							return $this->redirect()->toRoute('shorthand-target', array('action'=>'index'));

					}
			
			 }
		
        }
		
        return array('form' => $form, 'flashMessages'  => $this->flashmessenger()->getMessages());	
	
	}
	

	public function editAction() {
	
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			 return $this->redirect()->toRoute('shorthand-target', array('action'=>'add'));
		 }
		 
		$getData = $this->getShorthandTargetTable()->getShorthandTarget($id);	
		$form = new ShorthandTargetForm();
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
					$this->getShorthandTargetTable()->saveShorthandTarget($getData);								
					return $this->redirect()->toRoute('shorthand-target', array('action'=>'index'));			
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
		$id = (int) $id;
		$this->getShorthandTargetTable()->deleteShorthandTarget($id);
		return $this->redirect()->toRoute('shorthand-target' , array('action' => 'index'));
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

