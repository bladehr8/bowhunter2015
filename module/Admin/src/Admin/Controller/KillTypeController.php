<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\KillType;          // <-- Add this import
use Admin\Form\KillTypeForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;



class KillTypeController extends AbstractActionController
{
	
	protected $killtypeTable;
	
	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }
	
	public function getKillTypeTable()
    {
        if (!$this->killtypeTable) {
            $sm = $this->getServiceLocator();
            $this->killtypeTable = $sm->get('Admin\Model\KillTypeTable');
        }
        return $this->killtypeTable;
    }
	
	public function indexAction()
	{ 
		$this->init();	
		//echo '<pre>'; print_r($this->getRegionTable()->fetchAll()); exit;
		return new ViewModel( array('rows' => $this->getKillTypeTable()->fetchAll(), 'flashMessages'  => $this->flashmessenger()->getMessages(), ));			

	}
	
	
	
	

	
	public function addAction()	{
		$this->init();
		$form = new KillTypeForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
	
				$region = new KillType();
				$form->setInputFilter($region->getInputFilter());
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

					
					
					if( $this->getKillTypeTable()->isExistName($name) ) {	
					
						$this->flashMessenger()->addMessage("Duplicate entry into database !");
						return $this->redirect()->toRoute('kill-type', array('action'=>'add'));
					}
					else
					{
						
							$region->exchangeArray($dataArr);
							$this->getKillTypeTable()->saveKillType($region);			
							return $this->redirect()->toRoute('kill-type', array('action'=>'index'));

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
		 
		$getData = $this->getKillTypeTable()->getKillType($id);	
		$form = new KillTypeForm();
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
					$this->getKillTypeTable()->saveKillType($getData);								
					return $this->redirect()->toRoute('kill-type', array('action'=>'index'));			
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
		$this->getKillTypeTable()->deleteKillType($id);
		return $this->redirect()->toRoute('kill-type' , array('action' => 'index'));
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

