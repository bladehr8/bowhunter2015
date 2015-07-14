<?php
namespace Application\Model;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class Payment implements InputFilterAwareInterface
{
    public $id;
    public $player_id;
	public $bank_id;
	public $pay_amount;
	public $game_currency;
	public $created_on;
	public $status;
	public $post_data;
	protected $inputFilter;     
	
	//protected $inputFilter;  

    public function exchangeArray($data)
    {
        $this->id     			= (isset($data['id'])) ? $data['id'] : null;
		$this->player_id 		= (isset($data['player_id'])) ? $data['player_id'] : null;
		$this->bank_id			= (isset($data['bank_id'])) ? $data['bank_id'] : null;
		$this->pay_amount 		= (isset($data['pay_amount'])) ? $data['pay_amount'] : null;
		$this->game_currency 	= (isset($data['game_currency'])) ? $data['game_currency'] : null;		
		$this->created_on 		= (isset($data['created_on'])) ? $data['created_on'] : null;
      	$this->status  			= (isset($data['status'])) ? $data['status'] : null;
		$this->post_data  		= (isset($data['post_data'])) ? $data['post_data'] : null;
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

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

      



            $inputFilter->add($factory->createInput(array(
                'name'     => 'player_id',
                'required' => true,     
            )));
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'bank_id',
                'required' => true,     
            )));
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'pay_amount',
                'required' => true,     
            )));
			
			
			
			
			
			
			

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
	
	
	
}