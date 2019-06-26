@inject('widget', 'App\Services\Widget')
<div class="sidebar hidden-xs">
    <div class="title">
        <h2>
            <i aria-hidden="true" class="glyphicon glyphicon-file"></i>
            最新文章
        </h2>
    </div>
    <div class="sidebar-body article">
        <ul class="list-unstyled">
            @foreach($widget->getPost() as $value)
                <li>
                    <a href="{{ URL::route('homePostIndex', ['post_id' => $value->post_id]) }}">
                        <nobr><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span> {{ $value->post_title }}</nobr>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>