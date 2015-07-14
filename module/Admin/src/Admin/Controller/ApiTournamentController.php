<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;



use Zend\View\Model\ViewModel;
use Admin\Model\Region;          // <-- Add this import
use Admin\Form\RegionForm;       // <-- Add this import
use Admin\Model\RegionTable; 

use Admin\Model\Deerinfo;     
use Admin\Model\DeerinfoTable; 

use Admin\Model\Suggestion; 

use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;



use Zend\View\Model\JsonModel;
use Zend\Debug\Debug;


class ApiTournamentController extends AbstractRestfulController
{
	
		protected $tournamentTable;
		protected $deerinfoTable;
		protected $suggestionTable;
		protected $restApiKey = "AXDFG65432@789GAME#987";
		
		public function getTournamentTable()
		{
			if ( !$this->tournamentTable ) {
				$sm = $this->getServiceLocator();
				$this->tournamentTable = $sm->get('Admin\Model\TournamentTable');
			}
			return $this->tournamentTable;
		}
		
			
	
	public function getSuggestionTable()
    {
        if (!$this->suggestionTable) {
            $sm = $this->getServiceLocator();
            $this->suggestionTable = $sm->get('Admin\Model\SuggestionTable');
        }
        return $this->suggestionTable;
    }
	
		
		
		
		
		
		
		
		
		

		public function getDeerinfoTable()
		{
			if (!$this->deerinfoTable) {
				$sm = $this->getServiceLocator();
				$this->deerinfoTable = $sm->get('Admin\Model\DeerinfoTable');
			}
			return $this->deerinfoTable;
		}
		
		
		
		
		public function getList()
		{

				$results 	= $this->getTournamentTable()->getTournamentLists();
				$data 		= array();
				$result2 = array();
				foreach($results as $result) {
				
					//$data[] = $result;
					
				$missionId 			= trim($result['missions_id']);
				$deerInfoData 		= array();
				$huntSuggestionData = array();
				$deerInforesults  	= $this->getTournamentTable()->getMissionHasDeerInfo($missionId);
				$huntSuggestionRsults  =$this->getTournamentTable()->getMissionsHasHuntSuggestions($missionId);
				//echo '<pre>'; print_r($huntSuggestionRsults); exit;
				$result['hunt_suggestions'] = 	$huntSuggestionRsults;
				if( 0=== $deerInforesults->count()) {
					$data[] = $result;
				}
				else {
				
				foreach($deerInforesults as $info) { $deerInfoData[] = $info;	 } 
				$result['deer_info'] = 	$deerInfoData;			
				$data[] = $result;
				
				}	
					/*
				if( 0=== $huntSuggestionRsults->count()) {
					$data[] = $result;
				}
				else {
				
				foreach($huntSuggestionRsults as $rowval) { $huntSuggestionData[] = $rowval;	 } 
				$result['hunt_suggestions'] = 	$huntSuggestionData;			
				$data[] = $result;
				
				}		
					
					*/
					
					
					
					
					
					
				}

				
				//echo '<pre> '; print_r($data); exit;
				
				
				
				if(count($data) >0 )
				{
					
					return new JsonModel(array( 'data' => $data ));
				}
				else
				{

					return new JsonModel(array( 'error' => true,'message' => "No Data Found" ));
				}
	
		}

		public function get($id)
		{
			# code...
			//$response = array();
			$id = (int) $id;
			$results  = $this->getTournamentTable()->getTournamentLists($id);                  
			//e//cho '<pre> '; print_r($results); exit;
			//echo $results['missions_id'];
		
			$data 		= array();
			$result2 = array();
			foreach($results as $result) {
				
				$missionId 					= trim($result['missions_id']);
				$deerInfoData 				= array();
				$huntSuggestionData 		= array();
				$deerInforesults  			= $this->getTournamentTable()->getMissionHasDeerInfo($missionId);
				$huntSuggestionRsults  		= $this->getTournamentTable()->getMissionsHasHuntSuggestions($missionId);

				$result['hunt_suggestions'] = 	$huntSuggestionRsults;			
				//$data[] = $result;
				
				
				if( 0=== $deerInforesults->count()) {
					$data[] = $result;
				}
				else {
				
				foreach($deerInforesults as $info) { $deerInfoData[] = $info;	 } 
				$result['deer_info'] = 	$deerInfoData;			
				$data[] = $result;
				
				}
				
				/*
				if( 0=== $huntSuggestionRsults->count()) {
					$data[] = $result;
				}
				else {
				
				foreach($huntSuggestionRsults as $rowval) { $huntSuggestionData[] = $rowval;	 } 
				$result['hunt_suggestions'] = 	$huntSuggestionData;			
				$data[] = $result;
				
				}		
				
				*/
				
				
				
				
				
			}

			
			
			//echo $data[0]['missions_id'];
			//echo '<pre> '; print_r($data); exit;
			if(count($data) >0 )
			{
					return new JsonModel(array( 'data' => $data ));
			}
			else
			{
					return new JsonModel(array( 'error' => true,'message' => "No Data Found" ));
			}

			
			
			
			
					/*


					if(!$dataVal)
					{				

						return new JsonModel(array( 'error' => true,'message' => "Content ID $id does not exist " ));
					}	
					else
					{	

						return new JsonModel(array( 'data' => $dataVal));
					}
					*/

		}

		public function create($data)
		{
				#code...
				/*
				return new JsonModel(array(
									'data' => 'Created !',
				));
				*/
		}

		public function update($id, $data)
		{
				/*
				$data['id'] = $id;

				
				$bank = $this->getBankTable()->getBank($id);
				$form = new BankForm();
				$form->bind($bank);
				$form->setInputFilter($bank->getInputFilter());
				$form->setData($data);
				if ($form->isValid()) {
					
					$dataArr['id']		=	$data['id'];
					$dataArr['name']	=	$data['name'];
					$dataArr['active']	= 	$data['active'];				
					$dataArr['price']	=	$data['price'];		
				
					
					$bank->exchangeArray($dataArr);
					$returnid = $this->getBankTable()->updateBank($bank);
					
					
					
					
					
				}

				
				
				return new JsonModel(array(
										'data' => $returnid,
					));

			*/
		}

		public function delete($id)
		{
			# code...
			/*	
			$this->getBankTable()->deleteBank($id);

			return new JsonModel(array(
						'data' => 'deleted',
						));
				
			*/	
			
			
		}

	

}

