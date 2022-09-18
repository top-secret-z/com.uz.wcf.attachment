<?php
namespace wcf\system\event\listener;
use wcf\system\cache\builder\AttachmentStatsCacheBuilder;
use wcf\system\WCF;

/**
 * Adds the attachment stats in the statistics box.
 * 
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.attachment
 */
class AttachmentStatisticsBoxControllerListener implements IParameterizedEventListener {
	/**
	 * @inheritDoc
	 */
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		WCF::getTPL()->assign([
				'attachmentStatistics' => AttachmentStatsCacheBuilder::getInstance()->getData()
		]);
	}
}
