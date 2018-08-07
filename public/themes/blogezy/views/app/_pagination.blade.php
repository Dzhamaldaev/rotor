<nav>
    <ul class="pagination">
        @foreach ($pages as $page)
            @if (isset($page['separator']))
                <li class="page-item disabled"><span class="page-link">{{ $page['name'] }}</span></li>
            @elseif (isset($page['current']))
                <li class="active"><span class="page-link">{{ $page['name'] }}</span></li>
            @else
                <li><a class="page-link" href="?page={{ $page['page'] }}{{ $request }}">{{ $page['name'] }}</a></li>
            @endif
        @endforeach
    </ul>
</nav>
