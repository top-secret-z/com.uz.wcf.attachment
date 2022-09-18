<?php
namespace wcf\acp\page;
use wcf\data\attachment\log\AttachmentLogList;
use wcf\page\SortablePage;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Shows a list of attachments log entries.
 * 
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.attachment
 */
class AttachmentLogListPage extends SortablePage {
	/**
	 * @inheritDoc
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.attachment.log';
	
	/**
	 * @inheritDoc
	 */
	public $neededPermissions = ['admin.attachment.canManageAttachment'];
	
	/**
	 * @inheritDoc
	 */
	public $defaultSortField = 'time';
	
	/**
	 * @inheritDoc
	 */
	public $defaultSortOrder = 'DESC';
	
	/**
	 * @inheritDoc
	 */
	public $validSortFields = ['logID', 'time', 'filename', 'fileType', 'objectType', 'username'];
	
	/**
	 * @inheritDoc
	 */
	public $objectListClassName = AttachmentLogList::class;
	
	/**
	 * filter
	 */
	public $username = '';
	public $filename = '';
	public $fileType = '';
	
	/**
	 * available file types
	 */
	public $availableFileTypes = [];
	
	/**
	 * @inheritDoc
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (!empty($_REQUEST['username'])) $this->username = StringUtil::trim($_REQUEST['username']);
		if (!empty($_REQUEST['filename'])) $this->filename = StringUtil::trim($_REQUEST['filename']);
		if (!empty($_REQUEST['fileType'])) $this->fileType = $_REQUEST['fileType'];
	}
	
	/**
	 * @inheritDoc
	 */
	protected function initObjectList() {
		parent::initObjectList();
		
		$this->availableFileTypes = $this->objectList->getAvailableFileTypes();
		
		// filter
		if (!empty($this->username)) {
			$this->objectList->getConditionBuilder()->add('username = ?', [$this->username]);
		}
		if (!empty($this->filename)) {
			$this->objectList->getConditionBuilder()->add('filename LIKE ?', [$this->filename.'%']);
		}
		if (!empty($this->fileType)) {
			$this->objectList->getConditionBuilder()->add('fileType LIKE ?', [$this->fileType]);
		}
	}
	
	/**
	 * @inheritDoc
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign([
			'username' => $this->username,
			'filename' => $this->filename,
			'fileType' => $this->fileType,
			'availableFileTypes' => $this->availableFileTypes
		]);
	}
}
