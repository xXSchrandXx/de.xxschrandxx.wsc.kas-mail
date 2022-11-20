<ul>
	<li>
		<a 
			onclick="
				WCF.System.Confirmation.show('{jslang}wcf.acp.page.kasMail.button.list.clear.sure{/jslang}', $.proxy(function (action) {
					if (action == 'confirm')
						window.location.href = $(this).attr('href');
				}, this));
				return false;
			" 
			href="{link controller='KasMaillistResetList'}{/link}" class="button">
				<span class="icon icon16 fa-refresh"></span>
				<span>{lang}wcf.acp.page.kasMail.button.list.clear{/lang}</span>
		</a>
	</li>
</ul>