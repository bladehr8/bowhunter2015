<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\PlayerTable;
use Application\Model\Player;
use Admin\Model\Pages;          // <-- Add this import
use Admin\Form\PagesForm;       // <-- Add this import
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Admin\Model\subscribersMail; 
use Admin\Model\subscribersMailTable; 
use Zend\Paginator\Adapter\DbSelect;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mail;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\SmtpOptions;
use Admin\Form\MailForm;
use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Stdlib\ErrorHandler;


class PagesController extends AbstractActionController
{
	
	protected $pagesTable;
    protected $subscribersMailTable;
    protected $playerTable;   
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
        if (!$this->pagesTable) {
            $sm = $this->getServiceLocator();
            $this->pagesTable = $sm->get('Admin\Model\PagesTable');
        }
        return $this->pagesTable;
    }
	#To store the send mails for the subscribers and the players
	public function getsubscribersMailTable()
    {
        if (!$this->subscribersMailTable) {
            $sm = $this->getServiceLocator();
            $this->subscribersMailTable = $sm->get('Admin\Model\subscribersMailTable');
        }
        return $this->subscribersMailTable;
    }
	#Call the player table to insert 
		public function getPlayerTable()
    {
        if (!$this->playerTable) {
            $sm = $this->getServiceLocator();
            $this->playerTable = $sm->get('Admin\Model\PlayerTable');
        }
        return $this->playerTable;
    }	
    
	public function seoUrl($string) {
    //Unwanted:  {UPPERCASE} ; / ? : @ & = + $ , . ! ~ * ' ( )
    $string = strtolower($string);
    //Strip any unwanted characters
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    //Clean multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);
    //Convert whitespaces and underscore to dash
    $string = preg_replace("/[\s_]/", "-", $string);
    return $string;
}
	
	
	
	
	
	
	public function indexAction()
	{ 
		$this->init();	
		return new ViewModel( array('rows' => $this->getTable()->fetchAll(), 'flashMessages'  => $this->flashmessenger()->getMessages(), ));			

	}
	
	
	
	

	
	public function addAction()	{
	

		$this->init();
		$this->appendMyJs();
		$form = new PagesForm();
        $form->get('submit')->setValue('Submit');

        $request = $this->getRequest();
        if ($request->isPost()) {
		
				// Make certain to merge the files info!
				$post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray() );
				$pages = new Pages();
				$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
				$pages->setDbAdapter($dbAdapter);
				
				
				
				
				
				
				
				
				$form->setInputFilter($pages->getInputFilter());
				$form->setData($post);

			   if ($form->isValid())
			   {
					$data = $form->getData();
					$isValid = true; 
					$thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
					$ext_type = array('gif','jpg','jpe','jpeg','png');
					//echo '<pre>'; print_r($data); exit;

					if (!empty($data['field_image']['name'])) {
						ErrorHandler::start();
						$originalFileName = trim($data['field_image']['name']);
						//$ext = end(explode('.',$originalFileName));
						$efilename = explode('.', $originalFileName);
						//$ext = $efilename[count($originalFileName) - 1];
						$ext = end( $efilename );
						//echo $ext; exit();
						
						if( !in_array($ext, $ext_type) ) { 
							throw new \Exception("Invalid file extenstion - $ext ");
						}
						$dataArr['field_image']			=	$originalFileName;
						move_uploaded_file($data['field_image']['tmp_name'], 'public/upload/'.$data['field_image']['name']);
						$imagePath   = 'public/upload/'.$data['field_image']['name'];
						$thumb       = $thumbnailer->create($imagePath, $options = array(), $plugins = array());
						$thumb->resize($this->thumbHeight, $this->thumbWidth);
						$thumb->save('public/upload/thumb/'.$originalFileName);
						ErrorHandler::stop(true);
					} 



					$dataArr['title']						=	$request->getPost('title') ; 
					$dataArr['seo_title']					=   $this->seoUrl($request->getPost('title'));
					$dataArr['short_note']					=	$request->getPost('short_note');	
					$dataArr['content']						=	$request->getPost('content');						
					$dataArr['modified_on'] 				=	date("Y-m-d H:i:s") ;
					$dataArr['created_on'] 					=	date("Y-m-d H:i:s") ;
					$dataArr['active']						= 	$request->getPost('active'); 	

					$pages->exchangeArray($dataArr);
					$this->getTable()->saveData($pages);			
					return $this->redirect()->toRoute('pages', array('action'=>'index'));

			 }
		
        }
		
        return array('form' => $form, 'flashMessages'  => $this->flashmessenger()->getMessages());	
	
	}
	

	public function editAction() {
	
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			 return $this->redirect()->toRoute('pages', array('action'=>'add'));
		 }
		 
		$getData = $this->getTable()->getData($id);	
		$form = new PagesForm();
		$form->bind($getData);
		//$form->get('submit')->setValue('Edit');
		$form->get('submit')->setAttribute('value', 'Update & Save');	
        $request = $this->getRequest();
		
		//$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		//$form->setDbAdapter($dbAdapter);	
		
		
		
		
		
        if ($request->isPost()) {	
		
			
			$post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray() );
			$pages = new Pages();
			//$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
			//$pages->setDbAdapter($dbAdapter);  
			$form->setInputFilter($pages->getInputFilter());
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
						//$ext = end(explode('.',$originalFileName));
						$efilename = explode('.', $originalFileName);
						//$ext = $efilename[count($originalFileName) - 1];
						$ext = end( $efilename );
						//echo $ext; exit();
						
						if( !in_array($ext, $ext_type) ) { 
							throw new \Exception("Invalid file extenstion - $ext ");
						}
						$dataArr['field_image']			=	$originalFileName;
						move_uploaded_file( $data->field_image['tmp_name'] , 'public/upload/'.$data->field_image['name'] );
						$imagePath   = 'public/upload/'.$data->field_image['name'] ;
						$thumb       = $thumbnailer->create($imagePath, $options = array(), $plugins = array());
						$thumb->resize($this->thumbHeight, $this->thumbWidth);
						$thumb->save('public/upload/thumb/'.$originalFileName);
						ErrorHandler::stop(true);
					} 


					$dataArr['id']							=	$request->getPost('id') ; 
					$dataArr['title']						=	$request->getPost('title') ; 
					$dataArr['seo_title']					=   $this->seoUrl($request->getPost('title'));
					$dataArr['short_note']					=	$request->getPost('short_note');	
					$dataArr['content']						=	$request->getPost('content');						
					$dataArr['modified_on'] 				=	date("Y-m-d H:i:s") ;
					$dataArr['created_on'] 					=	date("Y-m-d H:i:s") ;
					$dataArr['active']						= 	$request->getPost('active'); 	

					$pages->exchangeArray($dataArr);
					$this->getTable()->saveData($pages);			
					return $this->redirect()->toRoute('pages', array('action'=>'index'));

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
		return $this->redirect()->toRoute('pages' , array('action' => 'index'));
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
	
	
	public function mailsubscribersAction()
	{
	

		$this->init();
		$this->appendMyJs();
		$form = new MailForm();
        $form->get('submit')->setValue('Submit');

        $request = $this->getRequest();
        if ($request->isPost()) {
		          $dateTime = date('Y-m-d H:i:s');
                    $dateTime = explode(' ',$dateTime);
                    $date =$dateTime[0];
                    $originalDate = date("F j, Y<==>g:i a", strtotime($date));
                    $originalDate = explode('<==>',$originalDate);
                    $currentDate = $originalDate[0];  /*--Get the current date--*/
                    $currentTime = date("h:i:sa");    /*--Get the current time--*/
				// Make certain to merge the files info!
				$post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray() );
				$form->setData($post);

			   if ($form->isValid())
			   {
                    $subscribersMail = new subscribersMail();
                   /* $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
                    $subscribersMail->setDbAdapter($dbAdapter);   */                 
					$mailContent				=	$request->getPost('content');
					$date					    = 	$currentDate; 
                    $time                       =   $currentTime;	
                    $groupOfUser                =   "subscribers";
                    
                    $data['mailContent'] = $mailContent;
        			$data['date']  = $date;
        			$data['time'] =$time;
                    $data['groupOfUser'] =$groupOfUser;
            			 
                             
                    $subscribersMail->exchangeArray($data);
					//$this->getTable()->saveData($pages);	
                    $getdata = $this->getsubscribersMailTable()->insertsubscribersMail($subscribersMail);
				//	print_r($dataArr);
                if($data['mailContent']!= "")
                {
                $this->flashMessenger()->addMessage('Successfully send the mail !!!!');
                return new viewModel(array('form' => $form,'flashMessages'  => $this->flashmessenger()->getMessages()));
                
               }
               else
                {
                $this->flashMessenger()->addMessage('Please put your mail message !!!!');
                return new viewModel(array('form' => $form,'flashMessages'  => $this->flashmessenger()->getMessages()));
                
               }

			 }
               	
		
        }
		
        return new viewModel(array('form' => $form));	
	
	}
	public function mailplayersAction()
    {
		$this->init();
		$this->appendMyJs();
		$form = new MailForm();
        $form->get('submit')->setValue('Submit');

        $request = $this->getRequest();
        if ($request->isPost()) {
		          $dateTime = date('Y-m-d H:i:s');
                    $dateTime = explode(' ',$dateTime);
                    $date =$dateTime[0];
                    $originalDate = date("F j, Y<==>g:i a", strtotime($date));
                    $originalDate = explode('<==>',$originalDate);
                    $currentDate = $originalDate[0];  /*--Get the current date--*/
                    $currentTime = date("h:i:sa");    /*--Get the current time--*/
				// Make certain to merge the files info!
				$post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray() );
				$form->setData($post);

			   if ($form->isValid())
			   {
                    $subscribersMail = new subscribersMail();
                   /* $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
                    $subscribersMail->setDbAdapter($dbAdapter);   */                 
					$mailContent				=	$request->getPost('content');
					$date					    = 	$currentDate; 
                    $time                       =   $currentTime;	
                    $groupOfUser                =   "players";
                    
                    $data['mailContent'] = $mailContent;
        			$data['date']  = $date;
        			$data['time'] =$time;
                    $data['groupOfUser'] =$groupOfUser;
            			 
                             
                    $subscribersMail->exchangeArray($data);
					//$this->getTable()->saveData($pages);	
                    $resultData = $this->getsubscribersMailTable()->insertsubscribersMail($subscribersMail);
                   
				//	print_r($dataArr);
                if($data['mailContent']!= "")
                {
                     $this->flashMessenger()->addMessage('Please put your mail message !!!!');
                return new viewModel(array('form' => $form,'flashMessages'  => $this->flashmessenger()->getMessages()));
               
                
               }
              else
                {
               
                 $this->flashMessenger()->addMessage('Successfully send the mail !!!!');
                return new viewModel(array('form' => $form,'flashMessages'  => $this->flashmessenger()->getMessages()));
               }

			 }
               	
		
        }
		
        return new viewModel(array('form' => $form));	
	
	}

	
	

}

