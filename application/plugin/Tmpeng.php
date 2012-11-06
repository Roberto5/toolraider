<?php
class Zend_View_Filter_Tmpeng //implements Zend_Filter_Interface
{
	static private $key=array();
	public function filter($buffer)
	{
		foreach (self::$key as $key => $value) {
			$buffer=str_replace($key, $value, $buffer);
		}
		return $buffer;
	}
	public static function addkey(Array $keys) {
		self::$key=array_merge(self::$key,$keys);
	}
}
?>