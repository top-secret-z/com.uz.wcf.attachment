<?php
namespace wcf\system\cache\builder;
use wcf\system\WCF;

/**
 * Caches the amount of attachments for stats.
 * 
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.attachment
 */
class AttachmentStatsCacheBuilder extends AbstractCacheBuilder {
	/**
	 * @inheritDoc
	 */
	protected $maxLifetime = 300;
	
	/**
	 * @see	\wcf\system\cache\builder\AbstractCacheBuilder::rebuild()
	 */
	protected function rebuild(array $parameters) {
		// amount of attachments
		$data = [];
		$sql = "SELECT	COUNT(*) AS amount
				FROM	wcf".WCF_N."_attachment
				WHERE	tmpHash = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(['']);
		$data['count'] = $statement->fetchColumn();
		
		return $data;
	}
}
