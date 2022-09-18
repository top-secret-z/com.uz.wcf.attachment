<?php
namespace wcf\system\box;
use wcf\system\cache\builder\AttachmentTopMembersCacheBuilder;
use wcf\system\cache\runtime\UserProfileRuntimeCache;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;

/**
 * Shows members with the most attachments.
 *
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.attachment
 */
class AttachmentTopMembersBoxController extends AbstractBoxController {
	/**
	 * @inheritDoc
	 */
	protected static $supportedPositions = [];
	
	/**
	 * @inheritDoc
	 */
	public function hasLink() {
		return MODULE_MEMBERS_LIST == 1;
	}
	
	/**
	 * @inheritDoc
	 */
	public function getLink() {
		if (MODULE_MEMBERS_LIST) {
			$parameters = 'sortField=uzAttachments&sortOrder=DESC';
			
			return LinkHandler::getInstance()->getLink('MembersList', [], $parameters);
		}
		
		return '';
	}
	
	/**
	 * @inheritDoc
	 */
	protected function loadContent() {
		if (MODULE_ATTACHMENT && WCF::getSession()->getPermission('user.attachment.canViewAttachmentCount')) {
			$userIDs = AttachmentTopMembersCacheBuilder::getInstance()->getData();
			if (!empty($userIDs)) {
				$userProfiles = UserProfileRuntimeCache::getInstance()->getObjects($userIDs);
				
				WCF::getTPL()->assign([
						'userProfiles' => $userProfiles
				]);
				$this->content = WCF::getTPL()->fetch('boxAttachmentTopMembers');
			}
		}
	}
}
