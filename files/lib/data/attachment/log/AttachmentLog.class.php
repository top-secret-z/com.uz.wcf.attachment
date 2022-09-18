<?php 
namespace wcf\data\attachment\log;
use wcf\data\DatabaseObject;
use wcf\system\request\IRouteController;
use wcf\system\WCF;

/**
 * Represents an Attachment Log entry
 * 
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.attachment
 */
class AttachmentLog extends DatabaseObject implements IRouteController {
	/**
	 * @inheritDoc
	 */
	protected static $databaseTableName = 'attachment_log';
	
	/**
	 * @inheritDoc
	 */
	protected static $databaseTableIndexName = 'logID';
	
	/**
	 * @inheritDoc
	 */
	public function getTitle() {
		return '';
	}
}
