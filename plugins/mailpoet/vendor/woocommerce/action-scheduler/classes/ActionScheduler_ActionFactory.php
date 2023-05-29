<?php
if (!defined('ABSPATH')) exit;
class ActionScheduler_ActionFactory {
 public function get_stored_action( $status, $hook, array $args = array(), ActionScheduler_Schedule $schedule = null, $group = '' ) {
 switch ( $status ) {
 case ActionScheduler_Store::STATUS_PENDING:
 $action_class = 'ActionScheduler_Action';
 break;
 case ActionScheduler_Store::STATUS_CANCELED:
 $action_class = 'ActionScheduler_CanceledAction';
 if ( ! is_null( $schedule ) && ! is_a( $schedule, 'ActionScheduler_CanceledSchedule' ) && ! is_a( $schedule, 'ActionScheduler_NullSchedule' ) ) {
 $schedule = new ActionScheduler_CanceledSchedule( $schedule->get_date() );
 }
 break;
 default:
 $action_class = 'ActionScheduler_FinishedAction';
 break;
 }
 $action_class = apply_filters( 'action_scheduler_stored_action_class', $action_class, $status, $hook, $args, $schedule, $group );
 $action = new $action_class( $hook, $args, $schedule, $group );
 return apply_filters( 'action_scheduler_stored_action_instance', $action, $hook, $args, $schedule, $group );
 }
 public function async( $hook, $args = array(), $group = '' ) {
 return $this->async_unique( $hook, $args, $group, false );
 }
 public function async_unique( $hook, $args = array(), $group = '', $unique = true ) {
 $schedule = new ActionScheduler_NullSchedule();
 $action = new ActionScheduler_Action( $hook, $args, $schedule, $group );
 return $unique ? $this->store_unique_action( $action, $unique ) : $this->store( $action );
 }
 public function single( $hook, $args = array(), $when = null, $group = '' ) {
 return $this->single_unique( $hook, $args, $when, $group, false );
 }
 public function single_unique( $hook, $args = array(), $when = null, $group = '', $unique = true ) {
 $date = as_get_datetime_object( $when );
 $schedule = new ActionScheduler_SimpleSchedule( $date );
 $action = new ActionScheduler_Action( $hook, $args, $schedule, $group );
 return $unique ? $this->store_unique_action( $action ) : $this->store( $action );
 }
 public function recurring( $hook, $args = array(), $first = null, $interval = null, $group = '' ) {
 return $this->recurring_unique( $hook, $args, $first, $interval, $group, false );
 }
 public function recurring_unique( $hook, $args = array(), $first = null, $interval = null, $group = '', $unique = true ) {
 if ( empty( $interval ) ) {
 return $this->single_unique( $hook, $args, $first, $group, $unique );
 }
 $date = as_get_datetime_object( $first );
 $schedule = new ActionScheduler_IntervalSchedule( $date, $interval );
 $action = new ActionScheduler_Action( $hook, $args, $schedule, $group );
 return $unique ? $this->store_unique_action( $action ) : $this->store( $action );
 }
 public function cron( $hook, $args = array(), $base_timestamp = null, $schedule = null, $group = '' ) {
 return $this->cron_unique( $hook, $args, $base_timestamp, $schedule, $group, false );
 }
 public function cron_unique( $hook, $args = array(), $base_timestamp = null, $schedule = null, $group = '', $unique = true ) {
 if ( empty( $schedule ) ) {
 return $this->single_unique( $hook, $args, $base_timestamp, $group, $unique );
 }
 $date = as_get_datetime_object( $base_timestamp );
 $cron = CronExpression::factory( $schedule );
 $schedule = new ActionScheduler_CronSchedule( $date, $cron );
 $action = new ActionScheduler_Action( $hook, $args, $schedule, $group );
 return $unique ? $this->store_unique_action( $action ) : $this->store( $action );
 }
 public function repeat( $action ) {
 $schedule = $action->get_schedule();
 $next = $schedule->get_next( as_get_datetime_object() );
 if ( is_null( $next ) || ! $schedule->is_recurring() ) {
 throw new InvalidArgumentException( __( 'Invalid action - must be a recurring action.', 'action-scheduler' ) );
 }
 $schedule_class = get_class( $schedule );
 $new_schedule = new $schedule( $next, $schedule->get_recurrence(), $schedule->get_first_date() );
 $new_action = new ActionScheduler_Action( $action->get_hook(), $action->get_args(), $new_schedule, $action->get_group() );
 return $this->store( $new_action );
 }
 protected function store( ActionScheduler_Action $action ) {
 $store = ActionScheduler_Store::instance();
 return $store->save_action( $action );
 }
 protected function store_unique_action( ActionScheduler_Action $action ) {
 $store = ActionScheduler_Store::instance();
 return method_exists( $store, 'save_unique_action' ) ?
 $store->save_unique_action( $action ) : $store->save_action( $action );
 }
}
