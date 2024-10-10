<ul class="small pl-4">
    @foreach($groups AS $group)
        <li>
            <a href="{{ route('group', [$city->url, $group->url]) }}">{{ $group->name }}</a>
        </li>
    @endforeach
</ul>