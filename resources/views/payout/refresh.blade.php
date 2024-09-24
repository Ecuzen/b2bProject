@foreach($accounts as $account)
                         <tr>
                             <td>
                                 <div class="d-flex align-items-center">
                                    <div class="ms-3">
                                        <h6 class="fs-4 fw-semibold mb-0">{{$account->account}}</h6>
                                        <span class="fw-normal">{{$account->ifsc}}</span>
                                    </div>
                                </div>
                             </td>
                             <td><h6 class="fw-semibold mb-0">{{$account->name}}</h6></td>
                             <td>
                                @if($account->status == 'PENDING')
                                    <span class="badge bg-light-warning rounded-3 py-2 text-primary fw-semibold fs-2 d-inline-flex align-items-center gap-1"><i class="ti ti-alert-triangle fs-4"></i></i>{{$account->status}}</span>
                                @else
                                     <span class="badge bg-light-success rounded-3 py-2 text-primary fw-semibold fs-2 d-inline-flex align-items-center gap-1"><i class="ti ti-check fs-4"></i>{{$account->status}}</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-rounded btn-danger delete" data-delete= "{{$account->id}}"><i class="ti ti-trash"></i></button>
                            </td>
                         </tr>
                         @endforeach