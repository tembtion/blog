@extends('admin.base')

@section('breadcrumbs')
    <ul class="breadcrumbs">
        <li><a href="/admin"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
        <li>汽车一览</li>
    </ul>
@endsection

@section('contents')
<h4 class="widgettitle">汽车一览</h4>
<table id="dyntable" class="table table-bordered responsive">
    <colgroup>
        <col class="con0" style="align: center; width: 4%" />
        <col class="con1" />
        <col class="con0" />
        <col class="con1" />
        <col class="con0" />
        <col class="con1" />
    </colgroup>
    <thead>
        <tr>
          	<th class="head0 nosort"><input type="checkbox" class="checkall" /></th>
            <th class="head0">ID</th>
            <th class="head1">汽车名称</th>
            <th class="head0">价格</th>
            <th class="head1">阅览数</th>
            <th class="head0">首页显示</th>
            <th class="head1">创建时间</th>
            <th class="head0">操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cars as $car)
        <tr class="gradeX" data-id="{{ $car->car_id }}">
          <td class="aligncenter"><span class="center">
            <input type="checkbox" />
          </span></td>
            <td>{{ $car->car_id }}</td>
            <td>{{ $car->car_name }}</td>
            <td>{{ $car->car_price }}</td>
            <td class="center">{{ $car->read_count }}</td>
            <td class="center">
                @if ($car->is_top == 1)
                    <a class="topset" href="javascript:;"><span class="icon-ok"></span></a>
                @else
                    <a class="topset" href="javascript:;"><span class="icon-remove"></span></a>
                @endif
            </td>
            <td class="center">{{ $car->created_at }}</td>
            <td class="center">
            <a target="_blank" href="/car/{{ $car->car_id }}">
            <span class="icon-eye-open"></span>
            </a>
            <a href="/admin/car/edit/{{ $car->car_id }}">
            <span class="icon-edit"></span>
            </a>
            <a class="deleterow" href="javascript:;" data-car-id="{{ $car->car_id }}">
            <span class="icon-trash"></span>
            </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

@section('js')
    <script type="text/javascript" src="{{ URL::asset('/') }}shamcey/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function(){
            // dynamic table
            jQuery('#dyntable').dataTable({
                "sPaginationType": "full_numbers",
                "aaSortingFixed": [[0,'asc']],
                "fnDrawCallback": function(oSettings) {
                    jQuery.uniform.update();
                }
            });

            jQuery('#dyntable2').dataTable( {
                "bScrollInfinite": true,
                "bScrollCollapse": true,
                "sScrollY": "300px"
            });

            jQuery('#dyntable2').dataTable( {
                "bScrollInfinite": true,
                "bScrollCollapse": true,
                "sScrollY": "300px"
            });
            jQuery('.deleterow').click(function(e){
                e.preventDefault();
                var self = this;
                var carId = jQuery(self).data('carId');
                jConfirm('确定要删除吗?', '删除提示', function(result) {
                    if (result === true) {
                        var data = {'carId' : carId,
                            '_token': jQuery('meta[name="csrf-token"]').attr('content')};
                        $.post('/admin/car/delete', data, function(response){
                            if (response.result !== true) {
                                jQuery.jGrowl(response.message);
                                return false;
                            }
                            jQuery(self).closest('tr').remove();
                        })
                    }
                });
            });
            jQuery(document).on('click','.topset',function(e){
                e.preventDefault();
                var self = this;
                var carId = jQuery(self).closest('tr').data('id');
                var data = {'carId' : carId,
                            '_token': jQuery('meta[name="csrf-token"]').attr('content')};
                jQuery.post('/admin/car/top', data, function(response){
                    if (response.result !== true) {
                        jQuery.jGrowl(response.message);
                        return false;
                    }
                    if (response.is_top == 1) {
                        jQuery(self).find('span').removeClass('icon-remove').addClass('icon-ok');
                    } else {
                        jQuery(self).find('span').removeClass('icon-ok').addClass('icon-remove');
                    }

                })
            });
        });
    </script>
@endsection
