<?php
namespace Uhin\X12Parser\EDI\Segments;

/**
 * Class SE
 *
 * @package Uhin\X12Parser\EDI\Segments
 *
 * @property string SE01 Number of Included Functional Groups
 * @property string SE02 Interchange Control Number
 */
class SE extends Segment
{

	public function __construct($dataElements)
	{
		parent::__construct($dataElements);
		
		$this->arElemNames['NumberOfIncludedSegments'] = 1;
		$this->arElemNames['TransactionSetControlNumber'] = 2;
	}
	
}