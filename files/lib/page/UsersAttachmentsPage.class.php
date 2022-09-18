<?php

/*
 * Copyright by Udo Zaydowicz.
 * Modified by SoftCreatR.dev.
 *
 * License: http://opensource.org/licenses/lgpl-license.php
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program; if not, write to the Free Software Foundation,
 * Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
namespace wcf\page;

use wcf\data\attachment\AdministrativeAttachmentList;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\menu\user\UserMenu;
use wcf\system\WCF;

/**
 * Shows a list of attachments in the user menu.
 */
class UsersAttachmentsPage extends SortablePage
{
    /**
     * @inheritDoc
     */
    public $loginRequired = true;

    /**
     * @inheritDoc
     */
    public $neededModules = ['MODULE_ATTACHMENT'];

    /**
     * @inheritDoc
     */
    public $itemsPerPage = 15;

    /**
     * @inheritDoc
     */
    public $defaultSortField = 'uploadTime';

    /**
     * @inheritDoc
     */
    public $defaultSortOrder = 'DESC';

    /**
     * @inheritDoc
     */
    public $validSortFields = ['filename', 'filesize', 'fileType', 'downloads', 'uploadTime'];

    /**
     * @inheritDoc
     */
    public $objectListClassName = AdministrativeAttachmentList::class;

    /**
     * user
     */
    public $userID;

    /**
     * @inheritDoc
     */
    public function readParameters()
    {
        parent::readParameters();

        if (!WCF::getUser()->userID) {
            throw new PermissionDeniedException();
        }

        $this->userID = WCF::getUser()->userID;
    }

    /**
     * @inheritDoc
     */
    protected function initObjectList()
    {
        parent::initObjectList();

        // get all attachments of this user except temporary
        $this->objectList->getConditionBuilder()->add('attachment.userID = ?', [$this->userID]);
        $this->objectList->getConditionBuilder()->add('attachment.tmpHash = ?', ['']);
    }

    /**
     * @inheritDoc
     */
    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'userID' => $this->userID,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function show()
    {
        // set active tab
        UserMenu::getInstance()->setActiveMenuItem('wcf.user.menu.community.usersAttachments');

        parent::show();
    }
}
