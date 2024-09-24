@include('users/header')
<div class="row row-cols-1 row-cols-lg-3">
    
    <div class="col-12 col-xl-12">
        <hr>
        <div class="card border-top border-0 border-4 border-primary">
            <div class="card-body p-5">
                <div class="card-title d-flex align-items-center justify-content-between">
                    <div class="d-flex"><i class="bx bx-history me-1 font-22 text-primary"></i>
                    <h5 class="mb-0 text-primary">{{ $service_entity->title ?? '' }}</h5>
                    </div>
                    <a href="{{route('service_form.create',$service_id)}}" class="btn btn-primary">Add {{ $service_entity->title ?? '' }}</a>
                </div>
                <hr>
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>User Name</th>
                                <th>Firm Name</th>
                                <th>Unique Id</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Status</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($entities as $entity_key => $entity)
                                <tr>
                                    <td>{{$entity->User->name ?? ''}}</td>
                                    <td>{{$entity->firm_name ?? ''}}</td>
                                    <td>{{$entity->random_id ?? ''}}</td>
                                    <td>{{$entity->User->email ?? ''}}</td>
                                    <td>{{$entity->User->phone ?? ''}}</td>
                                    <td>
                                        @switch($entity->status)
                                            @case('PENDING')
                                                <a href="javascript:void(0);" class="btn btn-warning">{{$entity->status}}</a>
                                            @break
                                        
                                            @case('ACCEPT')
                                                <a href="javascript:void(0);" class="btn btn-success">{{$entity->status}}</a>
                                            @break
                                            
                                            @case('REJECTED')
                                                <a href="javascript:void(0);" class="btn btn-danger">{{$entity->status}}</a>
                                            @break
                                        
                                            @default
                                                <span>Something went wrong, please try again</span>
                                        @endswitch
                                    </td>
                                </tr>    
                            @empty
                                <tr>No record found</tr>
                            @endforelse
                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>User Name</th>
                                <th>Firm Name</th>
                                <th>Unique Id</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Status</th>
                            </tr>
                        </tfoot>
                    </table>
                    <br>
						{{ $entities->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@include('users/footer')
