<?php
namespace Uhin\X12Parser\EDI\Segments;

/**
 * Class CSH
 *
 * @package Uhin\X12Parser\EDI\Segments
 *
 * @property string CSH01 Sales Requirement Code
*/
class CSH extends Segment
{

	public function __construct($dataElements)
	{
		parent::__construct($dataElements);
		
		$this->arElemNames['SalesRequirementCode'] = 1;
	}
	
}