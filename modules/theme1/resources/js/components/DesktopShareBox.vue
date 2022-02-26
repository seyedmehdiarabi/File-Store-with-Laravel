<template>
    <div>

        <v-tooltip left>

            <template v-slot:activator="{ on }">
                <div  v-on="on"
                      @click="dialog=!dialog">
                    <v-icon
                    >
                        mdi-share-variant
                    </v-icon>
                </div>

            </template>
            <span>اشتراک گذاری</span>

        </v-tooltip>

        <v-dialog
            v-model="dialog"
            width="450"
        >

            <v-card class="share-box">
                <v-card-title class="headline lighten-2">
                    <h5>اشتراک ‌گذاری</h5>
                </v-card-title>

                <v-card-text>

                    <p>
                        با استفاده از روش‌های زیر می‌توانید این صفحه را با دوستان خود به اشتراک بگذارید.
                    </p>

                    <ul>
                        <li>
                            <a :href="'https://telegram.me/share/url?'+product_url+'url=&ref=telegram'">
                                <img :src="$siteUrl+'/modules/shop/telegram.png'" class="share-icon" />
                            </a>
                        </li>
                        <li>
                            <a :href="'https://twitter.com/intent/tweet/?url='+product_url">
                                <img :src="$siteUrl+'/modules/shop/twitter.png'" class="share-icon" />
                            </a>
                        </li>
                        <li>
                            <a :href="'https://www.facebook.com/sharer/sharer.php?m2w&s=100&p[url]='+product_url">
                                <img :src="$siteUrl+'/modules/shop/facebook.png'" class="share-icon facebook"/>
                            </a>
                        </li>
                        <li>
                            <a :href="'https://wa.me?text='+product_url">
                                <img :src="$siteUrl+'/modules/shop/whatsapp.png'" class="share-icon" />
                            </a>
                        </li>
                        <li>
                            <a @click="show_form=!show_form">
                                <img :src="$siteUrl+'/modules/shop/envelope.png'" class="share-icon" />
                            </a>
                        </li>
                        <li>
                            <button id="copy_btn" @click="copy_link()">{{ copy_text }}</button>
                        </li>
                    </ul>

                    <div class="share_link_form" v-if="show_form">
                        <div class="email_form">

                            <v-text-field
                                v-model="email"
                                solo
                                label="ایمیل"
                                clearable
                                class="form-control"
                            ></v-text-field>

                            <input type="hidden" id="share_product_id" :value="product_id">
                            <button class="btn send-btn" @click="sendEmail()">
                                <span v-if="send_request">ارسال</span>
                                <v-progress-circular v-else
                                    indeterminate
                                    color="white"
                                ></v-progress-circular>
                            </button>
                        </div>
                        <span class="has_error" v-if="error">{{ error }}</span>
                    </div>

                </v-card-text>


            </v-card>
        </v-dialog>

    </div>
</template>

<script>
    export default {
        name: "DesktopShareBox",
        props:['product_id','product_url','short_product_url'],
        data(){
            return {
                dialog:false,
                email:'',
                error:'',
                show_form:false,
                copy_text:'کپی لینک',
                send_request:true
            }
        },
        methods:{
            validateShareEmailAddress:function(){
                if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.email))
                {
                    this.error=false;
                }
                else{
                    this.error='آدرس ایمیل وارد شده معتبر نمی باشد';
                }
            },
            copy_link:function () {
                const self=this;
                const input=document.createElement('input');
                input.setAttribute('value',this.short_product_url);
                input.setAttribute('id',"url_input");
                input.setAttribute('contenteditable',true);
                document.querySelector('.share-box').append(input);
                input.select();
                document.execCommand('copy');
                document.querySelector('#url_input').remove();
                this.copy_text='کپی شد';
                setTimeout(function(){
                    self.copy_text='کپی لینک';
                },3000);
            },
            sendEmail:function () {
                this.validateShareEmailAddress();
                if(this.email.trim()!='' && this.error===false){
                    if(this.send_request){
                        this.send_request=false;
                        const url=this.$siteUrl+"/site/share_product";
                        this.axios.post(url,{email:this.email,product_id:this.product_id}).then(response=>{
                            if(response.data==='ok'){
                                this.email='';
                            }
                            else{
                                this.error='خطا در ارسال ایمیل مجددا تلاش نمایید';
                            }
                            this.send_request=true;
                        })
                        .catch(error=>{
                            this.send_request=true;
                            this.error='خطا در ارسال ایمیل مجددا تلاش نمایید';
                        });
                    }
                }
            }
        }
    }
</script>

<style>
   @import "../../css/share.css";
</style>
