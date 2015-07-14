<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\ImageSlider;          // <-- Add this import
use Admin\Form\ImageSliderForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Stdlib\ErrorHandler;


class ImageSliderController extends AbstractActionController
{
	
	protected $imgsliderTable;
	protected $thumbWidth = 1920;
	protected $thumbHeight = 365;
	
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
        if (!$this->imgsliderTable) {
            $sm = $this->getServiceLocator();
            $this->imgsliderTable = $sm->get('Admin\Model\ImageSliderTable');
        }
        return $this->imgsliderTable;
    }
	
	public function indexAction()
	{ 
		$this->init();	
		//$servoceLocator = $this->getServiceLocator();
		//$config         = $this->getServiceLocator()->get('config');
		//$myValue        = $config['offers_thumb_img'];
		
		//echo $myValue['thumbWidth'] ; exit;

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
		$form = new ImageSliderForm();
        $form->get('submit')->setValue('Submit');

        $request = $this->getRequest();
        if ($request->isPost()) {
		
				// Make certain to merge the files info!
				$post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray() );
				$imgSlider = new ImageSlider();
				//$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
				//$screenshots->setDbAdapter($dbAdapter);
				
				
				
				
				
				
				
				
				$form->setInputFilter($imgSlider->getInputFilter());
				$form->setData($post);

			   if ($form->isValid())
			   {
					$data = $form->getData();
					$isValid = true; 
					$thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
					$ext_type = array('gif','jpg','JPEG','jpeg','png');

					if (!empty($data['field_image'])) {
						ErrorHandler::start();
						$originalFileName = trim($data['field_image']['name']);						
						$efilename = explode('.', $originalFileName);						
						$ext = end( $efilename );
						
						
						if( !in_array($ext, $ext_type) ) { 
							throw new \Exception("Invalid file extenstion - $ext ");
						}
						$originalFileName = 'slider-'.time().'.'.$ext;

						
						
						$dataArr['field_image']			=	$originalFileName;
						move_uploaded_file($data['field_image']['tmp_name'], 'public/upload/slider/'.$originalFileName );
						$imagePath   = 'public/upload/slider/'.$originalFileName ;
						$thumb       = $thumbnailer->create($imagePath, $options = array(), $plugins = array());
						$thumb->resize($this->thumbWidth , $this->thumbHeight);
						$thumb->save('public/upload/thumb/'.$originalFileName);
						ErrorHandler::stop(true);
					} 



					$dataArr['title']						=	$data['title'] ; 
					$dataArr['short_note']					=	$data['short_note'];	
					$dataArr['external_link']				=	$data['external_link'];						
					
					$dataArr['created_on'] 					=	time();
					$dataArr['active']						= 	$data['active']; 	

					$imgSlider->exchangeArray($dataArr);
					$this->getTable()->saveData($imgSlider);
					$this->flashmessenger()->addMessage("Record has been added succesfully.");					
					return $this->redirect()->toRoute('img-slider', array('action'=>'index'));

			 }
		
        }
		
        return array('form' => $form, 'flashMessages'  => $this->flashmessenger()->getMessages());	
	
	}
	

	public function editAction() {
	
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			 return $this->redirect()->toRoute('img-slider', array('action'=>'add'));
		 }
		 
		$getData = $this->getTable()->getData($id);	
		$form = new ImageSliderForm();
		$form->bind($getData);
		//$form->get('submit')->setValue('Edit');
		$form->get('submit')->setAttribute('value', 'Update & Save');	
        $request = $this->getRequest();
		
		//$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		//$form->setDbAdapter($dbAdapter);	
		
		
		
		
		
        if ($request->isPost()) {	
		
			
			$post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray() );
			$imgSlider = new ImageSlider();
			//$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
			//$pages->setDbAdapter($dbAdapter);  
			$form->setInputFilter($imgSlider->getInputFilter());
			$form->setData($post);
			

			   if ($form->isValid())
			   {
					$data = $form->getData();
					//echo '<pre>'; print_r($data); exit;
					$isValid = true; 
					$thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
					$ext_type = array('gif','jpg','jpe','jpeg','png');

					if (!empty($data->field_image['name'])) {					
						ErrorHandler::start();
						$originalFileName = trim($data->field_image['name']);					
						$efilename = explode('.', $originalFileName);					
						$ext = end( $efilename );					
						
						if( !in_array($ext, $ext_type) ) { 
							throw new \Exception("Invalid file extenstion - $ext ");
						}
						
						$originalFileName = 'slider-'.time().'.'.$ext;
						$dataArr['field_image']			=	$originalFileName;
						move_uploaded_file( $data->field_image['tmp_name'] , 'public/upload/slider/'.$originalFileName );
						$imagePath   = 'public/upload/slider/'.$originalFileName;
						$thumb       = $thumbnailer->create($imagePath, $options = array(), $plugins = array());
						$thumb->resize($this->thumbWidth , $this->thumbHeight );
						$thumb->save('public/upload/thumb/'.$originalFileName);
						ErrorHandler::stop(true);
					} 


					$dataArr['id']							=	$data->id; 
					$dataArr['title']						=	$data->title ; 
					$dataArr['short_note']					=	$data->short_note;
					$dataArr['external_link']				=	$data->external_link;
					$dataArr['created_on'] 					=	time();
					$dataArr['active']						= 	$data->active; 	

					$imgSlider->exchangeArray($dataArr);
					$this->getTable()->saveData($imgSlider);
					$this->flashmessenger()->addMessage("Record has been updated succesfully.");								
					return $this->redirect()->toRoute('img-slider', array('action'=>'index'));

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
		$getRow = $this->getTable()->getData($id);
		if( $getRow->field_image ) { 
			### Unlink file before attached row is deleted @ ########
			$thumbFile = "public/upload/thumb/".$getRow->field_image;
			$orgFile = "public/upload/slider/".$getRow->field_image;
			unlink($thumbFile) ; unlink($orgFile);
		}		

		$this->getTable()->deleteData($id);
		$this->flashmessenger()->addMessage("Record has been deleted succesfully.");
		return $this->redirect()->toRoute('img-slider' , array('action' => 'index'));
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

