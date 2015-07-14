<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


use Admin\Model\Tournament;          // <-- Add this import
use Admin\Form\TournamentForm;       // <-- Add this import

use Admin\Model\Tournamentattrubutevalue;          // <-- Add this import

use Admin\Model\Attributeset;          // <-- Add this import

use Admin\Model\Attributefield;          // <-- Add this import
use Admin\Form\AttributefieldForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\View\Model\JsonModel;



class TournamentController extends AbstractActionController
{
	
	protected $tournamentTable;
	protected $tournamentattrubutevalueTable;
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
	
	public function getTournamentTable()
    {
        if (!$this->tournamentTable) {
            $sm = $this->getServiceLocator();
            $this->tournamentTable = $sm->get('Admin\Model\TournamentTable');
        }
        return $this->tournamentTable;
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
				$this->getTournamentTable()->updateTournament($myData , $id);
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
		
		$regionLists100=$this->getTournamentTable()->regionList();
			
		
		foreach($regionLists100 as $regionLists1){
		$regionList[$regionLists1['id']]=$regionLists1['name'];		
		}
		
		$myformdata['regionList']=$regionList;
	
		$form = new TournamentForm($myformdata);
        $form->get('submit')->setValue('Save & Continue');		
		$request = $this->getRequest();
		
		
		
        if ($request->isPost()) {
            $tournament = new Tournament();		       
            $form->setData($request->getPost());
			
           if ($form->isValid()) {						

				 $allFormData=$request->getPost();
				 $name	 			=  $request->getPost('name'); 
				 $duration	 		=  (int) $request->getPost('duration');
				 
				/*				
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
				 
				*/
				
				// $tournamentType 	=  $request->getPost('tournamentType'); 
				// $minPointForEntry 	=  $request->getPost('minPointForEntry'); 					
				 //$numberOfMissions = 	(int)$request->getPost('number_0f_missions'); 
				 $startDate="";
				 if($request->getPost('start_date')!="")
				 {	
					$startDate 	=  strtotime($request->getPost('start_date')); 
				 }
				 $endDate="";
				 if($request->getPost('end_date')!="")
				 {	
					$endDate 	=  strtotime($request->getPost('end_date')); 
				 }
				 
				
				 $tournamentType 	=  $request->getPost('tournament_type'); 
				 $minPointForEntry 	=  $request->getPost('entry_points'); 
				 
				 
				 				
				$dataArr['name']				=	$name;
				$dataArr['duration']			=	$duration;
				$dataArr['start_date']			=	$startDate;
				$dataArr['end_date']			=	$endDate;
				$dataArr['tournament_type']		=	$tournamentType;
				$dataArr['entry_points']		=	$minPointForEntry;
				 
				 
				 
				 
				 
				 
				
				//$dataArr['name']				=	$name;
				//$dataArr['duration']			=	$duration;
				//$dataArr['startDate']			=	$startDate;
				//$dataArr['endDate']				=	$endDate;
				//$dataArr['tournamentType']		=	$tournamentType;
				//$dataArr['minPointForEntry']	=	$minPointForEntry;
				
				$tournament->exchangeArray($dataArr);
				$tournament_last_id=$this->getTournamentTable()->saveTournament($tournament);
				if($tournament_last_id) $this->flashMessenger()->addMessage('Tournament has been created initially .');		
				return $this->redirect()->toRoute('tournament', array('action'=>'index'));
		
         }
		
        }
		
        return array('form' => $form,'messages'  => $this->flashmessenger()->getMessages());

	
	
	}
	
	public function nextAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		$missionCount = (int) $this->params()->fromRoute('count', 0);
		return new ViewModel( array(
					'id' => $id,
					'missionCount' => $missionCount,
		));
	}
	
	
    public function indexAction()
    { 
		$this->init();	
	
		 return new ViewModel(array(
				'tournaments' => $this->getTournamentTable()->fetchAll(),
				'flashMessages' 		=> $this->flashMessenger()->getMessages(),
			));
		
	}
	
	public function deleteAction()
	{
		$this->init();
		
		
		$id = (int) $this->params()->fromRoute('id', 0);
		$this->getTournamentTable()->deleteTournament($id);
		$this->flashMessenger()->addMessage('Successfully deleted this content.');
		return $this->redirect()->toRoute('tournament' , array('action' => 'index'));
		
		
	}
	
	
	
	
	public function detailsAction()
	{
		
		$this->init();
		$id = (int) $this->params()->fromRoute('id', 0);
		return new ViewModel(     array(
								'tournamentDetails' => $this->getTournamentTable()->getTournament($id) ,
								)
		);
		
		
		
	}
	
	
		
	public function editAction()
	{
		$this->init();		
		$id = (int) $this->params()->fromRoute('id', 0);
		$tournament = $this->getTournamentTable()->getTournament($id);

		$form  = new TournamentForm();

	
		$request = $this->getRequest();			
        if($request->isPost()) {
		
		//$tournament = new Tournament();	
			$form->setInputFilter($tournament->getInputFilter());
			$form->setData($request->getPost());
		
		
			 if($form->isValid()) {
			 

		
				//echo '<pre>'; print_r($request->getPost()); exit;
		
				$name	  	=  $request->getPost('name'); 
				$duration 	=  (int) $request->getPost('duration');
					
					
				 $startDate="";
				 if($request->getPost('start_date')!="")
				 {	
					$startDate 	=  strtotime($request->getPost('start_date')); 
				 }
				 $endDate="";
				 if($request->getPost('end_date')!="")
				 {	
					$endDate 	=  strtotime($request->getPost('end_date')); 
				 }
				 
				
				 $tournamentType 	=  $request->getPost('tournament_type'); 
				 $minPointForEntry 	=  $request->getPost('entry_points'); 
				
				 
				
				$dataArr['name']				=	$name;
				$dataArr['duration']			=	$duration;
				$dataArr['start_date']			=	$startDate;
				$dataArr['end_date']			=	$endDate;
				$dataArr['tournament_type']		=	$tournamentType;
				$dataArr['entry_points']		=	$minPointForEntry;
				
								
				$tournament->exchangeArray($dataArr);
                $this->getTournamentTable()->updateTournament($tournament,$id);
				$this->flashMessenger()->addMessage('Successfully updated this content.');
				return $this->redirect()->toRoute('tournament', array('action'=>'index'));
            }
        }
		else
		{
				
				//echo $tournament->end_date; exit;
				$start_date = date("Y-m-d h:i"); $end_date = date("Y-m-d h:i");
				if($tournament->start_date > 0 )
				{
					$start_date=date("Y-m-d h:i",$tournament->start_date);
				}
				if($tournament->end_date > 0 )
				{
					$end_date=date("Y-m-d h:i",$tournament->end_date);
				}
				$form->bind($tournament);
				$form->get('start_date')->setAttribute('value' , $start_date);
				$form->get('end_date')->setAttribute('value' , $end_date);
				$form->get('entry_points')->setAttribute('value' , $tournament->entry_points);		
				$form->get('submit')->setAttribute('value', 'Edit & Continue');
		
		
		
		
		
		
		
		
		
		}
		
		 return array('id' => $id,  'form' => $form,   );
		
		
	}

	
	

}

