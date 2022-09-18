{if MODULE_ATTACHMENT && ATTACHMENT_DISPLAY_MESSAGE_SIDEBAR && $userProfile->uzAttachments}
	{if $__wcf->session->getPermission('mod.attachment.canViewAttachments')}
		<dt><a href="{link controller='UserAttachmentList' object=$userProfile}{/link}" title="{lang user=$userProfile}wcf.user.uzattachments.show{/lang}" class="jsTooltip">{lang user=$userProfile}wcf.user.uzattachments.attachments{/lang}</a></dt>
		<dd>{#$userProfile->uzAttachments}</dd>
	{elseif $__wcf->session->getPermission('user.attachment.canViewAttachmentCount')}
		<dt>{lang user=$userProfile}wcf.user.uzattachments.attachments{/lang}</dt>
		<dd>{#$userProfile->uzAttachments}</dd>
	{/if}
{/if}
