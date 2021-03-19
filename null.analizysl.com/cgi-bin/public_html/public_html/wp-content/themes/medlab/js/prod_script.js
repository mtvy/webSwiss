


jQuery(function($){
	$('.upload-data').click(function(){
//		alert('Если это работает, уже неплохо');
//		alert(ajax_url);
        var id=this.id;
        var type=$(this).data('type');
        var target=$(this).data('target');
        var offset=$(this).data('offset');
        var count=parseInt($(this).data('count'));
        var sch=$(this).data('sch');
        var post=$(this).data('post');
        var post=$(this).data('post');
//        console.log(post);
//        post = JSON.parse(post);                           
//        post = JSON.parse(post, function(k, v) {
//            if (k === '') { return v; }
//            return v ;
//          });                           
        var act='get_list';
        var data = {
            action:'medlab',
            isajax: 1,
            act: act,
            type: type,
            offset: offset,
            count: count,
            sch: sch,
            target: target
        };
        data = Object.assign(data, post);
        if(ajax_url){
            $.ajax({
                url: ajax_url,//'/wp-admin/admin-ajax.php',
                type: 'POST',
                data: data, // можно также передать в виде объекта
                beforeSend: function( xhr ) {
                    $('#'+id).text('Загрузка, 5 сек...');	
                },
                success: function( data ) {
                    $('#'+id).text('Ещё');
//                    $('#'+id).insertBefore(data);
//                    $('#'+id).before(data.data);
                    console.log('#'+id,'id');
                    console.log('#'+target,'target');
                    $('#'+target).before(data.data);
                    var newoffset = (parseInt(data.offset) * count)+count;
                    var newoffset = (parseInt(data.offset) )+count;
                    console.log(newoffset,'newoffset');
                    $('#'+id).data('offset',newoffset);
                    if(data.limit_end==1)$('#'+id).remove();
//                    alert( data );
                    $('a.link-to-users').on('click', function (e) {
                        e.preventDefault()
                        $('#users-tab-btn').tab('show')
                        return false;
                    })
                },
                error: function( data ) {
                    $('#'+id).text('Ещё');
//                    alert( data );
                }
            });
            // если элемент – ссылка, то не забываем:
            // return false;
        }
	});
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
$('#update-cart-button').onclick = function () {
//		alert('Если это работает, уже неплохо');
//		alert(ajax_url);
        var id=this.id;
//        var act=$(this).data('act');
        var act='add_c';
//        var target=$(this).data('target');
//        var offset=$(this).data('offset');
//        var pid=$(this).data('pid');
//        var count=parseInt($(this).data('count'));
        var pid=$('#pr_pid').val();
        var count=parseInt($('#pr_cou').val());
        var data = {
            action:'dscart',
            isajax: 1,
            act:act,
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
//                    $('#'+id).text('Ещё');
//                    alert( data );
                }
            });
            // если элемент – ссылка, то не забываем:
            // return false;
        }
        return false;
    };
