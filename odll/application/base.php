<?php

class Application
{
	var $uri;
	var $model;
	var $db;
	
	function __construct($uri)
	{
		$this->uri = $uri;
	}
	
	function loadDatabase()
	{
		$this->db = new Database;
        //connect to database
        $this->db->connect();
	}
	
	function loadController($class)
	{
		$file = "application/controller/".$this->uri['controller'].".php";
		
		if(!file_exists($file)) die();

		require_once($file);

		$controller = new $class();
		
		if(method_exists($controller, $this->uri['method']))
		{
			$controller->{$this->uri['method']}($this->uri['var']);
		} else {
			$controller->index();
		}
	}

	function loadView($view,$vars)
	{
		if(is_array($vars) && count($vars) > 0)
			extract($vars, EXTR_PREFIX_SAME, "wddx");
		require_once('view/'.$view.'.php');
	}

	function loadModel($model)
	{
		require_once('model/'.$model.'.php');
		$this->$model = new $model;
	}
	
	function uploadImg($a, $b)
	{
		//define ("MAX_SIZE","400");
		$errors=0;
		$image = $a;
		$uploadedfile = $b;

		if ($image) 
		{
			$filename = stripslashes($a);
			$extension = $this->getExtension($filename);
			$extension = strtolower($extension);
				
			if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "jpe") && ($extension != "jfif")
			&& ($extension != "png") && ($extension != "gif") && ($extension != "tif") && ($extension != "tiff")) 
			{
				//echo '<script type=\'text/javascript\'>alert(\'Unknown image extension. Photo not uploaded.\')</script>';
				$errors=1;
			} else {
				$size=filesize($b);
				if($extension=="jpg" || $extension=="jpeg" )
				{
					$uploadedfile = $b;
					$src = imagecreatefromjpeg($uploadedfile);
				} else if($extension=="png") {
					$uploadedfile = $b;
					$src = imagecreatefrompng($uploadedfile);
				} else  {
					$src = imagecreatefromgif($uploadedfile);
				}
				list($width,$height)=getimagesize($uploadedfile);
	
				//$newwidth=400;
				//$newheight=($height/$width)*$newwidth;
				//$tmp=imagecreatetruecolor($newwidth,$newheight);

				//imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
				
				//$u = "1000";
				$locate = $a;

				$filename = "application/img/". $locate;
				imagejpeg($src,$filename,100);
				imagedestroy($src);
				//imagedestroy($tmp);
				return true;
			}
		}
	}
	
	function uploadFiles($a,$b)
	{
		if($a)
		{
			$filename = stripslashes($a);
			$extension = $this->getExtension($filename);
			$extension = strtolower($extension);
			
			//if($extension=="doc" || $extension=="docx" || $extension=="pdf"  || $extension=="xls" || $extension=="xlsx" || $extension == "txt")
			if($extension=="xls" || $extension=="xlsx")
			{
				$target = "application/database/";
				$target =  $target .$a;
				move_uploaded_file($b, $target);
				return true;
			} else {
				return false;
			}
		}
	}
	
	function getExtension($str)
	{
		$i = strrpos($str,".");
		if (!$i) { return ""; } 
		$l = strlen($str) - $i;
		$ext = substr($str,$i+1,$l);
		return $ext;
	}
}
?>