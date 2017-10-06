<?php

/**
  * A class for manipulating an image 
  *
  * Requires $file_paths and $url_paths arrays, and HOME_ROOT constant
  *
  * @author Taylor Collins
  * @copyright Taylor Collins 2010
  * @version 1.0.1
*/

class Image
{
	
	protected $_image;
	protected $_temp;
	protected $_mode; // crop, fit, fit-x, fit-y
	protected $_path;
	protected $_url;
	protected $_width;
	protected $_height;
	protected $_quality;
	protected $_filename;
	protected $_image_info;
	protected $_errors;
	
	/** 
	  * Constructor: resize image according to supplied parameters
	*/
	public function __construct($filename, $width, $height, $mode, $path, $quality=85)
	{
		if(!function_exists('gd_info')){
			throw new Exception('GD Library required in class Image.');
		}
		ini_set("memory_limit", "512M");
	
		global $file_paths;
		global $url_paths;
		
		$this->_filename = $filename;
		$this->_mode = $mode;
		$this->_url = $url_paths[$path];
		$this->_path = $file_paths[$path];
		$this->_width = $width;
		$this->_height = $height;
		$this->_quality = $quality;
		$this->_errors = array();
		
		$sourceFile = $this->_path.$this->_filename;
		
		// Make sure source file exists
		if(!file_exists($sourceFile) || is_dir($sourceFile)){
			$_errors[] = 'File not found or is a directory.';
			$this->errorHandler();
			return;
		}
			
		// File already there so don't bother creating it
		if(file_exists($this->getPath(true))) return;
		
		// Make sure file type is supported
		if(file_exists($sourceFile)){
			$this->_image_info = @getimagesize($sourceFile);
			
			switch ($this->_image_info['mime']) {
				case 'image/gif':
					if (imagetypes() && IMG_GIF) {
						$this->_image = @imageCreateFromGIF($sourceFile);
					} 
					else {
						$_errors[] = 'GIF images are not supported';
						$this->errorHandler();
					}
					break;
				case 'image/jpeg':
					if (imagetypes() && IMG_JPG) {
						$this->_image = @imageCreateFromJPEG($sourceFile);
					} 
					else {
						$_errors[] = 'JPEG images are not supported';
						$this->errorHandler();
					}
					break;
				case 'image/jpg':
					if (imagetypes() && IMG_JPG) {
						$this->_image = @imageCreateFromJPEG($sourceFile);
					} 
					else {
						$_errors[] = 'JPG images are not supported';
						$this->errorHandler();
					}
					break;
				case 'image/png':
					if (imagetypes() && IMG_PNG) {
						$this->_image = @imageCreateFromPNG($sourceFile);
					} 
					else {
						$_errors[] = 'PNG images are not supported';
						$this->errorHandler();
					}
					break;
				default:
					$_errors[] = $this->_image_info['mime'].' images are not supported';
					$this->errorHandler();
					break;
			}
			
			$this->resize();
			$this->save();
			
		} else {
			$_errors[] = 'File does not exist';
			$this->errorHandler();
		}
	}
	
	/** 
	  * Get full image path including filename
	*/
	public function getPath($fromRoot=false)
	{
		return $this->getFolder($fromRoot)."/".$this->_filename;
	}
	
	/** 
	  * Get array of errors
	*/
	public function getErrors()
	{
		return $this->_errors;	
	}
	
