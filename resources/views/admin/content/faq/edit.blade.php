@extends('admin.layouts.master')

@section('head-tag')
    <title>ویرایش سوال</title>
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item font-size-12"><a href="#">خانه</a></li>
            <li class="breadcrumb-item font-size-12"><a href="#">بخش فروش</a></li>
            <li class="breadcrumb-item font-size-12"><a href="#">سوالات متداول</a></li>
            <li class="breadcrumb-item font-size-12 active" aria-current="page">ویرایش سوال</li>
        </ol>
    </nav>

    <section class="row">
        <section class="col-12">
            <section class="main-body-container">
                <section class="main-body-container-header">
                    <h5>
                        ویرایش سوال
                    </h5>
                </section>
                <section class="d-flex justify-content-between align-items-center mt-4 mb-3 border-bottom pb-2">
                    <a href="{{ route('admin.content.faq.index') }}" class="btn btn-info btn-sm">بازگشت</a>
                </section>
                <section>
                    <form action="{{route('admin.content.faq.update', $faq->id)}}" method="post" id="form">
                        {{method_field('put')}}
                        @csrf
                        <section class="row">
                            <section class="col-12">
                                <div class="form-group">
                                    <label for="">پرسش</label>
                                    <input type="text" value="{{old('question',$faq->question)}}" name="question"
                                           class="form-control form-control-sm">
                                </div>
                                @error('question')
                                <span class="alert_required bg-danger text-white p-1 rounded" role="alert">
                                    <strong>
                                        {{$message}}
                                    </strong>
                                </span>
                                @enderror
                            </section>

                            <section class="col-12">
                                <div class="form-group">
                                    <label for="">پاسخ</label>
                                    <textarea name="answer" id="answer" class="form-control form-control-sm"
                                              rows="6">{{old('answer',$faq->answer)}}</textarea>
                                </div>
                                @error('answer')
                                <span class="alert_required bg-danger text-white p-1 rounded" role="alert">
                                    <strong>
                                        {{$message}}
                                    </strong>
                                </span>
                                @enderror
                            </section>
                            <section class="col-12 col-md-6 my-2">
                                <div class="form-group">
                                    <label for="tags">تگ ها</label>
                                    <input type="hidden" class="form-control form-control-sm" name="tags" id="tags"
                                           value="{{old('tags', $faq->tags)}}">
                                    <select class="form-control form-control-sm select2" id="select_tags" multiple>

                                    </select>
                                </div>
                                @error('tags')
                                <span class="alert_required bg-danger text-white p-1 rounded" role="alert">
                                    <strong>
                                        {{$message}}
                                    </strong>
                                </span>
                                @enderror
                            </section>

                            <section class="col-12 col-md-6 my-2">
                                <div class="form-group">
                                    <label for="status">وضعیت</label>
                                    <select name="status" class="form-control form-control-sm" id="status">
                                        <option value="0" @if(old('status',$faq->status) == 0) selected @endif>
                                            غیرفعال
                                        </option>
                                        <option value="1" @if(old('status',$faq->status) == 1) selected @endif>
                                            فعال
                                        </option>
                                        @error('status')
                                        <span class="alert_required bg-danger text-white p-1 rounded" role="alert">
                                    <strong>
                                        {{$message}}
                                    </strong>
                                </span>
                                        @enderror
                                    </select>
                                </div>
                            </section>

                            <section class="col-12">
                                <button class="btn btn-primary btn-sm">ثبت</button>
                            </section>
                        </section>
                    </form>
                </section>
            </section>
        </section>
    </section>
@endsection

@section('script')

    <script src="{{ asset('admin-assets/ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('answer');
    </script>

    <script>
        $(document).ready(() => {
            let tagsInput = $('#tags');
            let selectTags = $('#select_tags');
            let selectedTagsValue = tagsInput.val();
            let selectedTagsData = null;

            if (tagsInput.val() !== null && tagsInput.val().length > 0) {
                selectedTagsData = selectedTagsValue.split(',');
            }

            selectTags.select2({
                placeholder: 'لطفا تگ های خود را وارد نمایید',
                tags: true,
                data: selectedTagsData
            });

            selectTags.children('option').attr('selected').trigger('change');

            $('#form').submit(() => {
                if (selectTags.val() !== null && selectTags.val().length > 0) {
                    let selectedSource = selectTags.val().join(',');
                    tagsInput.val(selectedSource)
                }
            });
        });
    </script>
@endsection
