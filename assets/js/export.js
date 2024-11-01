jQuery(document).ready(function($){



	jQuery('#from_date').datepicker(); 

	jQuery('#to_date').datepicker(); 

	

	$( "#export-btn" ).click( function (e) {

		e.preventDefault();

		if ( (

			     $( "#from_date" ).val()

		     ) && (

			     $( "#to_date" ).val()

		     ) ) {

			var d1 = new Date( $( "#from_date" ).val() );

			var d2 = new Date( $( "#to_date" ).val() );

			if ( d1.getTime() > d2.getTime() ) {

				alert( 'Invalid Date Range. Please select proper Dates' );

				return false;

			}

		}

		var obj = jQuery( '#export_order_form' );

		data = waei_get_data(obj);



		data.push( {name: 'method', value: 'order_exporter'} );

		data.push( {name: 'waei_nonce', value: waei_nonce} );

		data.push( {name: 'tab', value: waei_active_tab} );



		jQuery.ajax( {

			type: "post",

			data: data,

			cache: false,

			url: waei_ajaxurl,

			dataType: "json",

			error: function ( xhr, status, error ) {
			    

				alert( xhr.responseText.replace( /<\/?[^>]+(>|$)/g, "" ) );
    
			},

			success: function ( response ) {
			    
			    if ( ! response || typeof response['orderMsgNew'] != '' ) {
			        
                    if(response['orderMsgNew'] ==''){
                        
                    }else{
					    alert( response['orderMsgNew'] );
                    }
				}
                    

				if ( ! response || typeof response['tmpname'] == 'undefined' ) {
                    console.log(11211);
					alert( response['tmpname'] );

					//return;

				}

				jQuery( '#export_csv_frame' ).attr( "src", waei_ajaxurl + (

					ajaxurl.indexOf( '?' ) === - 1 ? '?' : '&'

				) + 'action=woei_order_exporter&method=order_download&tmp=' +response['tmpname']+ '&tab=' + waei_active_tab );

			}

		} );



		return false;

	} );



	$('#track-btn').click(function (e) {

		e.preventDefault();

		tinyMCE.triggerSave();



		var obj = jQuery( '#tracking_form' );

		data = waei_get_data(obj);



		data.push( {name: 'method', value: 'import_tracking'} );

		data.push( {name: 'waei_nonce', value: waei_nonce} );

		data.push( {name: 'tab', value: waei_active_tab} );



		jQuery.ajax( {

			type: "post",

			data: data,

			cache: false,

			url: waei_ajaxurl,

			dataType: "json",

			error: function ( xhr, status, error ) {

				alert( xhr.responseText.replace( /<\/?[^>]+(>|$)/g, "" ) );

			},

			success: function ( response ) {

				if ( ! response || typeof response['status'] == 'undefined' ) {

					alert( response );

					return;

				}else{

					jQuery('#import-tab').prepend('<div class="updated notice"><div class="success">Tracking Form Data updated Successfully.</div></div>');



					jQuery('html, body').animate({

					    scrollTop: (jQuery('#wpcontent').offset().top)

					},1000);



					jQuery('.' + 'updated').fadeTo(6000, 0, function() {

		               jQuery('.' + 'updated').slideUp(1000, function() {

		                   jQuery('.' + 'updated').remove();

		               });

		           });

				}

			}

		} );



	});



	$('#import_order_form').on('submit',function (e) {

		e.preventDefault();

		jQuery('.' + 'notice').remove();
		jQuery('.progress-bar').animate({ 'width': '0%' }, 1500);
		jQuery('#waei_nonce').val(waei_nonce);

		jQuery('#tab').val(waei_active_tab);

		var obj = jQuery( '#import_order_form' );

		var data = new FormData(this);

		jQuery('#import-btn').attr('disabled',true);

		jQuery.ajax( {

			type: "post",

			data: data,

			cache: false,

			url: waei_ajaxurl,

			processData: false,

			contentType: false,

			dataType: "json",

			error: function ( xhr, status, error ) {

				alert( xhr.responseText.replace( /<\/?[^>]+(>|$)/g, "" ) );

			},

			success: function ( response ) {

				if ( response['success'] == false ) {

					

					jQuery('#import-tab').prepend('<div class="notice notice-error is-dismissible"><div class="error-block">'+response['msg']+'</div></div>');

					jQuery('html, body').animate({

					    scrollTop: (jQuery('#wpcontent').offset().top)

					},1000);



				// 	jQuery('.' + 'notice').fadeTo(6000, 0, function() {

		  //             jQuery('.' + 'notice').slideUp(1000, function() {

		  //                 jQuery('.' + 'notice').remove();

		  //             });

		  //          });

					return;

				}else{

					window.file_name = response['file'];

					window.file_id = response['file_id'];

					window.total_row = response['total_row'];

					// alert('succes');

					jQuery('#total_data').text(window.total_row);

					$('#process').css('display', 'block');

					import_uploaded_csv(window.file_name,window.file_id);

					clear_timer = setInterval(get_csv_process, 6000);

				}

			}

		} );



	});

});





