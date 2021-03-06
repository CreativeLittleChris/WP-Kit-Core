<?php
    
    namespace WPKit\Core;
    
    class Invoker {
	    
	    public static $routes = [];
	    
		public static function invoke_by_url( $controller, $url, $priority = 20 ) {
			
			add_action( 'wp', function() use($controller, $url, $priority) {
				
				if( $url ) {
				
					self::invoke_by_action( $controller, 'wp', $priority );
				
				}
				
			});
			
		}
		
		public static function invoke_by_page( $controller, $page, $priority = 20 ) {
			
			add_action( 'wp', function() use($controller, $page, $priority) {
			
				if( is_page( $page ) ) {
					
					self::invoke_by_action( $controller, 'wp', $priority );
				
				}
				
			});
			
		}
		
		public static function invoke_by_condition( $controller, $condition, $priority = 20 ) {
			
			add_action( 'wp', function() use($controller, $condition, $priority) {
			
				if( is_callable($condition) && call_user_func($condition) ) {
					
					self::invoke_by_action( $controller, 'wp', $priority );
				
				}
				
			});
			
		}
		
		public static function invoke_by_action( $controller, $action = 'wp', $priority = 20 ) {
			
			$controller = stripos($controller, '\\') === 0 ? $controller : "App\Controllers\\$controller";
			
			self::$routes[] = [
				$controller,
				$action,
				$priority
			];
			
			add_action( $action, array($controller, 'init'), $priority );
			
		}
		
		public static function invoked( $controller, $action = 'wp', $priority = 20 ) {
			
			return array_search(array_merge(array(
				'controller' => '',
				'action' => 'wp',
				'priority' => 20
			), get_defined_vars()), self::$routes) > -1 ? true : false;
			
		}
		
		public static function uninvoke( $controller, $action = 'wp', $priority = 20 ) {
			
			$index = array_search(array_merge(array(
				'controller' => '',
				'action' => 'wp',
				'priority' => 20
			), get_defined_vars()), self::$routes);
			
			if( $index > -1 ) {
				
				unset( self::$routes[$index] );
				
			}
			
		}
	    
	}
