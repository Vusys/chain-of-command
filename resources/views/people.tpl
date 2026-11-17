{extends file="layouts/main.tpl"}
{block name="title"}People on {$on->format('j M Y')}{/block}
{block name="content"}
    <h1>
        People
        <small>{$results->total()} in post on {$on->format('j F Y')}{if $department} · {department_path department=$department}{/if}</small>
    </h1>

    <form class="as-of" method="get" action="{route name='people.index'}" role="search">
        <label>Search <input type="search" name="q" value="{$q}" placeholder="Name or title"></label>
        <label>Department
            <select name="department">
                <option value="">Everyone</option>
                {foreach $departments as $option}
                    <option value="{$option->id}"{if $department && $department->id == $option->id} selected{/if}>{str_repeat('— ', $option->depth)}{$option->name}</option>
                {/foreach}
            </select>
        </label>
        <label>On <input type="date" name="on" value="{$on->toDateString()}"></label>
        <button type="submit">Find</button>
    </form>

    {if $results->isEmpty()}
        <p class="notice" data-no-results>Nobody matches{if $q} “{$q}”{/if}.</p>
    {else}
        <ul class="directory">
            {foreach $results as $assignment}
                {include file="partials/directory-row.tpl"}
            {/foreach}
        </ul>
        {$results->links('pagination::simple-tailwind')}
    {/if}
{/block}
