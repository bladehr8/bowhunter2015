<?php
namespace Admin\Form;


use Zend\Form\Form;
use Zend\Form\Element;
use Admin\Model\TournamentTable;    

class TournamentForm extends Form
{
	

   public function __construct($myformdata = null)
    {
        // we want to ignore the name passed
        parent::__construct('tournament');
        $this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-tournament');
		 //$tournament = new TournamentTable();
		
		//$mydata=$tournament->getTournamentTable->fetchAll(2);
		
		$regionList= array();
		$i=0;
			
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
				'placeholder' => "Tournament Name",
				'required'  	=> 'required',
            ),
    
        ));
		
		/*
		$this->add(array(
            'name' => 'duration',
			'options' => array(
             'label' => ''
			),
			  'attributes' => array(
                'type'  => 'text',
				'class' => 'form-control',
				'placeholder' => "Tournament Duration - (In Days)",
				'required'  	=> 'required',
            ),
    
        ));	
		*/
			$this->add(array(
			 'type' => 'Zend\Form\Element\Number',
			 'name' => 'duration',
			 'options' => array(
					 'label' => ''
			 ),
			 'attributes' => array(
					 'min' => '1',
					 'class' => 'form-control bfh-number',
					
					
			 )
		 ));	
		
		
		
		
		
		
		
		
		
		
		
	 /*
	 $this->add(array(
			 'type' => 'Zend\Form\Element\Select',
			 'name' => 'regionType',
			 'attributes' => array(					
			 'required'  => 'required',
				'class' => 'form-control',	
				'required'  	=> 'required',				
		),

			 'options' => array(
					 'label' => '',
					 'value_options' => $myformdata['regionList'],
			 )
	 ));
	
*/
		
		
		
	$this->add(array(
     'type' => 'text',
     'name' => 'start_date',
     'options' => array(
             'label' => ''
     ),
     'attributes' => array(
            'id' => 'datetimepicker1',
			'required'  	=> 'required',
			'class' => 'form-control date-picker',
			
     )
 ));
 
 
$this->add(array(
     'type' => 'text',
     'name' => 'end_date',
     'options' => array(
             'label' => ''
     ),
     'attributes' => array(
            'id' => 'datetimepicker2',
			'required'  	=> 'required',
			'class' => 'form-control date-picker',
			
     )
 ));
	 
 
  $this->add(array(
			 'type' => 'Zend\Form\Element\Select',
			 'name' => 'tournament_type',
			 'attributes' => array(					
			 'required'  => 'required',	
				'class' => 'form-control',						 
		),

			 'options' => array(
					 'label' => '',
					 'value_options' => array(
							 'Daily' => 'Daily',
							 'Weekly' => 'Weekly',
							 'Monthly' => 'Monthly',
							 
					 ),
			 )
	 ));
	 
		$this->add(array(
			 'type' => 'Zend\Form\Element\Number',
			 'name' => 'entry_points',
			 'options' => array(
					 'label' => ''
			 ),
			 'attributes' => array(
					 'min' => '1',
					 'class' => 'form-control',
					
					
			 )
		 ));	 
	 
	 
	 /*
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