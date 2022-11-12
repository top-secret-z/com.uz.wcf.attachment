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

use wcf\data\attachment\log\AttachmentLogAction;
use wcf\data\object\type\ObjectTypeCache;
use wcf\data\user\UserEditor;
use wcf\data\user\UserList;
use wcf\system\exception\SystemException;
use wcf\system\language\LanguageFactory;
use wcf\system\moderation\queue\ModerationQueueReportManager;
use wcf\system\WCF;

/**
 * Listen to attachment actions.
 */
class AttachmentListener implements IParameterizedEventListener
{
    /**
     * @inheritDoc
     */
    public function execute($eventObj, $className, $eventName, array &$parameters)
    {
        // attachment download
        if ($className == 'wcf\page\AttachmentPage') {
            if (!$eventObj->attachmentID) {
                return;
            }

            // image?
            if (!ATTACHMENT_LOG_IMAGES && \strncmp($eventObj->attachment->fileType, 'image/', 6) == 0) {
                return;
            }

            // try to guess type / private
            $type = 'wcf.acp.uzattachments.unknown';
            $private = 0;
            try {
                $objectType = ObjectTypeCache::getInstance()->getObjectType($eventObj->attachment->objectTypeID);
                if ($objectType->private) {
                    $private = 1;
                }

                $temp = $objectType->objectType;

                if ($temp == 'com.woltlab.wcf.contact') {
                    $type = 'wcf.acp.uzattachments.contact';
                } elseif ($temp == 'com.woltlab.wcf.user.signature') {
                    $type = 'wcf.acp.uzattachments.signature';
                } elseif ('wcf.moderation.type.' . $temp != WCF::getLanguage()->get('wcf.moderation.type.' . $temp)) {
                    $type = 'wcf.moderation.type.' . $temp;
                }
            } catch (SystemException $e) { /* do nothing, will set private to 0 */
            }

            $user = WCF::getUser();
            $objectAction = new AttachmentLogAction([], 'create', [
                'data' => [
                    'attachmentID' => $eventObj->attachment->attachmentID,
                    'filename' => $eventObj->attachment->filename,
                    'fileType' => $eventObj->attachment->fileType,
                    'objectType' => $type,
                    'private' => $private,
                    'time' => TIME_NOW,
                    'userID' => $user->userID ? $user->userID : null,
                    'username' => $user->username ? $user->username : 'wcf.user.guest',
                ],
            ]);
            $objectAction->executeAction();

            return;
        }

        // upload / delete for user Count
        if (\substr($className, -16) == 'AttachmentAction') {
            $action = $eventObj->getActionName();
            if ($action == 'upload') {
                if (!WCF::getUser()->userID) {
                    return;
                }

                $returnValues = $eventObj->getReturnValues();
                $attachments = $returnValues['returnValues']['attachments'];

                $editor = new UserEditor(WCF::getUser());
                $editor->updateCounters([
                    'uzAttachments' => \count($attachments),
                ]);

                // report upload, if configured / permitted, but not private attachments
                if (MODULE_ATTACHMENT_REPORT && WCF::getSession()->getPermission('user.attachment.reportAttachmentUpload')) {
                    $params = $eventObj->getParameters();
                    $objectType = ObjectTypeCache::getInstance()->getObjectTypeByName('com.woltlab.wcf.attachment.objectType', $params['objectType']);
                    if (!$objectType->private) {
                        $language = LanguageFactory::getInstance()->getLanguage(LanguageFactory::getInstance()->getDefaultLanguageID());
                        $message = $language->get('wcf.user.uzattachments.report');
                        ModerationQueueReportManager::getInstance()->addReport('com.woltlab.wcf.user', WCF::getUser()->userID, $message);
                    }
                }
            }

            if ($action == 'delete') {
                $objects = $eventObj->getObjects();
                $userIDs = $count = [];
                foreach ($objects as $object) {
                    if (!$object->userID) {
                        continue;
                    }

                    $userIDs[] = $object->userID;
                    if (isset($count[$object->userID])) {
                        $count[$object->userID]++;
                    } else {
                        $count[$object->userID] = 1;
                    }
                }

                if (\count($userIDs)) {
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
    }
}
