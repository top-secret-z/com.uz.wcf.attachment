<?php
namespace wcf\data\attachment\log;
use wcf\data\DatabaseObjectEditor;

/**
 * Provides functions to edit Attachment Log entries.
 * 
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.attachment
 */
class AttachmentLogEditor extends DatabaseObjectEditor {
	/**
	 * @inheritDoc
	 */
	public static $baseClass = AttachmentLog::class;
}
