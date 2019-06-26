@foreach($album as $value)
<div class="photo-item ">
    <div class="photo-inner">
        <a class="link  x" href="{{ URL::route('homeAlbumDetail', ['album_id' => $value->album_id]) }}">
            @foreach($value->photo()->limit(5)->get() as $key => $photo)
                @if ($key == 0)
                <img class="large" src="{{ show_photo($photo->photo_key) }}?imageView2/1/w/235/q/100">
                @else
                <img class="normal" src="{{ show_photo($photo->photo_key) }}?imageView2/1/w/70/q/100">
                @endif
            @endforeach
            <div class="over "><h3>{{ $value->album_name }}</h3></div>
        </a>
    </div>
</div>
@endforeach