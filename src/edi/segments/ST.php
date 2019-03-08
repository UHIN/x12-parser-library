<?php
namespace Uhin\X12Parser\EDI\Segments;

/**
 * Class ST
 *
 * @package Uhin\X12Parser\EDI\Segments
 *
 * @property string ST01 Transaction Set Identifier Code
 * @property string ST02 Transaction Set Control Number
 * @property string ST03 Implementation Convention Reference
 */
class ST extends Segment
{

	/** @var Segment */
	public $SE;

	/** @var array */
	public $HL = [];

	/** @var Segment */
	public $BEG;
	
	/** @var array */
	public $REF = [];
	
	/** @var Segment */
	public $FOB;
	
	/** @var Segment */
	public $CSH;
	
	/** @var array */
	public $DTM = [];
	
	/** @var Segment */
	public $PKG;
	
	/** @var array */
	public $N9 = [];
	
	/** @var array */
	public $N1 = [];
	
	/** @var array */
	public $PO1 = [];
	
	/** @var array */
	public $properties = [];
	
	public function __construct($dataElements)
	{
		parent::__construct($dataElements);
		
		$this->arElemNames['TransactionSetIdentifierCode'] = 1;
		$this->arElemNames['TransactionSetControlNumber'] = 2;
		$this->arElemNames['ImplementationConventionReference'] = 3;
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

		// Properties
		if (count($this->properties) > 0) {
			$serialized["properties"] = $this->properties;
		}

		$serialized["SE"] = $this->SE;
		$serialized["BEG"] = $this->BEG;
		$serialized["FOB"] = $this->FOB;
		$serialized["CSH"] = $this->CSH;
		$serialized["PKG"] = $this->PKG;
		
		if (count($this->HL) > 0) {
			$serialized["HL"] = $this->HL;
		}
		if (count($this->REF) > 0) {
			$serialized["REF"] = $this->REF;
		}
		if (count($this->DTM) > 0) {
			$serialized["DTM"] = $this->DTM;
		}
		
		return $serialized;
	}
}