<?php
namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


use Admin\Model\Clothing;          // <-- Add this import
use Admin\Form\ClothingForm;       // <-- Add this import

use Admin\Model\Weaponattrubutevalue;          // <-- Add this import

use Admin\Model\Attributeset;          // <-- Add this import

use Admin\Model\Attributefield;          // <-- Add this import
use Admin\Form\AttributefieldForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\View\Model\JsonModel;



class ClothingController extends AbstractActionController
{
	
	protected $clothingTable;
	protected $regionTable;
	protected $weaponattrubutevalueTable;
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
	
	public function getClothingTable()
    {
        if (!$this->clothingTable) {
            $sm = $this->getServiceLocator();
            $this->clothingTable = $sm->get('Admin\Model\ClothingTable');
        }
        return $this->clothingTable;
    }
	
	public function getRegionTable()
    {
        if (!$this->regionTable) {
            $sm = $this->getServiceLocator();
            $this->regionTable = $sm->get('Admin\Model\RegionTable');
        }
        return $this->regionTable;
    }	
	
	
	
	
	
	
	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }
	
	public function getWeaponattrubutevalueTable()
    {
        if (!$this->weaponattrubutevalueTable) {
            $sm = $this->getServiceLocator();
            $this->weaponattrubutevalueTable = $sm->get('Admin\Model\WeaponattrubutevalueTable');
        }
        return $this->weaponattrubutevalueTable;
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
				$this->getWeaponTable()->updateWeapon($myData , $id);
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
		$setid1=$this->params()->fromQuery('id',null);
		
		$request = $this->getRequest();
		if(!isset($setid1))
		{
			$setid1=$request->getPost('setId');
		}

		if(isset($setid1) && $setid1!="")
		{
		
		$mydata=$this->getAttributefieldTable()->fetchAll($setid1);
		$regionsdata=$this->getRegionTable()->fetchAll();
		
		$form = new ClothingForm($mydata , $regionsdata);
		//echo '<pre>'; print_r($form); exit;
        $form->get('submit')->setValue('Add');
		
	
		
		
		$form->get('setId')->setValue($setid1);
		
	
        if ($request->isPost()) {
		
		//echo "<pre>";
		//print_r($request->getPost());
		
		
            $clothing = new Clothing();
			$weaponattrubutevalue = new Weaponattrubutevalue();
            $form->setInputFilter($clothing->getInputFilter());
            $form->setData($request->getPost());
			
           if ($form->isValid()) {							

				 $allFormData=$request->getPost();
				 
				 $setId 		=  $request->getPost('setId'); 
				 $name	 		=  $request->getPost('name'); 
				 $price_gold 	=  $request->getPost('price_gold'); 
				 $price_bucks 	=  $request->getPost('price_bucks'); 
				 $region_id 	=  $request->getPost('region_id'); 
				
				$deer_panic 			=  $request->getPost('deer_panic');
				$player_facing_name 	=  $request->getPost('player_facing_name');
				$basic_alertness 		=  $request->getPost('basic_alertness');
				$deer_reaction_range 	=  $request->getPost('deer_reaction_range');
				 
				
				$dataArr['setId']		=	$setId;
				$dataArr['name']		=	$name;
				$dataArr['price_bucks']	=	$price_bucks;
				$dataArr['price_gold']	=	$price_gold;
				$dataArr['region_id']	=	$region_id;
				
				$dataArr['deer_panic']			=	$deer_panic;
				$dataArr['player_facing_name']	=	$player_facing_name;
				$dataArr['basic_alertness']		=	$basic_alertness;
				$dataArr['deer_reaction_range']	=	$deer_reaction_range;
				$dataArr['created_on']	=	time();
				

				
				$clothing->exchangeArray($dataArr);
				$product_last_id=$this->getClothingTable()->saveClothing($clothing);
				
				/*
				if($product_last_id!="")
				{
					$attValueArr = array();
					$j=0;
					foreach($allFormData as $key=>$val)
					{
					if($key!="submit")
					{
						$attValueArr[$j]['attribute_field_id']=0;
						$attValueArr[$j]['attribute_field_name']=$key;					
						$attValueArr[$j]['productId']=$product_last_id;
						$attValueArr[$j]['value']=$val;						
						$weaponattrubutevalue->exchangeArray($attValueArr[$j]);
						$this->getWeaponattrubutevalueTable()->saveWeaponattrubutevalue($weaponattrubutevalue);
						$j++;
					
					}

					}

				}
				*/
		

			
			     return $this->redirect()->toRoute('clothing', array('action'=>'index','id'=>$setId));
		
         }
		
        }
		
        return array('form' => $form,'messages'  => $this->flashmessenger()->getMessages());

	} else {
	
		$attributeset = new Attributeset();
		$attributeTypeId = 3; 
		$attributesetData=$this->getAttributesetTable()->fetchAllByType($attributeTypeId);
		return new ViewModel(array('noSetSelected' => 1,'attributesetLists' =>$attributesetData));
	}	
	
	}
	
	public function editAction() {
	
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		//echo $id; exit;
		//if (!$id) {
			 //return $this->redirect()->toRoute('clothing', array('action'=>'add'));
		 //}
		 
		$clothing 		=	$this->getClothingTable()->getClothing($id);
		$regionsdata	=	$this->getRegionTable()->fetchAll();
		//echo '<pre>'; print_r($bank);	
		 //echo $bank->name;
		// exit;	
		$form = new ClothingForm(Null , $regionsdata);
		$form->bind($clothing);
		//$form->get('submit')->setValue('Edit');
		$form->get('submit')->setAttribute('value', 'Save');	
        $request = $this->getRequest();
		
        if ($request->isPost()) {	
           // $haircolor = new Haircolor();
            $form->setInputFilter($clothing->getInputFilter());
            $form->setData($request->getPost());

			   if ($form->isValid()) {
			   
				 //$allFormData=$request->getPost();
				 
				 $setId 		=  $request->getPost('setId'); 
				 $name	 		=  $request->getPost('name'); 
				 $price_gold 	=  $request->getPost('price_gold'); 
				 $price_bucks 	=  $request->getPost('price_bucks'); 
				 $region_id 	=  $request->getPost('region_id'); 
				
				$deer_panic 			=  $request->getPost('deer_panic');
				$player_facing_name 	=  $request->getPost('player_facing_name');
				$basic_alertness 		=  $request->getPost('basic_alertness');
				$deer_reaction_range 	=  $request->getPost('deer_reaction_range');
				 
				$dataArr['id']			=	$request->getPost('id'); 
				$dataArr['setId']		=	$setId;
				$dataArr['name']		=	$name;
				$dataArr['price_bucks']	=	$price_bucks;
				$dataArr['price_gold']	=	$price_gold;
				$dataArr['region_id']	=	$region_id;
				
				$dataArr['deer_panic']			=	$deer_panic;
				$dataArr['player_facing_name']	=	$player_facing_name;
				$dataArr['basic_alertness']		=	$basic_alertness;
				$dataArr['deer_reaction_range']	=	$deer_reaction_range;
				//$dataArr['created_on']	=	time();
				

				
				$clothing->exchangeArray($dataArr);
				$this->getClothingTable()->saveClothing($clothing);					
				return $this->redirect()->toRoute('clothing', array('action'=>'index'));			
			 }
		
        }

	
	 return array(
         'id' => $id,
         'form' => $form,
		 'clothing' => $clothing,
     );
	

	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
    public function indexAction()
    { 
		$this->init();
		//$setid1=$this->params()->fromQuery('id',null);
		$setid1 = (int) $this->params()->fromRoute('id', 0);
		//echo 'ggggg'.$setid1;
		//exit;
		 return new ViewModel(array(
				'weapons' => $this->getClothingTable()->fetchAll($setid1),
				'attributeSets' => $this->getAttributesetTable()->fetchAll(),
				'setID' => $setid1,
			));
		
	}
	
	public function deleteAction()
	{
		$this->init();
		$id = (int) $this->params()->fromRoute('id', 0);
		$this->getClothingTable()->deleteClothing($id);
		return $this->redirect()->toRoute('clothing' , array('action' => 'index'));
	}
	
	
	public function detailsAction()
	{
		
		$this->init();
		$id = (int) $this->params()->fromRoute('id', 0);
		return new ViewModel(     array(
								'weaponDetails' => $this->getWeaponTable()->getWeapon($id) ,
								)
		);
		
		
		
	}
	
	
	

	
	

}

