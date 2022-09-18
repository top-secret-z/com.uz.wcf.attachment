<ul class="sidebarItemList">
	{foreach from=$userProfiles item=userProfile}
		<li class="box32">
			<a href="{link controller='User' object=$userProfile}{/link}" aria-hidden="true">{@$userProfile->getAvatar()->getImageTag(32)}</a>
			
			<div class="sidebarItemTitle">
				<h3><a href="{link controller='User' object=$userProfile}{/link}" class="userLink" data-user-id="{@$userProfile->userID}">{$userProfile->username}</a></h3>
				<small>{lang user=$userProfile}wcf.user.uzattachments.top{/lang}</small>
			</div>
		</li>
	{/foreach}
</ul>
