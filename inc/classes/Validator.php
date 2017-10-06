<?php

/**
  * A $_POST or $_GET validation class
  *
  * @author Taylor Collins
  * @author Based on/extension of a validation class from the book, "PHP Object Oriented Solutions" by David Powers 2008
  * @version 1.2.0
*/

class Validator
{
	protected $_inputType;
	protected $_submitted;
	protected $_required;
	protected $_filterArgs;
	protected $_filtered;
	protected $_missing;
	protected $_errors;
	protected $_error_fields;
	protected $_booleans;
	protected $_aliases;
	
	public function __construct($required = array(), $inputType = 'post')
	{
		// Make sure that the Filter Functions are available
		if(!function_exists('filter_list')){
			throw new Exception('The Validator class requires Filter Functions in >= PHP 5.2 or PECL.');
		}
		
		// Make sure the required fields array ($required) is actually an array 
		if(!is_null($required) && !is_array($required)){
			throw new Exception('The names of required fields must be an array, even if only one field is required.');
		}
		$this->_required = $required;
		$this->_filterArgs = array();
		$this->_errors = array();
		$this->_error_fields = array();
		$this->_booleans = array();
		$this->_missing = array();
		$this->_aliases = array();
		$this->setInputType($inputType);
	}
	
	/**
	 * Validate Interger
	 */
	public function isInt($fieldName, $min = null, $max = null)
	{
		$this->checkDuplicateFilter($fieldName);
		$this->_filterArgs[$fieldName] = array('filter'=>FILTER_VALIDATE_INT);
		if(is_int($min)){
			$this->_filterArgs[$fieldName]['options']['min_range'] = $min;
		}
		if(is_int($max)){
			$this->_filterArgs[$fieldName]['options']['max_range'] = $max;
		}
	}
	
	/**
	 * Validate Floating-Point Number
	 */
	public function isFloat($fieldName, $decimalPoint = '.', $allowThousandSeparator = true)
	{
		$this->checkDuplicateFilter($fieldName);
		if($decimalPoint != '.' && $decimalPoint != ','){
			throw new Exception('Decimal point must be a comma or period in isFloat().');	
		}
		$this->_filterArgs[$fieldName] = array(
			'filter'		=> FILTER_VALIDATE_FLOAT,
			'options'	=> array('decimal'=>$decimalPoint)
		);
		if($allowThousandSeparator){
			$this->_filterArgs[$fieldName]['flags'] = FILTER_FLAG_ALLOW_THOUSAND;
		}
	}
	
	/**
	 * Validate a Numeric Array
	 */
	public function isNumericArray($fieldName, $allowDecimalFractions = true, $decimalPoint = '.', $allowThousandSeparator = true)
	{
		$this->checkDuplicateFilter($fieldName);
		if($decimalPoint != '.' && $decimalPoint != ','){
			throw new Exception('Decimal point must be a comma or period in isNumericArray().');	
		}
		$this->_filterArgs[$fieldName] = array(
			'filter'		=> FILTER_VALIDATE_FLOAT,
			'flags'		=> FILTER_REQUIRE_ARRAY,
			'options'	=> array('decimal'=>$decimalPoint)
		);
		if($allowDecimalFractions){
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ALLOW_FRACTION;
		}
		if($allowThousandSeparator){
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ALLOW_THOUSAND;
		}
	}
	
	/**
	 * Valid an Email Address
	 */
	public function isEmail($fieldName)
	{
		$this->checkDuplicateFilter($fieldName);
		$this->_filterArgs[$fieldName] = FILTER_VALIDATE_EMAIL;
	}
	
	/**
	 * Validate a Full URL
	 */
	public function isFullURL($fieldName, $queryStringRequired = false)
	{
		$this->checkDuplicateFilter($fieldName);
		$this->_filterArgs[$fieldName] = array(
			'filter'	=> FILTER_VALIDATE_URL,
			'flags'		=> FILTER_FLAG_SCHEME_REQUIRED | FILTER_FLAG_HOST_REQUIRED | FILTER_FLAG_PATH_REQUIRED
		);
		if($queryStringRequired){
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_QUERY_REQUIRED;
		}
	}
	
	/**
	 * Validate a URL
	 */
	public function isURL($fieldName, $queryStringRequired = false)
	{
		$this->checkDuplicateFilter($fieldName);
		$this->_filterArgs[$fieldName]['filter'] = FILTER_VALIDATE_URL;
		if($queryStringRequired){
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_QUERY_REQUIRED;
		}
	}
	
