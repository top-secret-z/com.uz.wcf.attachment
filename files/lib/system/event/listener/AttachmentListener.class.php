<?php
namespace wcf\system\event\listener;
use wcf\data\attachment\log\AttachmentLogAction;
use wcf\data\object\type\ObjectTypeCache;
use wcf\data\user\UserEditor;
use wcf\data\user\UserList;
use wcf\system\exception\SystemException;
use wcf\system\language\LanguageFactory;
use wcf\system\moderation\queue\ModerationQueueReportManager;
use wcf\system\WCF;

/**
 * Listen to attachment actions.
 * 
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.attachment
 */
class AttachmentListener implements IParameterizedEventListener {
	/**
	 * @inheritDoc
	 */
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		// attachment download
		if ($className == 'wcf\page\AttachmentPage') {
			if (!$eventObj->attachmentID) return;
			
			// image?
			if (!ATTACHMENT_LOG_IMAGES && strncmp($eventObj->attachment->fileType, 'image/', 6) == 0) return;
			
			// try to guess type / private
			$type = 'wcf.acp.uzattachments.unknown';
			$private = 0;
			try {
				$objectType = ObjectTypeCache::getInstance()->getObjectType($eventObj->attachment->objectTypeID);
				if ($objectType->private) $private = 1;
				
				$temp = $objectType->objectType;
				
				if ($temp == 'com.woltlab.wcf.contact') {
					$type = 'wcf.acp.uzattachments.contact';
				}
				else if ($temp == 'com.woltlab.wcf.user.signature') {
					$type = 'wcf.acp.uzattachments.signature';
				}
				else if ('wcf.moderation.type.' . $temp != WCF::getLanguage()->get('wcf.moderation.type.' . $temp)) {
					$type = 'wcf.moderation.type.' . $temp;
				}
			}
			catch (SystemException $e) { /* do nothing, will set private to 0 */ }
			
			$user = WCF::getUser();
			$objectAction = new AttachmentLogAction([], 'create', [
					'data' => [
							'attachmentID' => $eventObj->attachment->attachmentID,
							'filename' => $eventObj->attachment->filename,
							'fileType' => $eventObj->attachment->fileType,
							'objectType' => $type,
							'private' => $private,
							'time' => TIME_NOW,
							'userID' => $user->userID ? $user->userID : null,
							'username' => $user->username ? $user->username : 'wcf.user.guest'
					]
			]);
			$objectAction->executeAction();
			
			return;
		}
		
		// upload / delete for user Count
		if (substr($className, -16) == 'AttachmentAction') {
			$action = $eventObj->getActionName();
			if ($action == 'upload') {
				if (!WCF::getUser()->userID) return;
				
				$returnValues = $eventObj->getReturnValues();
				$attachments = $returnValues['returnValues']['attachments'];
				
				$editor = new UserEditor(WCF::getUser());
				$editor->updateCounters([
						'uzAttachments' => count($attachments)
				]);
				
				// report upload, if configured / permitted, but not private attachments
				if (MODULE_ATTACHMENT_REPORT && WCF::getSession()->getPermission('user.attachment.reportAttachmentUpload')) {
					$params = $eventObj->getParameters();
					$objectType = ObjectTypeCache::getInstance()->getObjectTypeByName('com.woltlab.wcf.attachment.objectType', $params['objectType']);
					if (!$objectType->private) {
						$language = LanguageFactory::getInstance()->getLanguage(LanguageFactory::getInstance()->getDefaultLanguageID());
						$message = $language->get('wcf.user.uzattachments.report');
						ModerationQueueReportManager::getInstance()->addReport('com.woltlab.wcf.user', WCF::getUser()->userID, $message);
					}
				}
			}
			
			if ($action == 'delete') {
				$objects = $eventObj->getObjects();
				$userIDs = $count = [];
				foreach ($objects as $object) {
					if (!$object->userID) continue;
					
					$userIDs[] = $object->userID;
					if (isset($count[$object->userID])) {
						$count[$object->userID]++;
					}
					else {
						$count[$object->userID] = 1;
					}
				}
				
				if (count($userIDs)) {
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
	}
}
