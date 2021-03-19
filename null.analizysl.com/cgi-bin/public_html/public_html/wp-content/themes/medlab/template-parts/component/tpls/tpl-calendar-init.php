<?php

/* 
 * tpl-calendar-init.php
 */


?>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!--<link rel="stylesheet" href="/resources/demos/style.css">-->
<style>
    
/* 009788 */
/* 7ccac3 */
/* bdbdbd */
/* 727272 */
.ui-datepicker-calendar th span{
    color: #bdbdbd;
}
/*.ui-widget-content .ui-datepicker-calendar td a.ui-state-default,
.ui-datepicker-calendar td a.ui-state-default{*/
.ui-widget-content .ui-state-default{
    color: #727272;
}
.ui-widget.ui-widget-content {
    /*border: 1px solid #c5c5c5;*/
    border: 1px solid #ffffff;
}
.ui-widget-header {
/*    border: 1px solid #dddddd;
    background: #e9e9e9;
    color: #333333;*/
    border: 1px solid #ffffff;
    background: #ffffff;
    color: #000000;
    font-weight: bold;
}
.ui-state-default,
.ui-widget-content .ui-state-default,
.ui-widget-header .ui-state-default,
.ui-button,
html .ui-button.ui-state-disabled:hover,
html .ui-button.ui-state-disabled:active ,
.ui-state-default{
    border: 1px solid #ffffff;/*003eff;*/
    background: #ffffff;/*007fff;*/
}
.ui-widget-content .ui-state-range .ui-state-default,
.ui-state-range .ui-state-default{
    border: 1px solid #7ccac3;
    background: #7ccac3;
    color: #ffffff;
}
.ui-widget-content .ui-state-end-range .ui-state-default,
.ui-widget-content .ui-state-range .ui-state-active,
.ui-state-range .ui-state-active,
.ui-widget-content .ui-state-active,
.ui-state-active{
    border: 1px solid #009788;/*003eff;*/
    background: #009788;/*007fff;*/
    color: #ffffff;
}
.ui-datepicker td {
    border: 0;
    /*padding: 1px;*/
    padding: 0;
    border: 1px solid #ffffff;
}
.ui-datepicker td.ui-state-range{
    border: 1px solid #7ccac3;
}
.ui-widget-content .ui-state-end-range ,
.ui-datepicker td.ui-datepicker-current-day{
    border: 1px solid #009788;/*003eff;*/
}
.ui-datepicker-today,
.ui-state-highlight,
.ui-widget-content .ui-state-highlight,
.ui-widget-content .ui-state-range.ui-datepicker-today,
.ui-widget-content .ui-datepicker-current-day.ui-datepicker-today,
.ui-widget-content .ui-state-range .ui-state-default.ui-state-highlight,
.ui-widget-content .ui-state-range .ui-state-active.ui-state-highlight
{
    border: 1px solid #dad55e;
    background: #fffa90;
    color: #777620;
    color: #000000;
}
</style>

  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
      // Source: http://stackoverflow.com/questions/497790
