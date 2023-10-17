@foreach ($actions as $action)
@if(isset($action['type']))
<button type="{{$action['type']}}" class="{{$action['class']}}" 
        id="{{isset($action['id']) ? $action['id'] : ''}}"
        {{isset($action['url'])?'data-url='.$action['url'].'':''}}
        {{isset($action['attr'])? $action['attr'] :'' }}
        >
    @if(isset($action['img']))
    <img src="{{asset($action['img'])}}" />
    @endif
    @if(isset($action['icon']))
    <i class="{{$action['icon']}}"></i>
    @endif
    {{$action['name']}}
</button>
@else
<a class="{{$action['class']}}" href="{{url($action['url'])}}"  
    id="{{isset($action['id']) ? $action['id'] : ''}}"
    @if(isset($action['attr']))
    {{$action['attr']}}
    @endif
    >
    @if(isset($action['img']))
    <img src="{{asset($action['img'])}}" />
    @endif
    @if(isset($action['icon']))
    <i class="{{$action['icon']}}"></i>
    @endif
    {{$action['name']}}
</a>
@endif
@endforeach