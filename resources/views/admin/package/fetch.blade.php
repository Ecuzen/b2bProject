@php
    $size = sizeof($column);
@endphp
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    {{$label}}
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <form id="formdata">
                        <table id="file_export" class="table border table-striped table-bordered display text-nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    @for($j=0;$j<$size;$j++)
                                        <th>{{ strtoupper($column[$j]) }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $i = 0
                                @endphp
                                @foreach($tableData as $data)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        @for($j=0;$j<$size - 1;$j++)
                                            <td><input class="form-control numeric-input" type="text" name="{{$column[$j]}}{{$i}}" value="{{ data_get($data, $column[$j]) }}"></td>
                                            @continue
                                        @endfor
                                        
                                       
                                        @if($data->percent == 1)
                                            <td><input class=" form-control form-check-input" name="{{$column[$size-1]}}{{$i}}" value="1" type="checkbox" checked></td>
                                        @else
                                            <td><input class=" form-control form-check-input" name="{{$column[$size-1]}}{{$i}}" value="1" type="checkbox" ></td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                         </table>
                         <div>
                            <button type="submit" class="btn btn-primary float-end">save charges</button>
                         </div>
                     </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var numericInputs = document.getElementsByClassName('numeric-input');
    for (var i = 0; i < numericInputs.length; i++) {
        numericInputs[i].addEventListener('input', function(event) {
            this.value = this.value.replace(/[^0-9.]/g, '');
        });
    }
    
    var previlage = @json($previlage);
    if(previlage == 'user')
    {
        $("input").attr("readonly", true);
        $(".form-check-input").attr("disabled", true);
        $("button.btn.btn-primary.float-end[type='submit']").remove();
    }
    
    $(document).ready(function(){
        $("#formdata").on('submit',function(e){
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('table', @json($table));
            formData.append('package', @json($package));
            formData.append('bracket', @json($bracket));
            $.ajax({
                url : '/admin-save-comm-charges',
                method :'post',
                data:formData,
                    processData:false,
                    contentType:false,
                    cache:false,
                success:function(data)
                {
                    if(data.status == 'SUCCESS')
                    successReload(data.message);
                    else
                    error(data.message);
                }
            })
        })
    })
</script>