<?php
namespace wcf\data\attachment\log;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\WCF;

/**
 * Executes Attachment Log topics-related actions.
 * 
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.attachment
 */
class AttachmentLogAction extends AbstractDatabaseObjectAction {
	/**
	 * @inheritDoc
	 */
	protected $className = AttachmentLogEditor::class;
	
	/**
	 * @inheritDoc
	 */
	protected $permissionsDelete = ['admin.attachment.canManageAttachment'];
	
	/**
	 * @inheritDoc
	 */
	protected $permissionsUpdate = ['admin.attachment.canManageAttachment'];
	
	/**
	 * @inheritDoc
	 */
	protected $requireACP = ['delete'];
}
