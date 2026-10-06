@extends('admin.layouts.main')

@section('title', 'Email Management')
@section('breadcrumb-item', 'Email Management')

@section('breadcrumb-item-active', 'Edit')

@section('css')
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">

        <div class="col-lg-12">

            <div class="card">
                <div class="card-header">
                <h5>Update Email of {{$model->about}}</h5>
                </div>
                <div class="card-body">

                    <form class="row g-3" method="post" action="{{Route('emailNotification-edit',['id'=>$model->id])}}" enctype="multipart/form-data">
                        @csrf

                        <div class="col-md-6">
                            <label class="form-label" for="exampleInputSubject">Subject<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="Subject" name="subject" value="{{ (old('subject')!='') ? old('subject') : $model->subject}}" >
                            @if ($errors->has('subject'))
                                <span class="help-block"> {{ $errors->first('subject') }} </span>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="exampleInputPassword1">About<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="About" name="about" value="{{ (old('about')!='') ? old('about') : $model->about}}" >
                            @if ($errors->has('about'))
                                <span class="help-block"> {{ $errors->first('about') }} </span>
                            @endif
                        </div>

                        <div class="col-md-12">
                            <label for="inputBody" class="form-label">Body<span class="required">*</span></label>
                            <textarea class="form-control ckeditor" placeholder="Body" name="body"  id="body">{{ (old('body')!="") ? old('body') : $model->body }}</textarea>
                            @if ($errors->has('body'))
                                <span class="help-block"> {{ $errors->first('body') }} </span>
                            @endif
                        </div>
                        
                    
                        <div class="col-md-6 text-end btn-page">
                            <a href="{{route('emailNotification')}}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>

                        
                    </form>

                </div>
            </div>
          
        </div>
        
    </div>
@endsection
@section('scripts')
    <!-- [Page Specific JS] start -->
    <script src="{{ URL::asset('public/backend/assets/js/plugins/ckeditor/ckeditor.js') }}"></script>
    <script>
      (function () {
          $('.select2').select2();
        // ClassicEditor.create(document.querySelector('#body')).catch((error) => {
        //   console.error(error);
        // });
        ClassicEditor.create(document.querySelector('#body'), {
            
        })
        .then(editor => {
            console.log('Editor was initialized', editor);
        })
        .catch(error => {
            console.error(error);
        });
      })();
    </script>
@endsection
