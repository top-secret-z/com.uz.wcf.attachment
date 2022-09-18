{if MODULE_ATTACHMENT && $__wcf->session->getPermission('user.attachment.canViewAttachmentCount') && $attachmentStatistics|isset}
	<dt>{lang}wcf.user.uzattachments.attachments{/lang}</dt>
	<dd>{#$attachmentStatistics[count]}</dd>
{/if}
