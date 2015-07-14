<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\DeerSize;          // <-- Add this import
use Admin\Form\DeerSizeForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;



class DeerSizeController extends AbstractActionController
{
	
	protected $deersizeTable;
	
	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }
	
	public function getDeerSizeTable()
    {
        if (!$this->deersizeTable) {
            $sm = $this->getServiceLocator();
            $this->deersizeTable = $sm->get('Admin\Model\DeerSizeTable');
        }
        return $this->deersizeTable;
    }
	
	public function indexAction()
	{ 
		$this->init();	
		//echo '<pre>'; print_r($this->getRegionTable()->fetchAll()); exit;
		return new ViewModel( array('rows' => $this->getDeerSizeTable()->fetchAll(), 'flashMessages'  => $this->flashmessenger()->getMessages(), ));			

	}
	
	
	
	

	
	public function addAction()	{
		$this->init();
		$form = new DeerSizeForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
	
				$deerSize = new DeerSize();
				$form->setInputFilter($deerSize->getInputFilter());
				$form->setData($request->getPost());

			   if ($form->isValid())
			   {							

					$name 					=  $request->getPost('name') ; 
					$active 				=  $request->getPost('active'); 					
					$dataArr['name']		=	trim($name);
					$dataArr['type']		=	$request->getPost('type');						
					$dataArr['created_on'] 	=	time() ;
					$dataArr['active']		= 	$active ;	

					//$this->getKillTypeTable()->isExistName($name);
					//exit;

					
					
					if( $this->getDeerSizeTable()->isExistName($name) ) {	
					
						$this->flashMessenger()->addMessage("Duplicate entry into database !");
						return $this->redirect()->toRoute('deer-size', array('action'=>'add'));
					}
					else
					{
						
							$deerSize->exchangeArray($dataArr);
							$this->getDeerSizeTable()->saveData($deerSize);			
							return $this->redirect()->toRoute('deer-size', array('action'=>'index'));

					}
			
			 }
		
        }
		
        return array('form' => $form, 'flashMessages'  => $this->flashmessenger()->getMessages());	
	
	}
	

	public function editAction() {
	
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			 return $this->redirect()->toRoute('deer-size', array('action'=>'add'));
		 }
		 
		$getData = $this->getDeerSizeTable()->getData($id);	
		$form = new DeerSizeForm();
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
					$dataArr['type']					=	$request->getPost('type');									
					$dataArr['active']					= 	$active ;	
					$dataArr['id']						= 	$request->getPost('id');	
					
					$getData->exchangeArray($dataArr);	
					$this->getDeerSizeTable()->saveData($getData);								
					return $this->redirect()->toRoute('deer-size', array('action'=>'index'));			
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
		$this->getDeerSizeTable()->deleteData($id);
		return $this->redirect()->toRoute('deer-size' , array('action' => 'index'));
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

