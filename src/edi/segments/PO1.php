<?php
namespace Uhin\X12Parser\EDI\Segments;

/**
 * Class PO1
 *
 * @package Uhin\X12Parser\EDI\Segments
 *
 * @property string PO101 Assigned Identification
 * @property string PO102 Quantity Ordered
 * @property array  PO103 Unit or Basis for Measurement Code
 * @property string PO104 Unit Price
 * @property string PO105 Basis of Unit Price Code
 * @property string PO106 Product/Service ID Qualifier
 * @property string PO107 Product/Service ID
*/
class PO1 extends Segment
{

	/** @var array */
	public $PO1 = [];
	
	public function __construct($dataElements)
	{
		parent::__construct($dataElements);
		
		$this->arElemNames['AssignedIdentification'] = 1;
		$this->arElemNames['QuantityOrdered'] = 2;
		$this->arElemNames['UnitOrBasisForMeasurementCode'] = 3;
		$this->arElemNames['UnitPrice'] = 4;
		$this->arElemNames['BasisOfUnitPriceCode'] = 5;
		$this->arElemNames['ProductServiceIDQualifier'] = 6;
		$this->arElemNames['ProductServiceID'] = 7;
	}
	
	/**
	 * Specify data which should be serialized to JSON
	 *
	 * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 *         which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize()
	{
		// Serialize the data elements
		$serialized = parent::jsonSerialize();
		
		if (count($this->PO1) > 0) {
			$serialized["PO1"] = $this->PO1;
		}
		
		return $serialized;
	}
}