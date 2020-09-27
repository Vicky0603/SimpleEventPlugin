<?php
defined("ABSPATH") or die("Bad Access");
/**
 * Validation class
 *
 * this class contain the methods that deals with data validation
 *
 * when validate user data there is two output
 *  1 - if data passed -> return array with success data
 *  2 - otherwise return error 
 * @since      1.0.0
 */

class validation{
    // Validation Array
    private $data;
    // The Form Data Like $_POST['']
    private $dataValue;
    // The Field Name  -> The Key Of $data Array Like ("username"=>array(),"pass"=>array())
    private $fieldName;
    // The Name That Will Be Using In Case Of Error
    private $errorName;
    // Error
    private $error = array();
    // If The Data Validation Is True Return Array 
    private $dataSuccess = array();
    // result
    private $result = array();
	// validation vars
	private $validationVars;
	// this var for get user rules as string 
	private $rules;
	// this var for get rules as array
	private $rules_Array = array();
	// this var for check if user want return all errors or first error only
	private $continue_on_error;
    /**
     *  __construct
     *
     *  Set Data
     *
     *  @param     (array) Data To Validate
     *  @return    (non)
     *
     */
    public function __construct($data,$continue_on_error = FALSE) {
        $this->data = $data;
        $this->continue_on_error = $continue_on_error;
    }
	
    /**
     * checkStatus
     *
     * Check The Data Rules
     * like required | string | number
     *
     *  @param     $errorName (string)  The Name That Will Be Using In Case Of Error
     *  @param     $val (unknown)  The User Data That Will Be Cheked
     *  @param     $property (string)  The Key That Will Be Used For Check Like "required" "min-lenght"
     *  @return    (Boolean) TRUE if The Data Is Ok , Error Otherwise
     *  @since    1.0.0
     *  @access   private
     *
     */
    private function checkStatus($errorName,$val,$property)
    {
		
        switch(strtolower($property)):
           case "required":
             return ($this->checkNotNull($val))?TRUE:$this->result['error'][] = "The Field Of ".$errorName." is Required";
           break;
           case "number":
             return ($this->checkNumber($val))?TRUE:$this->result['error'][] =  "The Field Of ".$errorName." Must Be Only Numbers";
           break;
           case (preg_match('/(max-length)\[(.*)\]/', $property, $match) == TRUE):
             return ($this->checkMaxLength($val,$match[2]))?TRUE:$this->result['error'][] = "The Max Length of ".$errorName." Is ".$match[2]." ";;
           break;
           case (preg_match('/(min-length)\[(.*)\]/', $property, $match) == TRUE):
             return ($this->checkMinLength($val,$match[2]))?TRUE:$this->result['error'][] = "The Min Length of ".$errorName." Is ".$match[2]." ";;
        break;
		default:
		  return NULL;
        endswitch;
    }
    
