{if $__wcf->session->getPermission('mod.kas.canClearMails')}
	<ul>
		<li>
			<a 
				onclick="
					WCF.System.Confirmation.show('{jslang}wcf.page.kasMail.button.mail.clear.sure{/jslang}', $.proxy(function (action) {
						if (action == 'confirm')
							window.location.href = $(this).attr('href');
					}, this));
					return false;
				" 
				href="{link controller='KasMailResetList'}{/link}" class="button">
					{icon name='refresh'}
					<span>{lang}wcf.page.kasMail.button.mail.clear{/lang}</span>
			</a>
		</li>
	</ul>
{/if}