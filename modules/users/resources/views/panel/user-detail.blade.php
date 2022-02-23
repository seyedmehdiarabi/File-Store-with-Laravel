<?php
$args = [];
$args['title'] = 'جزییات اطلاعات کاربر - ' . replace_number($user->name);
?>

<x-panel-box :args="$args">

    <?php
    $personal_user_detail=personal_user_detail($additionalInfo);
    $j=0;
    ?>
        <table class="table table-bordered order_table_info">


            @for($i=0;$i<ceil(sizeof($personal_user_detail)/2);$i++)
                <tr>
                    <td @if(!array_key_exists(($j+1),$personal_user_detail)) colspan="2" style="text-align: center" @endif>
                        {{ $personal_user_detail[$j]['title'] }}
                        <span> {{ $personal_user_detail[$j]['value'] }}</span>
                    </td>
                    <?php $j++ ?>
                    @if(array_key_exists($j,$personal_user_detail))
                        <td>
                            {{ $personal_user_detail[$j]['title'] }}
                            <span> {{ $personal_user_detail[$j]['value'] }}</span>
                        </td>
                        <?php $j++ ?>
                    @endif

                </tr>
            @endfor

        </table>

</x-panel-box>
