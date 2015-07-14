<?php
namespace Admin\Form;


use Zend\Form\Form;
use Zend\Form\Element;
use Admin\Model\MissionsTable;    

class MissionsForm extends Form
{
	

   public function __construct($myformdata = null)
    {
        // we want to ignore the name passed
        parent::__construct('missions');
        $this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-missions');
		 //$tournament = new TournamentTable();
		
		//$mydata=$tournament->getTournamentTable->fetchAll(2);
		
		//$regionList= array();
		//$i=0;
			
	$this->add(array(
			 'type' => 'Zend\Form\Element\Hidden',
			 'name' => 'id',
			
		 ));	
	
	$this->add(array(
            'name' => 'name',
			'options' => array(
             'label' => ''
			),
			  'attributes' => array(
                'type'  => 'text',
				'class' => 'form-control',
				'placeholder' => "Mission Name",
				'required'  	=> 'required',
            ),
    
        ));
		
		
		$this->add(array(
            'name' => 'minimum_points_required',
			'options' => array(
             'label' => ''
			),
			  'attributes' => array(
                'type'  => 'text',
				'class' => 'form-control',
				'placeholder' => "Minimum Points",
				'required'  	=> 'required',
            ),
    
        ));	
		
		$this->add(array(
            'name' => 'duration',
			'options' => array(
             'label' => ''
			),
			  'attributes' => array(
                'type'  => 'text',
				'class' => 'form-control',
				'placeholder' => "Duration - Number of Days",
				'required'  	=> 'required',
            ),
    
        ));	
		
		
			$this->add(array(
            'name' => 'recommended_gear',
			'options' => array(
             'label' => ''
			),
			  'attributes' => array(
                'type'  => 'text',
				'class' => 'form-control',
				'placeholder' => "Enter recommended gears",
				'required'  	=> 'required',
            ),
    
        ));		
		
			$this->add(array(
            'name' => 'difficulty_level',
			'options' => array(
             'label' => ''
			),
			  'attributes' => array(
                'type'  => 'text',
				'class' => 'form-control',
				'placeholder' => "Difficulty level - Integer value",
				'required'  	=> 'required',
            ),
    
        ));			
		
		
		
		
			$this->add(array(
			'type' => 'Zend\Form\Element\Select',
            'name' => 'no_more_hunts',
			  'attributes' => array(                
				'class' => 'form-control',				
				'required'  	=> 'required',
            ),
			             'options' => array(
							'label' => '',
                            'value_options' => array('won' => 'Won' , 'tourney-ended' => 'Tourney Ended' , 'never'=>'Never'),
             )
    
        ));			
				
		
	$this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'kill_for_success',
			 'attributes' => array(
				'required'  => 'required',
				'class'  => 'form-control',
            ),
             'options' => array(
							'label' => '',
                            'value_options' => array('1' => 'Success' , '2' => 'Failure'),
             )
     ));
	 
		
		$this->add(array(
            'name' => 'premium_currency_rewarded',
			'options' => array(
             'label' => ''
			),
			  'attributes' => array(
                'type'  => 'text',
				'class' => 'form-control',
				'placeholder' => "premium_currency_rewarded - Integer Value ",
				'required'  	=> 'required',
            ),
    
        ));	
		
		
			$this->add(array(
            'name' => 'normal_currency_rewarded',
			'options' => array(
             'label' => ''
			),
			  'attributes' => array(
                'type'  => 'text',
				'class' => 'form-control',
				'placeholder' => "normal_currency_rewarded - Integer Value ",
				'required'  	=> 'required',
            ),
    
        ));		
		
		$this->add(array(
            'name' => 'xp_rewarded',
			'options' => array(
             'label' => ''
			),
			  'attributes' => array(
                'type'  => 'text',
				'class' => 'form-control',
				'placeholder' => "XP Rewarded - Integer Value ",
				'required'  	=> 'required',
            ),
    
        ));	
		
		
	/*	
		
	  $this->add(array(
			 'type' => 'Zend\Form\Element\Select',
			 'name' => 'hunt_suggestion_id',
			 'attributes' => array(					
			 'required'  => 'required',
				'class' => 'form-control',	
					
		),

			 'options' => array(
					 'label' => '',
					 'value_options' => array(),
			 )
	 ));
	*/

	  $this->add(array(
			 'type' => 'Zend\Form\Element\Select',
			 'name' => 'shorthand_target_type_id',
			 'attributes' => array(					
			 'required'  => 'required',
				'class' => 'form-control',	
						
		),

			 'options' => array(
					 'label' => '',
					 'value_options' => array(),
			 )
	 ));	
	
	

	  $this->add(array(
			 'type' => 'Zend\Form\Element\Select',
			 'name' => 'hunt_types_id',
			 'attributes' => array(					
			 'required'  => 'required',
				'class' => 'form-control',	
						
		),

			 'options' => array(
					 'label' => '',
					 'value_options' => array(),
			 )
	 ));		
	
	
	  $this->add(array(
			 'type' => 'Zend\Form\Element\Select',
			 'name' => 'player_position_id',
			 'attributes' => array(					
			 'required'  => 'required',
				'class' => 'form-control',	
							
		),

			 'options' => array(
					 'label' => '',
					 'value_options' => array(),
			 )
	 ));	
	

	  $this->add(array(
			 'type' => 'Zend\Form\Element\Select',
			 'name' => 'region_id',
			 'attributes' => array(					
			 'required'  => 'required',
				'class' => 'form-control',	
						
		),

			 'options' => array(
					 'label' => '',
					 'value_options' => array(),
			 )
	 ));



	  $this->add(array(
			 'type' => 'Zend\Form\Element\Select',
			 'name' => 'light_conditions_id',
			 'attributes' => array(					
			 'required'  => 'required',
				'class' => 'form-control',	
						
		),

			 'options' => array(
					 'label' => '',
					 'value_options' => array(),
			 )
	 ));


	  $this->add(array(
			 'type' => 'Zend\Form\Element\Select',
			 'name' => 'kill_type_id',
			 'attributes' => array(					
			 'required'  => 'required',
				'class' => 'form-control',	
					
		),

			 'options' => array(
					 'label' => '',
					 'value_options' => array(),
			 )
	 ));


	  $this->add(array(
			 'type' => 'Zend\Form\Element\Select',
			 'name' => 'mission_objectives_id',
			 'attributes' => array(					
			 'required'  => 'required',
				'class' => 'form-control',	
						
		),

			 'options' => array(
					 'label' => '',
					 'value_options' => array(),
			 )
	 ));


