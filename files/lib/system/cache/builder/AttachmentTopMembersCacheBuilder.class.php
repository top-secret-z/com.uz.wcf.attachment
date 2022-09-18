<?php
namespace wcf\system\cache\builder;
use wcf\system\cache\builder\AbstractSortedUserCacheBuilder;

/**
 * Caches the list of the top attachment members.
 * 
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.attachment
 */
class AttachmentTopMembersCacheBuilder extends AbstractSortedUserCacheBuilder {
	/**
	 * @inheritDoc
	 */
	protected $maxLifetime = 300;
	
	/**
	 * @inheritDoc
	 */
	protected $defaultLimit = ATTACHMENT_DISPLAY_BOX_ENTRIES + 1;
	
	/**
	 * @inheritDoc
	 */
	protected $positiveValuesOnly = true;
	
	/**
	 * @inheritDoc
	 */
	protected $sortField = 'uzAttachments';
}
