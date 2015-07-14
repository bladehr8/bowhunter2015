<?php
namespace Admin\Model;

use Zend\Http\PhpEnvironment\Request;
use Zend\Filter;



use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\Input;



use Zend\Validator;


use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class BankReport implements InputFilterAwareInterface
{
    public $id;
    public $player_id;
	public $bank_id;	
	public $pay_amount;
	public $product_name;
	public $transaction_id;
	public $gold_coins;
	public $bucks;
	public $game_currency;
	public $created_on; 
	public $status;

	protected $inputFilter;     
	
	//protected $inputFilter;  

    public function exchangeArray($data)
    {
        $this->id     				= (isset($data['id'])) ? $data['id'] : null;
		$this->player_id 			= (isset($data['player_id'])) ? $data['player_id'] : null;	
		$this->bank_id 				= (isset($data['bank_id'])) ? $data['bank_id'] : null;		
		$this->pay_amount 			= (isset($data['pay_amount'])) ? $data['pay_amount'] : null;	
		$this->product_name 		= (isset($data['product_name'])) ? $data['product_name'] : null;	
		$this->transaction_id 		= (isset($data['transaction_id'])) ? $data['transaction_id'] : null;	
		$this->game_currency 		= (isset($data['game_currency'])) ? $data['game_currency'] : null;	
		$this->bucks 				= (isset($data['bucks'])) ? $data['bucks'] : null;	
		$this->gold_coins 			= (isset($data['gold_coins'])) ? $data['gold_coins'] : null;	
		$this->created_on 			= (isset($data['created_on'])) ? $data['created_on'] : null;		
		$this->status 				= (isset($data['status'])) ? $data['status'] : null;	

    }
	
	public function getArrayCopy()
    {
        return get_object_vars($this);
    }

	
	
	
	
	
	
	
	
	
	// Add content to this method:
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
	/*
	public function addInputFilter()
    {
        $inputFilter = new InputFilter\InputFilter();

        // File Input
        $fileInput = new InputFilter\FileInput('image-1');
        $fileInput->setRequired(true);
        $fileInput->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'    => dirname(__DIR__).'/assets/avatar.png',
                'randomize' => true,
            )
        );
        $inputFilter->add($fileInput);

        $this->setInputFilter($inputFilter);
    }
	
	*/
	
	
	
	
	
	
	
	
	
	

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
		
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();
			

			

            $inputFilter->add($factory->createInput(array(
                'name'     => 'duration',
                'required' => true,   
				'filters'  => array(
										array('name' => 'Int'),
									),
			    'validators' => array(
										  array(	
											  'name' => 'Between',
											  'options' => array(
																	'min' => 1,
																	'max' => 1000,
																),
												),
									),	
				
				
				
				
				

				
            )));
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'image_1',
                'required' => true,  

            )));
			

		$inputFilter->add($factory->createInput(array(
                'name'     => 'content',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 5,
                            'max'      => 500,
                        ),
                    ),
                ),
            )));
			
			

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
	
	
	
}