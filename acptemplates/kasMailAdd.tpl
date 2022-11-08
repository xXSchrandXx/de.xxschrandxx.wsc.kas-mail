{assign var=pageTitle value='wcf.acp.form.kasMail'|concat:$action}
{append var=pageTitle value='.title'}

{include file='header'}

<header class="contentHeader">
    <div class="contentHeaderTitle">
        <h1 class="contentTitle">{lang}wcf.acp.form.kasMail{$action}.title{/lang}</h1>
    </div>
</header>

{@$form->getHtml()}

{include file='footer'}