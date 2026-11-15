{extends file="layouts/main.tpl"}
{block name="title"}Org chart on {$on->format('j M Y')}{/block}
{block name="content"}
    <form class="as-of" method="get" action="{route name='chart'}">
        <a class="button" href="{route name='chart' on=$previous->toDateString()}" rel="prev">&larr; {$previous->format('M Y')}</a>
        <label>On <input type="date" name="on" value="{$on->toDateString()}"></label>
        <label>As we knew it on <input type="date" name="known" value="{if $known}{$known->toDateString()}{/if}"></label>
        <button type="submit">Show</button>
        <a class="button" href="{route name='chart' on=$next->toDateString()}" rel="next">{$next->format('M Y')} &rarr;</a>
    </form>

    <h1>
        Kestrel Labs on {$on->format('j F Y')}
        <small>{$headcount} people{if $known} · as we knew it on {$known->format('j F Y')}{/if}</small>
    </h1>

    <ul class="tree">
        {foreach $departments as $department}
            {$people = $byDepartment->get($department->id, [])}
            <li class="department depth-{$department->depth}" data-department="{$department->name}">
                <h2>{$department->name} <span class="count">{count($people)}</span></h2>
                {if count($people)}
                    <ul class="people">
                        {foreach $people as $assignment}
                            <li class="person" data-person="{$assignment->person->name}">
                                <strong>{$assignment->person->name}</strong>
                                <span class="title">{$assignment->title}</span>
                                <span class="manager">{if $assignment->manager}reports to {$assignment->manager->name}{/if}</span>
                                <span class="salary">{$assignment->salary|currency:'GBP':'en_GB':0}</span>
                            </li>
                        {/foreach}
                    </ul>
                {/if}
            </li>
        {/foreach}
    </ul>
{/block}
