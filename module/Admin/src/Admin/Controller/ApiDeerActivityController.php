<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\ViewModel;

use Admin\Model\DeerActivity;          
//use Admin\Form\DeerActivityForm;       
use Admin\Model\DeerActivityTable; 



use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;



use Zend\View\Model\JsonModel;



class ApiDeerActivityController extends AbstractRestfulController
{
	
		protected $deeraActivityTable;
		protected $restApiKey = "AXDFG65432@789GAME#987";
		
		public function getTable()
		{
			if (!$this->deeraActivityTable) {
				$sm = $this->getServiceLocator();
				$this->deeraActivityTable = $sm->get('Admin\Model\DeerActivityTable');
			}
			return $this->deeraActivityTable;
		}

		
		
		
		
		public function getList()
		{
				# code...
				//$response = array();
				$results 	= $this->getTable()->fetchAll();
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
			$id = (int) $id;
            
			$dataVal = $this->getTable()->getData($id);
			
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

