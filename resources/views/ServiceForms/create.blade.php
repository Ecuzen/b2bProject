
@include('users/header')
<style type="text/css">
.ajax.request
{
    background: rgba(0, 0, 0, 0.3) none repeat scroll 0 0;
    border-radius: 15px;
    bottom: 1px;
    left: 1px;
    position: absolute;
    right: 1px;
    top: 1px;
    z-index: 11111;
}
.ajax.request img
{
    border: 0 none;
    bottom: 0;
    left: 0;
    margin: auto;
    position: absolute;
    right: 0;
    top: 0;
    width: 50px;
}
</style>
<div class="row row-cols-1 row-cols-lg-3">
    <div class="col-12 col-xl-12">
        <div class="ajax request" style="display: none;" id="loader_login">
            <img src="{{asset('public/fancybox_loading.gif')}}">
        </div>
        <h6 class="mb-0 text-uppercase">Form with icons</h6>
        <hr>
        <div class="card border-top border-0 border-4 border-danger">
            <div class="card-body p-5">
                <div class="card-title d-flex align-items-center">
                    <h5 class="mb-0 text-danger">{{ $service_entity->title ?? '' }}</h5>
                </div>
               
                <hr>
                @switch($service_id)
                    @case(2)
                        @include('ServiceForms.Elements.itr')
                    @break
                 
                    @case(3)
                        @include('ServiceForms.Elements.gst_registration')
                    @break
                    
                    @case(4)
                    @case(5)
                        @include('ServiceForms.Elements.pf_registration')
                    @break
                 
                    @default
                        No View Found. Please Check Service is Exist.
                @endswitch
                
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
 
</script>
@include('users/footer')
