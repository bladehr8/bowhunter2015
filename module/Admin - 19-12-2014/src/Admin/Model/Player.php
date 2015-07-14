<?php
namespace Admin\Model;

//use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
//use Zend\InputFilter\InputFilter;                 // <-- Add this import
//use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
//use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class Player 
{
    public $id;
    public $name;
	public $gender;
	public $socialType;
    public $socialId;
	public $xpPoint;
	public $totalNormalCurrency;
	public $totalPremiumCurrency;
	public $totalEnergy;
	public $challengeAmoutEscrow;
	public $lastLevelName;
	public $active;
	public $dressPickId;
	public $heirColorId;
	public $skinColorId;
	public $createOn;
	public $modifyOn;
	
	//protected $inputFilter;  

    public function exchangeArray($data)
    {
        $this->id     		= (isset($data['id'])) ? $data['id'] : null;
		$this->name 		= (isset($data['name'])) ? $data['name'] : null;
        $this->gender  		= (isset($data['gender'])) ? $data['gender'] : null;
		$this->socialType  		= (isset($data['socialType'])) ? $data['socialType'] : null;
		$this->socialId  		= (isset($data['socialId'])) ? $data['socialId'] : null;
		$this->xpPoint  		= (isset($data['xpPoint'])) ? $data['xpPoint'] : null;
		$this->totalNormalCurrency  = (isset($data['totalNormalCurrency'])) ? $data['totalNormalCurrency'] : null;
		$this->totalPremiumCurrency  		= (isset($data['totalPremiumCurrency'])) ? $data['totalPremiumCurrency'] : null;
		$this->totalEnergy  		= (isset($data['totalEnergy'])) ? $data['totalEnergy'] : null;
		$this->challengeAmoutEscrow  		= (isset($data['challengeAmoutEscrow'])) ? $data['challengeAmoutEscrow'] : null;
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
                'name'     => 'email',
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
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'password',
                'required' => true,     
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
	
	
	
}