<?php

namespace G28\B2bkingext;

class Startup
{
    protected static ?Startup $_instance = null;

	public static function getInstance(): ?Startup {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

    public function run( string $root ) 
    {
        add_action( 'plugins_loaded', function () use ( $root ) {
			Plugin::getInstance($root);
			new Controller();
		} );
    }
}