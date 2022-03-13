<template>
    <v-container id="comment_tag">
        <v-pagination
            v-if="pagination_total>1"
            v-model="pagination_page"
            :length="pagination_total"
            @input="next"
            :total-visible="7"
            circle
        ></v-pagination>
        <div v-for="(row,key) in comments" v-if="comments!=null">
            <div :class="[row.status===1  ? 'Accepted comment_box' : 'pending_approval comment_box']">
                <div class="comment_header_box">
                    <div>
                        <v-btn v-if="row.status===0" color="green" class="white--text" @click="set_status(row.id,1)">تایید کامنت</v-btn>
                        <v-btn v-else-if="row.status===1" color="red" class="white--text" @click="set_status(row.id,0)">عدم تایید کامنت</v-btn>
                        <span class="mx-16" v-if="row.status===1">تایید شده</span>
                        <span class="mx-16" v-else>در انتظار تایید</span>
                    </div>
                    <div>
                    <delete-link :row-id="row.id" label="کامنت" sendTrash="yes" :url="$siteUrl+'/admin/comments/'+row.id"></delete-link>
                        <span data-toggle="tooltip" data-placement="bottom" title='حذف نظر برای همیشه' onclick="del_row(row.id)">
                        <v-icon>mdi-delete-forever</v-icon>
                    </span>
                        <span data-toggle="tooltip" data-placement="bottom" title='بازیابی نظر' onclick="restore_row(row.id)">
                        <v-icon>mdi-refresh</v-icon>
                    </span>
                    </div>
                </div>
                <div>
                    <div class="title_product">
                        <span>ثبت شده برای : </span>
                        <a :href="$siteUrl+'/product/'+row['product']['slug']">{{ row['product']['title'] }}</a>
                    </div>
                    <div class="comment_content mx-4">
                        <nl2br tag="p" :text="row.content" />
                        <div class="time_reg">
                            <div class="time_reg">
                                <span>ثبت شده توسط</span>
                                <span v-if="row.user_id>0">{{row.name}}</span>
                                <span v-else>کاربر مهمان</span>
                                <span>در تاریخ</span>
                                <span>{{  row.created_at | moment("jDD-jMM-jYYYY  |  h:mm:ss") }}</span>
                            </div>
                        </div>
                    </div>
                    <span class="mx-6" v-if="row['get_parent']">در پاسخ برای پیام :</span>
                    <div class="comment_content mx-10" v-if="row['get_parent']">
                        <nl2br tag="p" :text="row['get_parent']['content']" />
                        <div class="time_reg">
                            <div class="time_reg">
                                <span>ثبت شده توسط</span>
                                <span v-if="row.get_parent.user_id>0">{{row.get_parent.name}}</span>
                                <span v-else>کاربر مهمان</span>
                                <span>در تاریخ</span>
                                <span>{{  row.get_parent.created_at | moment("jDD-jMM-jYYYY  |  h:mm:ss") }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mx-4 mb-4">
                    <v-btn color="primary" @click="add_answer(row)">ثبت پاسخ</v-btn>
                </div>
            </div>
        </div>
        <p v-else style="padding-top: 30px;padding-bottom: 20px;text-align: center">رکوردی برای نمایش یافت نشد</p>
        <v-pagination
            v-if="pagination_total>1"
            v-model="pagination_page"
            :length="pagination_total"
            @input="next"
            :total-visible="7"
            circle
        ></v-pagination>
        <v-dialog
            v-model="dialog"
            persistent
            max-width="600px"
        >
            <v-card>
                <v-card-title>
                    <span>پاسخ پیام</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <div class="bg-yellow-100 text-yellow-600 border-yellow-500 rounded pa-4 font-size-14 my-4">
                            <ul class="list-decimal pr-4">
                                <li>ارسال پاسخ به پیام : {{reply_to}}</li>
                            </ul>
                            <div class="content-area align-justify my-4">
                                <nl2br v-if="reply_content" tag="p" :text="reply_content"/>
                            </div>
                        </div>
                        <v-form
                            ref="form"
                        >
                        <v-textarea id="content" name="content" label="متن پیام" rows="8" :rules="contentRules" v-model="content" counter outlined required></v-textarea>
                        </v-form>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="red darken-1"
                        outlined
                        @click="dialog = false"
                    >
                        انصراف
                    </v-btn>
                    <v-btn
                        color="blue darken-1"
                        outlined
                        @click="send_comment"
                    >
                        ثبت دیدگاه
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
export default {
    name: "CommentPage",
    props: ['auth'],
    data() {
        return {
            dialog:false,
            comments:null,
            reply_to:null,
            reply_content:null,
            product_id:null,
            pagination_page:null,
            pagination_total:null,
            pagination_perPage:null,
            content: '',
            contentRules: [
                v => !!v || 'متن پیام نمی تواند خالی باشد!',
                v => v.length <= 500 || 'حداکثر تعداد کاراکتر 500',
            ],
        }
    },
    mounted(){
        this.get_comments();
    },
    methods: {
        get_comments:function () {
            this.axios.get(this.$siteUrl+'/admin/get_comments').then(response=>{
                this.pagination_page = response.data.current_page;
                this.pagination_total = response.data.last_page;
                this.pagination_perPage = response.data.per_page;
                if (response.data.data.length>0){
                    this.comments=response.data.data;
                }
            });
        },
        next:function (page) {
            this.axios.get(this.$siteUrl+'/admin/get_comments?page='+page).then(response=>{
                this.pagination_page = response.data.current_page;
                this.pagination_total = response.data.last_page;
                this.pagination_perPage = response.data.per_page;
                if (response.data.data.length>0){
                    this.comments=response.data.data;
                    let comments_body = document.getElementById("comment_tag");
                    comments_body.scrollIntoView({behavior: 'smooth'});
                }
            });
        },
        del_row:function (url) {
            this.$root.$emit('send_delete_request',url);
            this.dialog=false;
        },
        set_status:function (id,status) {
            this.$root.$emit('show_progress');
            const formData = new FormData();
            formData.append('comment_id', id);
            formData.append('status', status);
            formData.append('request-type', 'axios');
            this.axios.post(this.$siteUrl + '/admin/comments/set_status', formData).then(response => {
                this.$root.$emit('hide_progress');
                if(response.data['message']!==undefined){
                    const type=response.data['status']===undefined ? 'success' : response.data['status'];
                    this.$root.$emit('showResponse',response.data['message'],type);

                }else{
                    this.$root.$emit('showResponse','درگاه مورد نظر با موفقیت تغییر وضعیت داد.','success');
                }
            });
            this.get_comments();
        },
        add_answer:function (value) {
            this.dialog=true;
            if (value.parent_id===0){
                this.parent_id=value.id;
                this.reply_to=value.name;
                this.reply_content=value.content;
            }else{
                this.parent_id=value['get_parent']['id'];
                this.reply_to=value['get_parent']['name'];
                this.reply_content=value['get_parent']['content'];
            }
            this.product_id=value.product_id;
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
                formData.append('status','1');
                formData.append('request-type', 'axios');
                this.axios.post(this.$siteUrl + '/add_comment', formData).then(response => {
                    this.$root.$emit('hide_progress');
                    if(response.data['message']!==undefined){
                        const type=response.data['status']===undefined ? 'success' : response.data['status'];
                        this.$root.$emit('showResponse',response.data['message'],type);
                    }else{
                        this.comment_box=false;
                        this.$root.$emit('showResponse','نظر با موفقیت ثبت شد.','success');
                    }
                });
                this.get_comments();
            }
        },
    },
}
</script>

<style scoped>

</style>
