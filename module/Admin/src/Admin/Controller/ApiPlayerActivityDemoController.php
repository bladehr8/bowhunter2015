<?php

/**
 * @author Dibyendu konar
 * @copyright 2015
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\ViewModel;

use Admin\Model\Player;
use Admin\Model\PlayerTable;



use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;



use Zend\View\Model\JsonModel;



class ApiPlayerActivityDemoController extends AbstractRestfulController
{
	
		protected $playerTable;
		protected $restApiKey = "AXDFG65432@789GAME#987";
        
		public function getPlayerTable()
    {
        if (!$this->playerTable) {
            $sm = $this->getServiceLocator();
            $this->playerTable = $sm->get('Admin\Model\PlayerTable');
        }
        return $this->playerTable;
    }

		
		
		
		
		public function getList()
		{
				# code...
				//$response = array();
				$results 	= $this->getPlayerTable()->fetchAll(true);
				$data 		= array();
				foreach($results as $result) {
					$data[] = $result;
				}

				//return array('data' => $data);
				if(count($data) >0 )
				{
					
					return new JsonModel(array( 'data' => $data ));
				}
				else
				{
					//return new JsonModel(array( 'error' => true,'message' => "No Data Found" ));
					$errorJson = array(
									'message'   => "empty",
																
								);
					return new JsonModel(array('errors' => array($errorJson)));	

					
				}
				

				
		}

		public function get($id)
		{
			# code...
			//$response = array();
            $playerName = (string)$id;
			$dataVal = $this->getPlayerTable()->getPlayerByName($playerName);
			
			if(!$dataVal)
			{				
				$errorJson = array('message'   => "failure",);
				return new JsonModel(array('errors' => array($errorJson)));	
			}	
			else
			{	
								
				return new JsonModel(array( 'data' => $dataVal));
			}

	
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



?>