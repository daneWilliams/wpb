<?php


namespace WPB;


/**
 *
 *	Router class
 *
 *	================================================================ 
 *
 *	@package	WPB
 *
 *	@since		1.0.0
 *
 */


class Router
{


	// Routes
	protected $routes;

	// Requests
	protected $requests;
	protected $wildcards;
	protected $shortcodes;

	// Registered shortcodes
	protected $registered_shortcodes;


	/**
	 *
	 *	Setup the class
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function __construct()
	{

		// Load requests
		wpb()->file( 'includes/helpers/requests-methods' );
		wpb()->file( 'includes/helpers/requests-register' );

		// Register requests
		add_action( 'wpb/setup', array( $this, 'register_requests' ), 5 );

		// Add shortcodes
		add_action( 'wpb/init', array( $this, 'add_shortcodes' ), 5 );

	}


	/**
	 *
	 *	Process a request
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function request( $args = array() )
	{

		if ( empty( $args ) )
			return false;

		// Get formatted request
		$request = $this->format( $args );

		if ( !$request )
			return false;

		// Route request
		$routed = $this->route( $request );

		if ( !$routed )
			return false;

		// Perform request
		return $this->perform( $request, $routed );
	
	}


	/**
	 *
	 *	Register a request
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$request		// Request string
	 *	@param		string		$callback		// Callback function
	 *	@param		array 		$args			// Request arguments
	 *	@param		boolean		$shortcode		// Register shortcode request
	 *
	 *	@since		1.0.0
	 *
	 */

	public function register( $request, $callback, $args = array(), $shortcode = false )
	{

		// Check for wildcard
		if ( substr( $request, -2, 2 ) == '/*' ) {

			if ( isset( $this->wildcards[ $request ] ) )
				return false;

			// Get arguments
			if ( !is_array( $args ) ) {

				if ( strstr( $args, '=' ) ) {

					$args = wp_parse_args( $args );

				} else {

					$args = array( $args => '*' );

				}

			}

			$this->wildcards[ substr( $request, 0, -2 ) ] = array(
				'method' => $callback,
				'args'   => $args
			);

			return true;

		}

		if ( isset( $this->requests[ $request ] ) )
			return false;

		// Get arguments
		if ( !is_array( $args ) ) {

			if ( strstr( $args, '=' ) ) {

				$args = wp_parse_args( $args );

			}

		}

		$this->requests[ $request ] = array(
			'callback' => $callback,
			'args'     => $args
		);

		if ( $shortcode ) {

			$this->shortcodes[ $request ] = $shortcode;

		}

		return true;

	}


	/**
	 *
	 *	Register requests
	 *
	 *	================================================================ 
	 *
	 *	@return		boolean						// Registered or not
	 *
	 *	@since		1.0.0
	 *
	 */

	public function register_requests()
	{

		if ( !is_null( $this->requests ) )
			return false;

		// Register methods
		do_action( 'wpb/requests/register' );

		if ( is_null( $this->requests ) )
			$this->requests = array();

		if ( is_null( $this->wildcards ) )
			$this->wildcards = array();

		if ( is_null( $this->shortcodes ) )
			$this->shortcodes = array();

		return true;

	}


	/**
	 *
	 *	Get registered requests
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Requests
	 *
	 *	@since		1.0.0
	 *
	 */

	protected function get_requests()
	{

		if ( !is_null( $this->requests ) )
			return $this->requests;

		// Register requests
		$this->register_requests();

		return $this->requests;

	}


	/**
	 *
	 *	Get registered wildcards
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Wildcards
	 *
	 *	@since		1.0.0
	 *
	 */

	protected function get_wildcards()
	{

		if ( !is_null( $this->wildcards ) )
			return $this->wildcards;

		// Register requests
		$this->register_requests();

		return $this->wildcards;

	}


	/**
	 *
	 *	Get registered shortcodes
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Shortcodes
	 *
	 *	@since		1.0.0
	 *
	 */

	protected function get_shortcodes()
	{

		if ( !is_null( $this->shortcodes ) )
			return $this->shortcodes;

		// Register requests
		$this->register_requests();

		return $this->shortcodes;

	}


	/**
	 *
	 *	Get formatted shortcodes
	 *
	 *	================================================================ 
	 *
	 *	@return		array 						// Formatted shortcodes
	 *
	 *	@since		1.0.0
	 *
	 */

