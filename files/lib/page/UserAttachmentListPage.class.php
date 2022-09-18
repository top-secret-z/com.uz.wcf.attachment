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
use wcf\data\object\type\ObjectTypeCache;
use wcf\data\user\User;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\WCF;

/**
 * Shows a list of a user's attachments in the frontend.
 */
class UserAttachmentListPage extends SortablePage
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
    public $neededPermissions = ['mod.attachment.canViewAttachments'];

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
    public $validSortFields = ['attachmentID', 'filename', 'filesize', 'fileType', 'downloads', 'uploadTime'];

    /**
     * @inheritDoc
     */
    public $objectListClassName = AdministrativeAttachmentList::class;

    /**
     * user
     */
    public $userID;

    public $username = '';

    public $total = 0;

    /**
     * @inheritDoc
     */
    public function readParameters()
    {
        parent::readParameters();

        if (!WCF::getUser()->userID) {
            throw new PermissionDeniedException();
        }

        if (isset($_REQUEST['id'])) {
            $this->userID = \intval($_REQUEST['id']);
            $user = new User($this->userID);
            if ($user->userID) {
                $this->total = $user->uzAttachments;
                $this->username = $user->username;
            }
        } else {
            throw new IllegalLinkException();
        }
    }

    /**
     * @inheritDoc
     */
    protected function initObjectList()
    {
        parent::initObjectList();

        // don't show private attachments
        if (WCF::getUser()->userID != $this->userID) {
            $objectTypeIDs = [];
            foreach (ObjectTypeCache::getInstance()->getObjectTypes('com.woltlab.wcf.attachment.objectType') as $objectType) {
                if (!$objectType->private) {
                    $objectTypeIDs[] = $objectType->objectTypeID;
                }
            }
        }
        if (!empty($objectTypeIDs)) {
            $this->objectList->getConditionBuilder()->add('attachment.objectTypeID IN (?)', [$objectTypeIDs]);
        }

        // user
        $this->objectList->getConditionBuilder()->add('attachment.userID = ?', [$this->userID]);
    }

    /**
     * @inheritDoc
     */
    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'userID' => $this->userID,
            'username' => $this->username,
            'total' => $this->total,
        ]);
    }
}