	/** 
	  * Resize an image using the provided mode and dimensions
	*/
	protected function resize()
	{
		$width = $this->_width;
		$height = $this->_height;
		$orig_width = @imagesx($this->_image);
		$orig_height = @imagesy($this->_image);
		
		// Determine new image dimensions
		if($this->_mode === "crop"){ // Crop image
			
			$max_width = $width;
			$max_height = $height;
		
			$x_ratio = @($max_width / $orig_width);
			$y_ratio = @($max_height / $orig_height);
			
			if($orig_width > $orig_height){ // original is wide
				$height = $max_height;
				$width = ceil($y_ratio * $orig_width);
				$crop_height = $max_height;
				$crop_width = $max_width;
				
			} elseif ($orig_height > $orig_width){ // original is tall
				$width = $max_width;
				$height = ceil($x_ratio * $orig_height);
				$crop_height = $max_height;
				$crop_width = $max_width;
				
			} else { // original is square
				$this->_mode = "fit";
				
				$crop_height = $height;
				$crop_width = $width;
			}
			
		} elseif ($this->_mode === "fit"){ // Fits the image according to aspect ratio to within max height and width
			$max_width = $width;
			$max_height = $height;
		
			$x_ratio = @($max_width / $orig_width);
			$y_ratio = @($max_height / $orig_height);
			
			if( ($orig_width <= $max_width) && ($orig_height <= $max_height) ){  // image is smaller than max height and width so don't resize
				$tn_width = $orig_width;
				$tn_height = $orig_height;
			} elseif (($x_ratio * $orig_height) < $max_height){ // wider rather than taller
				$tn_height = ceil($x_ratio * $orig_height);
				$tn_width = $max_width;
			} else { // taller rather than wider
				$tn_width = ceil($y_ratio * $orig_width);
				$tn_height = $max_height;
			}
			
			$width = $tn_width;
			$height = $tn_height;
			
		} elseif ($this->_mode === "fit-x"){ // sets the width to the max width and the height according to aspect ratio (will stretch if too small)
			$height = @round($orig_height * $width / $orig_width);
			if($orig_height <= $height){ // don't stretch if smaller
				$width = $orig_width;
				$height = $orig_height;
			}
			
		} elseif($this->_mode === "fit-y"){ // sets the height to the max height and the width according to aspect ratio (will stretch if too small)
			$width = @round($orig_width * $height / $orig_height);
			if($orig_width <= $width){ // don't stretch if smaller
				$width = $orig_width;
				$height = $orig_height;
			}
		} else {
			throw new Exception('Invalid mode.');
		}
		
		
		$this->_temp = @imageCreateTrueColor($width, $height);
		@imageCopyResampled($this->_temp, $this->_image, 0, 0, 0, 0, $width, $height, $orig_width, $orig_height);
		$this->sync();
		
		if($this->_mode === "crop"){
			$orig_width = @imagesx($this->_image);
			$orig_height = @imagesy($this->_image);
			$this->_temp = @imageCreateTrueColor($crop_width, $crop_height);
			@imageCopyResampled($this->_temp, $this->_image, 0, 0, 0, 0, $crop_width, $crop_height, $crop_width, $crop_height);
			$this->sync();
		}
	}
	
	/**
	  * Get the directory of the image if it exists, otherwise, create it and return it
	*/
	protected function getFolder($fromRoot=true)
	{
		$foldername = $this->_path.$this->_width."_".$this->_height; // first made dimensions folder
		if(!file_exists($foldername)){
			if(!mkdir($foldername, 0777))
				throw new Exception('Error creating directory');
		}
		$foldername = $foldername."/".$this->_mode; // then make mode folder
		if(!file_exists($foldername)){ 
			if(!mkdir($foldername, 0777))
				throw new Exception('Error creating directory');
		}
		
		if($fromRoot)
			return $foldername;
		else
			return $this->_url.$this->_width."_".$this->_height."/".$this->_mode;
	}
	
	/** 
	  * Set the $_image as an alias of $_temp, then unset $_temp
	*/
	protected function sync()
	{
		$this->_image =& $this->_temp;
		unset($this->_temp);
	}
	
	/** 
	  * Send image header
	  *
	  * @param string $mime Mime type of the image to be displayed
	*/
	protected function sendHeader($mime='jpeg')
	{
		header('Content-Type: image/'.$mime);
	}
	
	/** 
	  * Display image to screen
	*/
	protected function show()
	{
		$this->sendHeader();
		@imagejpeg($this->_image ,"", $this->_quality);
	}
	
	/** 
	  * Save image to server
	*/
	protected function save()
	{
		@imagejpeg($this->_image, $this->getPath(true), $this->_quality);
		chmod($this->getPath(true), 0777);
	}
	
	/** 
	  * Display error image
	*/
	protected function errorHandler()
	{
		$sourceFile = HOME_ROOT."img/errorpic.gif";
		$this->_path = HOME_ROOT."img/";
		$this->_url = "img/";
		$this->_filename = 'error_pic.gif';
		if(file_exists($sourceFile)){
			$this->_image_info = @getimagesize($sourceFile);
			$this->_image = @imageCreateFromGIF($sourceFile);
			$this->resize();
			$this->save();
		} else {
			$this->_errors[] = 'Error image not found.';	
		}
	}
	
	/** 
	  * Magic __toString() method. Returns image path when used with "echo".
	*/
	public function __toString()
	{
		return $this->getPath();
	}
	
	/** 
	  * Destructor: Destroy image references from memory
	*/
	public function __destruct()
	{
		if($this->_image) imageDestroy($this->_image);
		if($this->_temp) imageDestroy($this->_temp);
	}
}
?>
