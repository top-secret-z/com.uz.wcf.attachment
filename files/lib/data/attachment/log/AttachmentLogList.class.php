<?php
namespace wcf\data\attachment\log;
use wcf\data\DatabaseObjectList;
use wcf\system\WCF;

/**
 * Represents a list of Attachment Log entries.
 * 
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.attachment
 */
class AttachmentLogList extends DatabaseObjectList {
	/**
	 * @inheritDoc
	 */
	public $className = AttachmentLog::class;
	
	/**
	 * Returns a list of available file types.
	 */
	public function getAvailableFileTypes() {
		$fileTypes = [];
		$sql = "SELECT	DISTINCT fileType
				FROM	wcf".WCF_N."_attachment_log";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute();
		$fileTypes = $statement->fetchMap('fileType', 'fileType');
		
		ksort($fileTypes);
		
		return $fileTypes;
	}
}
