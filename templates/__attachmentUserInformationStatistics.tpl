{if MODULE_ATTACHMENT && ATTACHMENT_DISPLAY_USERINFORMATION && $user->uzAttachments}
    {if $__wcf->session->getPermission('mod.attachment.canViewAttachments')}
        <dt><a href="{link controller='UserAttachmentList' object=$user}{/link}" title="{lang}wcf.user.uzattachments.show{/lang}" class="jsTooltip">{lang}wcf.user.uzattachments.attachments{/lang}</a></dt>
        <dd>{#$user->uzAttachments}</dd>
    {elseif $__wcf->session->getPermission('user.attachment.canViewAttachmentCount')}
        <dt>{lang}wcf.user.uzattachments.attachments{/lang}</dt>
        <dd>{#$user->uzAttachments}</dd>
    {/if}
{/if}