	protected function formatted_shortcodes()
	{

		// Cached
		if ( wpb()->data( 'request/shortcodes/formatted' ) )
			return wpb()->data( 'request/shortcodes/formatted' );

		// Get shortcodes
		$shortcodes = $this->get_shortcodes();

		if ( empty( $shortcodes ) )
			return false;

		// Get requests
		$requests = $this->get_requests();

		// Format
		$formatted = array();

		foreach ( $shortcodes as $request => $shortcode ) {

			if ( !isset( $requests[ $request ] ) )
				continue;

			// Get shortcode array
			if ( !is_array( $shortcode ) ) {

				if ( !strstr( $shortcode, '=' ) ) {

					$shortcode = array(
						'str' => ( is_bool( $shortcode ) ? $request : $shortcode )
					);

				}
				
				$shortcode = wp_parse_args( $shortcode, array(
					'str'  => '',
					'args' => array(),
					'text' => true
				) );

			}

			// Get arguments
			$args = ( !empty( $requests[ $request ]['args'] ) ? $requests[ $request ]['args'] : array() );

			if ( !empty( $shortcode['args']) )
				$args = $this->map_args( $shortcode['args'], $args );

			$shortcode['args'] = $args;

			// Get text argument
			$text = ( !empty( $shortcode['text'] ) ? $shortcode['text'] : true );

			if ( $text ) {

				$key = $text;

				if ( is_bool( $text ) )
					$key = 'text';

				if ( !isset( $args[ $key ] ) )
					$text = false;

			}

			// Get shortcode string
			$str = ( !empty( $shortcode['str'] ) ? $shortcode['str'] : $request );
			$str = str_replace( '/', '-', $str );
			$str = sanitize_title( $str );
			$str = 'wpb-' . $str;
			$str = str_replace( 'wpb-wpb', 'wpb', $str );

			$formatted[ $str ] = array(
				'request' => $request,
				'str'     => $str,
				'text'    => $text,
				'args'    => $shortcode['args']
			);

		}

		// Cache
		wpb()->data( 'request/shortcodes/formatted', $formatted );

		return $formatted;

	}


	/**
	 *
	 *	Add shortcodes
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function add_shortcodes()
	{

		// Already added
		if ( !is_null( $this->registered_shortcodes ) )
			return false;

		// Get shortcodes
		$shortcodes = $this->formatted_shortcodes();

		if ( empty( $shortcodes ) )
			return false;

		// Add shortcodes
		foreach ( $shortcodes as $str => $shortcode ) {

			$this->registered_shortcodes[ $str ] = $shortcode;

			add_shortcode( $str, array( $this, 'do_shortcode' ), 10, 3 );

		}

		return true;

	}


	/**
	 *
	 *	Perform a shortcode action
	 *
	 *	================================================================ 
	 *
	 *	@since		1.0.0
	 *
	 */

	public function do_shortcode( $atts, $content = '', $tag = '' )
	{

		if ( !$tag )
			return;

		// Get shortcodes
		$shortcodes = $this->formatted_shortcodes();

		if ( !isset( $shortcodes[ $tag ] ) )
			return;

		$shortcode = $shortcodes[ $tag ];

		if ( empty( $shortcode['request'] ) )
			return;

		// Get arguments
		$atts = shortcode_atts( $shortcode['args'], $atts, $tag );

		// Add text
		if ( $content && !empty( $shortcode['text'] ) ) {

			if ( empty( $atts[ $shortcode['text'] ] ) ) {

				$key = ( is_bool( $shortcode['text'] ) ? 'text' : $shortcode['text'] );

				$atts[ $key ] = $content;

			}

		}

		// Perform request
		return wpb( $shortcode['request'], $atts );

	}


	/**
	 *
	 *	Format a request
	 *
	 *	================================================================ 
	 *
	 *	@param		array 		$args			// Request arguments
	 *
	 *	@return		object						// Formatted request object
	 *
	 *	@since		1.0.0
	 *
	 */

	protected function format( $args = array() )
	{

		$formatted = array(
			'method' => '',
			'args'   => array()
		);

		if ( empty( $args ) )
			return (object) $formatted;

		// Add method
		$formatted['method'] = array_shift( $args );

		// Add arguments
		if ( !empty( $args ) )
			$formatted['args'] = wp_parse_args( $args );

		return (object) $formatted;

	}


	/**
	 *
	 *	Route a request
	 *
	 *	================================================================ 
	 *
	 *	@param		object 		$request		// Request to route
	 *
	 *	@return		mixed						// Routed request object, or false
	 *
	 *	@since		1.0.0
	 *
	 */

