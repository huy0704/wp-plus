<?php
if (!defined('ABSPATH')) exit;
class ActionScheduler_OptionLock extends ActionScheduler_Lock {
 public function set( $lock_type ) {
 return update_option( $this->get_key( $lock_type ), time() + $this->get_duration( $lock_type ) );
 }
 public function get_expiration( $lock_type ) {
 return get_option( $this->get_key( $lock_type ) );
 }
 protected function get_key( $lock_type ) {
 return sprintf( 'action_scheduler_lock_%s', $lock_type );
 }
}
