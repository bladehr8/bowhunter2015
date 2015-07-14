<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\Region;          // <-- Add this import
use Admin\Form\RegionForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;



class RegionController extends AbstractActionController
{
	
	protected $regionTable;
	
	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }
	
	public function getRegionTable()
    {
        if (!$this->regionTable) {
            $sm = $this->getServiceLocator();
            $this->regionTable = $sm->get('Admin\Model\RegionTable');
        }
        return $this->regionTable;
    }
	
	public function indexAction()
	{ 
		$this->init();	
		//echo '<pre>'; print_r($this->getRegionTable()->fetchAll()); exit;

		return new ViewModel( array('regions' => $this->getRegionTable()->fetchAll(), ));			

	}
	
	
	
	

	
	public function addAction()	{
		$this->init();
		$form = new RegionForm();
        $form->get('submit')->setValue('Add');
		
		//$id = (int) $this->params()->fromRoute('id', 0);

		//if($id) {
		//$form->get('id')->setValue($id );
		//$bank = $this->getBankTable()->getBank($id);
		//}
		

        $request = $this->getRequest();
        if ($request->isPost()) {
	
				$region = new Region();
				$form->setInputFilter($region->getInputFilter());
				$form->setData($request->getPost());

			   if ($form->isValid())
			   {							

					$name 		=  $request->getPost('name') ; 
					$short_key 		=  $request->getPost('short_key') ;
					$require_xp_level = $request->getPost('require_xp_level');
					$active 	=  $request->getPost('active'); 
					
					$dataArr['name']		=	$name;
					$dataArr['short_key']	=	$short_key;
					$dataArr['require_xp_level']		=	$require_xp_level;					
					$dataArr['created_on'] 	=time() ;
					$dataArr['active']		= 	$active ;		
					
					$region->exchangeArray($dataArr);
					$this->getRegionTable()->saveRegion($region);			
					 return $this->redirect()->toRoute('region', array('action'=>'index'));
			
			 }
		
        }
		
        return array('form' => $form, 'messages'  => $this->flashmessenger()->getMessages());	
	
	}
	

	public function editAction() {
	
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			 return $this->redirect()->toRoute('region', array('action'=>'add'));
		 }
		 
		$region = $this->getRegionTable()->getRegion($id);
		//echo '<pre>'; print_r($bank);	
		 //echo $bank->name;
		// exit;	
		$form = new RegionForm();
		$form->bind($region);
		//$form->get('submit')->setValue('Edit');
		$form->get('submit')->setAttribute('value', 'Update & Save');	
        $request = $this->getRequest();
		
        if ($request->isPost()) {	
           // $haircolor = new Haircolor();
            $form->setInputFilter($region->getInputFilter());
            $form->setData($request->getPost());

			   if ($form->isValid()) {				

					$name 				=  $request->getPost('name') ; 
					$short_key 			=  $request->getPost('short_key') ;
					$require_xp_level 	= $request->getPost('require_xp_level');
					$active 			=  $request->getPost('active'); 

					$dataArr['name']					=	$name;
					$dataArr['short_key']				=	$short_key;
					$dataArr['require_xp_level']		=	$require_xp_level;				
					$dataArr['active']					= 	$active ;	
					$dataArr['id']						= 	$request->getPost('id');	
					
					$region->exchangeArray($dataArr);
					$this->getRegionTable()->updateRegion($region);						
					return $this->redirect()->toRoute('region', array('action'=>'index'));			
			 }
		
        }

	
	 return array(
         'id' => $id,
         'form' => $form,
		 'region' => $region,
     );
	

	
	}
	
	public function deleteAction()
	{
		$this->init();	
		if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
		
		
		$id = (int) $this->params()->fromRoute('id', 0);
		if($id) { 
		$this->getRegionTable()->deleteRegion($id);
		return $this->redirect()->toRoute('region' , array('action' => 'index'));
		}
	}
	
	
	public function detailsAction()
	{
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		return new ViewModel( array('bankoptions' => $this->getRegionTable()->fetchRowsById($id) ,)
		);
		
	}
	
	
	

	
	

}

