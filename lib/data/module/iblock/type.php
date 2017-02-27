<?namespace Intervolga\Migrato\Data\Module\Iblock;

use Bitrix\Main\Loader;
use Intervolga\Migrato\Data\BaseData;
use Intervolga\Migrato\Data\Record;
use Intervolga\Migrato\Data\RecordId;
use Intervolga\Migrato\Tool\XmlIdProvider\TableXmlIdProvider;

class Type extends BaseData
{
	protected function __construct()
	{
		Loader::includeModule("iblock");
		$this->xmlIdProvider = new TableXmlIdProvider($this);
	}

	public function getList(array $filter = array())
	{
		$result = array();
		$getList = \CIBlockType::GetList();
		while ($type = $getList->fetch())
		{
			$record = new Record($this);
			$id = RecordId::createStringId($type["ID"]);
			$record->setXmlId($this->getXmlIdProvider()->getXmlId($id));
			$record->setId($id);
			$record->setFields(array(
				"ID" => $type["ID"],
				"SECTIONS" => $type["SECTIONS"],
				"EDIT_FILE_BEFORE" => $type["EDIT_FILE_BEFORE"],
				"EDIT_FILE_AFTER" => $type["EDIT_FILE_AFTER"],
				"IN_RSS" => $type["IN_RSS"],
				"SORT" => $type["SORT"],
			));
			$result[] = $record;
		}

		return $result;
	}

	public function update(Record $record)
	{
		$typeObject = new \CIBlockType();
		$isUpdated = $typeObject->update($record->getId()->getValue(), $record->getFieldsStrings());
		if (!$isUpdated)
		{
			throw new \Exception(trim(strip_tags($typeObject->LAST_ERROR)));
		}
	}

	public function create(Record $record)
	{
		$typeObject = new \CIBlockType();
		$typeId = $typeObject->add($record->getFieldsStrings());
		if ($typeId)
		{
			$id = RecordId::createNumericId($typeId);
			$this->getXmlIdProvider()->setXmlId($id, $record->getXmlId());

			return $id;
		}
		else
		{
			throw new \Exception(trim(strip_tags($typeObject->LAST_ERROR)));
		}
	}

	public function delete($xmlId)
	{
		$id = $this->findRecord($xmlId);
		$typeObject = new \CIBlockType();
		if (!$typeObject->delete($id->getValue()))
		{
			throw new \Exception("Unknown error");
		}
	}
}