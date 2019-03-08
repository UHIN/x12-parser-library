<?php
namespace Uhin\X12Parser\EDI\Segments;

/**
 * Class GS
 *
 * @package Uhin\X12Parser\EDI\Segments
 *
 * @property string GS01 Functional ID code
 * @property string GS02 Application Sender's Code
 * @property string GS03 Application Receiver's Code
 * @property string GS04 Date
 * @property string GS05 Time
 * @property string GS06 Group Control Number
 * @property string GS07 Responsible Agency Code
 * @property string GS08 Version/Rel. Ind. ID Code
 */
class GS extends Segment
{

	/** @var Segment */
	public $GE;

	/** @var array */
	public $ST = [];

	public function __construct($dataElements)
	{
		parent::__construct($dataElements);
		
		$this->arElemNames['FunctionalIDCode'] = 1;
		$this->arElemNames['ApplicationSendersCode'] = 2;
		$this->arElemNames['ApplicationReceiversCode'] = 3;
		$this->arElemNames['Date'] = 4;
		$this->arElemNames['Time'] = 5;
		$this->arElemNames['GroupControlNumber'] = 6;
		$this->arElemNames['ResponsibleAgencyCode'] = 7;
		$this->arElemNames['VersionRelIndIDCode'] = 8;
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

		if (count($this->ST) > 0) {
			$serialized["ST"] = $this->ST;
		}
		$serialized["GE"] = $this->GE;

		return $serialized;
	}
}