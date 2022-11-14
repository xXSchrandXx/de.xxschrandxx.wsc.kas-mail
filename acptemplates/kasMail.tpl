{include file='header' pageTitle='wcf.acp.page.kasMail.title'}

<header class="contentHeader">
	<div class="contentHeaderTitle">
		<h1 class="contentTitle">{lang}wcf.acp.page.kasMail.title{/lang}</h1>
	</div>

	<nav class="contentHeaderNavigation">
		{include file='__kasMailResetButtons'}
		{event name='contentHeaderNavigation'}
	</nav>
</header>

{if $mails|count}
	<div class="section">
		<table class="table">
			<thead>
				<tr>
					<th></th>
					<th>{lang}wcf.acp.page.kasMail.mail_adresses{/lang}</th>
					<th>{lang}wcf.acp.page.kasMail.mail_login{/lang}</th>
					<th>{lang}wcf.acp.page.kasMail.mail_password{/lang}</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$mails item=mail}
					<tr>
						<td class="columnIcon">
							<a href="{link controller='KasMailEdit' mail_login=$mail['mail_login']}{/link}"
								title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip">
								<span class="icon icon16 fa-pencil"></span>
							</a>
							<a 
								onclick="
									WCF.System.Confirmation.show('{jslang}wcf.acp.kasMail.delete.sure{/jslang}', $.proxy(function (action) {
										if (action == 'confirm')
											window.location.href = $(this).attr('href');
									}, this));
									return false;
								" 
								href="{link controller='KasMailDeleteAction' mail_login=$mail['mail_login']}{/link}" class="button">
									<span class="icon icon16 fa-times"></span>
									<span>{lang}wcf.acp.kasMail.button.delete{/lang}</span>
							</a>
							{event name='rowButtons'}
						</td>
						<td class="columnTitle">{$mail['mail_adresses']}</td>
						<td class="columnText">{$mail['mail_login']}</td>
						<td class="columnText">{$mail['mail_password']}</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
{else}
	<p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

<footer class="contentFooter">
	<nav class="contentFooterNavigation">
		{include file='__kasMailResetButtons'}
		{event name='contentFooterNavigation'}
	</nav>
</footer>

{include file='footer'}
