( function ( $, mk ) {
  const mkAdminCustomizations = {
    billingState : $( '#_billing_state' ),
    init: function () {
      this.initBillingStateChange();
      this.initShippingStateChange();
    },

    initBillingStateChange: function (  ) {
      $( 'body' ).on(
        'change',
        '#_billing_state', // recipient city
        function ( event ) {
          const selectedValue = $( this ).val();

          mkAdminCustomizations.getAreas( selectedValue, '#_billing_city' );
        } );
    },

    initShippingStateChange: function (  ) {
      $( 'body' ).on(
        'change',
        '#_shipping_state', // recipient city
        function ( event ) {
          const selectedValue = $( this ).val();

          mkAdminCustomizations.getAreas( selectedValue, '#_shipping_city' );
        } );
    },

    getAreas: function ( district, recipientAreaSelector ) {
      let data = {
        action: 'mk_get_area_by_district',
        district: district,
        _nonce: mk.nonce,
      };

      $.post( mk.ajaxurl, data, function ( response ) {
        if ( response.success ) {
          const recipientArea = $( recipientAreaSelector );
          recipientArea.empty();

          const areas = response.data;

          areas.map( function ( area, index ) {
            recipientArea.append( `<option data-thana="${area.thana}" data-post_code="${area.post_code}" value="${area.name.toLowerCase()}">${area.name}</option>` );
          } );
        }
      } );
    }
  };

  mkAdminCustomizations.init();

} )( jQuery, window.MADKOFFEE_ADMIN );
