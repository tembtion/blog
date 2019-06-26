@extends('home.common.base')

@section('title', "")
@section('keywords', "")
@section('description', "")

@section('css')
<link rel="stylesheet" href="{{ URL::asset('/') }}home/css/photo.css" type="text/css" media="all">
@endsection

@section('main')
    <div id="photo"></div>
    <div id="more"></div>
@endsection

@section('footer')
@endsection

@section('js')
    <script type="text/javascript" src="{{ URL::asset('/') }}home/js/imagesloaded.js"></script>
    <script type="text/javascript" src="{{ URL::asset('/') }}home/js/masonry.js"></script>
    <script type="text/javascript" src="{{ URL::asset('/') }}home/js/photo.js"></script>
    <script type="text/javascript">
        <!--
        $(function(){
            var $container = $('#photo');
            var columnWidth = 236;
            var gutter = 16;

            var photo = new Photo({
                'url': "{{ URL::route('homePhotoItem', array('album_id' => $album_id)) }}",
                'container': $container,
                'columnWidth': columnWidth,
                'gutter': gutter,
                'itemSelector': '.photo-item'});
        })
        //-->
    </script>
@endsection