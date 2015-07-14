<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


use Admin\Model\Weapon;          // <-- Add this import
use Admin\Form\WeaponForm;       // <-- Add this import

use Admin\Model\Weaponattrubutevalue;          // <-- Add this import

use Admin\Model\Attributeset;          // <-- Add this import

use Admin\Model\Attributefield;          // <-- Add this import
use Admin\Form\AttributefieldForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\View\Model\JsonModel;



class WeaponController extends AbstractActionController
{
	
	protected $weaponTable;
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
	
	public function getWeaponTable()
    {
        if (!$this->weaponTable) {
            $sm = $this->getServiceLocator();
            $this->weaponTable = $sm->get('Admin\Model\WeaponTable');
        }
        return $this->weaponTable;
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
		
		$form = new WeaponForm($mydata);
        $form->get('submit')->setValue('Add');
		
	
		
		
		$form->get('setId')->setValue($setid1);
		
	
        if ($request->isPost()) {
		
		//echo "<pre>";
		//print_r($request->getPost());
		
		
            $weapon = new Weapon();
			$weaponattrubutevalue = new Weaponattrubutevalue();
           // $form->setInputFilter($weapon->getInputFilter());
            $form->setData($request->getPost());
			
           if ($form->isValid()) {							

				 $allFormData=$request->getPost();
				 
				 $setId 	=  $request->getPost('setId'); 
				 $name	 	=  $request->getPost('name'); 
				 $price 	=  $request->getPost('price'); 
				
				 
				
				$dataArr['setId']=$setId;
				$dataArr['name']=$name;
				$dataArr['price']=$price;
				
				//echo "<pre>";
				//print_r($dataArr);
				
				
				
				
				
					
				
				$weapon->exchangeArray($dataArr);
				$product_last_id=$this->getWeaponTable()->saveWeapon($weapon);
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
						//echo "<pre>";
						//print_r($attValueArr[$j]);
						
						$weaponattrubutevalue->exchangeArray($attValueArr[$j]);
						$this->getWeaponattrubutevalueTable()->saveWeaponattrubutevalue($weaponattrubutevalue);
						$j++;
					
					}

					}
				
					
					
				}
				
		
				//$this->getWeaponTable()->saveWeapon($weapon);
			
			     return $this->redirect()->toRoute('weapon', array('action'=>'index','id'=>$setId));
		
         }
		
        }
		
        return array('form' => $form,'messages'  => $this->flashmessenger()->getMessages());

	} else {
	
		$attributeset = new Attributeset();
		$attributesetData=$this->getAttributesetTable()->fetchAll($setid1);
		return new ViewModel(array('noSetSelected' => 1,'attributesetLists' =>$attributesetData));
	}	
	
	}
	
    public function indexAction()
    { 
		$this->init();
		$setid1=$this->params()->fromQuery('id',null);
	
		 return new ViewModel(array(
				'weapons' => $this->getWeaponTable()->fetchAll($setid1),
			));
		
	}
	
	public function deleteAction()
	{
		$this->init();
		
		
		$id = (int) $this->params()->fromRoute('id', 0);
		$this->getWeaponTable()->deleteWeapon($id);
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