var bt = document.querySelector('#test-add-to-cart');
if(bt){
    bt.onclick = function () {
//		alert('Если это работает, уже неплохо');
//		alert(ajax_url);
        var id=this.id;
        var act=$(this).data('act');
//        var target=$(this).data('target');
//        var offset=$(this).data('offset');
        var pid=$(this).data('pid');
        var count=parseInt($(this).data('count'));
        var data = {
            action:'dscart',
            isajax: 1,
            act:act,
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
//                    $('#'+id).text('Ещё');
//                    alert( data );
                }
            });
            // если элемент – ссылка, то не забываем:
            // return false;
        }
        return false;
    };
}
var bt = document.querySelector('#test-remove-from-cart');
if(bt){
    bt.onclick = function () {
        var cookie = document.cookie.split(';')
                .filter(function (c) {
                    return c.indexOf('cartUserId') !== -1;
                })[0];
        var cuid = cookie ? cookie.split('=')[1] : null;
        var jsonType = 'application/vnd.allegro.public.v4+json';
        var body = '{"action":"dscart","act":"rem_c",'
            +'"pid":"10","count":1,"delta":1}';
        var options = {
            body: body,
            method: 'POST',
//            headers: {
//                'Content-Type': jsonType,
//                'Accept': jsonType,
//                'Accept-Language': 'ru-RU'
//    //          'Accept-Language':'pl-PL'
//            },
//            credentials: 'include'
        };
    //  fetch('/cart-aggregator/carts/'+cuid+'/changeQuantityCommand',options)
        fetch(ajax_url, options)
                .then(function (dt) {
    //                window.location.assign('/cart')
                    console.log('dt',dt);
                });
        return false;
    };
}
    $('#btn_cou_plus').on('click', function (e) {
        e.preventDefault()
        var cou = parseInt($('#pr_cou').val());
        var max = parseInt($('#pr_max').val());
        cou=cou+1;
        if(cou>max)cou=max;
        $('#pr_cou').val(cou)
        return false;
    })
    $('#btn_cou_minus').on('click', function (e) {
        e.preventDefault()
        var cou = parseInt($('#pr_cou').val());
        cou=cou-1;
        if(cou<1)cou=1
        $('#pr_cou').val(cou)
        return false;
    })
    $('.btn_cou_plus_cart').on('click', function (e) {
        e.preventDefault()
        var pid=$(this).data('pid');
        var max=$(this).data('max');
        var cou = parseInt($('.pr_cou-'+pid).val());
        var max = parseInt(max);
//        var max = parseInt($('#pr_max').val());
        cou=cou+1;
        if(cou>max)cou=max;
        $('.pr_cou-'+pid).val(cou)
        return false;
    })
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
    mess_wrapp_id: 'info_messages_wrap',
    show_mess: function (mess){
        for(var i = 0; i<mess.length;i++){
            this.bild_mess(mess[i]);
        }
    },
    bild_mess: function (mess){
        var mw= document.createElement('div')
        mw.className='log_mess alert alert-primary';
        var m = document.createTextNode(mess);
        mw.appendChild(m);
        var w = document.getElementById(this.mess_wrapp_id);
//        w.appendChild(mw);
        $('#'+this.mess_wrapp_id).append(mw);
        this.remove_mess(mw);
    },
    messages: [],
    rem_mess_delay: 3000,
    remove_mess:function(mess){
        setTimeout(mess.remove.bind(mess),this.rem_mess_delay);
    },
    clear_mess:function(){
        this.remove();
    },
    check_email_success: function(data){
        console.log( data );
        order.show_mess.bind(order,data.mess)();
    },
    update_cart: function(){
//		alert('Если это работает, уже неплохо');
//		alert(ajax_url);
        var id=this.id;
        this.act=$(this).data('act');
        this.act='add_c';
        this.action='dscart';
        this.ajax_url=ajax_url;
//        var target=$(this).data('target');
//        var offset=$(this).data('offset');
        var pid=$(this).data('pid'); // product id
        var count=parseInt($(this).data('count')); // product count
        var pid=$('#pr_pid').val(); // product id
        var count=parseInt($('#pr_cou').val()); // product count
        
        var data = {
            action: this.action,
            isajax: 1,
            act:this.act,
            pid: pid,
            count: count
        };
        
        this.data = data;
        this.r_beforeSend = this.beforeSend;
    },
    success_update_cart_btn: function( data ) {
        console.log(data)
        $('#count-in-cart').text(data.count);
//        $('#'+id).text('Ещё');
////                    $('#'+id).insertBefore(data);
////                    $('#'+id).before(data.data);
//        console.log('#'+id,'id');
//        console.log('#'+target,'target');
//        $('#'+target).before(data.data);
//        var newoffset = (parseInt(data.offset) * count)+count;
//        var newoffset = (parseInt(data.offset) )+count;
//        console.log(newoffset,'newoffset');
//        $('#'+id).data('offset',newoffset);
//        if(data.limit_end==1)$('#'+id).remove();
////                    alert( data );
//        $('a.link-to-users').on('click', function (e) {
//            e.preventDefault()
//            $('#users-tab-btn').tab('show')
//            return false;
//        })
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