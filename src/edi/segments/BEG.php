<?php
namespace Uhin\X12Parser\EDI\Segments;

/**
 * Class BEG
 *
 * @package Uhin\X12Parser\EDI\Segments
 *
 * @property string BEG01 Transaction Set Purpose Code
 * @property string BEG02 Purchase Order Type Code
 * @property array  BEG03 Purchase Order Number
 * @property string BEG05 Date
*/
class BEG extends Segment
{

	public function __construct($dataElements)
	{
		parent::__construct($dataElements);
		
		$this->arElemNames['TransactionSetPurposeCode'] = 1;
		$this->arElemNames['PurchaseOrderTypeCode'] = 2;
		$this->arElemNames['PurchaseOrderNumber'] = 3;
		$this->arElemNames['Date'] = 5;
	}
	
}