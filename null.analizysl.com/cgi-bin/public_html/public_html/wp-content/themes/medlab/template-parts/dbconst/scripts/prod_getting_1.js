/* 
 * prod_getting.js
 */


jQuery(function($){

    function addToCart() {
//                    console.log('addToCart')
//		alert('Если это работает, уже неплохо');
//		alert(ajax_url);

//        var id=this.id;
//        var act=$(this).data('act');
        var act='add_c';
        
//        var target=$(this).data('target');
//        var offset=$(this).data('offset');
//        var pid=$(this).data('pid');
//        var count=parseInt($(this).data('count'));


        var bt = document.querySelector('#add-to-cart-button');
        if(bt){
            var pid=$('#pr_pid').val();
            var count=parseInt($('#pr_cou').val());
            
        }else{
            var pid=$(this).data('id');
            var count=1;
        }
        
        var data = {
            action:'dscart',
            isajax: 1,
            act: act,
            pid: pid,
            count: count
        };
        if(ajax_url){
            $.ajax({
                url: ajax_url,//'/wp-admin/admin-ajax.php',
                type: 'POST',
                data: data, // можно также передать в виде объекта
                beforeSend: function( xhr ) {
//                    $('#'+id).text('Загрузка, 5 сек...');	
                },
                success: function( data ) {
                    console.log(data)
                    $('#count-in-cart').text(data.count);
                    if (data.act !== undefined) {
                        switch(data.act){
                            case 'block_button':
                                var btadd = document.querySelector('#add-to-cart-button');
                                var bts = document.querySelector('#add-to-cart-submit-button');
                                if(btadd){
                                    bts.setAttribute("disabled", "disabled");
                                }
                                if(btadd){
                                    btadd.setAttribute("disabled", "disabled");
//                                    var pid=$('#pr_pid').val();
//                                    var count=parseInt($('#pr_cou').val());

                                }else{
                                    var pid=$(this).data('id');
                                    var count=1;
                                }
                                break;
                            case 'no':
                                break;
                        }
                    }

                    
//                    $('#'+id).text('Ещё');
////                    $('#'+id).insertBefore(data);
////                    $('#'+id).before(data.data);
//                    console.log('#'+id,'id');
//                    console.log('#'+target,'target');
//                    $('#'+target).before(data.data);
//                    var newoffset = (parseInt(data.offset) * count)+count;
//                    var newoffset = (parseInt(data.offset) )+count;
//                    console.log(newoffset,'newoffset');
//                    $('#'+id).data('offset',newoffset);
//                    if(data.limit_end==1)$('#'+id).remove();
////                    alert( data );
//                    $('a.link-to-users').on('click', function (e) {
//                        e.preventDefault()
//                        $('#users-tab-btn').tab('show')
//                        return false;
//                    })
                },
                error: function( data ) {
                    console.log(data)
//                    $('#'+id).text('Ещё');
//                    alert( data );
                }
            });
            // если элемент – ссылка, то не забываем:
            // return false;
        }
        return false;
    };
var bt = document.querySelector('#add-to-cart-button');
if(bt){
    bt.onclick = addToCart ;
}
var bts = document.querySelectorAll('.add-to-cart-button');
//console.log(bts);
for (var i = 0; i < bts.length; i++) {
//  alert( bts[i].innerHTML ); // "тест", "пройден"
    bts[i].onclick = addToCart ;
}
    $('.btn_cou_minus_cart').on('click', function (e) {
        e.preventDefault()
        var pid=$(this).data('pid');
        var min=$(this).data('min');
        var cou = parseInt($('.pr_cou-'+pid).val());
        var min = parseInt(min);
        cou=cou-1;
        if(cou<min)cou=min
        $('.pr_cou-'+pid).val(cou)
        return false;
    })
    $('#order_field_email').blur(function(){
        var em = $(this).val();
        var lgd = $(this).data('logged');
        console.log(em);
        console.log(lgd);
//        var o = order.b;
        if(lgd == 0 && em.length>6)
            order.check_email(em);
    });
var order = {
//class order {
    action: 'dscart', // method
    act:null, // whot to do
    data: {}, // args
    r_succ:null, // whot to do
    r_err:null, // whot to do
    r_beforeSend: null,

//    constructor (name) {
//      this.name = name;
//    },
    init: function(){
        
    },
    check_email: function(em){
        this.act='order_ch_mail_ex'; // check email exists
        
        var data = {
            action: this.action,
            isajax: 1,
            act: this.act,
            email: em
        };
        
        this.data = data;
        this.ajax_url = ajax_url;
        this.r_succ = this.check_email_success;
        this.r_err = this.error;
        this.r_beforeSend = this.beforeSend;
        console.log( 'ajax_url',ajax_url );
        this.get();
    },
    get: function(){
        $.ajax({
            url: this.ajax_url,//'/wp-admin/admin-ajax.php',
            type: 'POST',
            data: this.data, // можно также передать в виде объекта
            beforeSend: this.r_beforeSend,
            success: this.r_succ,
            error: this.r_err
        });
    },
    beforeSend: function( xhr ) {
//                    $('#'+id).text('Загрузка, 5 сек...');	
    },
    error: function( data ) {
//                    $('#'+id).text('Ещё');
//                    console.log( data );
    }
}
});