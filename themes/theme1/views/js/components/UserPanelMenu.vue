<template>
    <article class="profile">
        <section class="avatar">
                <span class="img_box" v-on:click="select_file()">
                  <input type="file" name="avatar" id="avatar" v-on:change="loadFile()" accept="image/jpeg,image/png" style="display:none">
                  <img v-bind:src="user.pic_profile ? $siteUrl+'/files/upload/profile/'+user.pic_profile : $siteUrl+'/themes/front/theme1/images/avatar.jpg'" id="avatar_output" alt="تصویر پروفایل">
                </span>
            <span class="text-center d-block" :style="user.verify ===1 ? 'margin-right: -20px' : null">
                    <img v-if="user.verify ===1" class="verifyAccount" v-bind:src="$siteUrl+'/themes/front/theme1/images/verify.svg'" alt="حساب تایید شده">
                    {{user.name}}
                </span>
            <span class="text-center d-block">{{replaceNumber(user.mobile)}}</span>
        </section>
        <section class="left-side">
            <div class="side-wrapper">
                <div class="side-title">پروفایل من</div>
                <aside class="side-menu">
                    <ul class="profile_menu_ul text-center">
                        <li v-for="item in list_menu">
                            <a :href="item.url">
                                <span :class="item.icon"></span>
                                {{item.label}}
                            </a>
                        </li>
                    </ul>
                </aside>
            </div>
        </section>
    </article>
</template>
<script>
import myMixin from "../../../../../../resources/js/myMixin";
export default {
    mixins:[myMixin],
    name: "UserPanelMenu",
    props: ['fields','user'],
    mounted() {
        this.list_menu = this.fields.sideBarMenu;
    },
    data() {
        return {
            list_menu: []
        }
    },
    methods:{
        select_file: function () {
            $("#avatar").click();
        },
        loadFile: function () {
            const render = new FileReader();
            render.onload = function () {
                const output = document.getElementById('avatar_output');
                output.src = render.result;
            };
            render.readAsDataURL(event.target.files[0]);
            this.submitProfile()
        },
        submitProfile: function () {
            $("#loading_box").show();
            const url = this.$siteUrl + "/user/changePicProfile/"+this.user.id;
            const formData = new FormData();
            const imagefile = document.querySelector('#avatar');
            formData.append('avatar', imagefile.files[0]);
            this.axios.post(url, formData).then();
            $("#loading_box").hide();
        },
    }
}
</script>
