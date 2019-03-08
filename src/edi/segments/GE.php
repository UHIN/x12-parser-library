<?php
namespace Uhin\X12Parser\EDI\Segments;

/**
 * Class GE
 *
 * @package Uhin\X12Parser\EDI\Segments
 *
 * @property string GE01 Number of Transaction Sets Included
 * @property string GE02 Group Control Number
 */
class GE extends Segment
{

	public function __construct($dataElements)
	{
		parent::__construct($dataElements);
		
		$this->arElemNames['NumberOfTransactionSetsIncluded'] = 1;
		$this->arElemNames['GroupControlNumber'] = 2;
	}
	
}