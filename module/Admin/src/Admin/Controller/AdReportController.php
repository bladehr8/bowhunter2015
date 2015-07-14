<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\AdReport;          // <-- Add this import
//use Admin\Form\AdvertisementForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Stdlib\ErrorHandler;


class AdReportController extends AbstractActionController
{
	
	protected $adlogsTable;

	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }
	/*
	public function appendMyJs()
	{                 
		$this->getViewHelper('HeadScript')->appendFile('/assets/plugins/jquery-inputlimiter/jquery.inputlimiter.1.3.1.min.js');
		$this->getViewHelper('HeadScript')->appendFile('/assets/plugins/autosize/jquery.autosize.min.js');  
		$this->getViewHelper('HeadScript')->appendFile('/assets/plugins/select2/select2.min.js');  
		$this->getViewHelper('HeadScript')->appendFile('/assets/plugins/jquery.maskedinput/src/jquery.maskedinput.js'); 

		$this->getViewHelper('HeadScript')->appendFile( '/assets/plugins/jquery-maskmoney/jquery.maskMoney.js');  
		$this->getViewHelper('HeadScript')->appendFile( '/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.js');  
		$this->getViewHelper('HeadScript')->appendFile('/assets/plugins/ckeditor/adapters/jquery.js');  
		$this->getViewHelper('HeadScript')->appendFile('/assets/js/form-elements.js');  
		

	     
		
		$this->getViewHelper('inlineScript')->appendScript('jQuery(document).ready(function() { FormElements.init(); });',	        
	        'text/javascript', array('noescape' => true));
		
		
		

	}
*/
	protected function getViewHelper($helperName)
	{
		return $this->getServiceLocator()->get('viewhelpermanager')->get($helperName);
	}

	
	
	
	
	
	
	
	
	
	
	public function getTable()
    {
        if (!$this->adlogsTable) {
            $sm = $this->getServiceLocator();
            $this->adlogsTable = $sm->get('Admin\Model\AdReportTable');
        }
        return $this->adlogsTable;
    }
	
	public function indexAction()
	{ 
		$this->init();	
		$this->getViewHelper('inlineScript')->appendScript('jQuery(document).ready(function() { UIModals.init(); });',	 'text/javascript', array('noescape' => true));  
		
		$request = $this->getRequest();
        if ($request->isPost())
		{
			//$daterange = $request->getPost('daterange');
			//$results = $this->getTable()->fetchAll($daterange);	
			$ad_status 			= $request->getPost('ad_status');
			$daterange 			= $request->getPost('daterange');
			
			$ad_status     	= (!empty($ad_status)) ? $ad_status : null;
			$daterange     	= (!empty($daterange )) ? $daterange  : null;

			$results 		= $this->getTable()->fetchAll($ad_status , $daterange)	;


		}
		else
		{
			$results = $this->getTable()->fetchAll();
			$daterange = null ; 
			$ad_status = null;
		}
		
		
		
		
		
		
		
		
		return new ViewModel( array('rows' => $results, 'flashMessages'  => $this->flashmessenger()->getMessages(), 'daterange' => $daterange , 'adStatus'=>$ad_status , ));			

	}
	

	
	public function deleteAction()
	{
		$this->init();	
		if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
		
		
		$id = (int) $this->params()->fromRoute('id', 0);
		if($id) { 
		$this->getTable()->deleteData($id);
		return $this->redirect()->toRoute('advertisement' , array('action' => 'index'));
		}
		
		
	}
	
	
	public function detailsAction()
	{
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		return new ViewModel( array('bankoptions' => $this->getRegionTable()->fetchRowsById($id) ,)
		);
		
	}
	
		public function modalajaxAction()
		{
	
	    
	    $result = array('status' => 'error', 'message' => 'There was some error. Try again.');
		
	    
	    $request = $this->getRequest();
	    
	    if($request->isXmlHttpRequest()){
	    	
	        //$data = $request->getPost();
			$id = (int) $this->params()->fromRoute('id', 0);
			$getUsers = $this->getTable()->fetchUsersByAdId($id );
	        
	       /* if(isset($data['UID']) && !empty($data['UID'])){
			
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
			*/
	    }
	    
	    //return new JsonModel($result);
		//echo json_encode($result);
		//exit();
		//echo $this->partial('modalajax.phtml') ;
		$result = new ViewModel();
		$result->setTerminal(true);
		$result->setVariables(array('adId' => $id , 'userLists' => $getUsers ));
		return $result; 
		
	}
	

	
	

}

