<?php
namespace Uhin\X12Parser\EDI\Segments;

/**
 * Class N1
 *
 * @package Uhin\X12Parser\EDI\Segments
 *
 * @property string N101 Entity Identifier Code
 * @property string N103 Identification Code Qualifier
 * @property string N104 Identification Code
*/
class N1 extends Segment
{

	/** @var array */
	public $N1 = [];
	
	public function __construct($dataElements)
	{
		parent::__construct($dataElements);
		
		$this->arElemNames['EntityIdentifierCode'] = 1;
		$this->arElemNames['IdentificationCodeQualifier'] = 3;
		$this->arElemNames['IdentificationCode'] = 4;
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
		
		if (count($this->N1) > 0) {
			$serialized["N1"] = $this->N1;
		}
		
		return $serialized;
	}
}