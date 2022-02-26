import mixin from "../../../../../resources/js/myMixin";

export default {
    namespaced: true,
    state: () => ({
        cartData:[],
        city_id:0,
        send_type:'normal',
    }),
    mutations: {
        set_cart_data:function (state,data) {
            state.cartData=data;
        },
        change_send_type:function (state,type) {
            state.send_type=type;
            document.getElementById('send_type').value=type;
            $("#final_price").text(mixin.methods.replaceNumber(mixin.methods.number_format(state.cartData.final_price[1][type]))+' تومان');
            this.commit("set_final_sending_price")
        },
        set_final_sending_price:function(state){
            let price=0;
            let otherPrice=false;
            if(Object.keys(state.cartData).length>0){
                const ar=state.cartData['final_sending_price'][1][state.send_type];
                price=ar['price'];
                otherPrice=['after_price'];
            }
            let result=mixin.methods.replaceNumber(mixin.methods.number_format(price))+' تومان';
            if(otherPrice){
                if(mixin.methods.number_format(price)==0){
                    result=' پس کرایه';
                }
                else{
                    result=result+' + پس کرایه';
                }
            }
            else{
                if(mixin.methods.number_format(price)==0){
                    result='رایگان';
                }
            }
           // $("#total_send_order_price").text(result);
           // document.getElementById('total_send_order_pric').text(result);
        }
    },
    actions:{
        getCartData:function ({state,commit}) {
            if(state.city_id>0){
                const vm=Vue.prototype;
                Vue.axios.get(vm.$siteUrl+"/shipping/getSendData/"+state.city_id).then(response=>{
                    commit('set_cart_data',response.data);
                    commit('set_final_sending_price');
                });
            }
        },
        change_send_type:function ({commit},key) {
            commit('change_send_type',key);
            commit('set_final_sending_price');
        }
    }
}
