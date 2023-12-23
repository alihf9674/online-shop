@extends('admin.layouts.master')

@section('head-tag')
    <title>دسته بندی</title>
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item font-size-14"><a href="#">خانه</a></li>
            <li class="breadcrumb-item font-size-14"><a href="#">پخش فروش</a></li>
            <li class="breadcrumb-item font-size-14 active" aria-current="page">دسته بندی</li>
        </ol>
    </nav>

    <section class="row">
        <section class="col-12">
            <section class="main-body-container">
                <section class="main-body-container-header">
                    <h5>دسته بندی ها</h5>
                </section>

                <section class="d-flex justify-content-between align-items-center mt-4 mb-3 border-bottom">
                    <a href="#" class="btn btn-info btn-sm">ایجاد دسته بندی</a>
                    <div class="width-16-rem">
                        <input type="text" class="form-control form-control-sm form-text" placeholder="جستجو">
                    </div>
                </section>

                <section class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>نام دسته بندی</th>
                            <th>دسته والد</th>
                            <th><i class="fa fa-cogs"></i>تنظیمات</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th>1</th>
                            <td>نمایشگر</td>
                            <td>کالای الکتریکی</td>
                            <td>
                                <a href="" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i>ویرایش</a>
                                <button class="btn btn-danger btn-sm"><i class="fa fa-trash-alt"></i>حذف</button>
                            </td>
                        </tr>
                        <tr>
                            <th>2</th>
                            <td>نمایشگر</td>
                            <td>کالای الکتریکی</td>
                            <td>
                                <a href="" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i>ویرایش</a>
                                <button class="btn btn-danger btn-sm"><i class="fa fa-trash-alt"></i>حذف</button>
                            </td>
                        </tr>
                        <tr>
                            <th>3</th>
                            <td>نمایشگر</td>
                            <td>کالای الکتریکی</td>
                            <td>
                                <a href="" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i>ویرایش</a>
                                <button class="btn btn-danger btn-sm"><i class="fa fa-trash-alt"></i>حذف</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </section>
            </section>
        </section>
    </section>
@endsection
