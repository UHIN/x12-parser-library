<?php
namespace Uhin\X12Parser\EDI\Segments;

/**
 * Class IEA
 *
 * @package Uhin\X12Parser\EDI\Segments
 *
 * @property string IEA01 Number of Included Functional Groups
 * @property string IEA02 Interchange Control Number
 */
class IEA extends Segment
{

	public function __construct($dataElements)
	{
		parent::__construct($dataElements);
		
		$this->arElemNames['NumberOfIncludedFunctionalGroups'] = 1;
		$this->arElemNames['InterchangeControlNumber'] = 2;
	}
	
}