{include file='header' pageTitle='wcf.page.kasMail.title'}

<header class="contentHeader">
	<div class="contentHeaderTitle">
		<h1 class="contentTitle">{lang}wcf.page.kasMail.title{/lang}</h1>
	</div>

	<nav class="contentHeaderNavigation">
		{if !$section|isset || $section == 'mail'}
			{include file='__kasMailResetButtons'}
		{else}
			{include file='__kasMaillistResetButtons'}
		{/if}
		{event name='contentHeaderNavigation'}
	</nav>
</header>

<div class="section sectionContainerList">
	<nav class="tabMenu">
		<ul>
			<li {if !$section|isset || $section == 'mail'}class="active"{/if}>
				<a href="{link controller='KasMail'}section=mail{/link}">
					{lang}wcf.page.kasMail.mail{/lang}
				</a>
			</li>
			<li {if $section == 'list'}class="active"{/if}>
				<a href="{link controller='KasMail'}section=list{/link}">
					{lang}wcf.page.kasMail.list{/lang}
				</a>
			</li>
		</ul>
	</nav>

	{if !$section|isset || $section == 'mail'}
		{if $mails|count}
			<table class="table">
				<thead>
					<tr>
						<th>{lang}wcf.page.kasMail.mail_adresses{/lang}</th>
						<th>{lang}wcf.page.kasMail.mail_login{/lang}</th>
						<th>{lang}wcf.page.kasMail.mail_password{/lang}</th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$mails item=mail}
						<tr>
							<td class="columnTitle">{$mail['mail_adresses']}</td>
							<td class="columnText">{$mail['mail_login']}</td>
							<td class="columnText">{$mail['mail_password']}</td>
						</tr>
					{/foreach}
				</tbody>
			</table>
		{else}
			<p class="info">{lang}wcf.global.noItems{/lang}</p>
		{/if}
	{else}
		{if $maillists|count}
			<div class="section">
				<table class="table">
					<thead>
						<tr>
							<th></th>
							<th>{lang}wcf.page.kasMail.mailinglist_name{/lang}</th>
							<th>{lang}wcf.page.kasMail.mailinglist_is_active{/lang}</th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$maillists item=maillist}
							<tr>
								<td class="columnTitle">{$maillist['mailinglist_name']}</td>
								<td class="columnText">{$maillist['mailinglist_is_active']}</td>
							</tr>
						{/foreach}
					</tbody>
				</table>
			</div>
		{else}
			<p class="info">{lang}wcf.global.noItems{/lang}</p>
		{/if}
	{/if}
</div>

{include file='footer'}
