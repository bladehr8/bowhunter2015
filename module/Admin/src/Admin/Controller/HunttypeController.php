<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\Hunttype;          // <-- Add this import
use Admin\Form\HunttypeForm;       // <-- Add this import

use Admin\Model\Suggestion;          // <-- Add this import
use Admin\Form\SuggestionForm;       // <-- Add this import



use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;



class HunttypeController extends AbstractActionController
{
	
	protected $hunttypeTable;
	protected $suggestionTable;
	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }
	
	public function getHunttypeTable()
    {
        if (!$this->hunttypeTable) {
            $sm = $this->getServiceLocator();
            $this->hunttypeTable = $sm->get('Admin\Model\HunttypeTable');
        }
        return $this->hunttypeTable;
    }
	
	public function getSuggestionTable()
    {
        if (!$this->suggestionTable) {
            $sm = $this->getServiceLocator();
            $this->suggestionTable = $sm->get('Admin\Model\SuggestionTable');
        }
        return $this->suggestionTable;
    }	
	
	
	
	
	
	
	public function indexAction()
	{ 
		$this->init();
		//echo '<pre>';
		//var_dump($this->getHunttypeTable()->fetchAll()); exit;
		return new ViewModel( array(
		'results' => $this->getHunttypeTable()->fetchAll(),
		'flashMessages'  => $this->flashmessenger()->getMessages(),

		));			

	}
	

	
	public function addAction()	{
		$this->init();
		$form = new HunttypeForm();
        $form->get('submit')->setValue('Save & Continue');

        $request = $this->getRequest();
        if ($request->isPost()) {
	
				$hunttype = new Hunttype();
				$form->setInputFilter($hunttype->getInputFilter());
				$form->setData($request->getPost());

			   if ($form->isValid())
			   {							

					$name 		=  $request->getPost('name') ; 
					$active 	=  $request->getPost('active'); 
					
					$dataArr['name']		=	$name;	
					$dataArr['hunt_class_id']		=	$request->getPost('hunt_class_id') ; 						
					//$dataArr['created_on'] 	=	time() ;
					$dataArr['active']		= 	$active ;		
					
					$hunttype->exchangeArray($dataArr);
					$this->getHunttypeTable()->saveData($hunttype);
					$this->flashMessenger()->addMessage('Successfully added this content.');					
					return $this->redirect()->toRoute('hunttype', array('action'=>'index'));
			
			 }
		
        }
		
        return array('form' => $form, 'flashMessages'  => $this->flashmessenger()->getMessages());	
	
	}
	

	public function editAction() {
	
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			 return $this->redirect()->toRoute('hunttype', array('action'=>'add'));
		 }
		 
		$hunttype = $this->getHunttypeTable()->getHunttype($id);

		$form = new HunttypeForm();
		$form->bind($hunttype);		
		$form->get('submit')->setAttribute('value', 'Update & Save');	
        $request = $this->getRequest();
		
        if ($request->isPost()) {	
           // $haircolor = new Haircolor();
            $form->setInputFilter($hunttype->getInputFilter());
            $form->setData($request->getPost());

			   if ($form->isValid()) {				

					$name 				=  $request->getPost('name') ; 
					$active 			=  $request->getPost('active'); 
					$dataArr['name']					=	$name;
					$dataArr['hunt_class_id']					=	$request->getPost('hunt_class_id') ; 						
					$dataArr['active']					= 	$active ;	
					$dataArr['id']						= 	$request->getPost('id');	
					
					$hunttype->exchangeArray($dataArr);
					$this->getHunttypeTable()->saveData($hunttype);	
					$this->flashMessenger()->addMessage('Successfully updated this content.');	
					return $this->redirect()->toRoute('hunttype', array('action'=>'index'));			
			 }
		
        }

	
	 return array(
         'id' => $id,
         'form' => $form,
		 'light' => $hunttype,
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
		$this->getHunttypeTable()->deleteData($id);
		$this->flashMessenger()->addMessage('Successfully deleted this content.');			
		return $this->redirect()->toRoute('hunttype' , array('action' => 'index'));
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

