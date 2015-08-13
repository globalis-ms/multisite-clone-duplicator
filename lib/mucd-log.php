<?php

if ( ! class_exists( 'MUCD_Log' ) ) {

	class MUCD_Log {

		private static $instance = false;
		private static $log_written_and_closed = false;

		private $mod;

		private $log_dir_path;
		private $log_file_path;
		private $log_file_name;
		private $log_file_url;

		private $fp;

		/**
		 * Constructor
		 * @since 0.2.0
		 * @param boolean $mod is log active
		 * @param string $log_dir_path log directory
		 * @param string $log_file_name log file name
		 */
		private function __construct( $mod, $log_dir_path = '', $log_file_name = '' ) {
			$this->mod = $mod;

			$this->log_dir_path  = $log_dir_path;
			$this->log_file_name = $log_file_name;
			$this->log_file_path = $log_dir_path . $log_file_name;

			$this->log_file_url = str_replace( ABSPATH, get_site_url( 1, '/' ), $log_dir_path ) . $log_file_name;

			if ( false !== $mod ) {
				$this->init_file();
			}
		}

		/**
		 * Returns log directory path
		 * @since 0.2.0
		 * @return string $this->log_dir_path
		 */
		private function dir_path() {
			return $this->log_dir_path;
		}

		/**
		 * Returns log file path
		 * @since 0.2.0
		 * @return string $this->log_file_path
		 */
		private function file_path() {
			return $this->log_file_path;
		}

		/**
		 * Returns log file name
		 * @since 0.2.0
		 * @return string $this->log_file_name
		 */
		private function file_name() {
			return $this->log_file_name;
		}

		/**
		 * Returns log file url
		 * @since 0.2.0
		 * @return string $this->log_file_url
		 */
		private function file_url() {
			return $this->log_file_url;
		}

		/**
		 * Checks if log is writable
		 * @since 0.2.0
		 * @return boolean True if plugin can writes the log, or false
		 */
		private function can_write() {
			return ( is_resource( $this->fp ) && is_writable( $this->log_file_path ) );
		}

		/**
		 * Returns log mod (active or not)
		 * @since 0.2.0
		 * @return boolean $this->mod
		 */
		private function mod() {
			return $this->mod;
		}

		/**
		 * Initialize file before writing
		 * @since 0.2.0
		 * @return boolean True on success, False on failure
		 */
		private function init_file() {
			if ( false !== MUCD_Clone_Files::init_dir( $this->log_dir_path ) ) {
				if ( ! $this->fp = @fopen( $this->log_file_path, 'a' ) ) {
					return false;
				}
				//chmod( $this->log_file_path, 0777 );
				return true;
			}
			return false;
		}

		/**
		 * Writes a message in log file
		 * @since 0.2.0
		 * @param  string $message the message to write
		 * @return boolean True on success, False on failure
		 */
		private function write_log( $message ) {
			if ( false !== $this->mod && $this->can_write() ) {
				$time = @date( '[d/M/Y:H:i:s]' );
				fwrite( $this->fp, "$time $message" . "\r\n" );
				return true;
			}
			return false;
		}

		/**
		 * Closes the log file
		 * @since 0.2.0
		 */
		private function close_log() {
			@fclose( $this->fp );
		}

		/**
		 * Init log object
		 * @since 0.2.0
		 * @param  array $data data from FORM
		 */
		public static function init( $data ) {
			// INIT LOG AND SAVE OPTION
			if ( isset( $data['log'] ) && true === $data['log'] ) {
				if ( isset( $data['log-path'] ) && ! empty( $data['log-path'] ) ) {
					$log_name = @date( 'Y_m_d_His' ) . '-' . $data['domain'] . '.log';
					if ( substr( $data['log-path'], -1 ) != '/' ) {
						$data['log-path'] = $data['log-path'] . '/';
					}
					self::$instance = new MUCD_Log( true, $data['log-path'], $log_name );
				}
			}
			else {
				 self::$instance = new MUCD_Log( false );
			}
		}

		/**
		 * Check if log is active
		 * @since 0.2.0
		 * @return boolean
		 */
		public static function is_active() {
			return ( false !== self::$instance && self::$instance->can_write() && self::$instance->mod() !== false );
		}

		/**
		 * Check if log has error
		 * @since 0.2.0
		 * @return boolean
		 */
		public static function has_error() {
			return ( false !== self::$instance &&  true !== self::$log_written_and_closed && self::$instance->mod() !== false );
		}

		/**
		 * Writes a message in log file
		 * @since 0.2.0
		 * @param  string $msg the message
		 */
		public static function write( $msg ) {
			if ( false !== self::is_active() ) {
				self::$instance->write_log( $msg );
			}
		}

		/**
		 * Close the log file
		 * @since 0.2.0
		 */
		public static function close() {
			if ( false !== self::is_active() ) {
				self::$instance->close_log();
				self::$log_written_and_closed = true;
			}
		}

		/**
		 * Get the url of the created log file
		 * @since 0.2.0
		 * @return  string the url of false if no log file was created
		 */
		public static function get_url() {
			if ( false !== self::is_active() || true === self::$log_written_and_closed ) {
				return self::$instance->file_url();
			}
			return false;
		}

		/**
		 * Get log directory
		 * @since 0.2.0
		 * @return string the path
		 */
		public static function get_dir() {
			return self::$instance->dir_path();
		}

	}
}
