{if $__wcf->session->getPermission('mod.kas.canClearMails')}
	<ul>
		<li>
			<a 
				onclick="
					WCF.System.Confirmation.show('{jslang}wcf.page.kasMail.button.list.clear.sure{/jslang}', $.proxy(function (action) {
						if (action == 'confirm')
							window.location.href = $(this).attr('href');
					}, this));
					return false;
				" 
				href="{link controller='KasMaillistResetList'}{/link}" class="button">
					{icon name='refresh'}
					<span>{lang}wcf.page.kasMail.button.list.clear{/lang}</span>
			</a>
		</li>
	</ul>
{/if}