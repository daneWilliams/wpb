<?php


namespace WPB;


/**
 *
 *	Object template
 *
 *	================================================================ 
 *
 *	@package	WPB
 *
 *	@since		1.0.0
 *
 */


class Object
{


	// Object data
	private $_id;
	protected $_type = 'object';


	/**
	 *
	 *	Setup object
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$id				// ID
	 *	@param		array 		$data			// Data
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function __construct( $id = '', $data = array() )
	{

		// Set ID
		$this->_id = $id;

		// Set data
		$this->data( $data );

	}


	/**
	 *
	 *	Get object ID
	 *
	 *	================================================================ 
	 *
	 *	@return		string						// ID
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function id()
	{

		return $this->_id;

	}


	/**
	 *
	 *	Get object type
	 *
	 *	================================================================ 
	 *
	 *	@return		string						// ID
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function object_type()
	{

		return $this->_type;

	}


	/**
	 *
	 *	Get and set data
	 *
	 *	================================================================ 
	 *
	 *	@param		string		$key			// Data key
	 *	@param		mixed		$value			// Data value
	 *
	 *	@return		mixed						// Data value, or boolean if setting data
	 *
	 *	@since		1.0.0
	 *
	 */
	
	public function data( $key = '', $value = NULL )
	{

		return wpb()->data( $key, $value, $this->object_type() . '/' . $this->id() );

	}


	/**
	 *
	 *	Get relative directory path
	 *
	 *	================================================================ 
	 *
	 *	@param		string 		$append			// Path to append
	 *
	 *	@return		string						// Path
	 *
	 *	@since		1.0.0
	 *
	 */

	public function dir( $append = '' )
	{

		$path = $this->data( '_dir' );

		// Set path
		if ( !$path ) {

			$c = new \ReflectionClass( $this );

			$path = plugin_dir_path( $c->getFilename() );
			$path = str_replace( '\\', '/', $path );
			$this->data( '_dir', $path );

		}

		// Append
		if ( $append )
			$path .= ltrim( $append, '/' );

		return $path;

	}


	/**
	 *
	 *	Get relative URL path
	 *
	 *	================================================================ 
	 *
	 *	@param		string 		$append			// Path to append
	 *
	 *	@return		string						// Path
	 *
	 *	@since		1.0.0
	 *
	 */

	public function url( $append = '' )
	{

		$path = $this->data( '_url' );

		// Set path
		if ( !$path ) {

			$c = new \ReflectionClass( $this );

			$path = plugin_dir_url( $c->getFilename() );
			$path = str_replace( '\\', '/', $path );
			$this->data( '_url', $path );

		}

		// Append
		if ( $append )
			$path .= ltrim( $append, '/' );

		return $path;

	}
	

	/**
	 *
	 *	Load an object file
	 *
	 *	================================================================ 
	 *
	 *	@param		string 		$path			// Path to file, relative to object directory
	 *	@param		boolean		$once			// Include once
	 *
	 *	@return		string						// Full path to the file, or false if not found
	 *
	 *	@since		1.0.0
	 *
	 */

	public function file( $path, $once = true )
	{

		// Add file extension
		if ( !substr( strchr( $path, '.' ), 1 ) )
			$path .= '.php';

		$path = $this->dir( $path );

		if ( !file_exists( $path ) )
			return false;

		// Get file
		if ( $once )
			require_once $path;

		else
			require $path;

		return $path;

	}


}