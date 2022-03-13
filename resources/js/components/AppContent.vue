<template>
    <div>
        <slot v-if="componentName==''"/>
        <component v-else :is="componentName" />
    </div>
</template>

<script>
export default {
    name: "AppContent",
    data(){
        return {
            componentName:'',
            n:0,
            customLoader:false
        }
    },
    mounted(){
        const tags=document.getElementsByClassName('router-link');
        this.addClickEvent(tags);
        const self=this;
        window.onpopstate=function (event) {
            event.preventDefault();
            const href=window.location.href;
            self.getPageContent(href);
        };
        this.$root.$on('addLoadEvent',(className)=>{
            const root=document.querySelector('.'+className);
            if(root!==null){
                self.addClickEvent(root.getElementsByClassName('router-link'));
            }
        });
        this.$root.$on('send_delete_request',function (url) {
            self.sendDeleteRequest(url);
        });
        this.$root.$on('send_post_request',function (url,formData,headers) {
            const h=headers===undefined ? {} : { 'headers':headers };
            self.send_post_request(url,formData,h);
        });
        this.$root.$on('send_get_request',function (url,loader) {
            self.getPageContent(url,loader);
        });
        window.scrollTo({top: 0, behavior: 'smooth'});
        self.add_cart(self);
        self.go_to_comments_body();
        setTimeout(function () {
            self.addClickEvent(tags);
        },100);
    },
    methods:{
        addClickEvent:function (tags) {
            const self=this;
            for (let j = 0; j <tags.length ; j++) {
                let htmlCode=tags[j].outerHTML;
                tags[j].outerHTML=htmlCode;
            }
            for (let i = 0; i <tags.length ; i++) {
                const href=tags[i].href;
                const loader=tags[i].getAttribute('data-component');
                if(tags[i].getAttribute('onClick')===null){
                    tags[i].addEventListener('click',function (event) {
                        event.preventDefault();
                        self.getPageContent(href,loader);
                    })
                }
            }
        },
        getPageContent:async function (url,loader) {
            window.history.pushState('', '', url);
            const href=window.location.href;
            let result = href.includes("admin/");
            if (!result){
                this.getCountCart();
            }
            if(loader!=null){
                this.customLoader=true;
                this.componentName=loader;
            }
            else{
                this.customLoader=false;
                this.$root.$emit('show_progress');
            }
            if(url.toString().indexOf('?')>-1){
                url=url+"&request-type=axios";
            }
            else{
                url=url+"?request-type=axios";
            }

            try{
                const response=await this.axios.get(url);

                if(response.status==200){


                    const self=this;
                    await this.addJsFile(response.data.js_tags);

                    setTimeout(function () {
                        if(self.customLoader===false){
                            self.$root.$emit('hide_progress');
                        }
                        const template=response.data.content;
                        Vue.component('component'+self.n,{
                            template:template
                        });
                        self.componentName='component'+self.n;
                        self.n++;
                        self.$nextTick(function () {
                            const tags=document.getElementsByClassName('router-link');
                            self.addClickEvent(tags);
                        })
                    },10);
                }
                else{
                    if(this.customLoader===false){
                        this.$root.$emit('hide_progress');
                    }
                }
            }
            catch (e){
                if(e.response.status===401){
                    window.location.href=this.$siteUrl+'/login';
                }
                if(e.response.status===302){
                    window.location.href=e.response['reload'];
                }
            }
            window.scrollTo({top: 0, behavior: 'smooth'});
            const self=this;
            setTimeout(function () {
                self.add_cart(self);
                self.go_to_comments_body();
            },100);
        },
        addJsFile:async function (files) {

            if(files!=undefined && files.length>0){

                let componentFiles=[];
                const self=this;
                const jsTags=document.getElementsByTagName('script');

                const res=await (()=>{

                    return new Promise(async function (resolve) {

                        for (let i = 0; i <files.length ; i++) {

                            if(!self.checkHasFile(jsTags,files[i])){
                                const res=await self.loadJsFile(files[i]);
                                if(res.status=='ok'){
                                    componentFiles.push(res.file);
                                }
                                if(i===(files.length-1)){
                                    resolve('end');
                                }
                            }
                            else if(i===(files.length-1)){
                                resolve('end');
                            }
                        }
                    });

                })();

                if(res=='end'){
                    return  new Promise(resolve => {
                        setTimeout(() => {
                            loadComponent(componentFiles);
                            resolve();
                        }, 10);
                    });
                }
            }
        },
        checkHasFile:function (tags,file) {
            let res=false;
            for (let i = 0; i < tags.length; i++) {
                if(tags[i].src===this.$siteUrl+"/"+file['path']){
                    res=true;
                }
            }
            return res;
        },
        loadJsFile:function (file) {
            const self=this;
            return new Promise(function (resolve,reject) {
                let scriptTag=document.createElement('script');
                scriptTag.src=self.$siteUrl+"/"+file['path'];
                scriptTag.onload=function(){
                    resolve({'status':'ok','file':file});
                }
                document.body.appendChild(scriptTag);
            });
        },
        sendDeleteRequest:function (url) {
            this.$root.$emit('show_progress');
            if (url.includes('del_cart')){
                const formData = new FormData();
                formData.append('request', 'axios');
                this.axios.post(url,formData).then(response=>{
                    this.$root.$emit('hide_progress');
                    const href=window.location.href;
                    this.getPageContent(href);
                });
            }else{
                this.axios.delete(url).then(response=>{
                    if(response.data['message']!==undefined){
                        this.$root.$emit('showResponse',response.data['message'],'success');
                    }

                    if(response.data['redirect_url']===undefined){
                        this.$root.$emit('hide_progress');
                    }
                    else{
                        this.getPageContent(response.data['redirect_url']);
                    }
                    const href=window.location.href;
                    this.getPageContent(href);
                }).catch(error =>
                {
                    this.$root.$emit('showResponse','خطا در اجرای درخواست،مجددا تلاش نمایید','error');
                    this.$root.$emit('hide_progress');
                });
            }
        },
        send_post_request:function (url,data,headers) {

            this.$root.$emit('show_progress');

            this.axios.post(url,data,headers).then(response=>{

                if(response.data['message']!==undefined){
                    const type=response.data['status']===undefined ? 'success' : response.data['status'];
                    this.$root.$emit('showResponse',response.data['message'],type);
                }

                if(response.data['reload']!==undefined){
                    window.location.href=response.data['reload'];
                }
                else if(response.data['redirect_url']===undefined){
                    this.$root.$emit('hide_progress',response.data);
                }

                if(response.data['content']!==undefined) {
                    const template = response.data.content;
                    const self = this;
                    Vue.component('component' + this.n, {
                        template: template
                    });
                    this.componentName = 'component' + this.n;
                    this.n++;
                    this.$nextTick(function () {
                        const tags = document.getElementsByClassName('router-link');
                        this.addClickEvent(tags);
                        setTimeout(function () {
                            self.addClickEvent(tags);
                        }, 100);
                    });
                    url=url.toString().replace('?request-type=axios','');
                    window.history.pushState('', '', url);
                }
                else if(response.data['redirect_url']!==undefined){
                    this.getPageContent(response.data['redirect_url']);
                }

            }).catch(error => {
                if(error.response.status===422 && error.response.data!==undefined && error.response.data['errors']!==undefined){
                    const errors=[];
                    const keys=Object.keys(error.response.data['errors']);
                    for (let i = 0; i <keys.length ; i++) {
                        if(error.response.data['errors'][keys[i]][0]!==undefined){
                            errors.push(error.response.data['errors'][keys[i]][0])
                        }
                    }
                    this.$root.$emit('showResponse','خطا در اجرای درخواست،موارد زیر را بررسی نمایید','error',errors);
                }
                else{
                    this.$root.$emit('showResponse','خطا در اجرای درخواست،مجددا تلاش نمایید','error');
                }

                if(error.response!==undefined && error.response.data!==undefined){
                    this.$root.$emit('hide_progress',error.response.data);
                }
                else {
                    this.$root.$emit('hide_progress');
                }

            });
        },
        getCountCart:function () {
            this.axios.get(this.$siteUrl+'/get_count_cart').then(response=>{
                let a=document.getElementById("count_cart").lastElementChild.lastElementChild.innerHTML = response.data;
            });
        },
        add_cart:function (self) {
            const href=window.location.href;
            let result = href.includes("product/");
            if (result){
                let add_cart=document.getElementById('add_cart');
                add_cart.onclick=function () {
                    self.$root.$emit('show_progress');
                    let value=add_cart.value;
                    const formData = new FormData();
                    formData.append('product_id',value);
                    formData.append('request-type','axios');
                    self.axios.post(self.$siteUrl+'/add_cart',formData).then(response=>{
                        self.$root.$emit('hide_progress');
                        const href=window.location.href;
                        self.getPageContent(href);
                        if(response.data['message']!==undefined){
                            const type=response.data['status']===undefined ? 'success' : response.data['status'];
                            self.$root.$emit('showResponse',response.data['message'],type);
                        }else{
                            self.$root.$emit('showResponse','محصول با موفقيت به سبد خريد اضافه شد.','success');
                        }
                    });
                };
            }
        },
        go_to_comments_body:function () {
            const href=window.location.href;
            let result = href.includes("product/");
            if (result){
                let go_to_comments_body=document.getElementById('go_to_comments_body');
                let comments_body = document.getElementById("comments-body");
                go_to_comments_body.onclick=function () {
                    comments_body.scrollIntoView({behavior: 'smooth'});
                }
            }
        }
    }
}
</script>
