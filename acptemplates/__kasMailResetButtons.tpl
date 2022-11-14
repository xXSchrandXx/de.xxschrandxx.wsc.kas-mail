<ul>
	<li>
		<a 
			onclick="
				WCF.System.Confirmation.show('{jslang}wcf.acp.kasMail.clear.sure{/jslang}', $.proxy(function (action) {
					if (action == 'confirm')
						window.location.href = $(this).attr('href');
				}, this));
				return false;
			" 
			href="{link controller='KasMailResetList'}{/link}" class="button">
				<span class="icon icon16 fa-refresh"></span>
				<span>{lang}wcf.acp.kasMail.button.clear{/lang}</span>
		</a>
	</li>
	<li>
		<a href="{link controller='KasMailAdd'}{/link}" class="button">
			<span class="icon icon16 fa-plus"></span>
			<span>{lang}wcf.acp.form.kasMail.button.add{/lang}</span>
		</a>
	</li>
</ul>