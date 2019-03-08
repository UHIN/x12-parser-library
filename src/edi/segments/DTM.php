<?php
namespace Uhin\X12Parser\EDI\Segments;

/**
 * Class DTM
 *
 * @package Uhin\X12Parser\EDI\Segments
 *
 * @property string DTM01 Date/Time Qualifier
 * @property string DTM02 Date
*/
class DTM extends Segment
{

	/** @var array */
	public $DTM = [];
	
	public function __construct($dataElements)
	{
		parent::__construct($dataElements);
		
		$this->arElemNames['DateTimeQualifier'] = 1;
		$this->arElemNames['Date'] = 2;
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
		
		if (count($this->DTM) > 0) {
			$serialized["DTM"] = $this->DTM;
		}
		
		return $serialized;
	}
}