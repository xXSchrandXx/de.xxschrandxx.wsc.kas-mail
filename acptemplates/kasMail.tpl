{include file='header' pageTitle='wcf.acp.page.kasMail.title'}

<header class="contentHeader">
	<div class="contentHeaderTitle">
		<h1 class="contentTitle">{lang}wcf.acp.page.kasMail.title{/lang}</h1>
	</div>

	<nav class="contentHeaderNavigation">
		<ul>
			<li>
				<a href="#" id="jsKasMailResetListButton" class="button">
					<span class="icon icon16 fa-rotate-right"></span>
				</a>
			</li>
			<li>
				<a href="{link controller='KasMailAdd'}{/link}" class="button">
					<span class="icon icon16 fa-plus"></span>
					<span>{lang}wcf.acp.form.kasMail.title.add{/lang}</span>
				</a>
			</li>
			{event name='contentHeaderNavigation'}
		</ul>
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
							<a href="{link controller='KasMailEdit' id=$mail['mail_login']}{/link}"
								title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip">
								<span class="icon icon16 fa-pencil"></span>
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


<script data-relocate="true">
	require(["xXSchrandXx/Kas/KasMailResetList",], function(KasMailResetList) {
		KasMailResetList.default('jsKasMailResetListButton');
	});
</script>

{include file='footer'}