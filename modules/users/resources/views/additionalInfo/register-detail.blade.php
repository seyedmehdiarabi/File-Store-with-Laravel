<?php
$args = ['title' => 'تکمیل اطلاعات کاربری : ' . getUserNmae($additionalInfo->id)];
$j = 0;
?>
<x-panel-box :args="$args">

    <register-detail>

        <table class="table table-bordered user-detail-table">

            @for($i=0;$i<ceil(sizeof($personal_user_detail)/2);$i++)
                <tr>
                    <td @if(!array_key_exists(($j+1),$personal_user_detail)) colspan="2"
                        style="text-align: center" @endif>
                        <?php
                        $editValue = $personal_user_detail[$j]['value'];
                        if (array_key_exists('value2', $personal_user_detail[$j])) {
                            $editValue = $personal_user_detail[$j]['value2'];
                        }
                        ?>
                        <a>
                            <div class="box-insert-additional-info">
                                {{ $personal_user_detail[$j]['title'] }}
                                <span> {{ $personal_user_detail[$j]['value'] }}</span>
                            </div>
                            <div
                                onclick="vm.$root.$emit('edit_user_{{ $personal_user_detail[$j]['attr'] }}','{{ $editValue }}')">
                                <v-icon>mdi-circle-edit-outline</v-icon>
                            </div>
                        </a>
                    </td>
                    <?php $j++ ?>
                    @if(array_key_exists($j,$personal_user_detail))
                        <td>
                            <?php
                            $editValue = $personal_user_detail[$j]['value'];
                            if (array_key_exists('value2', $personal_user_detail[$j])) {
                                $editValue = $personal_user_detail[$j]['value2'];
                            }
                            ?>
                            <a>
                                <div class="box-insert-additional-info">
                                    {{ $personal_user_detail[$j]['title'] }}
                                    <span> {{ $personal_user_detail[$j]['value'] }}</span>
                                </div>
                                <div
                                    onclick="vm.$root.$emit('edit_user_{{ $personal_user_detail[$j]['attr'] }}','{{ $editValue }}')">
                                    <v-icon>mdi-circle-edit-outline</v-icon>
                                </div>
                            </a>
                        </td>
                        <?php $j++ ?>
                    @endif

                </tr>
            @endfor

        </table>

        <user-edit-father-name></user-edit-father-name>
        <user-edit-back-card-number></user-edit-back-card-number>
        <user-edit-email></user-edit-email>
        <user-edit-national-identity-number></user-edit-national-identity-number>
        <date-of-birth></date-of-birth>
        <user-edit-ceremonial-title></user-edit-ceremonial-title>
        <user-edit-id-number></user-edit-id-number>
        <user-edit-phone></user-edit-phone>
        <user-edit-reagent></user-edit-reagent>
        <user-edit-introduction></user-edit-introduction>
        <user-edit-action></user-edit-action>
        <user-edit-description></user-edit-description>
        <profile-address
            :user_id="{{$additionalInfo->user_id}}"
        >
            <template v-slot:loading_box>
                <a href="{{ url('') }}">
                    <img  src="{{ asset(config('shop-info.shop_icon')) }}" class="loading-logo">
                </a>
            </template>
        </profile-address>

    </register-detail>

</x-panel-box>
