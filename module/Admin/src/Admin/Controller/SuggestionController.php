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



class SuggestionController extends AbstractActionController
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

		return new ViewModel( array(
		'results' => $this->getSuggestionTable()->fetchAll(),
		'flashMessages'  => $this->flashmessenger()->getMessages(),

		));			

	}
	


	
	public function addAction()	{
		$this->init();
		$form = new SuggestionForm();
        $form->get('submit')->setValue('Save & Continue');
		
		//$id = (int) $this->params()->fromRoute('id', 0);

		//if($id) {
		//$form->get('id')->setValue($id );
		//$bank = $this->getBankTable()->getBank($id);
		//}
		

        $request = $this->getRequest();
        if ($request->isPost()) {
	
				$suggestion = new Suggestion();
				$form->setInputFilter($suggestion->getInputFilter());
				$form->setData($request->getPost());

			   if ($form->isValid())
			   {							

					$name 		=  $request->getPost('name') ; 
					$active 	=  $request->getPost('active'); 
					
					$dataArr['name']						=	$name;	
					$dataArr['suggestion_available']		=	$request->getPost('suggestion_available');
					$dataArr['hitpower']					=	$request->getPost('hitpower');
					$dataArr['sight']						=	$request->getPost('sight');	
					$dataArr['nsight']						=	$request->getPost('nsight');	
					$dataArr['infra_red']					=	$request->getPost('infra_red');	
					$dataArr['thermal']						=	$request->getPost('thermal');
					$dataArr['stabilizer']					=	$request->getPost('stabilizer');	
					$dataArr['camouflage_dress']			=	$request->getPost('camouflage_dress');

					$dataArr['created_on'] 	=	time() ;
					$dataArr['active']		= 	$active ;		
					
					$suggestion->exchangeArray($dataArr);
					$this->getSuggestionTable()->saveSuggestion($suggestion);
					$this->flashMessenger()->addMessage('Successfully added this content.');					
					return $this->redirect()->toRoute('suggestion', array('action'=>'index'));
			
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
		 
		$suggestion = $this->getSuggestionTable()->getSuggestion($id);
	
		$form = new SuggestionForm();
		$form->bind($suggestion);
		//$form->get('submit')->setValue('Edit');
		$form->get('submit')->setAttribute('value', 'Update & Save');	
        $request = $this->getRequest();
		
        if ($request->isPost()) {	
           // $haircolor = new Haircolor();
            $form->setInputFilter($suggestion->getInputFilter());
            $form->setData($request->getPost());

			   if ($form->isValid()) {				

					$name 				=  $request->getPost('name') ; 
					$active 			=  $request->getPost('active'); 

					$dataArr['name']					=	$name;			
					$dataArr['active']					= 	$active ;	
					$dataArr['id']						= 	$request->getPost('id');	
					
					$dataArr['suggestion_available']		=	$request->getPost('suggestion_available');
					$dataArr['hitpower']					=	$request->getPost('hitpower');
					$dataArr['sight']						=	$request->getPost('sight');	
					$dataArr['nsight']						=	$request->getPost('nsight');	
					$dataArr['infra_red']					=	$request->getPost('infra_red');	
					$dataArr['thermal']						=	$request->getPost('thermal');
					$dataArr['stabilizer']					=	$request->getPost('stabilizer');	
					$dataArr['camouflage_dress']			=	$request->getPost('camouflage_dress');
					
					
					
					
					
					
					
					
					
					
					
					
					
					$suggestion->exchangeArray($dataArr);
					$this->getSuggestionTable()->saveSuggestion($suggestion);	
					$this->flashMessenger()->addMessage('Successfully updated this content.');	
					return $this->redirect()->toRoute('suggestion', array('action'=>'index'));			
			 }
		
        }

	
	 return array(
         'id' => $id,
         'form' => $form,
		 'light' => $suggestion,
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
		$this->getSuggestionTable()->deleteSuggestion($id);
		$this->flashMessenger()->addMessage('Successfully deleted this content.');			
		return $this->redirect()->toRoute('suggestion' , array('action' => 'index'));
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

