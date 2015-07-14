<?php
namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\Admin;          // <-- Add this import
use Admin\Form\AdminForm;       // <-- Add this import
use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;

#Added by Dibyendu to send demo json data
use Admin\Model\Player;
use Admin\Model\PlayerTable;
use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Json\Json;
use Zend\Http\ClientStatic;
#Added by Dibyendu to send demo json data


class AdminController extends AbstractActionController
{
    protected $playerTable; #Added by Dibyendu to send demo json data
	protected $adminTable;
	protected $form;
    protected $storage;
    protected $authservice;
	
	
	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }	
    	public function getPlayerTable()
    {
        if (!$this->playerTable) {
            $sm = $this->getServiceLocator();
            $this->playerTable = $sm->get('Admin\Model\PlayerTable');
        }
        return $this->playerTable;
    }

    public function getAuthService()
    {

        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()->get('AuthService');                                     
        }
		$this->authservice;
		return $this->authservice;
    }
	
	public function getSessionStorage()
    {
        if (! $this->storage) {
            $this->storage = $this->getServiceLocator()->get('Admin\Model\MyAuthStorage');
        }
         
        return $this->storage;
    }
	
	
/*	public function getAdminTable()
    {
        if (!$this->adminTable) {
            $sm = $this->getServiceLocator();
            $this->adminTable = $sm->get('Admin\Model\AdminTable');
        }
        return $this->adminTable;
    }*/
	
	
	
	
	/// Login function
    public function indexAction()
    {	
		// If already login then redirect to dashboard.
		if($this->getServiceLocator()->get('AuthService')->hasIdentity()){				
			  return $this->redirect()->toRoute('admin', array('action'=>'dashboard'));
		} 
	
		$layout = $this->layout();
		$layout->setTemplate('layout/login');
		
		$form = new AdminForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
	
            $admin = new Admin();
            $form->setInputFilter($admin->getInputFilter());
            $form->setData($request->getPost());

			if ($form->isValid()) {							

					$username 	=  $request->getPost('email') ; 
					$password 	=  $request->getPost('password') ; 

					$this->getAuthService()->getAdapter()->setIdentity($username)->setCredential($password);
						  
					$result = $this->getAuthService()->authenticate();
					
				   foreach($result->getMessages() as $message)  {              
				   //save message temporary into flashmessenger
				   $this->flashmessenger()->addMessage($message);
					} 
					 
				if ($result->isValid()) {	
								
					$sessionAdmin = new Container('admin');
					$sessionAdmin->email = $username;
					$sessionAdmin->isvalid = "yes";			
					return $this->redirect()->toRoute('admin', array('action'=>'dashboard'));
					
				} 
				else
				{				
				return $this->redirect()->toRoute('admin', array('controller' => 'index', 'action'=>'index','id'=>0));
				}

			}
		
        }
		
        return array('form' => $form,'messages'  => $this->flashmessenger()->getMessages());
		

    }
	

	
	public function dashboardAction()
	{
	
		$this->init();
		
		$sessionAdmin = new Container('admin');
		echo $sessionAdmin->username;
		if($sessionAdmin->username=="") {
		//return $this->redirect()->toRoute('admin', array('controller' => 'index', 'action'=>'index','id'=>0));
		//echo $sessionAdmin->username;
		}
		return new ViewModel();
	}
	public function recvjsonAction()
    {
        $homepage =file_get_contents('http://localhost/bowhunter/public/admin/sendjson');
       print_r($homepage);
        exit;
    }
	public function sendjsonAction()
    {
      $playerName = 'fighters';
        $dataVal = $this->getPlayerTable()->getPlayerByName($playerName);
        
         /* $data = array(json_encode($dataVal));
        $request = new Request();
        
        $client = new Client('http://localhost/bowhunter/public/admin/recvjson');
         $client
            ->setHeaders([
                'Content-Type' => 'application/json',
            ])
            ->setOptions(['sslverifypeer' => false])
            ->setMethod('POST')
            ->setRawBody(Json::encode($dataVal));
        $client->setEncType(Client::ENC_FORMDATA);
      
       print_r($client);
       exit;*/
 
            $response = $this->getResponse();
            $response->getHeaders()->addHeaderLine( 'Content-Type', 'application/json' );
            $response->setContent(json_encode($dataVal));
            
            return $response;

    }
	
	
	public function logoutAction()	{
		//$this->init();
		//echo 'I am here !'; exit;
		$this->getSessionStorage()->forgetMe();
		$this->getAuthService()->clearIdentity();       
		return $this->redirect()->toRoute('admin', array('action'=>'index'));
		//exit();
		
	}
	
	
	
	


}

