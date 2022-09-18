<?php
namespace wcf\system\event\listener;
use wcf\system\WCF;

/**
 * Listen to membersPage for sort field.
 * 
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.attachment
 */
class AttachmentMembersListPageListener implements IParameterizedEventListener {
	/**
	 * @inheritDoc
	 */
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		if (WCF::getSession()->getPermission('user.attachment.canViewAttachmentCount')) {
			$eventObj->validSortFields[] = 'uzAttachments';
		}
	}
}
