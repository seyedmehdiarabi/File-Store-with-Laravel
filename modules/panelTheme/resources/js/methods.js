export  default {
    methods:{
        setValidate:function (r) {
            r=r.toString().split('|');
            this.rules=[];
            let j=0;
            for (let i = 0; i <r.length ; i++) {
                if(r[i]==='required'){
                    this.rules[j]=v => !!v || ''+this.label+' نمی تواند خالی باشد'
                    j++;
                }
            }
        },
    }
}
