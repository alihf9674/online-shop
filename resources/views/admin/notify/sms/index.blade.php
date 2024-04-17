@extends('admin.layouts.master')

@section('head-tag')
    <title>اطلاعیه پیامکی</title>
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item font-size-12"> <a href="#">خانه</a></li>
            <li class="breadcrumb-item font-size-12"> <a href="#">اطلاع رسانی</a></li>
            <li class="breadcrumb-item font-size-12 active" aria-current="page"> اطلاعیه پیامکی</li>
        </ol>
    </nav>

    <section class="row">
        <section class="col-12">
            <section class="main-body-container">
                <section class="main-body-container-header">
                    <h5>
                        اطلاعیه پیامکی
                    </h5>
                </section>
                <section class="d-flex justify-content-between align-items-center mt-4 mb-3 border-bottom pb-2">
                    <a href="{{ route('admin.notify.sms.create') }}" class="btn btn-info btn-sm">ایجاد اطلاعیه پیامکی</a>
                    <div class="max-width-16-rem">
                        <input type="text" class="form-control form-control-sm form-text" placeholder="جستجو">
                    </div>
                </section>
                <section class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>عنوان اطلاعیه</th>
                            <th>متن اطلاعیه</th>
                            <th>تاریخ ارسال</th>
                            <th>وضعیت</th>
                            <th class="max-width-16-rem text-center"><i class="fa fa-cogs"></i> تنظیمات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($sms as $key => $singleSms)
                        <tr>
                            <th>{{ $loop->iteration }}</th>
                            <td>{{ $singleSms->title }}</td>
                            <td>{{ $singleSms->body }}</td>
                            <td>{{ jalaliDate($singleSms->published_at, 'H:i:s Y-m-d') }}</td>
                            <td>
                                <label>
                                    <input id="{{$singleSms->id}}" onchange="changeStatus({{$singleSms->id}})"
                                           data-url="{{route('admin.notify.sms.status', $singleSms->id)}}"
                                           type="checkbox" @if($singleSms->status === 1) checked @endif>
                                </label>
                            </td>
                            <td class="width-16-rem text-left">
                                <a href="{{ route('admin.notify.sms.edit', $singleSms->id) }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> ویرایش</a>
                                <form class="d-inline"
                                      action="{{route('admin.notify.sms.destroy', $singleSms->id)}}"
                                      method="post">
                                    @csrf
                                    {{method_field('delete')}}
                                    <button class="btn btn-danger btn-sm delete" type="submit"><i
                                            class="fa fa-trash-alt"></i> حذف
                                    </button>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </section>
            </section>
        </section>
    </section>
@endsection

@section('script')
    <script type="text/javascript">
        changeStatus = (id) => {
            let element = $("#" + id);
            let url = element.attr('data-url');
            let elementValue = !element.prop('checked');

            $.ajax({
                url: url,
                type: "GET",
                success: function (response) {
                    if (response.status) {
                        if (response.checked) {
                            element.prop('checked', true);
                            successToast('پیامک با موفقیت فعال شد');
                        } else {
                            element.prop('checked', false);
                            successToast('پیامک با موفقیت غیرفعال شد');
                        }
                    } else {
                        element.prop('checked', elementValue);
                        errorToast('هنگام ویرایش مشکلی بوجود امده است');
                    }
                },
                error: function () {
                    element.prop('checked', elementValue);
                    errorToast('ارتباط برقرار نشد');
                }
            });

            function successToast(message) {

                let successToastTag = '<section class="toast" data-delay="5000">\n'
                    + '<section class="toast-body py-3 d-flex bg-success text-white">\n'
                    + '<strong class="ml-auto">' + message + '</strong>\n'
                    + '<button type="button" class="mr-2 close" data-dismiss="toast" aria-label="Close">\n'
                    + '<span aria-hidden="true">&times;</span>\n'
                    + '</button>\n'
                    + '</section>\n'
                    + '</section>';

                $('.toast-wrapper').append(successToastTag);
                $('.toast').toast('show').delay(5500).queue(function () {
                    $(this).remove();
                });
            }

            function errorToast(message) {
                let errorToastTag = '<section class="toast" data-delay="5000">\n'
                    + '<section class="toast-body py-3 d-flex bg-danger text-white">\n'
                    + '<strong class="ml-auto">' + message + '</strong>\n'
                    + '<button type="button" class="mr-2 close" data-dismiss="toast" aria-label="Close">\n'
                    + '<span aria-hidden="true">&times;</span>\n'
                    + '</button>\n'
                    + '</section>\n'
                    + '</section>';

                $('.toast-wrapper').append(errorToastTag);
                $('.toast').toast('show').delay(5500).queue(function () {
                    $(this).remove();
                });
            }
        }
    </script>
    @include('admin.alerts.sweetalert.delete-confirm',['className' => 'delete'])
@endsection
