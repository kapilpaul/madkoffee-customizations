(function ( $, mk ) {
	const mkAdminCustomizations = {
		billingState: $( '#_billing_state' ),
		init: function () {
			this.initBillingStateChange();
			this.initShippingStateChange();

			this.onSubmitOrderSourceReport();
			this.onSubmitSalesReport();
		},

		initBillingStateChange: function () {
			$( 'body' ).on(
				'change',
				'#_billing_state', // recipient city
				function ( event ) {
					const selectedValue = $( this ).val();

					mkAdminCustomizations.getAreas( selectedValue, '#_billing_city' );
				} );
		},

		initShippingStateChange: function () {
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
						recipientArea.append( `<option data-thana="${ area.thana }" data-post_code="${ area.post_code }" value="${ area.name.toLowerCase() }">${ area.name }</option>` );
					} );
				}
			} );
		},

		toggleLoader: function () {
			$( '.loader' ).toggleClass( 'is-active' ); //loader container
			$( '.loader .spinner' ).toggleClass( 'is-active' );
		},

		onSubmitOrderSourceReport: function () {
			const self = this;

			$( '.order-sources-report .form .submit' ).on(
				'click',
				function ( event ) {
					event.preventDefault();

					self.toggleLoader( this );

					const from_date = $( '.order-sources-report .form #order_sources_from_date' ).val();
					const to_date = $( '.order-sources-report .form #order_sources_to_date' ).val();

					if ( ! from_date || ! to_date ) {
						self.toggleLoader( this );
						return;
					}

					let data = {
						action: 'mk_get_order_sources_report_data',
						from_date: from_date,
						to_date: to_date,
						_nonce: mk.nonce,
					};

					$.post( mk.ajaxurl, data, function ( response ) {
						self.toggleLoader();

						if ( ! response.success ) {
							return;
						}

						$( '.order-sources-report__data' ).empty().append( response.data );
					} );
				}
			);
		},

		onSubmitSalesReport: function () {
			const self = this;
			$( '.sales-report .form .submit' ).on(
				'click',
				function ( event ) {
					event.preventDefault();

					self.toggleLoader();

					const from_date = $( '.sales-report .form #sales_from_date' ).val();
					const to_date = $( '.sales-report .form #sales_to_date' ).val();

					if ( ! from_date || ! to_date ) {
						self.toggleLoader();
						return;
					}

					let data = {
						action: 'mk_get_sales_report_data',
						from_date: from_date,
						to_date: to_date,
						_nonce: mk.nonce,
					};

					$.post( mk.ajaxurl, data, function ( response ) {
						self.toggleLoader();

						if ( ! response.success ) {
							return;
						}

						$( '.sales-reports__data' ).empty().append( response.data );
					} );
				}
			);
		}
	};

	mkAdminCustomizations.init();

})( jQuery, window.MADKOFFEE_ADMIN );