	/**
	 * Validate a Boolean Value
	 */
	public function isBool($fieldName, $nullOnFailure = false)
	{
		$this->checkDuplicateFilter($fieldName);
		$this->_booleans[] = $fieldName;
		$this->_filterArgs[$fieldName]['filter'] = FILTER_VALIDATE_BOOLEAN;
		if($nullOnFailure){
			$this->_filterArgs[$fieldName]['flags'] = FILTER_NULL_ON_FAILURE;
		}
	}
	
	/**
	 * Validate Date
	 */
	public function isValidDate($fieldName, $format='YYYY-MM-DD')
	{
		$this->checkDuplicateFilter($fieldName);
		$this->noFilter($fieldName);
    if(strlen( $this->_submitted[$fieldName] ) >= 8 && strlen( $this->_submitted[$fieldName] ) <= 10){
        $separator_only = str_replace(array('M','D','Y'),'', $format);
        $separator = $separator_only[0];
        if($separator){
            $regexp = str_replace($separator, "\\" . $separator, $format);
            $regexp = str_replace('MM', '(0[1-9]|1[0-2])', $regexp);
            $regexp = str_replace('M', '(0?[1-9]|1[0-2])', $regexp);
            $regexp = str_replace('DD', '(0[1-9]|[1-2][0-9]|3[0-1])', $regexp);
            $regexp = str_replace('D', '(0?[1-9]|[1-2][0-9]|3[0-1])', $regexp);
            $regexp = str_replace('YYYY', '\d{4}', $regexp);
            $regexp = str_replace('YY', '\d{2}', $regexp);
            if($regexp != $this->_submitted[$fieldName] && preg_match('/'.$regexp.'$/', $this->_submitted[$fieldName] )){
                foreach (array_combine(explode($separator, $format), explode($separator, $this->_submitted[$fieldName] )) as $key=>$value) {
                    if ($key == 'YY') $year = '20'.$value;
                    if ($key == 'YYYY') $year = $value;
                    if ($key[0] == 'M') $month = $value;
                    if ($key[0] == 'D') $day = $value;
                }
                if (checkdate($month,$day,$year)) return;
            }
        }
    }
    $this->_errors[] = ucwords(str_replace(array('_', '-'), ' ', $fieldName)) . " is not a valid date. Please ensure it is in the following format: ". $format;
		$this->_error_fields[] = $fieldName;
	}
	
	/**
	 * Validate Image Type
	 *
	 * 1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 13 = SWC
	 */
	public function isValidImageType($fieldName, $types=array('jpeg','gif','png'))
	{
		$this->noFilter($fieldName);
		$sourceFile = $_FILES[$fieldName]['tmp_name'];
		if(file_exists($sourceFile)){
			$size = @getimagesize($sourceFile);
			$fp = fopen($sourceFile, "rb");
			if($size && $fp) {
				$mime = image_type_to_mime_type($size[2]);
				$mime = str_replace('image/', '', $mime);
				$mime = str_replace('application/', '', $mime);
				if(!in_array($mime, $types)){
					$this->_errors[] = ucwords(str_replace(array('_', '-'), ' ', $fieldName)) . " must be one of following types: " . implode(', ', $types) . ".";
					$this->_error_fields[] = $fieldName;
				}
			} else {
				$this->_errors[] = ucwords(str_replace(array('_', '-'), ' ', $fieldName)) . " is not a valid image.";
				$this->_error_fields[] = $fieldName;
			}
		} else if(in_array($fieldName, $this->_required)){
			$this->_errors[] = ucwords(str_replace(array('_', '-'), ' ', $fieldName)) . " is a required field.";
			$this->_error_fields[] = $fieldName;
		}
	}
	
	/**
	 * Validate Document Extension
	 */
	public function isValidFileType($fieldName, $types=array('pdf','doc','docx','txt','rtf'))
	{
		$this->noFilter($fieldName);
		$sourceFile = $_FILES[$fieldName]['tmp_name'];
		if(file_exists($sourceFile)){
			$extension = @strtolower(array_pop(explode('.', $_FILES[$fieldName]['name'])));
			if(!in_array($extension, $types)){
				$this->_errors[] = ucwords(str_replace(array('_', '-'), ' ', $fieldName)) . " must be one of following types: " . implode(', ', $types) . ".";
				$this->_error_fields[] = $fieldName;
			}
		} else if(in_array($fieldName, $this->_required)){
			$this->_errors[] = ucwords(str_replace(array('_', '-'), ' ', $fieldName)) . " is a required field.";
			$this->_error_fields[] = $fieldName;
		}
	}
	
