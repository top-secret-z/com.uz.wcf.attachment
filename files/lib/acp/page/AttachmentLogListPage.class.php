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
namespace wcf\acp\page;

use wcf\data\attachment\log\AttachmentLogList;
use wcf\page\SortablePage;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Shows a list of attachments log entries.
 */
class AttachmentLogListPage extends SortablePage
{
    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'wcf.acp.menu.link.attachment.log';

    /**
     * @inheritDoc
     */
    public $neededPermissions = ['admin.attachment.canManageAttachment'];

    /**
     * @inheritDoc
     */
    public $defaultSortField = 'time';

    /**
     * @inheritDoc
     */
    public $defaultSortOrder = 'DESC';

    /**
     * @inheritDoc
     */
    public $validSortFields = ['logID', 'time', 'filename', 'fileType', 'objectType', 'username'];

    /**
     * @inheritDoc
     */
    public $objectListClassName = AttachmentLogList::class;

    /**
     * filter
     */
    public $username = '';

    public $filename = '';

    public $fileType = '';

    /**
     * available file types
     */
    public $availableFileTypes = [];

    /**
     * @inheritDoc
     */
    public function readParameters()
    {
        parent::readParameters();

        if (!empty($_REQUEST['username'])) {
            $this->username = StringUtil::trim($_REQUEST['username']);
        }
        if (!empty($_REQUEST['filename'])) {
            $this->filename = StringUtil::trim($_REQUEST['filename']);
        }
        if (!empty($_REQUEST['fileType'])) {
            $this->fileType = $_REQUEST['fileType'];
        }
    }

    /**
     * @inheritDoc
     */
    protected function initObjectList()
    {
        parent::initObjectList();

        $this->availableFileTypes = $this->objectList->getAvailableFileTypes();

        // filter
        if (!empty($this->username)) {
            $this->objectList->getConditionBuilder()->add('username = ?', [$this->username]);
        }
        if (!empty($this->filename)) {
            $this->objectList->getConditionBuilder()->add('filename LIKE ?', [$this->filename . '%']);
        }
        if (!empty($this->fileType)) {
            $this->objectList->getConditionBuilder()->add('fileType LIKE ?', [$this->fileType]);
        }
    }

    /**
     * @inheritDoc
     */
    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'username' => $this->username,
            'filename' => $this->filename,
            'fileType' => $this->fileType,
            'availableFileTypes' => $this->availableFileTypes,
        ]);
    }
}
