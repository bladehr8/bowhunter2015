<?php
namespace Admin\Model;

//use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
//use Zend\InputFilter\InputFilter;                 // <-- Add this import
//use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
//use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class PlayerLevelStatus 
{
    public $id;
    public $user_id;
	public $levelwin;
	public $region;
	public $hunt_type;
    public $progression_hunt_num;
	
	public $cash_hunt_num;
	public $invitational_hunt_num;
	public $premium_currency_won;
	public $normal_currency_won;
	public $xp_earned;
	public $largest_kill;
	
	public $longest_kill;
	
	public $valid_kills;
	public $arrows_fired;
	public $arrows_broken;
	
	public $created_on;
	public $active;
	
	//protected $inputFilter;  

    public function exchangeArray($data)
    {
        $this->id     					= (isset($data['id'])) ? $data['id'] : null;
		$this->user_id 					= (isset($data['user_id'])) ? $data['user_id'] : null;
		$this->levelwin 				= (isset($data['levelwin'])) ? $data['levelwin'] : null;
        $this->region  					= (isset($data['region'])) ? $data['region'] : null;
		$this->hunt_type  				= (isset($data['hunt_type'])) ? $data['hunt_type'] : null;
		$this->progression_hunt_num  	= (isset($data['progression_hunt_num'])) ? $data['progression_hunt_num'] : null;
		
		$this->cash_hunt_num  			= (isset($data['cash_hunt_num'])) ? $data['cash_hunt_num'] : null;
		$this->invitational_hunt_num  	= (isset($data['invitational_hunt_num'])) ? $data['invitational_hunt_num'] : null;
		$this->premium_currency_won  	= (isset($data['premium_currency_won'])) ? $data['premium_currency_won'] : null;
		$this->normal_currency_won  	= (isset($data['normal_currency_won'])) ? $data['normal_currency_won'] : null;
		$this->xp_earned  				= (isset($data['xp_earned'])) ? $data['xp_earned'] : null;
		
		$this->largest_kill  			= (isset($data['largest_kill'])) ? $data['largest_kill'] : null;
		$this->longest_kill  			= (isset($data['longest_kill'])) ? $data['longest_kill'] : null;
		$this->valid_kills  			= (isset($data['valid_kills'])) ? $data['valid_kills'] : null;
		$this->arrows_fired  			= (isset($data['arrows_fired'])) ? $data['arrows_fired'] : null;
		$this->arrows_broken  			= (isset($data['arrows_broken'])) ? $data['arrows_broken'] : null;		
		
		$this->created_on  				= (isset($data['created_on'])) ? $data['created_on'] : null;		
		$this->active  					= (isset($data['active'])) ? $data['active'] : null;
		
		
    }	
	
}