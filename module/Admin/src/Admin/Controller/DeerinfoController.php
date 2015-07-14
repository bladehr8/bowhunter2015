<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\Deerinfo;          // <-- Add this import
use Admin\Form\DeerinfoForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;



class DeerinfoController extends AbstractActionController
{
	
	protected $deerinfoTable;
	public $activityArray = array();
	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }
	
	public function getDeerinfoTable()
    {
        if (!$this->deerinfoTable) {
            $sm = $this->getServiceLocator();
            $this->deerinfoTable = $sm->get('Admin\Model\DeerinfoTable');
        }
        return $this->deerinfoTable;
    }
	
	public function indexAction()
	{ 
		$this->init();		
		return new ViewModel( array(
		'deerinfo' 				=> $this->getDeerinfoTable()->fetchAll(), 
		'deerActivityRows' 		=> $this->getDeerinfoTable()->deerActivityLists(),
		'deerSizeLists' 		=> $this->getDeerinfoTable()->deerSizeLists(),
		'deerFacingLists' 		=> $this->getDeerinfoTable()->deerFacingLists(),
		'deerPositionLists' 	=> $this->getDeerinfoTable()->deerPositionLists(),
		'flashMessages' 		=> $this->flashMessenger()->getMessages(),
		));
	}
	
	
	
	

	
	public function addAction()	{
		$this->init();
		# Set Form select lists Value - options values
		$form = new DeerinfoForm();
        $form->get('submit')->setValue('Save');
		
		$deerActivityRows 	= $this->getDeerinfoTable()->deerActivityLists();
		$deerSizeLists 		= $this->getDeerinfoTable()->deerSizeLists();
		$deerFacingLists 	= $this->getDeerinfoTable()->deerFacingLists();
		$deerPositionLists 	= $this->getDeerinfoTable()->deerPositionLists();
		
		
		$form->get('deer_activity_id')->setValueOptions($deerActivityRows);
		$form->get('deer_size_id')->setValueOptions($deerSizeLists);
		$form->get('deer_facing_id')->setValueOptions($deerFacingLists);
		$form->get('deer_position_id')->setValueOptions($deerPositionLists);
		
		# End Of this section
		
		
        $request = $this->getRequest();
        if ($request->isPost()) {
	
				$deerinfo = new Deerinfo();
				$form->setInputFilter($deerinfo->getInputFilter());
				$form->setData($request->getPost());

			   if ($form->isValid())
			   {							

					$deer_activity_id 		=  $request->getPost('deer_activity_id') ; 
					$deer_size_id 			=  $request->getPost('deer_size_id') ; 
					$deer_facing_id 		=  $request->getPost('deer_facing_id') ; 
					$deer_position_id 		=  $request->getPost('deer_position_id') ;
					$min_start_range 		=  $request->getPost('min_start_range') ;
					$max_start_range 		=  $request->getPost('max_start_range') ;
					$kill_for_success 		=  $request->getPost('kill_for_success') ;
					$active 				=  $request->getPost('active'); 
					
					$dataArr['deer_activity_id']	=	$deer_activity_id ;
					$dataArr['deer_size_id']		=	$deer_size_id 	 ;
					$dataArr['deer_facing_id']		=	$deer_facing_id ;
					$dataArr['deer_position_id'] 	=  	$deer_position_id ;
					
					$dataArr['min_start_range'] 	=  	$min_start_range ;
					$dataArr['max_start_range'] 	=  	$max_start_range ;
					$dataArr['kill_for_success'] 	=  	$kill_for_success ;
					
					
					$dataArr['created_on'] 	=  	time() ;
					$dataArr['active']		= 	$active ;		
					
					$deerinfo->exchangeArray($dataArr);
					$this->getDeerinfoTable()->saveDeerinfo($deerinfo);			
					return $this->redirect()->toRoute('deerinfo', array('action'=>'index'));
			
			 }
		
        }
		
        return array('form' => $form, 'messages'  => $this->flashmessenger()->getMessages());	
	
	}
	

	public function editAction() {
	
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			 return $this->redirect()->toRoute('bank', array('action'=>'add'));
		 }
		 
		$deerinfo = $this->getDeerinfoTable()->getDeerinfo($id);
		//echo '<pre>'; print_r($bank);	
		 //echo $bank->name;
		// exit;	
		$form = new DeerinfoForm();
		$form->bind($deerinfo);
		//$form->get('submit')->setValue('Edit');
		$form->get('submit')->setAttribute('value', 'Update');	
		$deerActivityRows 	= $this->getDeerinfoTable()->deerActivityLists();
		$deerSizeLists 		= $this->getDeerinfoTable()->deerSizeLists();
		$deerFacingLists 	= $this->getDeerinfoTable()->deerFacingLists();
		$deerPositionLists 	= $this->getDeerinfoTable()->deerPositionLists();
		
		
		$form->get('deer_activity_id')->setValueOptions($deerActivityRows);
		$form->get('deer_size_id')->setValueOptions($deerSizeLists);
		$form->get('deer_facing_id')->setValueOptions($deerFacingLists);
		$form->get('deer_position_id')->setValueOptions($deerPositionLists);
		
		# End Of this section
		
		
		
		
		
		
		
		
		
        $request = $this->getRequest();
		
        if ($request->isPost()) {	
           // $haircolor = new Haircolor();
            $form->setInputFilter($deerinfo->getInputFilter());
            $form->setData($request->getPost());

			   if ($form->isValid()) {				

					$dataArr['id']			= 	$request->getPost('id');	
					$deer_activity_id 		=  $request->getPost('deer_activity_id') ; 
					$deer_size_id 			=  $request->getPost('deer_size_id') ; 
					$deer_facing_id 		=  $request->getPost('deer_facing_id') ; 
					$deer_position_id 		=  $request->getPost('deer_position_id') ;
					$min_start_range 		=  $request->getPost('min_start_range') ;
					$max_start_range 		=  $request->getPost('max_start_range') ;
					$kill_for_success 		=  $request->getPost('kill_for_success') ;
					$active 				=  $request->getPost('active'); 
					
					$dataArr['deer_activity_id']	=	$deer_activity_id ;
					$dataArr['deer_size_id']		=	$deer_size_id 	 ;
					$dataArr['deer_facing_id']		=	$deer_facing_id ;
					$dataArr['deer_position_id'] 	=  	$deer_position_id ;
					
					$dataArr['min_start_range'] 	=  	$min_start_range ;
					$dataArr['max_start_range'] 	=  	$max_start_range ;
					$dataArr['kill_for_success'] 	=  	$kill_for_success ;			
					
					$dataArr['active']		= 	$active ;						
					$deerinfo->exchangeArray($dataArr);
					$this->getDeerinfoTable()->saveDeerinfo($deerinfo);	
					$this->flashMessenger()->addMessage('Your submission has been updated successfully !');	
					$this->view->flashMessenger = $this->flashMessenger;					
					return $this->redirect()->toRoute('deerinfo', array('action'=>'edit' , 'id' => $dataArr['id'] ));			
			 }
		
        }

	
	 return array(
         'id' => $id,
         'form' => $form,
		 'deerinfo' => $deerinfo,
		 'flashMessages' => $this->flashMessenger()->getMessages(),
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
		$this->getDeerinfoTable()->deleteDeerinfo($id);
		$this->flashMessenger()->addMessage('Successfully deleted this content.');
		return $this->redirect()->toRoute('deerinfo' , array('action' => 'index'));
		}
	}
	
	
	public function detailsAction()
	{
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		return new ViewModel( array('bankoptions' => $this->getBankTable()->fetchRowsById($id) ,)
		);
		
	}
	
	
	

	
	

}

