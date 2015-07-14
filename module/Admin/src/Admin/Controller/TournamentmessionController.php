<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


use Admin\Model\Tournamentmission;          // <-- Add this import
use Admin\Form\TournamentmissionForm;       // <-- Add this import

use Admin\Model\Tournamentmissionattrubutevalue;          // <-- Add this import

use Admin\Model\Attributeset;          // <-- Add this import

use Admin\Model\Attributefield;          // <-- Add this import
use Admin\Form\AttributefieldForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\View\Model\JsonModel;



class TournamentmissionController extends AbstractActionController
{
	
	protected $tournamentmissionTable;
	protected $tournamentmissionattrubutevalueTable;
	protected $attributesetTable;
	protected $attributefieldTable;
	public $setStatus;
	
	public function getAttributefieldTable()
    {
        if (!$this->attributefieldTable) {
            $sm = $this->getServiceLocator();
            $this->attributefieldTable = $sm->get('Admin\Model\AttributefieldTable');
        }
        return $this->attributefieldTable;
    }
	
	public function getAttributesetTable()
    {
        if (!$this->attributesetTable) {
            $sm = $this->getServiceLocator();
            $this->attributesetTable = $sm->get('Admin\Model\AttributesetTable');
        }
        return $this->attributesetTable;
    }
	
	public function getTournamentmissionTable()
    {
        if (!$this->tournamentmissionTable) {
            $sm = $this->getServiceLocator();
            $this->tournamentmissionTable = $sm->get('Admin\Model\TournamentmissionTable');
        }
        return $this->tournamentmissionTable;
    }
	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
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
				$this->getTournamentmissionTable()->updateTournamentmission($myData , $id);
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
		
		
		$myformdata = array();
		$regionList = array();
		
		$regionLists100=$this->getTournamentmissionTable()->regionList();
			
		
		foreach($regionLists100 as $regionLists1){ 

		$regionList[$regionLists1['id']]=$regionLists1['name'];
		
		}
		
		$myformdata['regionList']=$regionList;
	
		$form = new TournamentmissionForm($myformdata);
        $form->get('submit')->setValue('Add');
		
		$request = $this->getRequest();
		
		
		
        if ($request->isPost()) {
		
		
		
            $tournamentmission = new Tournamentmission();		
       
            $form->setData($request->getPost());
			
           if ($form->isValid()) {							

				 $allFormData=$request->getPost();
				 
				 
				 $name	 	=  $request->getPost('name'); 
				 $regionType	 	=  $request->getPost('regionType');
				 $startDate="";
				 if($request->getPost('startDate')!="")
				 {	
					$startDate 	=  strtotime($request->getPost('startDate')); 
				 }
				 $endDate="";
				 if($request->getPost('endDate')!="")
				 {	
					$endDate 	=  strtotime($request->getPost('endDate')); 
				 }
				 
				
				 $tournamentmissionType 	=  $request->getPost('tournamentmissionType'); 
				 $minPointForEntry 	=  $request->getPost('minPointForEntry'); 
				
				 
				
				$dataArr['name']=$name;
				$dataArr['regionType']=$regionType;
				$dataArr['startDate']=$startDate;
				$dataArr['endDate']=$endDate;
				$dataArr['tournamentmissionType']=$tournamentmissionType;
				$dataArr['minPointForEntry']=$minPointForEntry;
				
				//echo "<pre>";
				//print_r($dataArr);
				
				$tournamentmission->exchangeArray($dataArr);
				$tournamentmission_last_id=$this->getTournamentmissionTable()->saveTournamentmission($tournamentmission);
				
				
			
			     return $this->redirect()->toRoute('tournamentmission', array('action'=>'index','id'=>$setId));
		
         }
		
        }
		
        return array('form' => $form,'messages'  => $this->flashmessenger()->getMessages());

	
	
	}
	
    public function indexAction()
    { 
		$this->init();	
	
		 return new ViewModel(array(
				'tournamentmissions' => $this->getTournamentmissionTable()->fetchAll(),
			));
		
	}
	
	public function deleteAction()
	{
		$this->init();
		
		
		$id = (int) $this->params()->fromRoute('id', 0);
		$this->getTournamentmissionTable()->deleteTournamentmission($id);
	}
	
	
	
	
	public function detailsAction()
	{
		
		$this->init();
		$id = (int) $this->params()->fromRoute('id', 0);
		return new ViewModel(     array(
								'tournamentmissionDetails' => $this->getTournamentmissionTable()->getTournamentmission($id) ,
								)
		);
		
		
		
	}
	
	
		
	public function editAction()
	{
		//echo $str=strtotime('1910-12-30 08:16');
		//echo "<br>";
		//echo date("Y-m-d h:i",$str);
		
		//exit();
		$this->init();
		$id = $this->params()->fromQuery('id',null);
		$request = $this->getRequest();	
		if(!isset($id))		{
		$id=$request->getPost('id');
		}
		
		if (!$id) {
            return $this->redirect()->toRoute('tournamentmission', array(
                'action' => 'add'
            ));
        }
		
		$tournamentmission = $this->getTournamentmissionTable()->getTournamentmission($id);
		
		if($tournamentmission->startDate)
		{
		$tournamentmission->startDate=date("Y-m-d h:i",$tournamentmission->startDate);
		}
		if($tournamentmission->endDate)
		{
		$tournamentmission->endDate=date("Y-m-d h:i",$tournamentmission->endDate);
		}
		$myformdata = array();
		$regionList = array();
		
		$regionLists100=$this->getTournamentmissionTable()->regionList();
			
		
		foreach($regionLists100 as $regionLists1){ 

		$regionList[$regionLists1['id']]=$regionLists1['name'];
		
		}
		
	    $myformdata['regionList']=$regionList;
		
		$form  = new TournamentmissionForm($myformdata);
        $form->bind($tournamentmission);
        $form->get('submit')->setAttribute('value', 'Edit');
	
				
        if($request->isPost()) {
		
		$tournamentmission = new Tournamentmission();	
			 if($form->isValid()) {
			
		
				$name	 =  $request->getPost('name'); 
				 $regionType	 	=  $request->getPost('regionType');
				 $startDate="";
				 if($request->getPost('startDate')!="")
				 {	
					$startDate 	=  strtotime($request->getPost('startDate')); 
				 }
				 $endDate="";
				 if($request->getPost('endDate')!="")
				 {	
					$endDate 	=  strtotime($request->getPost('endDate')); 
				 }
				 
				
				 $tournamentmissionType 	=  $request->getPost('tournamentmissionType'); 
				 $minPointForEntry 	=  $request->getPost('minPointForEntry'); 
				
				 
				
				$dataArr['name']=$name;
				$dataArr['regionType']=$regionType;
				$dataArr['startDate']=$startDate;
				$dataArr['endDate']=$endDate;
				$dataArr['tournamentmissionType']=$tournamentmissionType;
				$dataArr['minPointForEntry']=$minPointForEntry;
				
								
				$tournamentmission->exchangeArray($dataArr);
		
		
		
		
            //$form->setInputFilter($tournamentmission->getInputFilter());
            $form->setData($request->getPost());

           
                $this->getTournamentmissionTable()->updateTournamentmission($tournamentmission,$id);

                // Redirect to list of albums
                //return $this->redirect()->toRoute('tournamentmission');
				 return $this->redirect()->toRoute('tournamentmission', array('action'=>'index'));
            }
        }
		
		 return array('id' => $id,  'form' => $form,   );
		
		
	}

	
	

}

