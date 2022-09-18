{capture assign='contentHeader'}
	<header class="contentHeader">
		<div class="contentHeaderTitle">
			<h1 class="contentTitle">{$__wcf->getActivePage()->getTitle()}{if $items} <span class="badge">{#$items}</span>{/if}</h1>
		</div>
		
		{hascontent}
			<nav class="contentHeaderNavigation">
				<ul>
					{content}
						
						{event name='contentHeaderNavigation'}
					{/content}
				</ul>
			</nav>
		{/hascontent}
	</header>
{/capture}

{include file='userMenuSidebar'}

{include file='header'}

{hascontent}
	<div class="paginationTop">
		{content}
			{assign var='linkParameters' value=''}
			
			{pages print=true assign=pagesLinks controller="UsersAttachments" link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder$linkParameters"}
		{/content}
	</div>
{/hascontent}

{if $objects|count}
	<div class="section tabularBox">
		<table class="table">
			<thead>
				<tr>
					<th class="columnTitle columnFilename{if $sortField == 'filename'} active {@$sortOrder}{/if}"><a href="{link controller='UsersAttachments'}pageNo={@$pageNo}&sortField=filename&sortOrder={if $sortField == 'filename' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.attachment.filename{/lang}</a></th>
					<th class="columnText columnFileType{if $sortField == 'fileType'} active {@$sortOrder}{/if}"><a href="{link controller='UsersAttachments'}pageNo={@$pageNo}&sortField=fileType&sortOrder={if $sortField == 'fileType' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.attachment.fileType{/lang}</a></th>
					<th class="columnDate columnUploadTime{if $sortField == 'uploadTime'} active {@$sortOrder}{/if}"><a href="{link controller='UsersAttachments'}pageNo={@$pageNo}&sortField=uploadTime&sortOrder={if $sortField == 'uploadTime' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.attachment.uploadTime{/lang}</a></th>
					<th class="columnDigits columnFilesize{if $sortField == 'filesize'} active {@$sortOrder}{/if}"><a href="{link controller='UsersAttachments'}pageNo={@$pageNo}&sortField=filesize&sortOrder={if $sortField == 'filesize' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.attachment.filesize{/lang}</a></th>
					<th class="columnDigits columnDownloads{if $sortField == 'downloads'} active {@$sortOrder}{/if}"><a href="{link controller='UsersAttachments'}pageNo={@$pageNo}&sortField=downloads&sortOrder={if $sortField == 'downloads' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.attachment.downloads{/lang}</a></th>
				</tr>
			</thead>
			
			<tbody>
				{foreach from=$objects item=attachment}
					<tr>
						<td class="columnTitle columnFilename">
							<div class="box64">
								{if !$attachment->tmpHash}
									<a href="{link controller='Attachment' id=$attachment->attachmentID}{/link}"{if $attachment->isImage} class="jsImageViewer" title="{$attachment->filename}"{/if}>
										{if $attachment->tinyThumbnailType}
											<img src="{link controller='Attachment' id=$attachment->attachmentID}tiny=1{/link}" class="attachmentTinyThumbnail" alt="" />
										{else}
											<span class="icon icon64 fa-paperclip"></span>
										{/if}
									</a>
								{else}
									<span class="icon icon64 fa-ban-circle"></span>
								{/if}
								
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

{include file='footer'}
