<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


use Admin\Model\Missions;          // <-- Add this import
use Admin\Form\MissionsForm;       // <-- Add this import

//use Admin\Model\Tournamentmissionattrubutevalue;          // <-- Add this import

use Admin\Model\Attributeset;          // <-- Add this import

use Admin\Model\Attributefield;          // <-- Add this import
use Admin\Form\AttributefieldForm;       // <-- Add this import

use Admin\Model\Tournament; 
use Admin\Model\Deerinfo;
use Admin\Form\DeerinfoForm; 
use Admin\Model\Suggestion;   


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\View\Model\JsonModel;



class MissionsController extends AbstractActionController
{
	
	protected $tournamentmissionTable;
	protected $tournamentmissionattrubutevalueTable;
	protected $attributesetTable;
	protected $attributefieldTable;
	protected $tournamentTable;
	public 	  $setStatus;
	protected $deerinfoTable;
	protected $suggestionTable;
	
	public function getSuggestionTable()
    {
        if (!$this->suggestionTable) {
            $sm = $this->getServiceLocator();
            $this->suggestionTable = $sm->get('Admin\Model\SuggestionTable');
        }
        return $this->suggestionTable;
    }
	
	
	
	
	
	
	
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
	
	public function getMissionsTable()
    {
        if (!$this->tournamentmissionTable) {
            $sm = $this->getServiceLocator();
            $this->tournamentmissionTable = $sm->get('Admin\Model\MissionsTable');
        }
        return $this->tournamentmissionTable;
    }
	
