{extends file="layouts/main.tpl"}
{block name="title"}{$person->name}{/block}
{block name="content"}
    <p><a href="{route name='chart'}">&larr; Org chart</a></p>
    <h1>{$person->name} <small>{$person->email}</small></h1>

    {if $session->has('status')}<p class="notice" role="status">{$session->status}</p>{/if}
    {error field="from"}<p class="error" role="alert">{$message}</p>{/error}

    <h2>Career, as we know it now</h2>
    <table class="timeline">
        <thead><tr><th>From</th><th>Until</th><th>Department</th><th>Reports to</th><th>Title</th><th>Salary</th><th></th></tr></thead>
        <tbody>
        {foreach $timeline as $period}
            <tr data-period="{$period->valid_from->toDateString()}">
                <td>{$period->valid_from->format('j M Y')}</td>
                <td>{if $period->valid_to}{$period->valid_to->format('j M Y')}{else}now{/if}</td>
                <td>{if $period->department}{$period->department->name}{/if}</td>
                <td>{if $period->manager}{$period->manager->name}{else}—{/if}</td>
                <td>{$period->title}</td>
                <td>{$period->salary|currency:'GBP':'en_GB':0}</td>
                <td>
                    <details class="correct">
                        <summary>Correct this period</summary>
                        <form method="post" action="{route name='people.correct' person=$person->id}">
                            {csrf_field}
                            <input type="hidden" name="from" value="{$period->valid_from->toDateString()}">
                            <input type="hidden" name="to" value="{if $period->valid_to}{$period->valid_to->toDateString()}{/if}">
                            <label>Title <input name="title" value="{$period->title}" required></label>
                            <label>Salary <input name="salary" type="number" min="1" value="{$period->salary}" required></label>
                            <button type="submit">Save correction</button>
                        </form>
                    </details>
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>

    <h2>Change from a date</h2>
    <form class="change" method="post" action="{route name='people.change' person=$person->id}">
        {csrf_field}
        <label>From <input type="date" name="from" min="{$today->toDateString()}" value="{old field='from' default=$today->toDateString()}" required></label>
        <label>New title <input name="title" value="{old field='title'}" required></label>
        <label>New salary <input name="salary" type="number" min="1" value="{old field='salary'}" required></label>
        <button type="submit">Record the change</button>
    </form>

    {if count($superseded)}
        <h2>Beliefs we have since corrected</h2>
        <table class="timeline superseded">
            <thead><tr><th>Period from</th><th>Title</th><th>Salary</th><th>Believed from</th><th>Until</th></tr></thead>
            <tbody>
            {foreach $superseded as $row}
                <tr>
                    <td>{$row->valid_from->format('j M Y')}</td>
                    <td>{$row->title}</td>
                    <td>{$row->salary|currency:'GBP':'en_GB':0}</td>
                    <td>{$row->recorded_from->format('j M Y')}</td>
                    <td>{$row->recorded_to->format('j M Y')}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    {/if}
{/block}
