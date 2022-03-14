<template>
    <div>
        <v-card>
            <div class="profile-personal_grid">
                <div class="profile-personal_grid-item px-4">
                    <div>
                        <div class="profile-personal_grid-item-title">
                            نام و نام خانوادگی
                        </div>
                        <div class="profile-personal_grid-item-value">
                            {{user['name']}}
                        </div>
                    </div>
                    <v-icon @click="edit_profile('name')">mdi-pencil-outline</v-icon>
                </div>
                <div class="profile-personal_grid-item px-4">
                    <div>
                        <div class="profile-personal_grid-item-title">
                            ايميل
                        </div>
                        <div class="profile-personal_grid-item-value">
                            {{user['email']}}
                        </div>
                    </div>
                    <v-icon @click="edit_profile('email')">mdi-pencil-outline</v-icon>
                </div>
                <div class="profile-personal_grid-item px-4">
                    <div>
                        <div class="profile-personal_grid-item-title">
                            شماره تلفن همراه
                        </div>
                        <div class="profile-personal_grid-item-value">
                            {{replaceNumber(user['mobile'])}}
                        </div>
                    </div>
                    <v-icon @click="edit_profile('mobile')">mdi-pencil-outline</v-icon>
                </div>
                <div class="profile-personal_grid-item px-4">
                    <div>
                        <div class="profile-personal_grid-item-title">
                            عكس نمايه
                        </div>
                    </div>
                    <div class="">
                        <v-tooltip bottom color="error">
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon v-bind="attrs" v-on="on" class="crimson" @click="delete_profile()">mdi-delete-outline</v-icon>
                            </template>
                            <span>حذف عکس نمایه</span>
                        </v-tooltip>
                        <v-icon @click="edit_profile('pic_profile')">mdi-pencil-outline</v-icon>
                    </div>
                </div>
            </div>
        </v-card>
        <v-dialog
            v-model="dialog"
            persistent
            max-width="600px"
        >
            <v-card>
                <v-card-title class="mb-4">
                    <span v-if="edit==='name'">ويرایش نام و نام خانوادگی</span>
                    <span v-else-if="edit==='mobile'">ويرایش شماره تلفن همراه</span>
                    <span v-else-if="edit==='email'">ويرایش ايميل</span>
                    <span v-else-if="edit==='pic_profile'">ويرایش عکس نمایه</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-form
                            ref="form"
                        >
                            <v-text-field v-if="edit==='name'" :rules="nameRules" v-model="name" label="نام و نام خانوادگی"  outlined></v-text-field>
                            <v-text-field v-if="edit==='email'" :rules="emailRules" v-model="email" label="ایمیل"  outlined></v-text-field>
                            <v-text-field type="number" v-if="edit==='mobile'" :rules="mobileRules" v-model="mobile" label="شماره تلفن همراه"  outlined></v-text-field>
                            <v-file-input v-if="edit==='pic_profile'" v-model="image" accept="image/png, image/jpeg, image/bmp" prepend-icon="mdi-camera" label="عکس نمایه" outlined show-size></v-file-input>
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
                        color="primary darken-1"
                        outlined
                        @click="send_edit_profile()"
                    >
                        ویرایش
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import myMixin from "../../../../../resources/js/myMixin";
export default {
    props: ['user'],
    mixins:[myMixin],
    name: "DetailsProfile",
    data: () => ({
        edit: null,
        dialog:false,
        name:'',
        email:'',
        mobile:'',
        image:[],
        nameRules: [
            v => !!v || 'نام و نام خانوادگی نمی تواند خالی باشد!',
        ],
        emailRules: [
            v => !!v || 'ایمیل نمی تواند خالی باشد!',
        ],
        mobileRules: [
            v => !!v || 'شماره موبایل نمی تواند خالی باشد!',
        ],
    }),
    mounted() {
        this.default_vars();
    },
    methods: {
        edit_profile:function (value) {
            this.edit= value;
            this.dialog= true;
        },
        default_vars:function () {
            this.name=this.user['name'];
            this.email=this.user['email'];
            this.mobile=this.user['mobile'];
        },
        delete_profile:function () {
            this.$root.$emit('show_progress');
            const formData = new FormData();
            formData.append('image', 'delete');
            formData.append('request-type', 'axios');
            this.axios.post(this.$siteUrl + '/profile/edit_profile', formData).then(response => {
                this.$root.$emit('send_get_request',this.$siteUrl + '/profile');
                this.$root.$emit('hide_progress');
                if (response.data['message'] !== undefined) {
                    const type = response.data['status'] === undefined ? 'success' : response.data['status'];
                    this.$root.$emit('showResponse', response.data['message'], type);

                } else {
                    this.$root.$emit('showResponse', 'حذف عكس نمايه با موفقیت انجام شد.', 'success');
                }
            });
        },
        send_edit_profile(){
            if (this.$refs.form.validate()) {
                this.dialog = false;
                this.$root.$emit('show_progress');
                const formData = new FormData();
                formData.append('name', this.name);
                formData.append('email', this.email);
                formData.append('mobile', this.mobile);
                if (this.image!==undefined){
                    formData.append('image', this.image);
                }
                formData.append('request-type', 'axios');
                this.axios.post(this.$siteUrl + '/profile/edit_profile', formData).then(response => {
                    this.$root.$emit('get_page_content',this.$siteUrl+'/profile');
                    this.$root.$emit('hide_progress');
                    if (response.data['message'] !== undefined) {
                        const type = response.data['status'] === undefined ? 'success' : response.data['status'];
                        this.$root.$emit('showResponse', response.data['message'], type);

                    } else {
                        this.$root.$emit('showResponse', 'تغییرات با موفقیت ثبت شدند.', 'success');
                    }
                });
            }
        }
    }
}
</script>

<style>
.profile-personal_grid {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
}
.profile-personal_grid-item:nth-child(odd) {
    padding-left: 16px;
    border-left: 1px solid #dfdfdf;
}
.profile-personal_grid-item {
    width: 50%;
    -ms-flex-negative: 0;
    flex-shrink: 0;
    border-bottom: 1px solid #dfdfdf;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: start;
    -ms-flex-align: start;
    align-items: flex-start;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
    padding: 20px 0;
}
.profile-personal_grid-item-title {
    font-size: 1rem;
    line-height: 1.571;
    color: #424750;
}
.profile-personal_grid-item-value {
    font-size: 1rem;
    line-height: 1.571;
    color: #81858b;
}
@media (max-width: 900px) {
    .profile-personal_grid-item-value {
        font-size: 0.6rem;
    }
}
</style>
