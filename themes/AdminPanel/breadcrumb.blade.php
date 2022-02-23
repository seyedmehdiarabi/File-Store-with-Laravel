<?php
use App\Lib\Jdf;
$Jdf=new Jdf();
?>
<div class="pishkhan">
    <div class="row">
        <div class="col-12">
            <div class="breadcrumb">
                <ul class="list-inline">
                    <li>
                        <a href="{{ url('admin') }}" class="router-link">
                            <v-icon color="white">mdi-home</v-icon>
                            <span>پیشخوان</span>
                            @if(isset($data))
                                <v-icon color="white">mdi-chevron-left</v-icon>
                            @endif
                        </a>
                    </li>
                    @if(isset($data) && is_array($data))
                        @foreach($data as $key=>$value)
                            <li>
                                <a href="{{ $value['url'] }}" class="router-link">
                                    <span>{{ $value['title'] }}</span>
                                    @if($key!=(sizeof($data)-1) || isset($_GET['trashed']))
                                        <v-icon color="white">mdi-chevron-left</v-icon>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    @endif
                    @if(isset($_GET['trashed']))
                        <li>
                            <a class="router-link">
                                <span>سطل زباله</span>
                            </a>
                        </li>
                    @endif
                    <li class="date_li">
                        <v-icon color="white">mdi-calendar</v-icon>
                        <span>امروز</span>
                        <span> {{ $Jdf->jdate('l') }} </span>
                        <span> {{ $Jdf->jdate('j') }} </span>
                        <span> {{ $Jdf->jdate('F') }} </span>
                        <span> {{ $Jdf->jdate('Y') }} </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
