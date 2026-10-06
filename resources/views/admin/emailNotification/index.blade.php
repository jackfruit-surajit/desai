@extends('admin.layouts.main')

@section('title', 'Email Management')
@section('breadcrumb-item', 'Site Settings')

@section('breadcrumb-item-active', 'Email Management')

@section('css')
    <!-- [Page specific CSS] start -->
    <!-- [Page specific CSS] end -->
@endsection

@section('content')
        <!-- [ Main Content ] start -->
        <div class="row">
          
          <!-- Multiple Table Control Elements start -->
          <div class="col-sm-12">
            <div class="card">
              <div class="card-header">
                <h5>Email Management</h5>
                
              </div>
              <div class="card-body">
                <div class="table-responsive dt-responsive">
                  <table class="table table-striped table-bordered nowrap" id="email-notification">
                    <thead>
                        <tr>
                            <th scope="col">Email Code</th>
                            <th scope="col">Subject</th>
                            <th scope="col">About</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                      
                    </table>
                </div>
              </div>
            </div>
          </div>
          <!-- Multiple Table Control Elements end -->
          
          
        </div>
        <!-- [ Main Content ] end -->
      
@endsection

@section('scripts')
    <!-- [Page Specific JS] start -->
    
    <script>
        $(function () {
            $('#email-notification').DataTable({
                processing: false,
                serverSide: true,
                ajax: '{!! route("emailNotification-list") !!}',
                columns: [
                    {data: 'email_code', name: 'email_code'},
                    {data: 'subject', name: 'subject'},
                    {data: 'about', name: 'about'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
                
            });
        });
    </script>
    
    <!-- [Page Specific JS] end -->
@endsection