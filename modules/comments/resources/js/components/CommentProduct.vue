<template>
    <div class="my-5" id="comments-body">
        <div class="d-flex justify-between mb-5 align-center">
            <h3 class="text-gray-600">دیدگاه‌ها و پرسش‌ها</h3>
            <v-btn color="rgba(71,85,105,1)" class="white--text pa-2 rounded-lg cursor-pointer" @click="show_send_comment()">ارسال دیدگاه</v-btn>
        </div>
        <div class="border-p bg-white rounded-lg padding-0 box_shadow_product pa-6 mb-5" v-if="comment_box">
            <div class="d-flex align-center">
                <img class="w-20 h-20 ml-5 rounded-circle border_img bg-gray-300"
                     :src="auth && auth['pic_profile'] ? $siteUrl+'/files/upload/'+auth['pic_profile'] : '/files/icons/default_user.png'"
                     alt="عکس نمایه شخصی">
                <div>
                    <span class="bold" v-if="auth && auth['name']">{{auth['name']}}</span>
                    <span class="bold" v-else>کاربر مهمان</span>
                    <span class="d-block font-size-12" v-if="auth && auth['role']==='admin'">مدیر سایت</span>
                    <span class="d-block font-size-12 mt-1">ارسال دیدگاه</span>
                </div>
            </div>
            <div class="bg-lightBlue-100 text-lightBlue-600 border-lightBlue-300 rounded pa-4 d-flex align-center font-size-12 mt-4">
                <ul class="list-decimal pr-4">
                    <li>دیدگاه شما بعد از <strong>تایید</strong> در قسمت نظرات نمایش داده خواهد شد.</li>
                </ul>
            </div>
            <div class="bg-yellow-100 text-yellow-600 border-yellow-500 rounded pa-4 font-size-14 mt-4" v-if="reply">
                <ul class="list-decimal pr-4">
                    <li>ارسال پاسخ به پیام : {{reply_to}}</li>
                </ul>
                <div class="content-area align-justify my-4">
                    <nl2br tag="p" :text="reply_content" />
                </div>
            </div>
            <v-form
                ref="form"
                >
                <div class="d-flex justify-lg-space-around mt-4">
                    <v-text-field id="name" class="col-12 col-lg-5 col-md-5" name="name" label="نام و نام خانوادگی" outlined required v-if="auth" :value="auth['name']" disabled></v-text-field>
                    <v-text-field id="name" class="col-12 col-lg-5 col-md-5" name="name" :rules="nameRules" v-model="name" label="نام و نام خانوادگی" outlined required v-else></v-text-field>
                    <v-text-field id="email" class="col-12 col-lg-5 col-md-5" dir="ltr" name="email" label="ایمیل" outlined required v-if="auth" :value="auth['email']" disabled></v-text-field>
                    <v-text-field id="email" class="col-12 col-lg-5 col-md-5" dir="ltr" name="email" :rules="emailRules" v-model="email" v-else label="ایمیل" outlined required></v-text-field>
                </div>
                <div class="col-12">
                    <v-textarea id="content" name="content" label="متن پیام" rows="8" :rules="contentRules" v-model="content" counter outlined required></v-textarea>
                </div>
                <div class="d-flex justify-end pt-4">
                    <v-btn color="primary" class="pa-2 mx-2 rounded-lg cursor-pointer" @click="send_comment">ثبت دیدگاه</v-btn>
                    <v-btn color="red" class="white--text pa-2 mx-2 rounded-lg cursor-pointer" @click="comment_box=false">انصراف</v-btn>
                </div>
            </v-form>
        </div>
        <div class="border-p bg-white rounded-lg padding-0 box_shadow_product pa-6 mb-5" v-if="comments!=null" v-for="(row,key) in comments">
            <div class="d-flex justify-between">
                <div class="d-flex align-center">
                    <img class="w-20 h-20 ml-5 rounded-circle border_img bg-gray-300"
                         :src="row['get_user_info'] && row['get_user_info']['pic_profile'] ? $siteUrl+'/files/upload/'+row['get_user_info']['pic_profile'] : $siteUrl+'/files/icons/default_user.png'"
                         alt="عکس نمایه شخصی1111">
                    <div>
                        <span class="bold" v-if="row['get_user_info']">{{ row['get_user_info']['name'] }}</span>
                        <span class="bold" v-else>{{ row.name }}</span>
                        <span class="d-block font-size-12" v-if="row['get_user_info']===null">کاربر مهمان</span>
                        <span class="d-block font-size-12" v-else-if="row['get_user_info']['role']==='admin'">مدیر سایت</span>
                        <span class="d-block font-size-12 mt-1">{{  row.created_at | moment("from", "now") }}</span>
                    </div>
                </div>
                <div class="d-flex h-fit-content">
                    <v-icon @click="add_answer(row)">mdi-arrow-left</v-icon>
                </div>
            </div>
            <div class="content-area align-justify my-5 ">
                <nl2br tag="p" :text="row.content" />
            </div>
            <div class="border-p bg-gray-50 rounded-lg padding-0 box_shadow_product pa-6 mb-5" v-if="row['get_child']" v-for="(row1,key1) in row['get_child']">
                <div class="d-flex justify-between">
                    <div class="d-flex align-center">
                        <img class="w-20 h-20 ml-5 rounded-circle border_img bg-gray-300"
                             :src="row1['get_user_info'] && row1['get_user_info']['pic_profile'] ? $siteUrl+'/files/upload/'+row1['get_user_info']['pic_profile'] : '/files/icons/default_user.png'"
                             alt="عکس نمایه شخصی">
                        <div>

                            <span class="bold" v-if="row1['get_user_info']">{{ row1['get_user_info']['name'] }}</span>
                            <span class="bold" v-else>{{ row1.name }}</span>
                            <span class="d-block font-size-12" v-if="row1['get_user_info']===null">کاربر مهمان</span>
                            <span class="d-block font-size-12" v-else-if="row1['get_user_info']['role']==='admin'">مدیر سایت</span>
                            <span class="d-block font-size-12 mt-1">{{  row1.created_at | moment("from", "now") }}</span>
                        </div>
                    </div>
                </div>
                <div class="content-area align-justify my-5 ">
                    <nl2br tag="p" :text="row1.content" />
                </div>
            </div>
        </div>
        <v-pagination
            v-if="pagination_total>1"
            v-model="pagination_page"
            :length="pagination_total"
            @input="next"
            :total-visible="7"
            circle
        ></v-pagination>
    </div>
