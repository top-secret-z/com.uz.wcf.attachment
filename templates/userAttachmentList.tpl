{capture assign='pageTitle'}{$__wcf->getActivePage()->getTitle()}{if $pageNo > 1} - {lang}wcf.page.pageNo{/lang}{/if}{/capture}

{capture assign='contentTitle'}{$__wcf->getActivePage()->getTitle()} <span class="badge">{#$items}</span>{/capture}
{capture assign='contentDescription'}{$username}{/capture}

{include file='header'}

{hascontent}
    <div class="paginationTop">
        {content}
            {assign var='linkParameters' value='&id='|concat:$userID}

            {pages print=true assign=pagesLinks controller="UserAttachmentList" link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder$linkParameters"}
        {/content}
    </div>
{/hascontent}

{if $objects|count}
    <div class="section tabularBox">
        <table class="table">
            <thead>
                <tr>
                    <th class="columnID columnAttachmentID{if $sortField == 'attachmentID'} active {@$sortOrder}{/if}" colspan="2"><a href="{link controller='UserAttachmentList'}pageNo={@$pageNo}&sortField=attachmentID&sortOrder={if $sortField == 'attachmentID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.global.objectID{/lang}</a></th>
                    <th class="columnTitle columnFilename{if $sortField == 'filename'} active {@$sortOrder}{/if}"><a href="{link controller='UserAttachmentList'}pageNo={@$pageNo}&sortField=filename&sortOrder={if $sortField == 'filename' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.attachment.filename{/lang}</a></th>
                    <th class="columnText columnFileType{if $sortField == 'fileType'} active {@$sortOrder}{/if}"><a href="{link controller='UserAttachmentList'}pageNo={@$pageNo}&sortField=fileType&sortOrder={if $sortField == 'fileType' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.attachment.fileType{/lang}</a></th>
                    <th class="columnDate columnUploadTime{if $sortField == 'uploadTime'} active {@$sortOrder}{/if}"><a href="{link controller='UserAttachmentList'}pageNo={@$pageNo}&sortField=uploadTime&sortOrder={if $sortField == 'uploadTime' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.attachment.uploadTime{/lang}</a></th>
                    <th class="columnDigits columnFilesize{if $sortField == 'filesize'} active {@$sortOrder}{/if}"><a href="{link controller='UserAttachmentList'}pageNo={@$pageNo}&sortField=filesize&sortOrder={if $sortField == 'filesize' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.attachment.filesize{/lang}</a></th>
                    <th class="columnDigits columnDownloads{if $sortField == 'downloads'} active {@$sortOrder}{/if}"><a href="{link controller='UserAttachmentList'}pageNo={@$pageNo}&sortField=downloads&sortOrder={if $sortField == 'downloads' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.attachment.downloads{/lang}</a></th>
                </tr>
            </thead>

            <tbody>
                {foreach from=$objects item=attachment}
                    <tr class="jsAttachmentRow">
                        <td class="columnIcon">
                            {if $__wcf->session->getPermission('mod.attachment.canDeleteAttachments')}
                                <span class="icon icon16 fa-remove jsDeleteButton jsTooltip pointer" title="{lang}wcf.global.button.delete{/lang}" data-object-id="{@$attachment->attachmentID}" data-confirm-message="{lang}wcf.attachment.delete.sure{/lang}"></span>
                            {else}
                                <span class="icon icon16 fa-remove disabled"></span>
                            {/if}
                        </td>
                        <td class="columnID columnAttachmentID">{@$attachment->attachmentID}</td>
                        <td class="columnTitle columnFilename">
                            <div class="box64">
                                <a href="{link controller='Attachment' id=$attachment->attachmentID}{/link}"{if $attachment->isImage} class="jsImageViewer" title="{$attachment->filename}"{/if}>
                                    {if $attachment->tinyThumbnailType}
                                        <img src="{link controller='Attachment' id=$attachment->attachmentID}tiny=1{/link}" class="attachmentTinyThumbnail" alt="" />
                                    {else}
                                        <span class="icon icon64 fa-paperclip"></span>
                                    {/if}
                                </a>

                                <div>
                                    {if !$attachment->tmpHash}
                                        <p><a href="{link controller='Attachment' id=$attachment->attachmentID}{/link}">{$attachment->filename|tableWordwrap}</a></p>
                                    {else}
                                        <p>{$attachment->filename|tableWordwrap}</p>
                                    {/if}
                                    <p><small>{if $attachment->userID}<a href="{link controller='User' id=$attachment->userID}{/link}">{$attachment->username}</a>{else}{lang}wcf.user.guest{/lang}{/if}</small></p>
                                    {if $attachment->getContainerObject()}
                                        <p><small><a href="{$attachment->getContainerObject()->getLink()}">{$attachment->getContainerObject()->getTitle()|tableWordwrap}</a></small></p>
                                    {else}
                                        <p><small>{lang}wcf.user.uzattachments.temporary{/lang}</small></p>
                                    {/if}
                                </div>
                            </div>
                        </td>
                        <td class="columnText columnFileType">{$attachment->fileType}</td>
                        <td class="columnDate columnUploadTime">{@$attachment->uploadTime|time}</td>
                        <td class="columnDigits columnFilesize">{@$attachment->filesize|filesize}</td>
                        <td class="columnDigits columnDownloads">{#$attachment->downloads}</td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </div>

    <div class="contentNavigation">
        {@$pagesLinks}

        {hascontent}
            <nav>
                {content}
                    <ul>
                        {event name='contentNavigationButtonsBottom'}
                    </ul>
                {/content}
            </nav>
        {/hascontent}
    </div>
{else}
    <p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

<script data-relocate="true">
    $(function() {
        new WCF.Action.Delete('wcf\\data\\attachment\\AttachmentAction', '.jsAttachmentRow');
    });
</script>

{include file='footer'}
