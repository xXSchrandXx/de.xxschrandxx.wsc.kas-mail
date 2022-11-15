{include file='header' pageTitle='{lang}wcf.page.kasMail.title{/lang}' contentTitle='{lang}wcf.page.kasMail.title{/lang}'}

<p class="info">{lang}wcf.page.kasMail.kasLink{/lang}</p>

{hascontent}
	<header class="contentHeader">
		<nav class="contentHeaderNavigation">
			{content}
				{include file='__kasMailResetButtons'}
				{event name='contentHeaderNavigation'}
			{/content}
		</nav>
	</header>
{/hascontent}

{if $mails|count}
	<div class="section">
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
						<td class="columnPassword">{$mail['mail_password']}</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
{else}
	<p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

{hascontent}
	<footer class="contentFooter">
		<nav class="contentFooterNavigation">
			{content}
				{include file='__kasMailResetButtons'}
				{event name='contentFooterNavigation'}
			{/content}
		</nav>
	</footer>
{/hascontent}

{include file='footer'}
