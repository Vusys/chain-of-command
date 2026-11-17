{* One person in the directory: name and title with the search term marked, and where they sit. *}
<li class="person" data-person="{$assignment->person->name}">
    <a class="name" href="{route name='people.show' person=$assignment->person_id}">{highlight text=$assignment->person->name term=$q}</a>
    <span class="title">{highlight text=$assignment->title term=$q}</span>
    <span class="manager">{department_path department=$assignment->department}</span>
    <span class="salary">{$assignment->salary|currency:'GBP':'en_GB':0}</span>
</li>
