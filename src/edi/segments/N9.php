<?php
namespace Uhin\X12Parser\EDI\Segments;

/**
 * Class N9
 *
 * @package Uhin\X12Parser\EDI\Segments
 *
 * @property string N901 Reference Identification Qualifier
 * @property string N902 Reference Identification
*/
class N9 extends Segment
{

	/** @var array */
	public $MSG = [];
	
	public function __construct($dataElements)
	{
		parent::__construct($dataElements);
		
		$this->arElemNames['ReferenceIdentificationQualifier'] = 1;
		$this->arElemNames['ReferenceIdentification'] = 2;
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
		
		if (count($this->MSG) > 0) {
			$serialized["MSG"] = $this->MSG;
		}
		return $serialized;
	}
}