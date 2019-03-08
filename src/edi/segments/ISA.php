<?php
namespace Uhin\X12Parser\EDI\Segments;

/**
 * Class ISA
 *
 * @property string ISA01 Authorization Information Qualifier
 * @property string ISA02 Authorization Information
 * @property string ISA03 Security Information Qualifier
 * @property string ISA04 Security Information
 * @property string ISA05 Interchange ID Qualifier
 * @property string ISA06 Interchange Sender ID
 * @property string ISA07 Interchange ID Qualifier
 * @property string ISA08 Interchange Receiver ID
 * @property string ISA09 Interchange Date
 * @property string ISA10 Interchange Time
 * @property string ISA11 Interchange Control Standards ID
 * @property string ISA12 Interchange Control Version Number
 * @property string ISA13 Interchange Control Number
 * @property string ISA14 Acknowledgement Requested
 * @property string ISA15 Test Indicator
 * @property string ISA16 Sub Element Separator
 *          
 * @package Uhin\X12Parser
 */
class ISA extends Segment
{

	/** @var Segment */
	public $IEA;

	/** @var Segment */
	public $TA1;

	/** @var array */
	public $GS = [];

	/** @var string */
	private $segmentDelimiter;

	/** @var string */
	private $dataElementDelimiter;

	/** @var string */
	private $repetitionDelimiter;

	/** @var string */
	private $subRepetitionDelimiter;

	/**
	 * ISA constructor.
	 *
	 * @param
	 *        	$dataElements
	 * @param
	 *        	$segmentDelimiter
	 * @param
	 *        	$dataElementDelimiter
	 * @param
	 *        	$repetitionDelimiter
	 * @param
	 *        	$subRepetitionDelimiter
	 */
	public function __construct($dataElements, $segmentDelimiter, $dataElementDelimiter, $repetitionDelimiter, $subRepetitionDelimiter)
	{
		// For ISAs, we need to save the delimiters so that we can use them for gluing later
		$this->segmentDelimiter = $segmentDelimiter;
		$this->dataElementDelimiter = $dataElementDelimiter;
		$this->repetitionDelimiter = $repetitionDelimiter;
		$this->subRepetitionDelimiter = $subRepetitionDelimiter;
		parent::__construct($dataElements);
		
		
		$this->arElemNames['AuthorizationInformationQualifier'] = 1;
		$this->arElemNames['AuthorizationInformation'] = 2;
		$this->arElemNames['SecurityInformationQualifier'] = 3;
		$this->arElemNames['SecurityInformation'] = 4;
		$this->arElemNames['InterchangeIDQualifier1'] = 5;
		$this->arElemNames['InterchangeSenderID'] = 6;
		$this->arElemNames['InterchangeIDQualifier2'] = 7;
		$this->arElemNames['InterchangeReceiverID'] = 8;
		$this->arElemNames['InterchangeDate'] = 9;
		$this->arElemNames['InterchangeTime'] = 10;
		$this->arElemNames['InterchangeControlStandardsID'] = 11;
		$this->arElemNames['InterchangeControlVersion Number'] = 12;
		$this->arElemNames['InterchangeControlNumber'] = 13;
		$this->arElemNames['AcknowledgementRequested'] = 14;
		$this->arElemNames['UsageIndicator'] = 15;
		$this->arElemNames['ComponentElementSeparator'] = 16;
		
	}

	public function getSegmentDelimiter()
	{
		return $this->segmentDelimiter;
	}

	public function getDataElementDelimiter()
	{
		return $this->dataElementDelimiter;
	}

	public function getRepetitionDelimiter()
	{
		return $this->repetitionDelimiter;
	}

	public function getSubRepetitionDelimiter()
	{
		return $this->subRepetitionDelimiter;
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

		// Delimiters
		$serialized["segmentDelimiter"] = $this->segmentDelimiter;
		$serialized["dataElementDelimiter"] = $this->dataElementDelimiter;
		$serialized["repetitionDelimiter"] = $this->repetitionDelimiter;
		$serialized["subRepetitionDelimiter"] = $this->subRepetitionDelimiter;

		if (count($this->GS) > 0) {
			$serialized["GS"] = $this->GS;
		}
		if ($this->TA1) {
			$serialized["TA1"] = $this->TA1;
		}
		$serialized["IEA"] = $this->IEA;

		return $serialized;
	}
}