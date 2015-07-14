<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\GameVideo;          // <-- Add this import
use Admin\Form\GameVideoForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Stdlib\ErrorHandler;


class GameVideoController extends AbstractActionController
{
	
	protected $gameVideoTable;
	public $thumbWidth = 100;
	public $thumbHeight = 100;
	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }
	
	public function appendMyJs()
	{                 
		$this->getViewHelper('inlineScript')->appendScript('jQuery(document).ready(function() {  $("textarea.ckeditor").ckeditor(); });',	        
	        'text/javascript', array('noescape' => true));
	}

	protected function getViewHelper($helperName)
	{
		return $this->getServiceLocator()->get('viewhelpermanager')->get($helperName);
	}


	
	public function getTable()
    {
        if (!$this->gameVideoTable) {
            $sm = $this->getServiceLocator();
            $this->gameVideoTable = $sm->get('Admin\Model\GameVideoTable');
        }
        return $this->gameVideoTable;
    }
	
	public function indexAction()
	{ 
		$this->init();	
		return new ViewModel( array('rows' => $this->getTable()->fetchAll(), 'flashMessages'  => $this->flashmessenger()->getMessages(), ));			

	}
	
	
	
	

	
	public function addAction()	{
	
	//$renderer = $this->serviceLocator->get('Zend\View\Renderer\RendererInterface');
	//echo  $renderer->basePath();
	//exit;
	//$date = Zend_Date::now();
	//define('CONST_SERVER_TIMEZONE', 'UTC');
	//echo (new \DateTime())->format('Y-m-d H:i:s');

		$this->init();
		//$this->appendMyJs();
		$form = new GameVideoForm();
        $form->get('submit')->setValue('Submit');

        $request = $this->getRequest();
        if ($request->isPost()) {
		
				// Make certain to merge the files info!
				$post = $request->getPost()->toArray();
				$gameVideo = new GameVideo();
				$form->setInputFilter($gameVideo->getInputFilter());
				$form->setData($post);

			   if ($form->isValid())
			   {
					$data = $form->getData();
					//echo '<pre>'; print_r($data); exit();

					$dataArr['title']						=	$data['title']; 
					$dataArr['short_note']					=	$data['short_note'];	
					$dataArr['youtube_link']				=	$data['youtube_link'];						
					
					$dataArr['created_on'] 					=	(new \DateTime())->format('Y-m-d');
					$dataArr['active']						= 	$data['active']; 	

					$gameVideo->exchangeArray($dataArr);
					$this->getTable()->saveData($gameVideo);			
					return $this->redirect()->toRoute('game-video', array('action'=>'index'));

			 }
		
        }
		
        return array('form' => $form, 'flashMessages'  => $this->flashmessenger()->getMessages());	
	
	}
	

	public function editAction() {
	
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			 return $this->redirect()->toRoute('game-video', array('action'=>'add'));
		 }
		 
		$getData 	= $this->getTable()->getData($id);	
		$form 		= new GameVideoForm();
		$form->bind($getData);
		//$form->get('submit')->setValue('Edit');
		$form->get('submit')->setAttribute('value', 'Update & Save');	
        $request = $this->getRequest();
		
		//$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		//$form->setDbAdapter($dbAdapter);	
		
        if ($request->isPost()) {	
		
			$post = $request->getPost()->toArray();
			$gamevideo = new GameVideo();
			$form->setInputFilter($gamevideo->getInputFilter());
			$form->setData($post);			

			   if ($form->isValid())
			   {
					$data = $form->getData();
					//echo $data->id;
					//echo '<pre>'; print_r($data); exit;

					$dataArr['id']							=	$data->id; 					
					$dataArr['title']						=	$data->title; 
					$dataArr['short_note']					=	$data->short_note;	
					$dataArr['youtube_link']				=	$data->youtube_link;					
					$dataArr['active']						= 	$data->active; 	

					$gamevideo->exchangeArray($dataArr);
					$this->getTable()->saveData($gamevideo);			
					return $this->redirect()->toRoute('game-video', array('action'=>'index'));

			 }			
			
			
			
			
			
			
			
			
			


		
        }

	
	 return array(
         'id' => $id,
         'form' => $form,
		 'getData' => $getData,
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
		$this->getTable()->deleteData($id);
		return $this->redirect()->toRoute('game-video' , array('action' => 'index'));
		}
		
		
	}
	
	
	public function detailsAction()
	{
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		//echo '<pre>'; print_r($this->getTable()->getData($id)); exit;
		return new ViewModel( array('row' => $this->getTable()->getData($id) ,)
		);
		
	}
	
	
	

	
	

}

