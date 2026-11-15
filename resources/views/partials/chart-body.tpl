{* The part of the chart that changes with the date — swapped in place by the as-of slider. *}
<h1>
    Kestrel Labs on {$on->format('j F Y')}
    <small>{$headcount} people{if $known} · as we knew it on {$known->format('j F Y')}{/if}</small>
</h1>

<ul class="tree">
    {foreach $departments as $department}
        {$people = $byDepartment->get($department->id, [])}
        <li class="department depth-{$department->depth}" data-department="{$department->name}" data-department-id="{$department->id}" style="margin-left: {$department->depth * 28}px"{if $department->depth > 0} draggable="true"{/if}>
            <h2>{$department->name} <span class="count">{count($people)}</span></h2>
            {if count($people)}
                <ul class="people">
                    {foreach $people as $assignment}
                        <li class="person" data-person="{$assignment->person->name}">
                            <a class="name" href="{route name='people.show' person=$assignment->person_id}">{$assignment->person->name}</a>
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
