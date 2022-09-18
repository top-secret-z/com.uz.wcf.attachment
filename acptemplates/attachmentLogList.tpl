{include file='header' pageTitle='wcf.acp.uzattachments.list'}

<header class="contentHeader">
	<div class="contentHeaderTitle">
		<h1 class="contentTitle">{lang}wcf.acp.uzattachments.list{/lang}{if $items} <span class="badge">{#$items}</span>{/if}</h1>
	</div>
	
	{hascontent}
		<nav class="contentHeaderNavigation">
			<ul>
				{content}{event name='contentHeaderNavigation'}{/content}
			</ul>
		</nav>
	{/hascontent}
</header>

{include file='formError'}

<form method="post" action="{link controller='AttachmentLogList'}{/link}">
	<section class="section">
		<h2 class="sectionTitle">{lang}wcf.global.filter{/lang}</h2>
		
		<div class="row rowColGap formGrid">
			<dl class="col-xs-12 col-md-4">
				<dt></dt>
				<dd>
					<input type="text" id="username" name="username" value="{$username}" placeholder="{lang}wcf.user.username{/lang}" class="long">
				</dd>
			</dl>
			
			<dl class="col-xs-12 col-md-4">
				<dt></dt>
				<dd>
					<input type="text" id="filename" name="filename" value="{$filename}" placeholder="{lang}wcf.attachment.filename{/lang}" class="long">
				</dd>
			</dl>
			
			{if $availableFileTypes|count > 1}
				<dl class="col-xs-12 col-md-4">
					<dt></dt>
					<dd>
						<select name="fileType" id="fileType">
							<option value="">{lang}wcf.attachment.fileType{/lang}</option>
							{htmlOptions options=$availableFileTypes selected=$fileType}
						</select>
					</dd>
				</dl>
			{/if}
			
			{event name='filterFields'}
		</div>
		
		<div class="formSubmit">
			<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s">
			{csrfToken}
		</div>
	</section>
</form>

{hascontent}
	<div class="paginationTop">
		{content}
			{assign var='linkParameters' value=''}
			{if $username}{capture append=linkParameters}&username={@$username|rawurlencode}{/capture}{/if}
			{if $filename}{capture append=linkParameters}&filename={@$filename|rawurlencode}{/capture}{/if}
			{if $fileType}{capture append=linkParameters}&fileType={@$fileType|rawurlencode}{/capture}{/if}
			
			{pages print=true assign=pagesLinks controller="AttachmentLogList" link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder$linkParameters"}
		{/content}
	</div>
{/hascontent}

{if $objects|count}
	<div class="section tabularBox">
		<table class="table">
			<thead>
				<tr>
					<th class="columnID columnLogID{if $sortField == 'logID'} active {@$sortOrder}{/if}"><a href="{link controller='AttachmentLogList'}pageNo={@$pageNo}&sortField=logID&sortOrder={if $sortField == 'logID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.global.objectID{/lang}</a></th>
					<th class="columnDate columnTime{if $sortField == 'time'} active {@$sortOrder}{/if}"><a href="{link controller='AttachmentLogList'}pageNo={@$pageNo}&sortField=time&sortOrder={if $sortField == 'time' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.acp.uzattachments.time{/lang}</a></th>
					<th class="columnText columnUsername{if $sortField == 'username'} active {@$sortOrder}{/if}"><a href="{link controller='AttachmentLogList'}pageNo={@$pageNo}&sortField=username&sortOrder={if $sortField == 'username' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.acp.uzattachments.username{/lang}</a></th>
					<th class="columnText columnObjectType{if $sortField == 'objectType'} active {@$sortOrder}{/if}"><a href="{link controller='AttachmentLogList'}pageNo={@$pageNo}&sortField=objectType&sortOrder={if $sortField == 'objectType' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.acp.uzattachments.objectType{/lang}</a></th>
					<th class="columnText columnFilename{if $sortField == 'filename'} active {@$sortOrder}{/if}"><a href="{link controller='AttachmentLogList'}pageNo={@$pageNo}&sortField=filename&sortOrder={if $sortField == 'filename' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.attachment.filename{/lang}</a></th>
					<th class="columnText columnFileType{if $sortField == 'fileType'} active {@$sortOrder}{/if}"><a href="{link controller='AttachmentLogList'}pageNo={@$pageNo}&sortField=fileType&sortOrder={if $sortField == 'fileType' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.attachment.fileType{/lang}</a></th>
					
					{event name='columnHeads'}
				</tr>
			</thead>
			
			<tbody>
				{foreach from=$objects item=log}
					<tr>
						<td class="columnID columnLogID">{@$log->logID}</td>
						<td class="columnDate columnTime">{@$log->time|time}</td>
						<td class="columnText columnUsername">{if $log->username=='wcf.user.guest'}{lang}wcf.user.guest{/lang}{else}{$log->username}{/if}</td>
						<td class="columnText columnObjectType">{lang}{$log->objectType}{/lang}</td>
						{if $log->private}
							<td class="columnText columnFilename">{lang}wcf.acp.uzattachments.private{/lang}</td>
						{else}
							<td class="columnText columnFilename"><a href="{link controller='Attachment' id=$log->attachmentID}{/link}">{$log->filename|tableWordwrap}</a></td>
						{/if}
						<td class="columnText columnFileType">{$log->fileType}</td>
						
						{event name='columns'}
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
	
	<footer class="contentFooter">
		{hascontent}
			<div class="paginationBottom">
				{content}{@$pagesLinks}{/content}
			</div>
		{/hascontent}
		
		{hascontent}
			<nav class="contentFooterNavigation">
				<ul>
					{content}{event name='contentFooterNavigation'}{/content}
				</ul>
			</nav>
		{/hascontent}
	</footer>
{else}
	<p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

{include file='footer'}
