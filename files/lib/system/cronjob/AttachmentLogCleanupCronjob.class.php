<?php
namespace wcf\system\cronjob;
use wcf\data\cronjob\Cronjob;
use wcf\system\WCF;

/**
 * Cronjob for a regular attachment log entry cleanup.
 * 
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.attachment
 */
class AttachmentLogCleanupCronjob extends AbstractCronjob {
	/**
	 * @inheritDoc
	 */
	public function execute(Cronjob $cronjob) {
		parent::execute($cronjob);
		
		if (!ATTACHMENT_LOG_CLEANUP_DAYS) return;
		
		// clean up
		$sql = "DELETE FROM	wcf".WCF_N."_attachment_log
				WHERE		time <= ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute([TIME_NOW - 86400 * ATTACHMENT_LOG_CLEANUP_DAYS]);
	}
}
