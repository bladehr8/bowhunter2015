<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\OthersGame;          // <-- Add this import
use Admin\Form\OthersGameForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Stdlib\ErrorHandler;


class OthersGameController extends AbstractActionController
{
	
	protected $othersgameTable;
	public $thumbWidth = 50;
	public $thumbHeight = 50;
	public $ext_type = array('gif','jpg','JPEG','jpeg','png');
	
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
        if (!$this->othersgameTable) {
            $sm = $this->getServiceLocator();
            $this->othersgameTable = $sm->get('Admin\Model\OthersGameTable');
        }
        return $this->othersgameTable;
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
	$this->init();
	
		//$renderer = $this->serviceLocator->get('Zend\View\Renderer\RendererInterface');
		//echo  $renderer->basePath();
		//exit;
		//$date = Zend_Date::now();
		//define('CONST_SERVER_TIMEZONE', 'UTC');
		//echo (new \DateTime())->format('Y-m-d H:i:s');
		//$this->appendMyJs();
		
		$form = new OthersGameForm();
        $form->get('submit')->setValue('Submit');

        $request = $this->getRequest();
        if ($request->isPost()) {
		
				// Make certain to merge the files info!
				$post 			= array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray() );
				$othersGame 	= new OthersGame();

				$form->setInputFilter($othersGame->getInputFilter());
				$form->setData($post);

			   if ($form->isValid())
			   {
					$data = $form->getData();
					$isValid = true; 
					$thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
					//$ext_type = array('gif','jpg','JPEG','jpeg','png');

					if (!empty($data['field_image']['name'])) {
						ErrorHandler::start();
						$originalFileName = trim($data['field_image']['name']);						
						$efilename = explode('.', $originalFileName);						
						$ext = end( $efilename );
						
						
						if( !in_array($ext, $this->ext_type) ) { 
							throw new \Exception("Invalid file extenstion - $ext ");
						}
						$originalFileName = 'others-game-icon-'.time().'.'.$ext;						
						
						$dataArr['field_image']			=	$originalFileName;
						move_uploaded_file($data['field_image']['tmp_name'], 'public/upload/others-game/'.$originalFileName );
						$imagePath   = 'public/upload/others-game/'.$originalFileName ;
						$thumb       = $thumbnailer->create($imagePath, $options = array(), $plugins = array());
						$thumb->resize($this->thumbHeight, $this->thumbWidth);
						$thumb->save('public/upload/thumb/'.$originalFileName);
						ErrorHandler::stop(true);
					} 



					$dataArr['title']						=	$request->getPost('title') ; 						
					$dataArr['external_link']				=	$request->getPost('external_link');						
					
					$dataArr['created_on'] 					=	(new \DateTime())->format('Y-m-d');
					$dataArr['active']						= 	$request->getPost('active'); 	

					$othersGame->exchangeArray($dataArr);
					$this->getTable()->saveData($othersGame);			
					return $this->redirect()->toRoute('others-game', array('action'=>'index'));

			 }
		
        }
		
        return array('form' => $form, 'flashMessages'  => $this->flashmessenger()->getMessages());	
	
	}
	

	public function editAction() {
	
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			 return $this->redirect()->toRoute('others-game', array('action'=>'add'));
		 }
		 
		$getData = $this->getTable()->getData($id);	
		$form = new OthersGameForm();
		$form->bind($getData);
		//$form->get('submit')->setValue('Edit');
		$form->get('submit')->setAttribute('value', 'Update & Save');	
        $request = $this->getRequest();
		

        if ($request->isPost()) {	
		
			
			$post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray() );
			$othersGame = new OthersGame();

			$form->setInputFilter($othersGame->getInputFilter());
			$form->setData($post);
			

			   if ($form->isValid())
			   {
					$data = $form->getData();
					//echo '<pre>'; print_r($data); exit;
					$isValid = true; 
					$thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
					//$ext_type = array('gif','jpg','jpe','jpeg','png');

					if (!empty($data->field_image['name'])) {					
						ErrorHandler::start();
						$originalFileName = trim($data->field_image['name']);					
						$efilename = explode('.', $originalFileName);					
						$ext = end( $efilename );					
						
						if( !in_array($ext, $this->ext_type) ) { 
							throw new \Exception("Invalid file extenstion - $ext ");
						}
						
						$originalFileName = 'others-game-icon-'.time().'.'.$ext;
						$dataArr['field_image']			=	$originalFileName;
						move_uploaded_file( $data->field_image['tmp_name'] , 'public/upload/others-game/'.$originalFileName );
						$imagePath   = 'public/upload/others-game/'.$originalFileName;
						$thumb       = $thumbnailer->create($imagePath, $options = array(), $plugins = array());
						$thumb->resize($this->thumbHeight, $this->thumbWidth);
						$thumb->save('public/upload/thumb/'.$originalFileName);
						ErrorHandler::stop(true);
					} 


					$dataArr['id']							=	$data->id; 
					$dataArr['title']						=	$data->title ;					
					$dataArr['external_link']				=	$data->external_link;
					$dataArr['created_on'] 					=	date("Y-m-d") ;
					$dataArr['active']						= 	$data->active; 	

					$othersGame->exchangeArray($dataArr);
					$this->getTable()->saveData($othersGame);			
					return $this->redirect()->toRoute('others-game', array('action'=>'index'));

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
			$orgFile = "public/upload/".$getRow->field_image;
			unlink($thumbFile) ; unlink($orgFile);
		}		
	
		$this->getTable()->deleteData($id);
		$this->flashmessenger()->addMessage("Record has been deleted successfully .")	;	
		return $this->redirect()->toRoute('others-game' , array('action' => 'index'));
		}
		
		
	}
	
	# Get Details By @ID
	public function detailsAction()
	{
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);		
		return new ViewModel( array('row' => $this->getTable()->getData($id) ,)
		);
		
	}

}