/*
	  $this->add(array(
			 'type' => 'Zend\Form\Element\Select',
			 'name' => 'deer_information_id',
			 'attributes' => array(					
			 'required'  => 'required',
				'class' => 'form-control',	
						
		),

			 'options' => array(
					 'label' => '',
					 'value_options' => array(),
			 )
	 ));

*/
	
	
	
	
	
	
	
	/*	
		
		
	$this->add(array(
     'type' => 'text',
     'name' => 'startDate',
     'options' => array(
             'label' => 'Start Date'
     ),
     'attributes' => array(
            'id' => 'datetimepicker1',
			'required'  	=> 'required',
			
     )
 ));
 
 
$this->add(array(
     'type' => 'text',
     'name' => 'endDate',
     'options' => array(
             'label' => 'End Date'
     ),
     'attributes' => array(
            'id' => 'datetimepicker2',
			'required'  	=> 'required',
			
     )
 ));
	 
 
  $this->add(array(
			 'type' => 'Zend\Form\Element\Select',
			 'name' => 'tournamentType',
			 'attributes' => array(					
			 'required'  => 'required',	
				'class' => 'form-control',						 
		),

			 'options' => array(
					 'label' => 'Tournament Type',
					 'value_options' => array(
							 'Daily' => 'Daily',
							 'Weekly' => 'Weekly',
							 'Monthly' => 'Monthly',
							 
					 ),
			 )
	 ));
	 
		$this->add(array(
			 'type' => 'Zend\Form\Element\Number',
			 'name' => 'minPointForEntry',
			 'options' => array(
					 'label' => 'Minium Entry Point'
			 ),
			 'attributes' => array(
					 'min' => '1',
					
					
			 )
		 ));	 
	 
	 
	 
	$this->add(array(
            'name' => 'number_0f_missions',
			'options' => array(
             'label' => ''
			),
			  'attributes' => array(
                'type'  => 'text',
				'class' => 'form-control',
				'placeholder' => "How many missions want to create ?",
				'required'  	=> 'required',
            ),
    
        ));	 
		
		
	*/	
		
		
	 
	 
	 
 	
		
		$this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
				'class'  => 'btn btn-bricky custom-form-control',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
       
    }
}