	/**
     * Validator
     *
     *  Validate User Data
     *  like required | min-lenth[4] | number
     *  @return    (string) The Error Occurred During Validation
     *  @since    1.0.0
     *  @access   public
     */
	public function validator()
    {
        foreach($this->data as $key=>$value)
		{
			// Check If The User Added The Value Key And errorName To His Array
			if(array_key_exists("value",$value) && array_key_exists("errorName",$value))
			{
				// set The Data Like Tha Value Of $_POST['username']
				  $this->dataValue = strip_tags($value['value']);
				 // desired field Name in Case Of Success
				  $this->fieldName = $key;
				  // desired Error Name in Case Of Error
				  $this->errorName = $value['errorName'];
				 // check if develper set rules
				 if(array_key_exists("rules",$value))
				 {
					 $this->rules = $value['rules'];
					 // set rules as array
					 $this->set_rules_array();
					 foreach($this->rules_Array as $property)
					 {
						 $dataStatus = $this->checkStatus($this->errorName,$this->dataValue,$property);
							// if Error Happend When Check User Data Retrun FALSE
						  if($dataStatus !== TRUE && $dataStatus !== NULL)
							{
							  // if user want stop and return first happend error
							    if($this->continue_on_error == FALSE)
							    {
								    return $this->get_result();
								    break 2; 
							     }
							 // if user add invalid validation attribute 
							}elseif($dataStatus == NULL){
                                die("Error : The Attribute ".$property." Not Found Please Check Validation Class");
                                exit;
							}else{
                              // Set The Data If No Error Happend
				              $this->result["success"][$this->fieldName] = $this->dataValue; 
                          }
				 		}
					// if user not add any rules add data to success array    
				   }else{
				       $this->result["success"][$this->fieldName] = $this->dataValue;
					   continue;
				   }
			 }
			 // If User Did Not Add The Value and errorName To His Array
			 else
			 {
				 $this->result['error'] = "Please Add The value Key And errorName Key To Your ".$key." Array";
				 return $this->get_result();
			 }
		}
        if(array_key_exists("error",$this->result))
		{
			// if there is success data remove it to return error
			if(array_key_exists("success",$this->result))
			{
				unset($this->result['success']);
			}
			return $this->get_result();
		}else{
			return $this->get_result();
		}
    }
    /**
	 *  set user rules
	 *
	 *  explode rules string to array
     *
	 *  @since    1.0.0
     *  @access   private
	 */
	private function set_rules_array()
	{
		if(isset($this->rules) && !empty($this->rules))
		{
			// explode roule
			 $this->rules_Array = explode("|",$this->rules);
			return;
		}
		return NULL;
	}
    
    /**
     *  get_result
     *
     *  get validation result
     *  @param     (none)
     *  @return (array) the result from validation
	 *  @since    1.0.0
     *  @access   public     
     *
     */
    public function get_result()
    {
		if(array_key_exists('error',$this->result) && $this->continue_on_error == FALSE)
		{
			return $this->result['error'][0];
		}else if(array_key_exists('error',$this->result) && $this->continue_on_error == TRUE)
		{
			return $this->result;
		}
        return $this->result;
    }

    /**
     *  checkNumber
     *
     *  check if variable contain only Numbers
     *  @param    $var (Unknown) The Data That Will Be Checked
     *  @return true if The Data Only contain Numbers , false otherwise
     *  @since    1.0.0
     *  @access   private
     */
     private function checkNumber($var)
     {
         $pattern = "/^([\d])+$/";
         return (preg_match($pattern,$var))?TRUE:FALSE;
     }
    
    /**
     *  checkNotNull
     *
     *  check if The variable Is Not Null
     *  @param    $var (Unknown) The Data That Will Be Checked
     *  @return true if The Data Is Not Null  , false otherwise
     *  @since    1.0.0
     *  @access   private
     */
      private function checkNotNull($var)
      {
         return(!empty($var))?TRUE:FALSE;
      }
    
    /**
     *  checkMaxLength
     *
     *  check if The variable <= Max length
     *  @param    $name (Unknown_Type) The Data That Will Be Checked
     *  @param    $maxNumber (Int) The Max Length
     *  @return true if THE $name <= Max length , false otherwise
     *  @since    1.0.0
     *  @access   private
     */
      private function checkMaxLength($name,$maxNumber)
      {
          if($this->checkNumber($maxNumber) == TRUE)
          {
              return (mb_strlen($name, 'UTF-8') <= $maxNumber)?TRUE:FALSE;
          }
          return FALSE;
      }
    
     /**
     *  checkMinLength
     *  check if The variable >= Min length
     *  @param    $name (Unknown_Type) The Data That Will Be Checked
     *  @param    $minNumber (Int) The Min Length
     *  @return true if THE $name >= Min length , false otherwise
     *  @since    1.0.0
     *  @access   private
     */
      private function checkMinLength($name,$minNumber)
      {
          if($this->checkNumber($minNumber) == TRUE)
          {
             return (mb_strlen($name, 'UTF-8') >= $minNumber)?TRUE:FALSE; 
          }
          return FALSE;
      }

}



?>
