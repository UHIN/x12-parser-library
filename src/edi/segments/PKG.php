<?php
namespace Uhin\X12Parser\EDI\Segments;

/**
 * Class PKG
 *
 * @package Uhin\X12Parser\EDI\Segments
 *
 * @property string PKG01 Item Description Type
 * @property string PKG02 Packaging Characteristic Code
 * @property string PKG05 Description
*/
class PKG extends Segment
{

	public function __construct($dataElements)
	{
		parent::__construct($dataElements);
		
		$this->arElemNames['ItemDescriptionType'] = 1;
		$this->arElemNames['PackagingCharacteristicCode'] = 2;
		$this->arElemNames['Description'] = 5;
	}
	
}