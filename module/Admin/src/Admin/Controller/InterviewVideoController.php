<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\InterviewVideo;          // <-- Add this import
use Admin\Form\InterviewVideoForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Stdlib\ErrorHandler;


class InterviewVideoController extends AbstractActionController
{
	
	protected $interviewVideoTable;
	public $thumbWidth = 75;
	public $thumbHeight = 75;
	
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
        if (!$this->interviewVideoTable) {
            $sm = $this->getServiceLocator();
            $this->interviewVideoTable = $sm->get('Admin\Model\InterviewVideoTable');
        }
        return $this->interviewVideoTable;
    }
	
	public function indexAction()
	{ 
		$this->init();	
		return new ViewModel( array('rows' => $this->getTable()->fetchAll(), 'flashMessages'  => $this->flashmessenger()->getMessages(), ));			

	}
	
	
	
	

	
	public function addAction()	{	

		$this->init();		
		$form = new InterviewVideoForm();
        $form->get('submit')->setValue('Submit');
        $request = $this->getRequest();
		
        if ($request->isPost()) {
		
				// Make certain to merge the files info!
				//$post = $request->getPost()->toArray();
				$post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray() );
				$interviewVideo = new InterviewVideo();
				$form->setInputFilter($interviewVideo->getInputFilter());
				$form->setData($post);

			   if ($form->isValid())
			   {
					$data = $form->getData();
					$isValid = true; 
					$thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
					$ext_type = array('gif','jpg','JPEG','jpeg','png','JPG');
					//echo '<pre>'; print_r($data); exit;

					if (!empty($data['field_image']['name'])) {
						ErrorHandler::start();
						$originalFileName = trim($data['field_image']['name']);						
						$efilename = explode('.', $originalFileName);					
						$ext = end( $efilename );						
						
						if( !in_array($ext, $ext_type) ) { 
							throw new \Exception("Invalid file extenstion - $ext ");
						}
						$originalFileName = 'interviewer-'.time().'.'.$ext;			
						$dataArr['field_image']			=	$originalFileName;
						move_uploaded_file($data['field_image']['tmp_name'], 'public/upload/'.$originalFileName);
						$imagePath   = 'public/upload/'.$originalFileName;
						$thumb       = $thumbnailer->create($imagePath, $options = array(), $plugins = array());
						$thumb->resize($this->thumbWidth , $this->thumbHeight );
						$thumb->save('public/upload/thumb/'.$originalFileName);
						ErrorHandler::stop(true);
					} 
	

					$dataArr['title']						=	$data['title']; 
					$dataArr['short_note']					=	$data['short_note'];	
					$dataArr['youtube_link']				=	$data['youtube_link'];	
					$dataArr['created_on'] 					=	(new \DateTime())->format('Y-m-d');
					$dataArr['active']						= 	$data['active']; 	

					$interviewVideo->exchangeArray($dataArr);
					$this->getTable()->saveData($interviewVideo);			
					return $this->redirect()->toRoute('interview-video', array('action'=>'index'));

			 }
		
        }
		
        return array('form' => $form, 'flashMessages'  => $this->flashmessenger()->getMessages());	
	
	}
	

	public function editAction() {
	
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			 return $this->redirect()->toRoute('interview-video', array('action'=>'add'));
		 }
		 
		$getData 	= $this->getTable()->getData($id);	
		$form 		= new InterviewVideoForm();
		$form->bind($getData);
		//$form->get('submit')->setValue('Edit');
		$form->get('submit')->setAttribute('value', 'Update & Save');	
        $request = $this->getRequest();
		
		//$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		//$form->setDbAdapter($dbAdapter);	
		
        if ($request->isPost()) {	
		
			$post = $request->getPost()->toArray();
			$interviewVideo = new InterviewVideo();
			$form->setInputFilter($interviewVideo->getInputFilter());
			$form->setData($post);			

			   if ($form->isValid())
			   {
					$data = $form->getData();
					$isValid = true; 
					$thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
					$ext_type = array('gif','jpg','JPEG','jpeg','png');

					if (!empty($data->field_image['name'])) {					
						ErrorHandler::start();
						$originalFileName = trim($data->field_image['name']);					
						$efilename = explode('.', $originalFileName);					
						$ext = end( $efilename );					
						
						if( !in_array($ext, $ext_type) ) { 
							throw new \Exception("Invalid file extenstion - $ext ");
						}
						
						$originalFileName = 'interviewer-'.time().'.'.$ext;	
						$dataArr['field_image']			=	$originalFileName;
						move_uploaded_file( $data->field_image['tmp_name'] , 'public/upload/'.$originalFileName );
						$imagePath   = 'public/upload/'.$originalFileName;
						$thumb       = $thumbnailer->create($imagePath, $options = array(), $plugins = array());
						$thumb->resize($this->thumbWidth , $this->thumbHeight );
						$thumb->save('public/upload/thumb/'.$originalFileName);
						ErrorHandler::stop(true);
					} 					
					
					
					
					
					
					
					
					
					
					
					$dataArr['id']							=	$data->id; 					
					$dataArr['title']						=	$data->title; 
					$dataArr['short_note']					=	$data->short_note;	
					$dataArr['youtube_link']				=	$data->youtube_link;					
					$dataArr['active']						= 	$data->active; 	

					$interviewVideo->exchangeArray($dataArr);
					$this->getTable()->saveData($interviewVideo);			
					return $this->redirect()->toRoute('interview-video', array('action'=>'index'));

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
		return $this->redirect()->toRoute('interview-video' , array('action' => 'index'));
		}
		
		
	}
	
	
	public function detailsAction()
	{
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);		
		return new ViewModel( array('row' => $this->getTable()->getData($id) ,)
		);
		
	}
	
	
	

	
	

}

