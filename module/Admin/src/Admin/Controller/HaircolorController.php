<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\Haircolor;          // <-- Add this import
use Admin\Form\HaircolorForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;



class HaircolorController extends AbstractActionController
{
	
	protected $haircolorTable;
	
	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }
	
	public function getHaircolorTable()
    {
        if (!$this->haircolorTable) {
            $sm = $this->getServiceLocator();
            $this->haircolorTable = $sm->get('Admin\Model\HaircolorTable');
        }
        return $this->haircolorTable;
    }
	
	public function indexAction()	{ 
	$this->init();	
	 return new ViewModel(array(
			'haircolors' => $this->getHaircolorTable()->fetchAll(),
		));

	}
	
	
	
	

	
	public function addAction()	{
		$this->init();
		$form = new HaircolorForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
	
            $haircolor = new Haircolor();
            $form->setInputFilter($haircolor->getInputFilter());
            $form->setData($request->getPost());

           if ($form->isValid()) {							

				$name 	=  $request->getPost('name') ; 
				$active 	=  $request->getPost('active'); 
				
				$dataArr['name']=$name;
				$dataArr['active']=1;
				
				
				$haircolor->exchangeArray($dataArr);
                $this->getHaircolorTable()->saveHaircolor($haircolor);
			
			     return $this->redirect()->toRoute('haircolor', array('action'=>'index'));
		
         }
		
        }
		
        return array('form' => $form,'messages'  => $this->flashmessenger()->getMessages());	
	
	}
	

	public function editAction() {
	
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			 return $this->redirect()->toRoute('haircolor', array('action'=>'add'));
		 }
		 
		$haircolor = $this->getHairColorTable()->getHaircolor($id);	
		 
		$form = new HaircolorForm();
		$form->bind($haircolor);
		//$form->get('submit')->setValue('Edit');
		$form->get('submit')->setAttribute('value', 'Edit');
	
        $request = $this->getRequest();
        if ($request->isPost()) {	
           // $haircolor = new Haircolor();
            $form->setInputFilter($haircolor->getInputFilter());
            $form->setData($request->getPost());

			   if ($form->isValid()) {				
					$name 		=  $request->getPost('name') ; 
					$active 	=  $request->getPost('active'); 				
					$dataArr['name']=$name;
					$dataArr['active']= $active;
					$dataArr['id']= $request->getPost('id');	
					
					$haircolor->exchangeArray($dataArr);
					$this->getHaircolorTable()->updateHaircolor($haircolor);	
					
					return $this->redirect()->toRoute('haircolor', array('action'=>'index'));			
			 }
		
        }

	
	 return array(
         'id' => $id,
         'form' => $form,
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
		$this->getHaircolorTable()->deleteHaircolor($id);
		return $this->redirect()->toRoute('haircolor' , array('action' => 'index'));
		}
	}
	
	
	public function detailsAction()
	{
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		return new ViewModel(array('haircolorDetails' => $this->getHaircolorTable()->getHaircolor($id) ,)
		);
		
	}
	
	
	

	
	

}

