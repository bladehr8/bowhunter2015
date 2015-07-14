<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\FaceColor;          // <-- Add this import
use Admin\Form\FaceColorForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;



class FaceColorController extends AbstractActionController
{
	
	protected $facecolorTable;
	
	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }
	
	public function getFaceColorTable()
    {
        if (!$this->facecolorTable) {
            $sm = $this->getServiceLocator();
            $this->facecolorTable = $sm->get('Admin\Model\FaceColorTable');
        }
        return $this->facecolorTable;
    }
	
	public function indexAction()	{ 
	$this->init();	
	 return new ViewModel(array(
			'rows' => $this->getFaceColorTable()->fetchAll(),
			'flashMessages'  => $this->flashmessenger()->getMessages(),
		));

	}
	
	
	
	

	
	public function addAction()	{
		$this->init();
		$form = new FaceColorForm();
        //$form->get('submit')->setValue('Save');

        $request = $this->getRequest();
        if ($request->isPost()) {
		
			$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');

            $faceColor = new FaceColor();
			$faceColor->setDbAdapter($dbAdapter);
            $form->setInputFilter($faceColor->getInputFilter());
            $form->setData($request->getPost());

           if ($form->isValid()) {							

				$name 		=  $request->getPost('name') ; 
				$active 	=  $request->getPost('active'); 
				
				$dataArr['name']=$name;
				$dataArr['active']=1;
				
				
				$faceColor->exchangeArray($dataArr);
                $this->getFaceColorTable()->saveData($faceColor);
				$this->flashMessenger()->addMessage('Successfully added.');
			     return $this->redirect()->toRoute('face-color', array('action'=>'index'));
		
         }
		
        }
		
        return array('form' => $form,'messages'  => $this->flashMessenger()->getMessages());	
	
	}
	

	public function editAction() {
	
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			 return $this->redirect()->toRoute('face-color', array('action'=>'add'));
		 }
		 
		$faceColor = $this->getFaceColorTable()->getData($id);	
		 
		$form = new FaceColorForm();
		$form->bind($faceColor);
		//$form->get('submit')->setValue('Edit');
		//$form->get('submit')->setAttribute('value', 'Edit');
		$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		//$dbAdapter = null;
		$faceColor->setDbAdapter($dbAdapter);
		
        $request = $this->getRequest();
        if ($request->isPost()) {	
            $face_color = new FaceColor();
			$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
			//$dbAdapter = null;
			$face_color->setDbAdapter($dbAdapter);
			
			
            $form->setInputFilter($face_color->getInputFilter());
            $form->setData($request->getPost());

			   if ($form->isValid()) {				
					$name 		=  $request->getPost('name') ; 
					$active 	=  $request->getPost('active'); 				
					$dataArr['name']=$name;
					$dataArr['active']= $active;
					$dataArr['id']= $request->getPost('id');	
					
					$face_color->exchangeArray($dataArr);
					$this->getFaceColorTable()->saveData($face_color);	
					$this->flashMessenger()->addMessage('Successfully updated.');
					return $this->redirect()->toRoute('face-color', array('action'=>'index'));			
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
		$this->getFaceColorTable()->deleteData($id);
		$this->flashMessenger()->addMessage('Successfully deleted this data.');
		return $this->redirect()->toRoute('face-color' , array('action' => 'index'));
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

