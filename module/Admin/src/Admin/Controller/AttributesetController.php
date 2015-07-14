<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\Attributeset;          // <-- Add this import
use Admin\Form\AttributesetForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\View\Model\JsonModel;



class AttributesetController extends AbstractActionController
{
	
	protected $attributesetTable;
	public $setStatus;
	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }
	
	public function getAttributesetTable()
    {
        if (!$this->attributesetTable) {
            $sm = $this->getServiceLocator();
            $this->attributesetTable = $sm->get('Admin\Model\AttributesetTable');
        }
        return $this->attributesetTable;
    }
	
	public function processajaxactiveAction(){
	
	    
	    $result = array('status' => 'error', 'message' => 'There was some error. Try again.');
	    
	    $request = $this->getRequest();
	    
	    if($request->isXmlHttpRequest()){
	    	
	        $data = $request->getPost();
	        
	        if(isset($data['UID']) && !empty($data['UID'])){
			
				if($data['action'] == 'active') 	$this->setStatus = 1;
				if($data['action'] == 'deactive') 	$this->setStatus = 0;
				$myData = array(					
					'active' => $this->setStatus
			
				);
				$id = $data['UID'];
				$this->getAttributesetTable()->updateAttributeset($myData , $id);
	        	$result['status'] = 'success';
				//$result['status'] = 'success';
	        	$result['message'] = 'We got the posted data successfully.';
				
				
	        }
	    }
	    
	    //return new JsonModel($result);
		echo json_encode($result);
		exit();
		
	}

	
	public function addAction()
	{
		$this->init();
		$form = new AttributesetForm();
        $form->get('submit')->setValue('Save & Continue');

        $request = $this->getRequest();
        if ($request->isPost()) {
	
            $attributeset = new Attributeset();
            $form->setInputFilter($attributeset->getInputFilter());
            $form->setData($request->getPost());

           if ($form->isValid()) {							

				$name 				=  $request->getPost('name') ; 
				$active 			=  $request->getPost('active'); 
				$attribute_type 	=  $request->getPost('attribute_type'); 
				
				$dataArr['name']			=	$name;
				$dataArr['active']			= 	$active;
				$dataArr['attribute_type']	= 	$attribute_type ;
				
				
				$attributeset->exchangeArray($dataArr);
                $this->getAttributesetTable()->saveAttributeset($attributeset);
			
			     return $this->redirect()->toRoute('attributeset', array('action'=>'index'));
		
         }
		
        }
		
        return array('form' => $form,'messages'  => $this->flashmessenger()->getMessages());	
	
	}
	
    public function indexAction()
    { 
		$this->init();
		 return new ViewModel(array(
				'attributesets' => $this->getAttributesetTable()->fetchAll(),
			));
		
	}
	
	public function deleteAction()
	{
		$this->init();	
		
		$id = (int) $this->params()->fromRoute('id', 0);
		$this->getAttributesetTable()->deleteAttributeset($id);
	}
	
	
	public function detailsAction()
	{
		$this->init();
		$id = (int) $this->params()->fromRoute('id', 0);
		return new ViewModel(     array(
								'attributesetDetails' => $this->getAttributesetTable()->getAttributeset($id) ,
								)
		);
		
		
		
	}
	
	
	

	
	

}

