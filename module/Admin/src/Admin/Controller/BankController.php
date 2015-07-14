<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\Bank;          // <-- Add this import
use Admin\Form\BankForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;



class BankController extends AbstractActionController
{
	
	protected $bankTable;
	
	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }
	
	public function getBankTable()
    {
        if (!$this->bankTable) {
            $sm = $this->getServiceLocator();
            $this->bankTable = $sm->get('Admin\Model\BankTable');
        }
        return $this->bankTable;
    }
	
	public function indexAction()
	{ 
		$this->init();	
		//echo '<pre>'; print_r($this->getBankTable()->fetchAll()); exit;
		return new ViewModel( array(
		'bank' => $this->getBankTable()->fetchAll(), 
		'flashMessages'  => $this->flashmessenger()->getMessages(),
		
		));			

	}
	
	
	
	

	
	public function addAction()	{
		$this->init();
		$form = new BankForm();
        $form->get('submit')->setValue('Add');
		
		//$id = (int) $this->params()->fromRoute('id', 0);

		//if($id) {
		//$form->get('id')->setValue($id );
		//$bank = $this->getBankTable()->getBank($id);
		//}
		

        $request = $this->getRequest();
        if ($request->isPost()) {
	
				$bank = new Bank();
				$form->setInputFilter($bank->getInputFilter());
				$form->setData($request->getPost());

			   if ($form->isValid())
			   {							

					$name 		=  $request->getPost('name') ; 
					$price 		=  $request->getPost('price') ;
					$active 	=  $request->getPost('active'); 
					
					$dataArr['name']		=	$name;
					$dataArr['bucks']		=	$request->getPost('bucks') ;
					$dataArr['price']		=	$price;
					$dataArr['gold_coins'] 	=  	$request->getPost('gold_coins') ;
					$dataArr['created_on'] 	=  	date("Y-m-d") ;
					$dataArr['active']		= 	$request->getPost('active') ;		
					
					$bank->exchangeArray($dataArr);
					$this->getBankTable()->saveBank($bank);	
					$this->flashMessenger()->addMessage('Your submission has been added successfully !');						
					 return $this->redirect()->toRoute('bank', array('action'=>'index'));
			
			 }
		
        }
		
        return array('form' => $form,'bank'=>$form, 'messages'  => $this->flashmessenger()->getMessages());	
	
	}
	

	public function editAction() {
	
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			 return $this->redirect()->toRoute('bank', array('action'=>'add'));
		 }
		 
		$bank = $this->getBankTable()->getBank($id);
		//echo '<pre>'; print_r($bank);	
		 //echo $bank->name;
		// exit;	
		$form = new BankForm();
		$form->bind($bank);
		//$form->get('submit')->setValue('Edit');
		$form->get('submit')->setAttribute('value', 'Save');	
        $request = $this->getRequest();
		
        if ($request->isPost()) {	
           // $haircolor = new Haircolor();
            $form->setInputFilter($bank->getInputFilter());
            $form->setData($request->getPost());

			   if ($form->isValid()) {				
					$name 		=  $request->getPost('name') ; 
					$price 		=  $request->getPost('price') ; 					
					$active 	=  $request->getPost('active'); 	
					
					$dataArr['name']		=	$name;
					$dataArr['active']		= 	$active;
					$dataArr['bucks']		=	$request->getPost('bucks');
					$dataArr['gold_coins']	=	$request->getPost(gold_coins);
					$dataArr['price']		=	$price;
					$dataArr['id']			= 	$request->getPost('id');	
					
					$bank->exchangeArray($dataArr);
					$this->getBankTable()->updateBank($bank);						
					return $this->redirect()->toRoute('bank', array('action'=>'index'));			
			 }
		
        }

	
	 return array(
         'id' => $id,
         'form' => $form,
		 'bank' => $bank,
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
		$this->getBankTable()->deleteBank($id);
		return $this->redirect()->toRoute('bank' , array('action' => 'index'));
		}
	}
	
	
	public function detailsAction()
	{
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		return new ViewModel( array('bankoptions' => $this->getBankTable()->fetchRowsById($id) ,)
		);
		
	}
	
	
	

	
	

}