var dt = {
    convert:function(d) {
        // Converts the date in d to a date-object. The input can be:
        //   a date object: returned without modification
        //  an array      : Interpreted as [year,month,day]. NOTE: month is 0-11.
        //   a number     : Interpreted as number of milliseconds
        //                  since 1 Jan 1970 (a timestamp) 
        //   a string     : Any format supported by the javascript engine, like
        //                  "YYYY/MM/DD", "MM/DD/YYYY", "Jan 31 2009" etc.
        //  an object     : Interpreted as an object with year, month and date
        //                  attributes.  **NOTE** month is 0-11.
        return (
            d.constructor === Date ? d :
//            d.constructor === Array ? new Date(d[2],d[1],d[0]) :
            d.constructor === Array ? new Date(d[0],d[1],d[2]) :
            d.constructor === Number ? new Date(d) :
            d.constructor === String ? new Date(d) :
            typeof d === "object" ? new Date(d.year,d.month,d.date) :
            NaN
        );
    },
    compare:function(a,b) {
        // Compare two dates (could be of any type supported by the convert
        // function above) and returns:
        //  -1 : if a < b
        //   0 : if a = b
        //   1 : if a > b
        // NaN : if a or b is an illegal date
        // NOTE: The code inside isFinite does an assignment (=).
        return (
            isFinite(a=this.convert(a).valueOf()) &&
            isFinite(b=this.convert(b).valueOf()) ?
            (a>b)-(a<b) :
            NaN
        );
    },
    eqwal:function(a,b) {
        // Compare two dates (could be of any type supported by the convert
        // function above) and returns:
        //  -1 : if a < b
        //   0 : if a = b
        //   1 : if a > b
        // NaN : if a or b is an illegal date
        // NOTE: The code inside isFinite does an assignment (=).
        return (
            isFinite(a=this.convert(a).valueOf()) &&
            isFinite(b=this.convert(b).valueOf()) ?
            (a == b) :
            NaN
        );
    },
    inRange:function(d,start,end) {
        // Checks if date in d is between dates in start and end.
        // Returns a boolean or NaN:
        //    true  : if d is between start and end (inclusive)
        //    false : if d is before start or after end
        //    NaN   : if one or more of the dates is illegal.
        // NOTE: The code inside isFinite does an assignment (=).
       return (
            isFinite(d=this.convert(d).valueOf()) &&
            isFinite(start=this.convert(start).valueOf()) &&
            isFinite(end=this.convert(end).valueOf()) ?
            start <= d && d <= end :
            NaN
        );
    }
}
  $( function() {
    var dates = ['2019/10/14', '2019/10/18']; //
            //tips are optional but good to have
    var tips  = ['some description','some other description']; 
    function highlightDays(date) {
//            console.log(date.toString() );
//        for (var i = 0; i < dates.length; i++) {
////            console.log(dates[i] );
////            console.log( new Date(dates[i]).toString());
//            
//            if (new Date(dates[i]).toString() == date.toString()) {              
////                return [true, 'ui-state-range'];       
////                return [true, 'highlight', tips[i]];
//            }
//        }
            var from = $( "#from" ).val();
            var to = $( "#to" ).val();
//            let from = from.split('.');
//            let to = to.split('.');
//            dates[0] = from.split('.').reverse().join('.');
//            dates[1] = to.split('.').reverse().join('.');
            from = from.split('.').reverse().join('.');
            to = to.split('.').reverse().join('.');
//            if (dt.inRange(date, dates[0], dates[1])) { 
// ui-state-end-range
            if (from.length>0 && to.length>0 && dt.eqwal(date, from)) {              
                return [true, 'ui-state-end-range'];       
//                return [true, 'highlight', tips[i]];
            }
            if (from.length>0 && to.length>0 && dt.eqwal(date, to)) {              
                return [true, 'ui-state-end-range'];       
//                return [true, 'highlight', tips[i]];
            }
            if (from.length>0 && to.length>0 && dt.inRange(date, from, to)) {              
                return [true, 'ui-state-range'];       
//                return [true, 'highlight', tips[i]];
            }
//            if (new Date(dates[1]).toString() == date.toString()) {              
//                return [true, 'ui-state-range'];       
////                return [true, 'highlight', tips[i]];
//            }
        return [true, ''];
     };
     
    var dateFormat = "dd.mm.yy",
      from = $( "#from" )
        .datepicker({
//            defaultDate: "-1w",
            beforeShowDay: highlightDays,
//          changeMonth: true,
          numberOfMonths: 2
//            beforeShowDay: function(date) {
//            var dto = $( "#to" ).val();
//            console.log(date );
//            console.log( dto);
//             if (date == dto) {
//              return [true, 'ui-state-range', 'tooltipText'];
//
//              }
//           }
        })
        .datepicker( "option", "dateFormat", dateFormat)
        .on( "change", function() {
          to.datepicker( "option", "minDate", getDate( this ) );
        })
        .val('<?= $date_from ?>')
        
      to = $( "#to" ).datepicker({
        defaultDate: "-4w",
            beforeShowDay: highlightDays,
//        changeMonth: true,
        numberOfMonths: 2
      })
      .datepicker( "option", "dateFormat", dateFormat)
      .on( "change", function() {
        from.datepicker( "option", "maxDate", getDate( this ) );
      })
        .val('<?= $date_to ?>');
//      $( "#from" ).datepicker( "option", "dateFormat", dateFormat);
//      $( "#to" ).datepicker( "option", "dateFormat", dateFormat);
 
    function getDate( element ) {
      var date;
      try {
        date = $.datepicker.parseDate( dateFormat, element.value );
      } catch( error ) {
        date = null;
      }
 
      return date;
    }
  } );
  </script>