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

    <label class="slider">
        <span>Scrub through time</span>
        <input type="range" data-as-of min="0" max="{count($months) - 1}" value="{$monthIndex}" data-months="{$monthList}">
        <output data-as-of-label>{$on->format('M Y')}</output>
    </label>

    <p class="hint">Drag a department onto another to move it there.</p>
    <p class="error" role="alert" data-chart-message hidden></p>

    <div data-chart-body>{include file="partials/chart-body.tpl"}</div>
    <script src="{asset path='js/chart.js'}" defer></script>
{/block}
