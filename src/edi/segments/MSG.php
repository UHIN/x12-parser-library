<?php
namespace Uhin\X12Parser\EDI\Segments;

/**
 * Class MSG
 *
 * @package Uhin\X12Parser\EDI\Segments
 *
 * @property string MSG01 Free-Form Message Text
*/
class MSG extends Segment
{

	public function __construct($dataElements)
	{
		parent::__construct($dataElements);
		
		$this->arElemNames['FreeFormMessageText'] = 1;
	}
	
}