	public function getTournamentTable()
    {
        if (!$this->tournamentTable) {
            $sm = $this->getServiceLocator();
            $this->tournamentTable = $sm->get('Admin\Model\TournamentTable');
        }
        return $this->tournamentTable;
    }
	
	
	public function getDeerinfoTable()
    {
        if (!$this->deerinfoTable) {
            $sm = $this->getServiceLocator();
            $this->deerinfoTable = $sm->get('Admin\Model\DeerinfoTable');
        }
        return $this->deerinfoTable;
    }
	
	
	
	
	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }

	public function addAction()
	{
		$this->init();	
		$tournamentId = (int) $this->params()->fromRoute('id', 0);
		# Get tournament details from tournament id
		if( $tournamentId > 0 ) $resultSet = $this->getTournamentTable()->getTournament($tournamentId); else $resultSet = null;
		
		$form = new MissionsForm();
        $form->get('submit')->setValue('Save & Continue');
		
		$deerResults = $this->getMissionsTable()->fetchAllDear();
		//echo '<pre>'; print_r($deerResults); exit;
		//$huntSuggestionObj = $this->getSuggestionTable()->fetchRowsById(2);
		//print $huntSuggestionObj->name;
		////echo '<pre>'; print_r($huntSuggestionObj);
		//exit;
		
		
		
		
		$huntTypeLists 			= $this->getMissionsTable()->huntTypeLists();
		$huntSuggestionLists 	= $this->getMissionsTable()->huntSuggestionLists();
		//echo '<pre>'; print_r($huntSuggestionLists); exit;
		
		
		$playerPositionLists 		= $this->getMissionsTable()->playerPositionLists();
		$shortHandTargetTypeLists 	= $this->getMissionsTable()->shortHandTargetTypeLists();
		$regionLists 				= $this->getMissionsTable()->regionLists();
		
		$lightConditionLists		= $this->getMissionsTable()->lightConditionLists();
		//$deerInfoLists				= $this->getMissionsTable()->deerInfoLists();
		$killTypeLists				= $this->getMissionsTable()->killTypeLists();
		$missionObjectives			= $this->getMissionsTable()->missionObjectives();
		
		
		$form->get('hunt_types_id')->setValueOptions($huntTypeLists);
		//$form->get('hunt_suggestion_id')->setValueOptions($huntSuggestionLists);
		$form->get('player_position_id')->setValueOptions($playerPositionLists);
		$form->get('shorthand_target_type_id')->setValueOptions($shortHandTargetTypeLists);
		$form->get('region_id')->setValueOptions($regionLists);
		
		$form->get('light_conditions_id')->setValueOptions($lightConditionLists);
		
		$form->get('kill_type_id')->setValueOptions($killTypeLists);
		$form->get('mission_objectives_id')->setValueOptions($missionObjectives);
		
		//$form->get('deer_information_id')->setValueOptions($deerInfoLists);
		/*
		$form->add(array(
							'type' => 'Zend\Form\Element\Checkbox',
							'name' => 'checkbox',
							'options' => array(
								'label' => '',
								'use_hidden_element' => false,
								'checked_value' => 'good',
								'unchecked_value' => 'bad'
							),
							'attributes' => array('class' => 'grey'),
						));
							
		
		$form->add(array(
            'type' => 'Zend\Form\Element\MultiCheckbox',
            'name' => 'resources',
            'options' => array(
                'label' => 'Please choose the deer information for this mission',
				'label_attributes' => array('class'  => 'checkbox-inline' ),
				'class' => 'checkbox-inline',
                'value_options' => $regionLists,
            ),
			'attributes' => array('class' => 'grey'),
        ));
		*/	

		
		
		
		

		$request = $this->getRequest();
        if ($request->isPost()) {

				//echo '<pre>'; print_r($request->getPost()); exit;
		
				$missions = new Missions();
				$form->setInputFilter($missions->getInputFilter());
				$form->setData($request->getPost());
			
			
			
           if ($form->isValid()) {				
				
				
				$dataArr['name']= $request->getPost('name');
				$dataArr['minimum_points_required']= $request->getPost('minimum_points_required');
				$dataArr['duration']= $request->getPost('duration');
				$dataArr['recommended_gear']= $request->getPost('recommended_gear');
				$dataArr['difficulty_level']= $request->getPost('difficulty_level');
				
				$dataArr['no_more_hunts']= $request->getPost('no_more_hunts');
				$dataArr['kill_for_success']= $request->getPost('kill_for_success');
				$dataArr['premium_currency_rewarded']= $request->getPost('premium_currency_rewarded');
				$dataArr['normal_currency_rewarded']= $request->getPost('normal_currency_rewarded');
				$dataArr['xp_rewarded']= $request->getPost('xp_rewarded');
				
				//$dataArr['hunt_suggestion_id']= $request->getPost('hunt_suggestion_id');
				$dataArr['hunt_suggestion_id']= 0; // Not in use
				
				$dataArr['shorthand_target_type_id']= $request->getPost('shorthand_target_type_id');
				$dataArr['hunt_types_id']= $request->getPost('hunt_types_id');
				$dataArr['player_position_id']= $request->getPost('player_position_id');
				$dataArr['region_id']= $request->getPost('region_id');
				$dataArr['light_conditions_id']= $request->getPost('light_conditions_id');
				$dataArr['kill_type_id']= $request->getPost('kill_type_id');
				$dataArr['mission_objectives_id']= $request->getPost('mission_objectives_id');
			
				
				
				$dataArr['created_on']= time();
				$dataArr['active']= 1;
				
				$addRowCount = (int) $request->getPost('count'); # Total number of rows created dynamically in form elements
				$deerActivity 			= $request->getPost('deer_activity_id');
				$deerSize 				= $request->getPost('deer_size_id');
				$deerFacing 			= $request->getPost('deer_facing_id');
				$deerPosition 			= $request->getPost('deer_position_id');
				$deerKillOfSuccess 		= $request->getPost('killforsuccess'); # kill for success for deer
				
				
				$deerMinRange = $request->getPost('min_start_range');
				$deerMaxRange = $request->getPost('max_start_range');

				
				//echo '<pre>'; print_r($$suggestionLists); exit;

				
				$missions->exchangeArray($dataArr);
				
				$suggestionLists = $request->getPost('huntSuggestion');
				//echo '<pre>'; print_r($suggestionLists); exit;
				if( count($suggestionLists) > 4 ) {
					throw new \Exception(" you can not select more than 4 !");
				}
				
				# get missions_id after insert data into mission table
				$last_insert_id=$this->getMissionsTable()->saveMission($missions);
				if($last_insert_id >0){
				
						if($addRowCount > 0 )
						{
							
							for($j=0 ; $j < $addRowCount ; $j++)
							{
								$deerData = array();
								$deerData['mission_id'] 			= $last_insert_id;
								$deerData['deer_information_id'] 	= 0;								
								$deerData['deer_activity'] 			= $deerActivity[$j] ; 
								$deerData['deer_size'] 				= $deerSize[$j] ; 
								$deerData['deer_facing'] 			= $deerFacing[$j] ; 
								$deerData['deer_position'] 			= $deerPosition[$j] ; 
								$deerData['kill_for_success'] 		= $deerKillOfSuccess[$j] ; 
								$deerData['min_start_range'] 		= $deerMinRange[$j] ; 
								$deerData['max_start_range'] 		= $deerMaxRange[$j] ;								
								$this->getMissionsTable()->saveMissionsHasDeerinfo($deerData);
							}
						}
						# Insert Data into "missions_has_hunt_suggestions" table based on missionID
						if( count($suggestionLists) > 0 )
						{
								for($k=0 ; $k < count($suggestionLists) ; $k++)
								{
									$suggestionId = trim($suggestionLists[$k]);
									$suggestionId = (int) $suggestionId;
									//$huntSuggestionObj = $this->getSuggestionTable()->fetchRowsById($suggestionId);
									//$huntSuggestionObj->name;									
										$suggData = array();
										$suggData['mission_id'] 	= $last_insert_id;
										$suggData['suggestion_id'] 	= $suggestionId	;			
										$this->getMissionsTable()->saveMissionsHasHuntSuggestions($suggData);

								}
						}
				
						if( $tournamentId > 0 ) 
						{ 						
								$data = array(
								 'tournament_id' => $tournamentId,
								 'missions_id' => $last_insert_id,
								 'active' => 1,
								);
								
								$returnId=$this->getMissionsTable()->saveTournamentHasMissions($data );								
						}
				
				}
				else {
						throw new \Exception('Data insertion failure ! ');
				}
				
			
			     return $this->redirect()->toRoute('tournament', array('action'=>'index','id'=>$returnId));
		
         }
		 else
		 {
			$messages = $form->getMessages();
			//print_r($messages);
			throw new \Exception('Form Validation Failure! ' . $messages);
		 }
		
        }
		//echo '<pre>'; print_r($this->getDeerinfoTable()->deerSizeLists()); exit;
		
        return array('form' => $form,
						'tournamentId' 			=> $tournamentId , 
						'tournament' 			=> $resultSet , 		
						'suggestionSet' 		=> $this->getMissionsTable()->huntSuggestionLists( null , 'lists' ),		
						'deerActivityRows' 		=> $this->getDeerinfoTable()->deerActivityLists(),
						'deerSizeLists' 		=> $this->getDeerinfoTable()->deerSizeLists(),
						'deerFacingLists' 		=> $this->getDeerinfoTable()->deerFacingLists(),
						'deerPositionLists' 	=> $this->getDeerinfoTable()->deerPositionLists(),		
						'messages'  => $this->flashmessenger()->getMessages()
		);

	
	
	}
	
	
			
	public function editAction()
	{
		$this->init();		
		$id = (int) $this->params()->fromRoute('id', 0);
		$mission = $this->getMissionsTable()->getMission($id);

		$form  = new MissionsForm();
		$form->bind($mission);
		$huntTypeLists 			= $this->getMissionsTable()->huntTypeLists();
		$huntSuggestionLists 	= $this->getMissionsTable()->huntSuggestionLists();
		//echo '<pre>'; print_r($huntSuggestionLists); exit;
		
		
		$playerPositionLists 		= $this->getMissionsTable()->playerPositionLists();
		$shortHandTargetTypeLists 	= $this->getMissionsTable()->shortHandTargetTypeLists();
		$regionLists 				= $this->getMissionsTable()->regionLists();
		
		$lightConditionLists		= $this->getMissionsTable()->lightConditionLists();
		//$deerInfoLists				= $this->getMissionsTable()->deerInfoLists();
		$killTypeLists				= $this->getMissionsTable()->killTypeLists();
		$missionObjectives			= $this->getMissionsTable()->missionObjectives();
		
		
		$form->get('hunt_types_id')->setValueOptions($huntTypeLists);
		//$form->get('hunt_suggestion_id')->setValueOptions($huntSuggestionLists);
		$form->get('player_position_id')->setValueOptions($playerPositionLists);
		$form->get('shorthand_target_type_id')->setValueOptions($shortHandTargetTypeLists);
		$form->get('region_id')->setValueOptions($regionLists);
		
		$form->get('light_conditions_id')->setValueOptions($lightConditionLists);
		
		$form->get('kill_type_id')->setValueOptions($killTypeLists);
		$form->get('mission_objectives_id')->setValueOptions($missionObjectives);
		
		
		
		
		
		
		
		
	
		$request = $this->getRequest();			
        if($request->isPost()) {
		
		//$tournament = new Tournament();	
			$form->setInputFilter($mission->getInputFilter());
			$form->setData($request->getPost());
		
		
			 if($form->isValid())
			 {
				$dataArr['id']	= $request->getPost('id');
				$dataArr['name']= $request->getPost('name');
				$dataArr['minimum_points_required']= $request->getPost('minimum_points_required');
				$dataArr['duration']= $request->getPost('duration');
				$dataArr['recommended_gear']= $request->getPost('recommended_gear');
				$dataArr['difficulty_level']= $request->getPost('difficulty_level');
				
				$dataArr['no_more_hunts']= $request->getPost('no_more_hunts');
				$dataArr['kill_for_success']= $request->getPost('kill_for_success');
				$dataArr['premium_currency_rewarded']= $request->getPost('premium_currency_rewarded');
				$dataArr['normal_currency_rewarded']= $request->getPost('normal_currency_rewarded');
				$dataArr['xp_rewarded']= $request->getPost('xp_rewarded');
				
				//$dataArr['hunt_suggestion_id']= $request->getPost('hunt_suggestion_id');
				$dataArr['hunt_suggestion_id']= 0; // Not in use
				
				$dataArr['shorthand_target_type_id']= $request->getPost('shorthand_target_type_id');
				$dataArr['hunt_types_id']= $request->getPost('hunt_types_id');
				$dataArr['player_position_id']= $request->getPost('player_position_id');
				$dataArr['region_id']= $request->getPost('region_id');
				$dataArr['light_conditions_id']= $request->getPost('light_conditions_id');
				$dataArr['kill_type_id']= $request->getPost('kill_type_id');
				$dataArr['mission_objectives_id']= $request->getPost('mission_objectives_id');
			
				
				
				$dataArr['created_on']= time();
				$dataArr['active']= 1;
				
				$addRowCount = (int) $request->getPost('count'); # Total number of rows created dynamically in form elements
				$deerActivity 			= $request->getPost('deer_activity_id');
				$deerSize 				= $request->getPost('deer_size_id');
				$deerFacing 			= $request->getPost('deer_facing_id');
				$deerPosition 			= $request->getPost('deer_position_id');
				$deerKillOfSuccess 		= $request->getPost('killforsuccess'); # kill for success for deer
				
				
				$deerMinRange = $request->getPost('min_start_range');
				$deerMaxRange = $request->getPost('max_start_range');

				
				//echo '<pre>'; print_r($$suggestionLists); exit;

				
				$mission->exchangeArray($dataArr);
				
				$suggestionLists = $request->getPost('huntSuggestion');
				//echo '<pre>'; print_r($suggestionLists); exit;
				if( count($suggestionLists) > 4 ) {
					throw new \Exception(" you can not select more than 4 !");
				}
				
				# get missions_id after insert data into mission table
				$last_insert_id=$this->getMissionsTable()->saveMission($mission);
				if($last_insert_id >0){
				
						if($addRowCount > 0 )
						{
							$this->getMissionsTable()->deleteMissionsHasDeerinfo($id);
							for($j=0 ; $j < $addRowCount ; $j++)
							{
								$deerData = array();
								$deerData['mission_id'] 			= $id;
								$deerData['deer_information_id'] 	= 0;								
								$deerData['deer_activity'] 			= $deerActivity[$j] ; 
								$deerData['deer_size'] 				= $deerSize[$j] ; 
								$deerData['deer_facing'] 			= $deerFacing[$j] ; 
								$deerData['deer_position'] 			= $deerPosition[$j] ; 
								$deerData['kill_for_success'] 		= $deerKillOfSuccess[$j] ; 
								$deerData['min_start_range'] 		= $deerMinRange[$j] ; 
								$deerData['max_start_range'] 		= $deerMaxRange[$j] ;								
								$this->getMissionsTable()->saveMissionsHasDeerinfo($deerData);
							}
						}
						# Insert Data into "missions_has_hunt_suggestions" table based on missionID
						if( count($suggestionLists) > 0 )
						{
								$this->getMissionsTable()->deleteMissionsHasHuntSuggestions($id);
								for($k=0 ; $k < count($suggestionLists) ; $k++)
								{
									$suggestionId = trim($suggestionLists[$k]);
									$suggestionId = (int) $suggestionId;								
									$suggData = array();
									$suggData['mission_id'] 	= $last_insert_id;
									$suggData['suggestion_id'] 	= $suggestionId	;			
									$this->getMissionsTable()->saveMissionsHasHuntSuggestions($suggData);

								}
						}
				
				/*
						if( $tournamentId > 0 ) 
						{ 						
								$data = array(
								 'tournament_id' => $tournamentId,
								 'missions_id' => $last_insert_id,
								 'active' => 1,
								);
								
								//$returnId=$this->getMissionsTable()->saveTournamentHasMissions($data );								
						}
				*/
				}
				else {
						throw new \Exception('Data insertion failure ! ');
				}
				
				$this->flashMessenger()->addMessage('Successfully updated this content.');
				return $this->redirect()->toRoute('missions', array('action'=>'index'));
            }
        }

		//print '<pre>'; print_r($this->getMissionsTable()->getMissionsHasHuntSuggestions($id)); exit;
		 return array('id' => $id,  
		 'form' => $form,
		 'mission' 				=> $mission , 
		'suggestionSet' 		=> $this->getMissionsTable()->huntSuggestionLists( null , 'lists' ),
		'missionsHasDeerinfoSet' => $this->getMissionsTable()->getMissionsHasDeerinfo($id),
		'missionsHasSuggestions' => $this->getMissionsTable()->getMissionsHasHuntSuggestions($id),
		'deerActivityRows' 		=> $this->getDeerinfoTable()->deerActivityLists(),
		'deerSizeLists' 		=> $this->getDeerinfoTable()->deerSizeLists(),
		'deerFacingLists' 		=> $this->getDeerinfoTable()->deerFacingLists(),
		'deerPositionLists' 	=> $this->getDeerinfoTable()->deerPositionLists(),	

		
		 );
		
		
	}

	
	
	
	
	
	
	
	
	
	
	
	
	
	
    public function indexAction()
    { 
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		//echo $id ; 
		//exit;
		if($id > 0 ) {
			$missionLists = $this->getMissionsTable()->fetchAllById($id);
			//echo '<pre>'; print_r($missionLists); exit;
		}
		else {
		$missionLists = $this->getMissionsTable()->fetchAll();
		//echo '<pre>'; print_r($missionLists); exit;
		}
		//echo '<pre>'; print_r($missionLists); exit;
		
		 return new ViewModel(array(
				'missions' => $missionLists,
				'flashMessages' 		=> $this->flashMessenger()->getMessages(),
			));
		
	}
	
	public function deleteAction()
	{
		$this->init();
		
		$id = (int) $this->params()->fromRoute('id', 0);
		$this->getMissionsTable()->deleteMission($id);
		$this->flashMessenger()->addMessage('Successfully deleted this content.');
		return $this->redirect()->toRoute('missions' , array('action' => 'index'));
		
		
	}
	
	
	
	
	public function detailsAction()
	{
		
		$this->init();
		$id = (int) $this->params()->fromRoute('id', 0);
		$resultValues = array();
		if($id > 0 )
		{ 
			$missionRow = $this->getMissionsTable()->getMissionDetails($id) ;
			$tournament_id = $missionRow['tournament_id'];
			if( !empty($tournament_id) ) { 
			$tournament_id = (int) $tournament_id; 
			$tournamentDetails = $this->getTournamentTable()->getTournament($tournament_id);
			} else $tournamentDetails = null ; 
			
			
			//echo '<pre>'; print_r($tournamentDetails); exit;
		}
		else 
		{
			throw new \Exception("Could not find row $id");
		}
		
		
		$getHasSuggestionLists = $this->getMissionsTable()->getMissionsHasHuntSuggestions($id);
		foreach($getHasSuggestionLists as $kay=>$val){
		$resultValues[] = $this->getSuggestionTable()->getSuggestion($val);		
		}
		
		
		//echo '<pre>'; print_r($missionRow); exit;
		
		return new ViewModel( array(
								'missionDetails' => $missionRow ,
								'tournamentDetails' => $tournamentDetails,
								'missionsHasDeerinfoSet' => $this->getMissionsTable()->getMissionsHasDeerinfo($id),
								'missionsHasSuggestions' => $resultValues,
							));
		
		
		
	}
	
	
		


	
	public function processajaxactiveAction()
	{	
	    
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
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

}