</template>

<script>
export default {
    name: "CommentProduct",
    props: ['product_id','auth'],
    data(){
        return{
            comment_box : false,
            comments:null,
            user_id:0,
            reply:false,
            reply_to:null,
            reply_content:null,
            parent_id:0,
            pagination_page:null,
            pagination_total:null,
            pagination_perPage:null,
            valid: true,
            name: '',
            nameRules: [
                v => !!v || 'نام و نام خانوادگی نمی تواند خالی باشد!',
            ],
            email: '',
            emailRules: [
                v => !!v || 'ایمیل نمی تواند خالی باشد!',
                v => /.+@.+\..+/.test(v) || 'ایمیل باید معتبر باشد!',
            ],
            content:'',
            contentRules: [
                v => !!v || 'متن پیام نمی تواند خالی باشد!',
                v => v.length <= 500 || 'حداکثر تعداد کاراکتر 500',
            ],
        }
    },
    mounted(){
        this.axios.get(this.$siteUrl+'/get_comment_product/'+this.product_id).then(response=>{
            this.pagination_page = response.data.current_page;
            this.pagination_total = response.data.last_page;
            this.pagination_perPage = response.data.per_page;
            if (response.data.data.length>0){
                this.comments=response.data.data;
            }else{
                this.comment_box=true;
            }
        });
    },
    methods: {
        show_send_comment:function () {
            this.comment_box=!this.comment_box;
            this.parent_id=0;
            this.reply=null;
            this.reply_to=null;
            this.reply_content=null;
            this.name='';
            this.email='';
            this.content='';
        },
        send_comment:function () {
            if (this.$refs.form.validate()){
                if (this.auth){
                    this.name=this.auth['name'];
                    this.email=this.auth['email'];
                    this.user_id=this.auth['id'];
                }
                this.$root.$emit('show_progress');
                const formData = new FormData();
                formData.append('product_id', this.product_id);
                formData.append('parent_id', this.parent_id);
                formData.append('name', this.name);
                formData.append('email', this.email);
                formData.append('content', this.content);
                formData.append('user_id', this.user_id);
                formData.append('request-type', 'axios');
                this.axios.post(this.$siteUrl + '/add_comment', formData).then(response => {
                    this.$root.$emit('hide_progress');
                    if(response.data['message']!==undefined){
                        const type=response.data['status']===undefined ? 'success' : response.data['status'];
                        this.$root.$emit('showResponse',response.data['message'],type);
                    }else{
                        this.comment_box=false;
                        this.$root.$emit('showResponse','نظر با موفقیت ثبت شد و بعد از تایید مدیر نمایش داده می شود.','success');
                    }
                });
            }
        },
        add_answer:function (value) {
            this.comment_box=true;
            this.reply=true;
            this.parent_id=value.id;
            this.reply_to=value.name;
            this.reply_content=value.content;
            this.name='';
            this.email='';
            this.content='';
            let comments_body = document.getElementById("comments-body");
            comments_body.scrollIntoView({behavior: 'smooth'});
        },
        next:function (page) {
            this.axios.get(this.$siteUrl+'/get_comment_product/'+this.product_id+'?page='+page).then(response=>{
                this.pagination_page = response.data.current_page;
                this.pagination_total = response.data.last_page;
                this.pagination_perPage = response.data.per_page;
                if (response.data.data.length>0){
                    this.comments=response.data.data;
                    let comments_body = document.getElementById("comments-body");
                    comments_body.scrollIntoView({behavior: 'smooth'});
                }
            });
        }
    },
}
</script>

<style scoped>

</style>
