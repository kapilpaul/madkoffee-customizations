<?php
/**
 * Frontend handler class
 *
 * @package MadKoffee\Customizations\Frontend
 */

namespace MadKoffee\Customizations;

/**
 * Frontend handler class
 */
class Frontend {

    /**
     * Frontend constructor.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function __construct() {
        $this->hooks();
    }

	/**
	 * Add hooks
	 */
	public function hooks() {
		add_action( 'wp_head', [ $this, 'add_sitelink_search_box' ] );
		add_action( 'wp_head', [ $this, 'add_fb_pixel_script' ] );
    }

	/**
	 * Add site link search box for google.
	 *
	 * @return void
	 */
	public function add_sitelink_search_box() {
		?>
		<script type="application/ld+json">
			{
				"@context": "https://schema.org",
				"@type": "WebSite",
				"url": "<?php echo site_url(); ?>",
				"potentialAction": {
					"@type": "SearchAction",
					"target": {
						"@type": "EntryPoint",
						"urlTemplate": "<?php echo site_url(); ?>/?s={search_term_string}&post_type=product"
					},
					"query-input": "required name=search_term_string"
				}
			}
		</script>
		<?php
    }

	/**
	 * Add FB pixel code.
	 *
	 * @return void
	 */
	public function add_fb_pixel_script() {
		?>
		<!-- Facebook Pixel Code -->
		<script>
			!function(f,b,e,v,n,t,s)
			{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
				n.callMethod.apply(n,arguments):n.queue.push(arguments)};
				if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
				n.queue=[];t=b.createElement(e);t.async=!0;
				t.src=v;s=b.getElementsByTagName(e)[0];
				s.parentNode.insertBefore(t,s)}(window,document,'script',
				'https://connect.facebook.net/en_US/fbevents.js');
			fbq('init', '1586893128489715');
			fbq('track', 'PageView');
		</script>
		<noscript>
			<img height="1" width="1" src="https://www.facebook.com/tr?id=1586893128489715&ev=PageView&noscript=1"/>
		</noscript>
		<!-- End Facebook Pixel Code -->
		<?php
    }
}
