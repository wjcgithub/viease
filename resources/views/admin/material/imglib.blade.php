@extends('admin.iframelayout')
@section('content')
<div class="ajax-content">

</div>
<div class="page-list" >
    <div id="page" class="m-pagination " style="clear:both;margin: 20px 30px;float:right"></div>
</div>
<!--BEGIN PAGE WRAPPER-->
<div id="page-wrapper" style="display: none">

    <!--BEGIN CONTENT-->
    <div class="page-content">

        <div id="table-action" class="row ">
            <div class="imgbox">
                <div class="form-group">
                    <input type="hidden" />
                    <img src="" />
                </div>
            </div>
            <div class="col-lg-12 " id="imglist" style="display:none">


            </div>
        </div>
    </div>
    <!--END CONTENT-->
</div>
@stop

@section('css')
    <link rel="stylesheet" href="{{asset('js/plugins/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/JqueryPagination/jquery.pagination.css')}}">
@stop
@section('js')
    <script>
        require(['pages/material.imglib']);
    </script>
@stop
</body>

</html>
