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
namespace wcf\system\event\listener;

use wcf\data\attachment\AttachmentEditor;
use wcf\data\user\UserEditor;
use wcf\data\user\UserList;
use wcf\system\WCF;

/**
 * Listen to AttachmentCleanUp.
 */
class AttachmentCleanUpListener implements IParameterizedEventListener
{
    /**
     * @inheritDoc
     */
    public function execute($eventObj, $className, $eventName, array &$parameters)
    {
        // do whatever AttachmentCleanUpCronjob is doing, just start a bit earlier
        // delete orphaned attachments
        $attachmentIDs = $userIDs = $count = [];
        $sql = "SELECT    attachmentID, userID
                FROM    wcf" . WCF_N . "_attachment
                WHERE    objectID = ? AND uploadTime < ?";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([0, (TIME_NOW - 86000)]); // - 400 secs
        while ($row = $statement->fetchArray()) {
            $attachmentIDs[] = $row['attachmentID'];

            if ($row['userID']) {
                $userIDs[] = $row['userID'];
                if (isset($count[$row['userID']])) {
                    $count[$row['userID']]++;
                } else {
                    $count[$row['userID']] = 1;
                }
            }
        }

        if (!empty($attachmentIDs)) {
            AttachmentEditor::deleteAll($attachmentIDs);
        }

        if (!empty($userIDs)) {
            // get users
            $userList = new UserList();
            $userList->getConditionBuilder()->add('user_table.userID IN (?)', [$userIDs]);
            $userList->readObjects();
            $users = $userList->getObjects();

            foreach ($users as $user) {
                $editor = new UserEditor($user);
                if ($user->uzAttachments < $count[$user->userID]) {
                    $counter = $user->uzAttachments;
                } else {
                    $counter = $count[$user->userID];
                }
                $editor->updateCounters([
                    'uzAttachments' => -1 * $counter,
                ]);
            }
        }
    }
}