	/**
	 * Validate Against Regular Expression
	 */
	public function matches($fieldName, $pattern)
	{
		$this->checkDuplicateFilter($fieldName);
		$this->_filterArgs[$fieldName] = array(
			'filter'		=> FILTER_VALIDATE_REGEXP,
			'options'	=> array('regexp'=>$pattern)
		);
	}
	
	/**
	 * Remove Tags From a String
	 */
	public function removeTags($fieldName, $encodeAmp = false, $preserveQuotes = false, $encodeLow = false, $encodeHigh = false, $stripLow = false, $stripHigh = false)
	{
		$this->checkDuplicateFilter($fieldName);
		$this->_filterArgs[$fieldName]['filter'] = FILTER_SANITIZE_STRING;
		$this->_filterArgs[$fieldName]['flags'] = 0;
		if($encodeAmp){
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ENCODE_AMP;
		}
		if($preserveQuotes){
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_NO_ENCODE_QUOTES;
		}
		if($encodeLow){
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ENCODE_LOW;
		}
		if($encodeHigh){
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ENCODE_HIGH;
		}
		if($stripLow){
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_STRIP_LOW;
		}
		if($stripHigh){
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_STRIP_HIGH;
		}
	}
	
	/**
	 * Remove Tags From an Array
	 */
	public function removeTagsFromArray($fieldName, $encodeAmp = false, $preserveQuotes = false, $encodeLow = false, $encodeHigh = false, $stripLow = false, $stripHigh = false)
	{
		$this->checkDuplicateFilter($fieldName);
		$this->_filterArgs[$fieldName]['filter'] = FILTER_SANITIZE_ARRAY;
		$this->_filterArgs[$fieldName]['flags'] = 0;
		if($encodeAmp){
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ENCODE_AMP;
		}
		if($preserveQuotes){
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_NO_ENCODE_QUOTES;
		}
		if($encodeLow){
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ENCODE_LOW;
		}
		if($encodeHigh){
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ENCODE_HIGH;
		}
		if($stripLow){
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_STRIP_LOW;
		}
		if($stripHigh){
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_STRIP_HIGH;
		}
	}
	
	/**
	 * Convert Special Characters to Entities in a String
	 */
	public function useEntities($fieldName, $isArray = false, $encodeHigh = false, $stripLow = false, $stripHigh = false)
	{
		$this->checkDuplicateFilter($fieldName);
		$this->_filterArgs[$fieldName]['filter'] = FILTER_SANITIZE_SPECIAL_CHARS;
		$this->_filterArgs[$fieldName]['flags'] = 0;
		if($isArray){
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_REQUIRE_ARRAY;
		}
		if($encodeHigh){
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ENCODE_HIGH;
		}
		if($stripLow){
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_STRIP_LOW;
		}
		if($stripHigh){
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_STRIP_HIGH;
		}
	}
	
	/**
	 * Check the Length of Text
	 */
	public function checkTextLength($fieldName, $min, $max = null)
	{
		// Get the submitted value
		$text = trim($this->_submitted[$fieldName]);
		// Make sure it's a string
		if(!is_string($text)){
			throw new Exception('The checkTextLength() method can be applied only to strings. ' . $fieldName . ' is the wrong data type.');
		}
		// Make sure second argument is a number
		if(!is_numeric($min)){
			throw new Exception('The checkTextLength() method expects a number as the second argument. (field name: ' . $fieldName . ').');	
		}
		// If the string is shorter than the minimum, create error message
		if(strlen($text) < $min){
			// Check whether a valid maximum value has been set
			if(is_numeric($max)){
				$this->_errors[] = ucwords(str_replace(array('_', '-'), ' ', $fieldName)) . " must be between $min and $max characters.";
				$this->_error_fields[] = $fieldName;
			} else {
				$this->_errors[] = ucwords(str_replace(array('_', '-'), ' ', $fieldName)) . " must be a minimum of $min characters.";
				$this->_error_fields[] = $fieldName;
			}
		}
		// If a maximum has been set, and the string is too long.
		if(is_numeric($max) && strlen($text) > $max){
			if($min == 0){
				$this->_errors[] = ucwords(str_replace(array('_', '-'), ' ', $fieldName)) . " must be no more that $max characters.";
				$this->_error_fields[] = $fieldName;
			} else {
				$this->_errors[] = ucwords(str_replace(array('_', '-'), ' ', $fieldName)) . " must be between $min and $max characters.";
				$this->_error_fields[] = $fieldName;
			}
		}
	}
	
