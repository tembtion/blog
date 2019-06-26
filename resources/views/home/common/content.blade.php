@foreach ($posts as $value)
    <div class="ibox">
        <div class="ibox-content">
            <div class="row m-t-xs">
                <div class="col-md-12">
                    <div class="ibox-left">
                        <a href="{{ URL::route('homeProfileIndex', ['user_id' => $value->user->id]) }}">
                            <img class="media-object img-circle" data-gallery="" src="{{ show_avatar($value->user->avatar) }}?imageView2/1/w/50/h/50/q/100">
                        </a>
                    </div>
                    <div class="ibox-right">
                        <a class="btn-link" href="{{ URL::route('homePostIndex', ['post_id' => $value->post_id]) }}">
                            <h2>{{ $value->post_title }}</h2>
                        </a>
                        <span class="text-muted small"><i class="glyphicon glyphicon-time"></i> {{ show_date($value->post_date) }}</span>
                    </div>
                </div>
            </div>
            <div class="row m-t-xs">
                <div class="col-md-12">
                    <div class="clearfix word-break">
                        <p>{{ post_excerpt($value->post_content) }}</p>
                    </div>
                </div>
            </div>
            @if ($post_image = post_image($value->post_content))
                @if (count($post_image) == 1)
                    <div>
                        <a data-gallery="post_{{ $value->post_id }}" href="{{ $post_image[0] }}">
                            <img class="img-responsive" src="{{ $post_image[0] }}?imageView2/2/w/230/q/100">
                        </a>
                    </div>
                @else
                    <div class="row m-t-xs" style="padding: 0 15px">
                    @foreach ($post_image as $image)
                        <a class="polaroid col-md-2 col-xs-4 col-sm-2" style="padding: 2px;" data-gallery="post_{{ $value->post_id }}" href="{{ $image }}">
                            <img class="img-responsive" src="{{ $image }}?imageView2/1/w/200/h/200">
                        </a>
                    @endforeach
                    </div>
                @endif
            @endif
            <div class="row m-t">
                <div class="col-md-6 col-xs-6 col-sm-6">
                    @foreach($value->termTaxonomy()->where('taxonomy', 'post_tag')->get() as $tag)
                        <a href="{{ URL::route('homeTagIndex', ['term_id' => $tag->term->term_id]) }}" class="btn bg-info btn-xs">{{ $tag->term->name }}</a>
                    @endforeach
                </div>
                <div class="col-md-6 col-xs-6 col-sm-6">
                    <div class="small text-right">
                        <div>
                            <i class="glyphicon glyphicon-comment"> </i> {{ number_format($value->comment_count) }} 评论
                            <i class="glyphicon glyphicon glyphicon-eye-open"> </i> {{ number_format($value->visitor_count) }} 浏览
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach