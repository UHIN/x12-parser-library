<?php
namespace Uhin\X12Parser\EDI\Segments;

/**
 * Class FOB
 *
 * @package Uhin\X12Parser\EDI\Segments
 *
 * @property string FOB01 Shipment Method of Payment
 * @property string FOB04 Transportation Terms Qualifier Code
 * @property string FOB05 Transportation Terms Code
 * @property string FOB06 Location Qualifier
 * @property string FOB07 Description
*/
class FOB extends Segment
{

	public function __construct($dataElements)
	{
		parent::__construct($dataElements);
		
		$this->arElemNames['ShipmentMethodOfPayment'] = 1;
		$this->arElemNames['TransportationTermsQualifierCode'] = 4;
		$this->arElemNames['TransportationTermsCode'] = 5;
		$this->arElemNames['LocationQualifier'] = 6;
		$this->arElemNames['Description'] = 7;
	}
	
}