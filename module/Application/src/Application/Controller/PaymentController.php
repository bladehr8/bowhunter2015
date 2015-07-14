<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;
use Application\Model\Payment;  

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

        // <-- Add this import



use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;




use Zend\View\Model\JsonModel;



class PaymentController extends AbstractActionController
{
	protected $bankTable;
	protected $paymentTable;

	public function getBankTable()
    {
        if (!$this->bankTable) {
            $sm = $this->getServiceLocator();
            $this->bankTable = $sm->get('Admin\Model\BankTable');
        }
        return $this->bankTable;
    }
	
	public function getPaymentTable()
    {
        if (!$this->paymentTable) {
            $sm = $this->getServiceLocator();
            $this->paymentTable = $sm->get('Application\Model\PaymentTable');
        }
        return $this->paymentTable;
    }







    public function indexAction()
    {
		# get post value form android apps
		
		$price 		= $this->getRequest()->getPost('price');
		$bankId 	= (int) $this->getRequest()->getPost('bankId');
		$playerId 	= (int) $this->getRequest()->getPost('playerId');
		
		
		// Testing Data by putting fixed value
		//$price 		= 1.12;
		//$bankId 	= 2;
		//$playerId 	= 1;
		 if (!$bankId) {
			throw new \Exception('BankId does not exist');
		}
		
		$banks = $this->getBankTable()->getBank($bankId);		
		
		$result = new ViewModel();
		$result->setTerminal(true);
		$result->setVariables(array('playerId' => $playerId , 'bankId' => $bankId , 'price' => $price , 'bank' => $banks ));
		return $result;   
    }
	
	
	public function paymentnotifyAction()
	{

			// STEP 1: read POST data
		// Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
		// Instead, read raw POST data from the input stream.
		$raw_post_data = file_get_contents('php://input');
		$raw_post_array = explode('&', $raw_post_data);
		$myPost = array();
		foreach ($raw_post_array as $keyval) {
		$keyval = explode ('=', $keyval);
		if (count($keyval) == 2)
		$myPost[$keyval[0]] = urldecode($keyval[1]);
		}
		// read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
		$req = 'cmd=_notify-validate';
		if(function_exists('get_magic_quotes_gpc')) {
		$get_magic_quotes_exists = true;
		}
		foreach ($myPost as $key => $value) {
		if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
		$value = urlencode(stripslashes($value));
		} else {
		$value = urlencode($value);
		}
		$req .= "&$key=$value";
		}
		// Step 2: POST IPN data back to PayPal to validate
		$ch = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
		// In wamp-like environments that do not come bundled with root authority certificates,
		// please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set
		// the directory path of the certificate as shown below:
		// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
		if( !($res = curl_exec($ch)) ) {
		// error_log("Got " . curl_error($ch) . " when processing IPN data");
		curl_close($ch);
		exit;
		}
		curl_close($ch); 
			
			if (strcmp ($res, "VERIFIED") == 0) {
		// The IPN is verified, process it:
		// check whether the payment_status is Completed
		// check that txn_id has not been previously processed
		// check that receiver_email is your Primary PayPal email
		// check that payment_amount/payment_currency are correct
		// process the notification
		// assign posted variables to local variables
		$custom = $_POST['custom'];
		$customArray = explode('#',$custom );
		$playerId = $customArray[0];
		$bankId = $customArray[1];
		$item_name = $_POST['item_name'];
		$item_number = $_POST['item_number'];
		$payment_status = $_POST['payment_status'];
		$payment_amount = $_POST['mc_gross'];
		$payment_currency = $_POST['mc_currency'];
		$txn_id = $_POST['txn_id'];
		$receiver_email = $_POST['receiver_email'];
		$payer_email = $_POST['payer_email'];
		// IPN message values depend upon the type of notification sent.
		// To loop through the &_POST array and print the NV pairs to the screen:
		$finalString = '';
		foreach($_POST as $key => $value) {
		$finalString .= $key." = ". $value."#";
		}
				$payment = new Payment();
				$dataArr['player_id']		=	$playerId;
				$dataArr['bank_id']			=	$bankId;
				$dataArr['pay_amount']		=	$payment_amount;
				$dataArr['game_currency'] 	=  	1000 ;
				$dataArr['created_on'] 		=  	time() ;
				$dataArr['status']			= 	$payment_status;	
				$dataArr['post_data']			= 	$finalString;							
				$payment->exchangeArray($dataArr);
				$this->getPaymentTable()->savePayment($payment);	
		
		
		
		
		
		
		
		} else if (strcmp ($res, "INVALID") == 0) {
		// IPN invalid, log for manual investigation
			//echo "The ponse from IPN was: <b>" .$res ."</b>";
				$payment = new Payment();
				$dataArr['player_id']		=	$playerId;
				$dataArr['bank_id']			=	$bankId;
				$dataArr['pay_amount']		=	$payment_amount;
				$dataArr['game_currency'] 	=  	10 ;
				$dataArr['created_on'] 		=  	time() ;
				$dataArr['status']			= 	$res;	
				$dataArr['post_data']			= 	"The response from IPN was: <b>" .$res ."</b>";				
				$payment->exchangeArray($dataArr);
			
				$this->getPaymentTable()->savePayment($payment);	
			
			
			
		} 
	

			exit;
			$result = new ViewModel();
			$result->setTerminal(true);
	}
	
	public function paymentreturnAction()
	{
		$msg  =  'Payment Success and Returned !';
		$result = new ViewModel();
		$result->setTerminal(true);
		$result->setVariables(array('ack' => $msg  ));
		return $result;
		//$data = array();
		//$data['ack'] = 'Success';
		//echo json_encode($data);
		//exit();
				
	}
	
	public function paymentcancelAction()
	{
		$msg  =  'Payment Cancelled and Returned !';		
		//$result = new ViewModel();
		//$result->setTerminal(true);
		//$result->setVariables(array('ack' => $msg  ));
		//return $result;
		$data = array();
		$data['ack'] = 'Cancelled';
		echo json_encode($data);
		exit();	
	}
	
	
	
	
}
