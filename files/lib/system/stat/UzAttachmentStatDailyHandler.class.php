<?php
namespace wcf\system\stat;

/**
 * Stat handler implementation for attachment downloads.
 * 
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.attachment
 */
class UzAttachmentStatDailyHandler extends AbstractStatDailyHandler {
	/**
	 * @inheritDoc
	 */
	public function getData($date) {
		return [
				'counter' => $this->getCounter($date, 'wcf'.WCF_N.'_attachment_log', 'time'),
				'total' => $this->getTotal($date, 'wcf'.WCF_N.'_attachment_log', 'time')
		];
	}
}
