<?php
namespace wcf\system\worker;
use wcf\data\user\UserList;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\WCF;

/**
 * Worker implementation for updating users' attachment count.
 * 
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.attachment
 */
class AttachmentUserRebuildDataWorker extends AbstractRebuildDataWorker {
	/**
	 * @inheritDoc
	 */
	protected $objectListClassName = UserList::class;
	
	/**
	 * @inheritDoc
	 */
	protected $limit = 100;
	
	/**
	 * @inheritDoc
	 */
	protected function initObjectList() {
		parent::initObjectList();
		
		$this->objectList->sqlOrderBy = 'user_table.userID';
	}
	
	/**
	 * @inheritDoc
	 */
	public function execute() {
		parent::execute();
		
		$userIDs = [];
		foreach ($this->getObjectList() as $user) {
			$userIDs[] = $user->userID;
		}
		
		if (!empty($userIDs)) {
			// update attachment counter
			$conditionBuilder = new PreparedStatementConditionBuilder();
			$conditionBuilder->add('user_table.userID IN (?)', [$userIDs]);
			$sql = "UPDATE	wcf".WCF_N."_user user_table
					SET	uzAttachments = (
							SELECT	COUNT(*)
							FROM	wcf".WCF_N."_attachment
							WHERE	userID = user_table.userID
						)
					".$conditionBuilder;
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute($conditionBuilder->getParameters());
		}
	}
}