	/**
	 * Filter for Input that Requires Special Handling
	 */
	public function noFilter($fieldName, $isArray = false, $encodeAmp = false)
	{
		$this->checkDuplicateFilter($fieldName);
		$this->_filterArgs[$fieldName]['filter'] = FILTER_UNSAFE_RAW;
		$this->_filterArgs[$fieldName]['flags'] = 0;
		if($isArray){
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_REQUIRE_ARRAY;
		}
		if($encodeAmp){
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ENCODE_AMP;
		}
	}
	
	/**
	 * Add an alias (substitude field name for a field
	 */
	public function addAlias($fieldName, $alias)
	{
		if(!$this->_aliases[$fieldName]){
			$this->_aliases[$fieldName] = $alias;
		}
	}
	
	/**
	 * Validate the Input
	 */
	public function validateInput()
	{
		// Make sure all required fields are not empty
		$this->checkRequired();
		// Initialize an array for required itmes that haven't been validated
		$notFiltered = array();
		// Get the names of all fields that have been validated
		$tested = array_keys($this->_filterArgs);
		// Loop through the required fields
		// Add any missing ones to the $notFiltered array
		foreach($this->_required as $field){
			if(!in_array($field, $tested)){
				$notFiltered[] = $field;
			}
		}
		// If any items have been added to the $notFiltered array, it means a
		// required item hasn't been validated, so throw an exception
		if($notFiltered){
			throw new Exception('No filter has been set for the following required items: ' . implode(', ', $notFiltered));
		}
		
		// Apply validation test using filter_input_array()
		$this->_filtered = filter_input_array($this->_inputType, $this->_filterArgs);
		
		// Now find which items failed validation
		foreach($this->_filtered as $key => $value){
			// Skip items that used isBool() method
			// Also skip any that are missed or not required		
			if(in_array($key, $this->_booleans) || in_array($key, $this->_missing) || !in_array($key, $this->_required)){
				continue;
			}
			// If the filtered value is false, it failed validation, so add it to the $_errors array
			else if($value === false){
				$label = ($this->_aliases[$key]) ? $this->_aliases[$key] : ucwords(str_replace(array('_', '-'), ' ', $key));
				$this->_errors[$key] = $label . ': invalid data supplied.';
				$this->_error_fields[] = $key;
			}
		}
		
		// Return the validated input as an array
		return $this->_filtered;
	}
	
	/**
	 * Get Missing Fields
	 */
	public function getMissing()
	{
		return $this->_missing;
	}
	
	/**
	 * Get Filtered Results
	 */
	public function getFiltered()
	{
		return $this->_filtered;
	}
	
	/**
	 * Get Any Errors
	 */
	public function getErrors()
	{
		return $this->_errors;
	}
	
	/**
	 * Get Any Field Names that Have Error
	 */
	public function getErrorFields()
	{
		return $this->_error_fields;
	}
	
	/**
	 * Protected Methods
	 */
	protected function setInputType($type)
	{
		switch(strtolower($type)){
		case 'post' :
			$this->_inputType = INPUT_POST;
			$this->_submitted = $_POST;
			break;
		case 'get' :
			$this->_inputType = INPUT_GET;
			$this->_submitted = $_GET;
			break;
		default : 
			throw new Exception('Invalid input type. Valid types are "post" and "get".');
		}
	}
	
	protected function checkRequired()
	{
		$OK = array();
		foreach($this->_submitted as $name => $value){
			$value = is_array($value) ? $value : trim($value);
			if(!empty($value)){
				$OK[] = $name;
			} else {
				if(in_array($name, $this->_required)){
					$label = ($this->_aliases[$name]) ? $this->_aliases[$name] : ucwords(str_replace(array('_', '-'), ' ', $name));
					$this->_errors[] = $label . ' is a required field.';
					$this->_error_fields[] = $name;
				}
			}
		}
		$this->_missing = array_diff($this->_required, $OK);
	}
	
	protected function checkDuplicateFilter($fieldName)
	{
		if(isset($this->_filterArgs[$fieldName])){
			throw new Exception('A filter has already been set for the following field: ' . $fieldName);
		}
	}
}

?>