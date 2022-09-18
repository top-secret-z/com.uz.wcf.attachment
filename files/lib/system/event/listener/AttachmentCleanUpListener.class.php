<?php
namespace wcf\system\event\listener;
use wcf\data\attachment\AttachmentEditor;
use wcf\data\user\UserList;
use wcf\data\user\UserEditor;
use wcf\system\WCF;

/**
 * Listen to AttachmentCleanUp.
 * 
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.attachment
 */
class AttachmentCleanUpListener implements IParameterizedEventListener {
	/**
	 * @inheritDoc
	 */
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		// do whatever AttachmentCleanUpCronjob is doing, just start a bit earlier
		// delete orphaned attachments
		$attachmentIDs = $userIDs = $count = [];
		$sql = "SELECT	attachmentID, userID
				FROM	wcf".WCF_N."_attachment
				WHERE	objectID = ? AND uploadTime < ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute([0, (TIME_NOW - 86000)]); // - 400 secs
		while ($row = $statement->fetchArray()) {
			$attachmentIDs[] = $row['attachmentID'];
			
			if ($row['userID']) {
				$userIDs[] = $row['userID'];
				if (isset($count[$row['userID']])) {
					$count[$row['userID']]++;
				}
				else {
					$count[$row['userID']] = 1;
				}
			}
		}
		
		if (!empty($attachmentIDs)) {
			AttachmentEditor::deleteAll($attachmentIDs);
		}
		
		if (!empty($userIDs)) {
			// get users
			$userList = new UserList();
			$userList->getConditionBuilder()->add('user_table.userID IN (?)', [$userIDs]);
			$userList->readObjects();
			$users = $userList->getObjects();
			
			foreach ($users as $user) {
				$editor = new UserEditor($user);
				if ($user->uzAttachments < $count[$user->userID]) {
					$counter = $user->uzAttachments;
				}
				else {
					$counter = $count[$user->userID];
				}
				$editor->updateCounters([
						'uzAttachments' => -1 * $counter
				]);
			}
		}
	}
}
