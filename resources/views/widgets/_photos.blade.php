@if ($photos)
    <div class="section-body">
    @foreach ($photos as $photo)
        @php
            $file = current($photo['files']);
        @endphp

        @if ($file)
            <a href="/photos/{{ $photo['id'] }}">{!! resizeImage($file['hash'], ['alt' => check($photo['title']), 'class' => 'rounded', 'style' => 'width: 100px;']) !!}</a>
        @endif
    @endforeach
    </div>
@endif