function waei_get_data(obj) {

	var data = new Array();

	data.push( {name: 'json', value: waei_make_json(obj)} );

	data.push( {name: 'action', value: 'woei_order_exporter'} );

	return data;



}



function waei_make_json(obj){

	return JSON.stringify( obj.serializeJSON() );

}





function import_uploaded_csv(file,id){



	jQuery('#waei_nonce').val(waei_nonce);

	jQuery('#tab').val(waei_active_tab);



	var data = new Array();

	data.push( {name: 'action', value: 'woei_order_exporter'} );

	data.push( {name: 'method', value: 'import_start_csv'} );

	data.push( {name: 'waei_nonce', value: waei_nonce} );

	data.push( {name: 'tab', value: waei_active_tab} );

	data.push( {name: 'file', value: file} );

	data.push( {name: 'file_id', value: id} );



	jQuery.ajax( {

		type: "post",

		data: data,

		cache: false,

		url: waei_ajaxurl,

		dataType: "json",

		error: function ( xhr, status, error ) {

			alert( xhr.responseText.replace( /<\/?[^>]+(>|$)/g, "" ) );

		},

		success: function ( response ) {

			if ( response['success'] == false ) {

				

				jQuery('#import-tab').prepend('<div class="notice notice-error is-dismissible"><div class="error-block">'+response['msg']+'</div></div>');



				jQuery('html, body').animate({

				    scrollTop: (jQuery('#wpcontent').offset().top)

				},1000);



				jQuery('.' + 'notice').fadeTo(6000, 0, function() {

	               jQuery('.' + 'notice').slideUp(1000, function() {

	                   jQuery('.' + 'notice').remove();

	               });

	            });

				return;

			}else{

				window.msg = response['msg'];

			}

		}

	} );

}



function get_csv_process(){



	jQuery('#waei_nonce').val(waei_nonce);

	jQuery('#tab').val(waei_active_tab);



	var data = new Array();

	data.push( {name: 'action', value: 'woei_order_exporter'} );

	data.push( {name: 'method', value: 'import_csv_progress'} );

	data.push( {name: 'waei_nonce', value: waei_nonce} );

	data.push( {name: 'tab', value: waei_active_tab} );

	data.push( {name: 'file_id', value: window.file_id} );



	jQuery.ajax( {

		type: "post",

		data: data,

		cache: false,

		url: waei_ajaxurl,

		dataType: "json",

		error: function ( xhr, status, error ) {

			alert( xhr.responseText.replace( /<\/?[^>]+(>|$)/g, "" ) );

		},

		success: function ( data ) {

			var total_data = window.total_row;

			var width = Math.round((data/total_data)*100);

			jQuery('#process_data').text(data);

			jQuery('.progress-bar').animate({ 'width': width + '%' }, 1500);



	      	if(width >= 100 ){

				clearInterval(clear_timer);

		      	

		      	jQuery('#order_csv').val('');

		      	jQuery('#import-btn').attr('disabled',false);

  				jQuery('#import-tab').prepend('<div id="message" class="notice notice-success is-dismissible"><div class="success">'+window.msg+'</div></div>');



  				// jQuery('.' + 'notice').fadeTo(5000, 0, function() {

      //              // jQuery('.' + 'notice').remove();

      //              jQuery('#process').css('display', 'none');

      //              jQuery('.progress-bar').css('width', '0%');

  	   //          });

		    }

		}

	} );

}