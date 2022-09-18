<?php
namespace wcf\page;
use wcf\data\attachment\AdministrativeAttachmentList;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\menu\user\UserMenu;
use wcf\system\WCF;

/**
 * Shows a list of attachments in the user menu.
 * 
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.attachment
 */
class UsersAttachmentsPage extends SortablePage {
	/**
	 * @inheritDoc
	 */
	public $loginRequired = true;
	
	/**
	 * @inheritDoc
	 */
	public $neededModules = ['MODULE_ATTACHMENT'];
	
	/**
	 * @inheritDoc
	 */
	public $itemsPerPage = 15;
	
	/**
	 * @inheritDoc
	 */
	public $defaultSortField = 'uploadTime';
	
	/**
	 * @inheritDoc
	 */
	public $defaultSortOrder = 'DESC';
	
	/**
	 * @inheritDoc
	 */
	public $validSortFields = ['filename', 'filesize', 'fileType', 'downloads', 'uploadTime'];
	
	/**
	 * @inheritDoc
	 */
	public $objectListClassName = AdministrativeAttachmentList::class;
	
	/**
	 * user
	 */
	public $userID = null;
	
	/**
	 * @inheritDoc
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (!WCF::getUser()->userID) {
			throw new PermissionDeniedException();
		}
		
		$this->userID = WCF::getUser()->userID;
	}
	
	/**
	 * @inheritDoc
	 */
	protected function initObjectList() {
		parent::initObjectList();
		
		// get all attachments of this user except temporary
		$this->objectList->getConditionBuilder()->add('attachment.userID = ?', [$this->userID]);
		$this->objectList->getConditionBuilder()->add('attachment.tmpHash = ?', ['']);
	}
	
	/**
	 * @inheritDoc
	 */
	public function assignVariables () {
		parent::assignVariables();
		
		WCF::getTPL()->assign([
				'userID' => $this->userID
		]);
	}
	
	/**
	 * @inheritDoc
	 */
	public function show() {
		// set active tab
		UserMenu::getInstance()->setActiveMenuItem('wcf.user.menu.community.usersAttachments');
		
		parent::show();
	}
}
