<? namespace Intervolga\Migrato\Module\Main\Data;

use Intervolga\Migrato\Base\Data;
use Intervolga\Migrato\Tool\DataRecord;
use Intervolga\Migrato\Tool\DataRecordId;
use Intervolga\Migrato\Tool\XmlIdProviders\UfXmlIdProvider;

class EventType extends Data
{
	public function __construct()
	{
		$this->xmlIdProvider = new UfXmlIdProvider($this);
	}

	public function getFromDatabase()
	{
		$result = array();
		$getList = \CEventType::getList();
		while ($type = $getList->fetch())
		{
			$record = new DataRecord();
			$id = DataRecordId::createNumericId($type["ID"]);
			$record->setXmlId($this->getXmlIdProvider()->getXmlId($id));
			$record->setId($id);
			$record->setFields(array(
				"LID" => $type["LID"],
				"EVENT_NAME" => $type["EVENT_NAME"],
				"NAME" => $type["NAME"],
				"DESCRIPTION" => $type["DESCRIPTION"],
				"SORT" => $type["SORT"],
			));
			$result[] = $record;
		}
		return $result;
	}

	/**
	 * @param DataRecord $record
	 */
	protected function update(DataRecord $record)
	{
		// TODO: Implement update() method.
	}

	/**
	 * @param DataRecord $record
	 */
	protected function create(DataRecord $record)
	{
		// TODO: Implement create() method.
	}

	/**
	 * @param $xmlId
	 */
	protected function delete($xmlId)
	{
		// TODO: Implement delete() method.
	}
}