	protected function route( $request )
	{

		// Get requests
		$requests  = $this->get_requests();
		$wildcards = $this->get_wildcards();

		// Get method
		$method   = $request->method;
		$wildcard = false;

		// Get method
		if ( !isset( $requests[ $method ] ) ) {

				if ( !strstr( $method, '/' ) || empty( $wildcards ) )
					return false;

			// Check for wildcard
			foreach ( $wildcards as $wildcard_method => $wildcard_data ) {

				$strlen = ( strlen( $wildcard_method ) + 1 );

				if ( substr( $method, 0, $strlen ) != $wildcard_method . '/' )
					continue;

				// Method doesn't exist
				if ( !isset( $requests[ $wildcard_data['method'] ] ) )
					continue;

				// Get wildcard data
				$wildcard = $wildcard_data;

				$wildcard_value = substr( $method, $strlen );
				$method = $wildcard_data['method'];

				if ( !empty( $wildcard['args'] ) ) {

					foreach ( $wildcard['args'] as $arg_key => $arg_value ) {

						$wildcard['args'][ $arg_key ] = str_replace( '*', $wildcard_value, $arg_value );

					}

				}

				if ( empty( $wildcards['args'] ) )
					$wildcards['args'] = array();

				$wildcard['args']['_wildcard'] = array(
					'request' => $request->method,
					'value'   => substr( $request->method, $strlen )
				);

				break;

			}

			if ( !$wildcard )
				return false;

		}

		// Get arguments
		$args = ( !empty( $requests[ $method ]['args'] ) ? $requests[ $method ]['args'] : array() );

		// Get route
		$routed = array(
			'request'  => $method,
			'callback' => $requests[ $method ]['callback'],
			'args'     => $args
		);

		if ( is_array( $routed['callback'] ) ) {

			$first = array_shift( $routed['callback'] );

			if ( empty( $routed['callback'] ) )
				return false;

			$second = array_shift( $routed['callback'] );

			if ( is_array( $second ) ) {

				$first  = array_shift( $second );
				$second = array_shift( $second );

			}

			$routed['callback'] = array( $first, $second );

		}

		// Check method can be called
		if ( is_string( $routed['callback'] ) ) {

			if ( !function_exists( $routed['callback'] ) )
				return false;

		} elseif ( is_array( $routed['callback'] ) ) {

			if ( !method_exists( $routed['callback'][0], $routed['callback'][1] ) )
				return false;

		}

		// Get the arguments
		$routed['args'] = $this->map_args( $request->args, $routed['args'], ( !empty( $wildcard['args'] ) ? $wildcard['args'] : false ) );

		// Filter
		$routed = apply_filters( 'wpb/requests/route/' . $method, $routed, $request, $wildcard );

		return (object) $routed;

	}


	/**
	 *
	 *	Map arguments
	 *
	 *	================================================================ 
	 *
	 *	@param		array		$user_args		// Original arguments
	 *	@param		array 		$method_args	// Method arguments
	 *	@param		array 		$wildcard_args	// Mapped wildcard arguments
	 *
	 *	@return		array						// Mapped arguments
	 *
	 *	@since		1.0.0
	 *
	 */

	public function map_args( $user_args, $method_args, $wildcard_args = array() )
	{

		$args = array();

		// Convert user arguments
		if ( count( $user_args ) == 1 ) {

			$arg = reset( $user_args );

			// Array given
			if ( is_array( $arg ) ) {

				if ( isset( $user_args[0] ) ) {

					if ( !empty( $method_args ) ) {

						$found = false;

						foreach ( $method_args as $key => $value ) {

							if ( isset( $arg[ $key ] ) ) {

								$found = true;
								break;

							}

						}

						if ( $found )
							$user_args = $arg;

					} else {

						$user_args = $arg;

					}

				}

			} else {

				// Query string
				$query = wp_parse_args( $arg, array() );

				if ( count( $query ) > 1 || strstr( $arg, '=' ) )
					$user_args = $query;

			}

		}

		// No method arguments
		if ( !$method_args || empty( $method_args ) )
			return $user_args;

		// Map the arguments
		$i = 0;

		foreach ( $method_args as $key => $default ) {

			$value = $default;

			if ( isset( $wildcard_args[ $key ] ) ) {

				$args[ $key ] = $wildcard_args[ $key ];
				continue;

			}

			if ( isset( $user_args[ $key ] ) || isset( $user_args[ $i ] ) ) {

				if ( isset( $user_args[ $key ] ) && is_array( $user_args[ $key ] ) && !is_array( $value ) ) {

					if ( !$value )
						$value = array();

					else
						$value = array( $value );

				}

				$value = (
					isset( $user_args[ $key ] ) 
					? ( is_array( $user_args[ $key ] ) ? array_merge( $value, $user_args[ $key ] ) : $user_args[ $key ]	) 
					: $user_args[ $i ]
				);

			}

			// Convert to appropriate types
			if ( 'false' === $value )
				$value = false;

			if ( 'true' === $value )
				$value = true;

			// Add back to arguments
			$args[ $key ] = $value;

			$i++;

		}

		return $args;

	}


	/**
	 *
	 *	Perform a request
	 *
	 *	================================================================
	 *
	 *	@param		object		$request		// The original request
	 *	@param		object		$routed			// The routed request
	 *
	 *	@return 	mixed 						// The returned value, if applicable
	 *
	 *	@since 		1.0.0
	 *
	 */

	private function perform( $request, $routed ) 
	{

		// Is a value going to be returned?
		$return = ( isset( $routed->args['return'] ) ? $routed->args['return'] : true );

		// Echo the value
		$echo = ( isset( $routed->args['echo'] ) ? $routed->args['echo'] : false );

		// Perform the request
		$value = call_user_func( $routed->callback, $routed->args );

		// Hook
		do_action( 'wpb/request/' . $routed->request, $routed, $request );

		// Return or echo the value
		if ( $return && $value ) {

			// Filter
			$value = apply_filters( 'wpb/requests/value/' . $routed->request, $value, $routed->args );

			if ( !$echo ) 
				return $value;

			echo $value;

		} elseif ( $echo && $value ) {

			echo $value;

		}

	}


}