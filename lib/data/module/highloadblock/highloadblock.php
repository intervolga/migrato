<? namespace Intervolga\Migrato\Data\Module\Highloadblock;

use Bitrix\Highloadblock\HighloadBlockTable;
use Intervolga\Migrato\Data\BaseData;
use Intervolga\Migrato\Data\Record;
use Intervolga\Migrato\Data\RecordId;

class HighloadBlock extends BaseData
{
	public function getList(array $filter = array())
	{
		$hlBlocks = HighloadBlockTable::getList();
		$result = array();
		while ($hlBlock = $hlBlocks->fetch())
		{
			$record = new Record($this);
			$id = RecordId::createNumericId($hlBlock["ID"]);
			$xmlId = $this->getXmlId($id);
			$record->setXmlId($xmlId);
			$record->setId($id);
			$record->addFieldsRaw(array(
				"NAME" => $hlBlock["NAME"],
				"TABLE_NAME" => $hlBlock["TABLE_NAME"],
			));

			$result[] = $record;
		}

		return $result;
	}

	public function getXmlId($id)
	{
		$record = HighloadBlockTable::getById($id->getValue())->fetch();

		return strtolower($record['TABLE_NAME']);
	}

	public function update(Record $record)
	{
		$result = HighloadBlockTable::update($record->getId()->getValue(), $record->getFieldsRaw());
		if (!$result->isSuccess())
		{
			throw new \Exception(trim(strip_tags($result->getErrorMessages())));
		}
	}

	public function create(Record $record)
	{
		$result = HighloadBlockTable::add($record->getFieldsRaw());
		if ($result->isSuccess())
		{
			$id = RecordId::createNumericId($result->getId());

			return $id;
		}
		else
		{
			throw new \Exception(trim(strip_tags($result->getErrorMessages())));
		}
	}

	public function delete($xmlId)
	{
		if ($id = $this->findRecord($xmlId))
		{
			$result = HighloadBlockTable::delete($id->getValue());
			if (!$result->isSuccess())
			{
				throw new \Exception(trim(strip_tags($result->getErrorMessages())));
			}
		}
	}
}