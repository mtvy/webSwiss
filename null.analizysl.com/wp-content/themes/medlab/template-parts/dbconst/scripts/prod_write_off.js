/* 
 * prod_write_off.js
 */
//Object.prototype.objCustom = function() {}; 
//Array.prototype.arrCustom = function() {};

jQuery(function () {
    jQuery('[data-toggle="popover"]').popover({
      trigger: 'hover'
    })
})
jQuery(function($){
    
    var btninit = function()
    {
        this.btns =
        {
//            '#whm-create':'create_material',
            '#whwoff-add-material':'add_material_to_list',
            '#whwoff-clear':'clear_items',
            '#whwoff-create':'create_list',
//            '.whwoff-create':'init_items_type',
//            '.whwoff-create':'init_items_comment',
            '':''
        },
        this.btnsObj =
        {
//            '#whm-create':'find',
            '#whwoff-add-material':'order',
            '#whwoff-clear':'order',
            '#whwoff-create':'order',
//            '#whwoff-create':'order',
//            '#whwoff-create':'order',
            '':''
        },
        this.obj =
        {
            'order':ord,
            'find':fnd
        },
        this.order_method = function(objt,method)
        {
//            console.log(objt)
//            console.log(method)
//            console.log(this.obj)
//            console.log(this.obj[objt])
            this.obj[objt][method]();
            return false;
        },
        this.init_buttons = function()
        {
//            console.table(this.btns);
//            console.table(this.btnsObj);
            for (var attv in this.btns) {
                if(!attv.length)continue;
                var tag = document.querySelector(attv);
//            console.log(attv)
//            console.log(this.btnsObj[attv])
                if(tag){
                    tag.onclick = this.order_method.bind(this,this.btnsObj[attv],this.btns[attv]) ;
                }else{
                    console.warn('not found: '+attv);
                }
            }
        }
    };
    var btnmess = function()
    {
        this.mess = function ()
        {
            console.log('proto mess ok');
        }
    };
    
//    var ajax_url = ajax_url;

    var material = function ()
    {
//    class findMat {
    
        constructor = function(name) {
          this.name = name;
        };
        
        this.weybillitems_wrapp_id = 'writeoffitems_added', // added items wrapper 'weybillitems_added',
        this.mess_wrapp_id = 'info_messages_wrap_found', // messages wrapper
        this.res_wrapp_id = 'mnf-found', // find resul wrapper

        this.init = function(){

        },
        this.create_material = function(em){
            this.act='add_material'; // find_materials
            var name = document.querySelector('.name_tool:checked');
            if(name)name = name.value;
//            var name = document.getElementById('mnf-name').value;
            var categ = document.getElementById('mnf-categ').value;
            var code = document.getElementById('mnf-code').value;
            var manuf = document.getElementById('mnf-manuf').value;

            console.log( 'create_material', 'name', name );
//        SET
//    `house_id` = '0',
//    `group_id` = '0',
//    `name_id` = '0',
//    `delivery_date` = '',
//    `write_off` = '0',
//    `expiry_date` = '',
//    `open_expiry_date` = '',
//    `measurement` = '0',
//    `measurement_item` = '0',
//    `mesurement_pack` = '0',
//    `measurement_box` = '0',
//    `count` = '0',
//    `count_item` = '0',
//    `count_pack` = '0',
//    `count_box` = '0',
//    `cost_item` = '0',
//    `cost_pack` = '0',
//    `stillage` = '0',
//    `board` = '',
//    `pack_width` = '0',
//    `pack_height` = '0',
//    `pach_length` = '0'

            var data_item_names = [];
            data_item_names[data_item_names.length] = 'house_id';
            data_item_names[data_item_names.length] = 'group_id';
//            data_item_names[data_item_names.length] = 'name_id';
            data_item_names[data_item_names.length] = 'expiry_date';
            data_item_names[data_item_names.length] = 'open_expiry_date';
            data_item_names[data_item_names.length] = 'measurement';
            data_item_names[data_item_names.length] = 'measurement_item';
            data_item_names[data_item_names.length] = 'mesurement_pack';
            data_item_names[data_item_names.length] = 'measurement_box';
            data_item_names[data_item_names.length] = 'count';
            data_item_names[data_item_names.length] = 'count_item';
            data_item_names[data_item_names.length] = 'count_pack';
            data_item_names[data_item_names.length] = 'count_box';
            data_item_names[data_item_names.length] = 'cost_item';
            data_item_names[data_item_names.length] = 'cost_pack';
            data_item_names[data_item_names.length] = 'stillage';
            data_item_names[data_item_names.length] = 'board';
            data_item_names[data_item_names.length] = 'pack_width';
            data_item_names[data_item_names.length] = 'pack_height';
            data_item_names[data_item_names.length] = 'pach_length';

            var data = {
                action: this.action,
                isajax: 1,
                act: this.act,
                name_id: name
            };
            
            var item_name;
            var item_val;
            for (var i = 0; i < data_item_names.length; i++) {
                item_name = data_item_names[i];
//                item_val = document.querySelector('whm-'+item_name).value;
                item_val = document.getElementById('whm-'+item_name);
                if(item_val)data[item_name] = item_val.value;
            }

            this.data = data;
            this.ajax_url = ajax_url;
            this.r_succ = this.create_material_success;
            this.r_err = this.error;
            this.r_beforeSend = this.beforeSend;
    //        console.log( 'ajax_url',ajax_url );
            this.get();
        },
        this.create_material_success = function(data){
            this.show_mess(data.mess);
        },
        end = null;
    };

    var findMat = function (tooltype)
    {
        this.tooltypes = ['check','radio'];
        this.tooltype = 'check';
        console.log('tooltype '+tooltype);
        if(this.tooltypes.includes(tooltype))
            this.tooltype = tooltype;
//    class findMat {
    
//        constructor (tooltype) {
////            super(length, length);
////          this.name = name;
//            console.log('tooltype '+tooltype);
//        };
        
        this.weybillitems_wrapp_id = 'writeoffitems_added', // added items wrapper 'weybillitems_added',
        this.mess_wrapp_id = 'info_messages_wrap_found', // messages wrapper
        this.res_wrapp_id = 'mnf-found', // find resul wrapper

        this.init = function(){

        },
                
        
        this.find_materials = function(em){
            this.act='find_material_items'; // find_materials
            var name = document.getElementById('mnf-name').value;
            var categ = document.getElementById('mnf-categ').value;
            var code = document.getElementById('mnf-code').value;
            var manuf = document.getElementById('mnf-manuf').value;

            var data = {
                action: this.action,
                isajax: 1,
                act: this.act,
                name: name,
                categ: categ,
                code: code,
                manuf: manuf
            };

            this.data = data;
            this.ajax_url = ajax_url;
            this.r_succ = this.find_materials_success;
            this.r_err = this.error;
            this.r_beforeSend = this.beforeSend;
    //        console.log( 'ajax_url',ajax_url );
            this.get();
        },
        this.find_materials_success = function(data){
//            console.log( data );
            var found = document.getElementById('mnf-found');
//            order.show_res.bind(order,data.res)();
//            order.show_mess.bind(order,data.mess)();
    //        order.show_mess.bind(order,data.mess)();
            this.show_res(data.res);
            this.show_mess(data.mess);
        },
        
        this.show_res = function (mess){
    //        console.log( mess );
            $('#'+this.res_wrapp_id).html('');
            if(mess)
            for(var i = 0; i<mess.length;i++){
                this.build_res(mess[i]);
            }
            this.init_name_tools();
        },
                
        this.init_name_tools = function(){
            var name_tools = document.querySelectorAll('.name_tool');
            //console.log(bts);
            for (var i = 0; i < name_tools.length; i++) {
                var e = name_tools[i];
    //            console.log(e);
            //  alert( bts[i].innerHTML ); // "тест", "пройден"
        console.log('init_name_tools '+this.tooltype);
                switch(this.tooltype){
                    case 'check': e.onchange = this.init_name_tool_check.bind(this,e) ; break;
                    case 'radio': e.onchange = this.init_name_tool_radio.bind(this,e) ; break;
                    default:      e.onchange = this.init_name_tool_check.bind(this,e) ; break;
                }
            }
        },
                
    //    res_names: ['catalog','category','factory','title'],
        this.res_names = ['title','category','catalog','factory'],
        this.res_names_class = {'id':'col-1','title':'col-3','category':'col-3','catalog':'col-3','factory':'col-3'},
                
        this.build_res = function (mess){
            var mw= document.createElement('label')
            mw.className='row mb-0 name_wrupp';

    //            var name = 'id';
    //            var mw2= document.createElement('div')
    //            mw2.className=this.res_names_class[name];
    //            var m = document.createTextNode(mess[name]);
    //            mw2.appendChild(m);
    //            mw.appendChild(mw2);

            var input = false;
            for(var i = 0; i<this.res_names.length;i++){
                name = this.res_names[i];
                input = false;
    //        console.log( mess[this.res_names[i]] );
                var mw2= document.createElement('div')
                mw2.className=this.res_names_class[name];
                var m = document.createTextNode(mess[name]);
                if(name == 'title'){
                    var label = document.createElement('div');
                    label.className = '-form-control';

                    input = document.createElement('input');
                    input.name = 'name_id';
                    switch(this.tooltype){
                        case 'check': input.type = 'checkbox' ; break;
                        case 'radio': input.type = 'radio' ; break;
                        default:      input.type = 'checkbox' ; break;
                    }
//                    input.type = 'radio';
//                    input.type = 'checkbox';
                    input.className = 'm-2 name_tool';
                    input.value = mess['id'];
                    label.appendChild(input);

                    var t = document.createTextNode(' [' + mess['id'] + '] ');
                    label.appendChild(t);

                    label.appendChild(m);

                    m = label;
                }
                mw2.appendChild(m);
                mw.appendChild(mw2);
            }
    //        var w = document.getElementById(this.mess_wrapp_id);
    //        w.appendChild(mw);
            $('#'+this.res_wrapp_id).append(mw);
    //        this.remove_mess(mw);
        },
                
        this.end = null
    }
//    var p = new findMat();
    

    var order = function (acts)
    {
    //class order {
        this.action = 'ml_warehouse', // method
        this.ajax_url = ajax_url,
        this.act = null, // whot to do
        this.data = {}, // args
        this.r_succ = null, // whot to do
        this.r_err =  null, // whot to do
        this.r_beforeSend = null,
        
        this.actions = {
            init_items:'get_writeoff_items',
            clear_items:'clear_writeoff',
            clear_item:'clear_writeoff_item',
            create_list:'create_writeoff',
            add_material_to_list:'add_to_writeoff',
            init_items_type:'add_to_writeoff_type',
            init_items_comment:'add_to_writeoff_comment',
            '':''
        };
//        var a = this.actions.slice();
//        var acts =  {
//            init_items:'get_writeoff_items 2',
//            clear_items:'clear_writeoff 2',
//            clear_item:'clear_writeoff_item 2',
//            create_list:'create_writeoff 2',
//            add_material_to_list:'add_to_writeoff 2',
//            '':''
//        };
        var k;
        if(typeof acts == 'object'){
            for(k in this.actions){
                if(acts.hasOwnProperty(k)){
                    this.actions[k] = acts[k];
                }
            }
        }
//        console.table(a);
//        console.table(this.actions);
        

//        constructor = function(name) {
//          this.name = name;
//        },
        this.init = function(){

        };
        this.init();
/*
 * query
 */
        this.init_items_type = function(id,e){
            this.act='add_to_writeoff_type'; // find_materials
            this.act=this.actions.init_items_type; // find_materials

            var data = {
                action: this.action,
                isajax: 1,
                act: this.act,
                conferm: 'ok',
                id: id,
                value:e.value
            };

            this.data = data;
            this.ajax_url = ajax_url;
            this.r_succ = this.get_items_success;
            this.r_err = this.error;
            this.r_beforeSend = this.beforeSend;
    //        console.log( 'ajax_url',ajax_url );
            this.get();
            return false;
        },
        this.init_items_comment = function(id,e){
            this.act='add_to_writeoff_comment'; // find_materials
            this.act=this.actions.init_items_comment; // find_materials

            var data = {
                action: this.action,
                isajax: 1,
                act: this.act,
                conferm: 'ok',
                id: id,
                value:e.value
            };

            this.data = data;
            this.ajax_url = ajax_url;
            this.r_succ = this.get_items_success;
            this.r_err = this.error;
            this.r_beforeSend = this.beforeSend;
    //        console.log( 'ajax_url',ajax_url );
            this.get();
            return false;
        },
        this.init_items = function(){
            this.act='get_weybill_items'; // find_materials
            this.act=this.actions.init_items; // find_materials

            var data = {
                action: this.action,
                isajax: 1,
                act: this.act
            };

            this.data = data;
            this.ajax_url = ajax_url;
            this.r_succ = this.get_items_success;
            this.r_err = this.error;
            this.r_beforeSend = this.beforeSend;
    //        console.log( 'ajax_url',ajax_url );
            this.get();
            return false;
        },
        this.clear_items = function(el){
            this.act='clear_weybill'; // find_materials
            this.act=this.actions.clear_items; // find_materials

            var data = {
                action: this.action,
                isajax: 1,
                act: this.act,
                conferm: 'ok'
            };

            this.data = data;
            this.ajax_url = ajax_url;
            this.r_succ = this.clear_items_success;
            this.r_err = this.error;
            this.r_beforeSend = this.beforeSend;
    //        console.log( 'ajax_url',ajax_url );
            this.get();
            return false;
        },
        this.clear_item = function(id){
            this.act='clear_weybill_item'; // find_materials
            this.act=this.actions.clear_item; // find_materials

            var data = {
                action: this.action,
                isajax: 1,
                act: this.act,
                conferm: 'ok',
                id: id
            };

            this.data = data;
            this.ajax_url = ajax_url;
            this.r_succ = this.clear_item_success;
            this.r_err = this.error;
            this.r_beforeSend = this.beforeSend;
    //        console.log( 'ajax_url',ajax_url );
            this.get();
            return false;
        },
        this.create_list = function(){
            this.act='create_weybill'; // find_materials
            this.act=this.actions.create_list; // find_materials
            
//            var house_id = document.getElementById('whwb-house_id').value;
//            var group_id = document.getElementById('whwb-group_id').value;
//            var status = document.getElementById('whwb-status').value;
            var comment = document.getElementById('whwb-comment').value;
            var types = [];
            var comments = [];
            
            var name_tools = document.querySelectorAll('.woff_type');
            //console.log(bts);
            for (var i = 0; i < name_tools.length; i++) {
                var e = name_tools[i];
                var id = e.dataset.id;
    //            console.log(e);
            //  alert( bts[i].innerHTML ); // "тест", "пройден"
//                e.onchange = this.init_name_tool_radio.bind(this,e) ;
//                e.onchange = this.init_items_type.bind(this,id,e) ;
                types[id] = e.value;
            }
            var name_tools = document.querySelectorAll('.woff_comment');
            //console.log(bts);
            for (var i = 0; i < name_tools.length; i++) {
                var e = name_tools[i];
                var id = e.dataset.id;
    //            console.log(e);
            //  alert( bts[i].innerHTML ); // "тест", "пройден"
//                e.onchange = this.init_name_tool_radio.bind(this,e) ;
//                e.onblur = this.init_items_comment.bind(this,id,e) ;
                comments[id] = e.value;
            }

            var data = {
                action: this.action,
                isajax: 1,
                act: this.act,
                conferm: 'ok',
                types: types,
                comments: comments,
//                status: status,
                comment: comment
            };

            this.data = data;
            this.ajax_url = ajax_url;
            this.r_succ = this.create_list_success;
            this.r_err = this.error;
            this.r_beforeSend = this.beforeSend;
    //        console.log( 'ajax_url',ajax_url );
            this.get();
            return false;
        },
        this.add_material_to_list = function(em){
            var name_tools = document.querySelectorAll('.name_tool:checked');
            var list_items = {};
//            console.log(name_tools);
            for (var i = 0; i < name_tools.length; i++) {
                var e = name_tools[i].value;
//                console.log(e);
                list_items[e]=e;
            //  alert( bts[i].innerHTML ); // "тест", "пройден"
//                e.onchange = this.init_name_tool_radio.bind(this,e) ;
//                e.onchange = this.init_name_tool_check.bind(this,e) ;
            }
//            console.log(waybill_items);
            var size = 0, key;
            for (key in list_items) {
                if (list_items.hasOwnProperty(key)) size++;
            }
            if(size){
                this.act='add_to_weybill'; // find_materials
                this.act=this.actions.add_material_to_list; // find_materials
                
                var data = {
                    action: this.action,
                    isajax: 1,
                    act: this.act,
                    list_items: list_items
                };

                this.data = data;
                this.ajax_url = ajax_url;
                this.r_succ = this.add_to_list_success;
                this.r_err = this.error;
                this.r_beforeSend = this.beforeSend;
        //        console.log( 'ajax_url',ajax_url );
                this.get();
            }
            return false;
        },
                
/*
 * query result
 */
        
        this.get_items_success = function(data){
//            console.log( data );
//            var found = document.getElementById('mnf-found');
//            order.show_res.bind(order,data.res)();
//            order.show_res_weybill.bind(order,data.res)();
//            order.show_mess.bind(order,data.mess)();
//            order.show_mess.bind(order,data.mess)();
//            ord.show_res_weybill(data.res);
//            ord.show_mess(data.mess);
//            console.table(this);
            this.show_res(data.res);
            this.show_mess(data.mess);
        },
        this.add_to_list_success = function(data){
//            console.log( data );
            this.show_res(data.res);
            this.show_mess(data.mess);
        },
        this.clear_items_success = function(data){
            this.show_res(data.res);
            this.show_mess(data.mess);
        },
        this.clear_item_success = function(data){
            this.show_res(data.res);
            this.show_mess(data.mess);
        },
        this.create_list_success = function(data){
            this.show_mess(data.mess);
        },
        
        
        this.check_email = function(em){
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
        this.check_email_success = function(data){
//            console.log( data );
//            order.show_mess.bind(order,data.mess)();
            this.show_mess(data.mess);
        },
        
        
    //    mess_wrapp_id: 'info_messages_wrap',
        this.items_wrapp_id = 'writeoffitems_added', // added items wrapper 'weybillitems_added',
        this.mess_wrapp_id = 'info_messages_wrap_found', // messages wrapper
        this.res_wrapp_id = 'mnf-found', // find resul wrapper
        
        this.show_res = function (mess){
//            console.log( mess );
//            console.log( mess.length );
            $('#'+this.items_wrapp_id).html('');
            if(mess)
            for(var i = 0; i<mess.length;i++){
                this.build_res(mess[i]);
            }
            this.init_items_tools();
        },
        this.init_items_tools = function(){
            var name_tools = document.querySelectorAll('.item_remove_tool');
            //console.log(bts);
            for (var i = 0; i < name_tools.length; i++) {
                var e = name_tools[i];
                var id = e.dataset.id;
    //            console.log(e);
            //  alert( bts[i].innerHTML ); // "тест", "пройден"
//                e.onchange = this.init_name_tool_radio.bind(this,e) ;
                e.onclick = this.clear_item.bind(this,id) ;
            }
            var name_tools = document.querySelectorAll('.woff_type');
            //console.log(bts);
            for (var i = 0; i < name_tools.length; i++) {
                var e = name_tools[i];
                var id = e.dataset.id;
    //            console.log(e);
            //  alert( bts[i].innerHTML ); // "тест", "пройден"
//                e.onchange = this.init_name_tool_radio.bind(this,e) ;
                e.onchange = this.init_items_type.bind(this,id,e) ;
            }
            var name_tools = document.querySelectorAll('.woff_comment');
            //console.log(bts);
            for (var i = 0; i < name_tools.length; i++) {
                var e = name_tools[i];
                var id = e.dataset.id;
    //            console.log(e);
            //  alert( bts[i].innerHTML ); // "тест", "пройден"
//                e.onchange = this.init_name_tool_radio.bind(this,e) ;
                e.onblur = this.init_items_comment.bind(this,id,e) ;
            }
        },

        this.res_items_names = ['title','category','number','stillage','board','type','comment'],
        this.res_items_names_class = {'id':'col-1','title':'col-3','category':'col-1','number':'col-1','catalog':'col-2',
            'stillage':'col-1','board':'col-1','factory':'col-3','type':'col-2','comment':'col-3'},
        this.build_res = function (mess){
//            writeoffitems_added
//                console.table(mess);
            var mw= document.createElement('div');
            mw.className='row mb-0 name_wrupp';

    //            var name = 'id';
    //            var mw2= document.createElement('div')
    //            mw2.className=this.res_names_class[name];
    //            var m = document.createTextNode(mess[name]);
    //            mw2.appendChild(m);
    //            mw.appendChild(mw2);

            var input = false;
            for(var i = 0; i<this.res_items_names.length;i++){
                name = this.res_items_names[i];
                input = false;
    //        console.log( mess[this.res_names[i]] );
                var mw2= document.createElement('div')
                mw2.className=this.res_items_names_class[name];
                var m = document.createTextNode(mess[name]);
                if(name == 'title'){
                    var label = document.createElement('div');
                    label.className = '-form-control';

                    input = document.createElement('button');
//                    input.name = 'name_id';
                    input.type = 'button';
//                    input.type = 'checkbox';
                    input.className = 'm-2 wbi item_remove_tool btn btn-danger btn-delete';
                    input.value = mess['id'];
                    input.dataset.id = mess['id'];
                    var wbi_remove_tool = document.createTextNode('X');
                    input.appendChild(wbi_remove_tool);
                    
                    label.appendChild(input);

                    var t = document.createTextNode(' [' + mess['id'] + '] ');
                    label.appendChild(t);

                    label.appendChild(m);

                    m = label;
                }
                if(name == 'type'){
                    var select = document.createElement('select');
                    select.name = 'type['+mess['id']+']';
                    for(var o in write_off_types){
                        var option = document.createElement('option');
                        option.value = o;
//                        if(mess['type'] == o)
//                            option.attr("selected", "selected");
                        option.appendChild(document.createTextNode(write_off_types[o]));
                        select.appendChild(option);
                    }
                    select.value = mess['type'];
                    select.dataset.id = mess['id'];
                    select.className = 'woff_type';
                    m = select;
                    
                }
                if(name == 'comment'){
                    var textarea = document.createElement('textarea');
                    textarea.name = 'comm['+mess['id']+']';
                    textarea.dataset.id = mess['id'];
                    textarea.className = 'woff_comment';
                    textarea.value = mess['comment'];
                    m = textarea;
                }
                mw2.appendChild(m);
                mw.appendChild(mw2);
            }
    //        var w = document.getElementById(this.mess_wrapp_id);
    //        w.appendChild(mw);
//                console.log(mw);
//                console.log(this.items_wrapp_id);
//                console.log($('#'+this.items_wrapp_id));
            $('#'+this.items_wrapp_id).append(mw);
    //        this.remove_mess(mw);
        },
        this.end = null
    }
    var helpers = function()
    {
        this.action = 'ml_warehouse', // method
        this.ajax_url = ajax_url,
        this.act = null, // whot to do
        this.data = {}, // args
        this.r_succ = null, // whot to do
        this.r_err =  null, // whot to do
        this.r_beforeSend = null,
        
        this.weybillitems_wrapp_id = 'writeoffitems_added', // added items wrapper 'weybillitems_added',
        this.mess_wrapp_id = 'info_messages_wrap_found', // messages wrapper
        this.res_wrapp_id = 'mnf-found', // find resul wrapper
        
        
        this.init_name_tool_check = function(el){
            var name_tools = document.querySelectorAll('.name_wrupp');
            var e;
    //        console.log(el);
            for (var i = 0; i < name_tools.length; i++) {
            //  alert( bts[i].innerHTML ); // "тест", "пройден"
                e = name_tools[i];

    //            var reg = /\bbg-danger\b/g;
//                var reg = new RegExp('\\b'+'text-white bg-danger'+'\\b','g');
//                e.className = e.className.replace(reg, "");
            }
    //        el.closest('div').closest('div').closest('label').className += ' bg-danger';
            if(el.checked)
                el.closest('.row').className += ' text-white bg-danger';
            else{
                var reg = new RegExp('\\b'+'text-white bg-danger'+'\\b','g');
                el.closest('.row').className = e.className.replace(reg, "");
            }
        },
        this.init_name_tool_radio = function(el){
            var name_tools = document.querySelectorAll('.name_wrupp');
            var e;
    //        console.log(el);
            for (var i = 0; i < name_tools.length; i++) {
            //  alert( bts[i].innerHTML ); // "тест", "пройден"
                e = name_tools[i];

    //            var reg = /\bbg-danger\b/g;
                var reg = new RegExp('\\b'+'text-white bg-danger'+'\\b','g');
                e.className = e.className.replace(reg, "");
            }
    //        el.closest('div').closest('div').closest('label').className += ' bg-danger';
            el.closest('.row').className += ' text-white bg-danger';
        },
        
        this.classToogle = function (el,classname) {
            if (el.classList) { 
                el.classList.toggle(classname);
            } else {
                // For IE9
                var classes = el.className.split(" ");
                var i = classes.indexOf(classname);

                if (i >= 0) 
                    classes.splice(i, 1);
                else 
                    classes.push(classname);
                el.className = classes.join(" "); 
            }
        },
        this.classRemove = function (el,classname) {
            var reg = new RegExp('\\b'+classname+'\\b','g');
            el.className = el.className.replace(reg, "");
            el.className = el.className.replace('  ', ' ');
        },
        this.classAdd = function (el,classname) {
            var  arr;
            arr = el.className.split(" ");
            if (arr.indexOf(classname) == -1) {
              el.className += " " + classname;
            }
        },
        
        this.show_mess = function (mess){
            $('#'+this.mess_wrapp_id).html('');
            for(var i = 0; i<mess.length;i++){
                this.build_mess(mess[i]);
            }
        },
        this.build_mess = function (mess){
            var mw= document.createElement('div')
            mw.className='log_mess alert alert-primary';
//            var m = document.createTextNode(mess);
//            mw.appendChild(m);
            var m= document.createElement('div')
            m.innerHTML = mess;
//            m.innerText(mess);
            mw.appendChild(m);
//                        console.log( m );
            var w = document.getElementById(this.mess_wrapp_id);
    //        w.appendChild(mw);
            $('#'+this.mess_wrapp_id).append(mw);
            this.remove_mess(mw);
        },
        this.messages = [],
        this.rem_mess_delay = 60000,
        this.remove_mess = function(mess){
            setTimeout(mess.remove.bind(mess),this.rem_mess_delay);
        },
        this.clear_mess = function(){
            this.remove();
        },
        this.get = function(){
            console.log('this.ajax_url',this.ajax_url);
            $.ajax({
                url: this.ajax_url,//'/wp-admin/admin-ajax.php',
                type: 'POST',
                data: this.data, // можно также передать в виде объекта
                beforeSend: this.r_beforeSend,
                context: this,
//                success: function(data) {
//                    console.log(this);
//                    this[this.r_succ](data) ;
//                },
                success: this.r_succ,
                error: this.r_err
            });
        },
        this.beforeSend = function( xhr ) {
    //                    $('#'+id).text('Загрузка, 5 сек...');	
        },
        this.error = function( data ) {
    //                    $('#'+id).text('Ещё');
    //                    console.log( data );
        }
    };
    
    order .prototype = new helpers();
    var ord = new order();
    findMat.prototype = new helpers();
//    var fnd = new findMat('radio');
    var fnd = new findMat('check');
    
    
    var mn_find = document.querySelectorAll('.mn-find');
    //console.log(bts);
    for (var i = 0; i < mn_find.length; i++) {
    //  alert( bts[i].innerHTML ); // "тест", "пройден"
        mn_find[i].onkeyup = materialFind ;
    }
    function materialFind(){
    //    var name = document.getElementById('mnf-name').value;
    //    var categ = document.getElementById('mnf-categ').value;
    //    var code = document.getElementById('mnf-code').value;
    //    var manuf = document.getElementById('mnf-manuf').value;
    //    console.log(name,categ,code,manuf);
        fnd.find_materials();
    }
    
    if(document.getElementById(ord.mess_wrapp_id))
        ord.init_items();
    
    btninit.prototype = new btnmess();
    var bi = new btninit();
    bi.init_buttons();
    bi.mess();
});