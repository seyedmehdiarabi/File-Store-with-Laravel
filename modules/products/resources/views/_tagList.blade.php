@php
    $keywords=$product->keywords;
    $e=explode(',',$keywords);
    $i=1;
@endphp
@if(is_array($e))
    @foreach($e as $key=>$value)
        @if(!empty($value))

            <div class="tag_div" id="tag_div_{{ $i }}">
                <span  class="fa fa-remove" onclick="remove_tag({{ $i }},'{{ $value }}')"></span>
                {{ $value }}
            </div>
            @php $i++;  @endphp
        @endif

    @endforeach
@endif
