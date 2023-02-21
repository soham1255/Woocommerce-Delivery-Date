jQuery(function($){

    class woo_delivery_manage_checkout {

        constructor( DD_field ){
            this.__this;
            this.DD_field = DD_field;

            this.eventHandlers();
        }

        eventHandlers(){
            $( document.body ).on( "click change", "input[name='wpk_delivery_checkbox']", this.delivery_date_field_show.bind(this) );
        }

        initDatepicker(){
            var _this = this;
            this.DD_field.datepicker({ 
                defaultDate: '+1w',
                minDate: 1,
                changeMonth: true,
                numberOfMonths: 1,
                beforeShowDay: function(date) {
                    if( wpkParam.show_weekend == "yes" ) {
                        return [true, "av", "available"];
                    } else {
                        if( date.getDay() == 0 || date.getDay() == 6 ) {
                            return [false, "notav", 'Not Available'];
                        } else {
                            return [true, "av", "available"];
                        }
                    }                    
                }
            });        
        }

       

        delivery_date_field_show(e){
            e.preventDefault();
            this.__this = $( e.currentTarget );
            this.DD_field.parents("p#datepicker_field").slideToggle();
            this.initDatepicker();
        }
    }
    new woo_delivery_manage_checkout( $( 'input#datepicker' ) );
});
