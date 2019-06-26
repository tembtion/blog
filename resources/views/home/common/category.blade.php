@inject('widget', 'App\Services\Widget')
<div class="sidebar hidden-xs">
    <div class="title">
        <h2>
            <i aria-hidden="true" class="glyphicon glyphicon-list"></i>
            分类
        </h2>
    </div>
    <div class="sidebar-body category">
        <ul class="list-unstyled">
            @foreach($widget->getCategory() as $value)
                <li>
                    <a class="text-primary" href="{{ URL::route('homeCategoryIndex', ['term_id' => $value->term_id]) }}">
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        {{ $value->term->name }}
                        <span class="badge pull-right">{{ $value->count }}</span>
                    </a>
                </li>
            @endforeach
        </ul>

    </div>
</div>