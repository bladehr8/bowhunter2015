<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\Attributefield;          // <-- Add this import
use Admin\Form\AttributefieldForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\View\Model\JsonModel;



class AttributefieldController extends AbstractActionController
{
	
	protected $attributefieldTable;
	public $setStatus;
	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }
	
	public function getAttributefieldTable()
    {
        if (!$this->attributefieldTable) {
            $sm = $this->getServiceLocator();
            $this->attributefieldTable = $sm->get('Admin\Model\AttributefieldTable');
        }
        return $this->attributefieldTable;
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
				$this->getAttributefieldTable()->updateAttributefield($myData , $id);
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
		
		$form = new AttributefieldForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
		///echo "<pre>";
		

		$setid1=$this->params()->fromQuery('id',null);
		$form->get('setId')->setValue($setid1);

	
		
        if ($request->isPost()) {
	
            $attributefield = new Attributefield();
           // $form->setInputFilter($attributefield->getInputFilter());
            $form->setData($request->getPost());
			
           if ($form->isValid()) {							

				 $setId 	=  	   $request->getPost('setId'); 
				 $fieldName 	=  $request->getPost('fieldName'); 
				 $fieldType 	=  $request->getPost('fieldType'); 
				 $active 	=      $request->getPost('active'); 
				 
				
				$dataArr['setId']=$setId;
				$dataArr['fieldName']=$fieldName;
				$dataArr['fieldType']=$fieldType;
				$dataArr['active']=1;
				
				
				$attributefield->exchangeArray($dataArr);
                $this->getAttributefieldTable()->saveAttributefield($attributefield);
			
			     return $this->redirect()->toRoute('attributefield', array('action'=>'index','id'=>$setId));
		
         }
		
        }
		
        return array('form' => $form,'messages'  => $this->flashmessenger()->getMessages());	
	
	}
	
    public function indexAction()
    { 
	
		$this->init();
		$setid1=$this->params()->fromQuery('id',null);
	
		 return new ViewModel(array(
				'attributefields' => $this->getAttributefieldTable()->fetchAll($setid1),
			));
		
	}
	
	public function deleteAction()
	{
		$this->init(); 
		
		
		$id = (int) $this->params()->fromRoute('id', 0);
		$this->getAttributefieldTable()->deleteAttributefield($id);
	}
	
	
	public function detailsAction()
	{
		$this->init();
		$id = (int) $this->params()->fromRoute('id', 0);
		return new ViewModel(     array(
								'attributefieldDetails' => $this->getAttributefieldTable()->getAttributefield($id) ,
								)
		);
		
		
		
	}
	
	
	

	
	

}

