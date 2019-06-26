@foreach($photos as $value)
<div class="photo-item">
    <div class="photo-inner">
        <a data-gallery="photo" href="{{ show_photo($value->photo_key) }}">
            <img src="{{ show_photo($value->photo_key) }}?imageView2/2/w/236/q/100" class="img-responsive center-block" />
        </a>
        <p class="photo-desc">{{ $value->photo_name }}</p>
    </div>
</div>
@endforeach