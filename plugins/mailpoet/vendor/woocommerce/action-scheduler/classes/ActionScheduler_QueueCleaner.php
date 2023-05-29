<?php
if (!defined('ABSPATH')) exit;
class ActionScheduler_QueueCleaner {
 protected $batch_size;
 private $store = null;
 private $month_in_seconds = 2678400;
 public function __construct( ActionScheduler_Store $store = null, $batch_size = 20 ) {
 $this->store = $store ? $store : ActionScheduler_Store::instance();
 $this->batch_size = $batch_size;
 }
 public function delete_old_actions() {
 $lifespan = apply_filters( 'action_scheduler_retention_period', $this->month_in_seconds );
 $cutoff = as_get_datetime_object($lifespan.' seconds ago');
 $statuses_to_purge = array(
 ActionScheduler_Store::STATUS_COMPLETE,
 ActionScheduler_Store::STATUS_CANCELED,
 );
 foreach ( $statuses_to_purge as $status ) {
 $actions_to_delete = $this->store->query_actions( array(
 'status' => $status,
 'modified' => $cutoff,
 'modified_compare' => '<=',
 'per_page' => $this->get_batch_size(),
 'orderby' => 'none',
 ) );
 foreach ( $actions_to_delete as $action_id ) {
 try {
 $this->store->delete_action( $action_id );
 } catch ( Exception $e ) {
 do_action( 'action_scheduler_failed_old_action_deletion', $action_id, $e, $lifespan, count( $actions_to_delete ) );
 }
 }
 }
 }
 public function reset_timeouts( $time_limit = 300 ) {
 $timeout = apply_filters( 'action_scheduler_timeout_period', $time_limit );
 if ( $timeout < 0 ) {
 return;
 }
 $cutoff = as_get_datetime_object($timeout.' seconds ago');
 $actions_to_reset = $this->store->query_actions( array(
 'status' => ActionScheduler_Store::STATUS_PENDING,
 'modified' => $cutoff,
 'modified_compare' => '<=',
 'claimed' => true,
 'per_page' => $this->get_batch_size(),
 'orderby' => 'none',
 ) );
 foreach ( $actions_to_reset as $action_id ) {
 $this->store->unclaim_action( $action_id );
 do_action( 'action_scheduler_reset_action', $action_id );
 }
 }
 public function mark_failures( $time_limit = 300 ) {
 $timeout = apply_filters( 'action_scheduler_failure_period', $time_limit );
 if ( $timeout < 0 ) {
 return;
 }
 $cutoff = as_get_datetime_object($timeout.' seconds ago');
 $actions_to_reset = $this->store->query_actions( array(
 'status' => ActionScheduler_Store::STATUS_RUNNING,
 'modified' => $cutoff,
 'modified_compare' => '<=',
 'per_page' => $this->get_batch_size(),
 'orderby' => 'none',
 ) );
 foreach ( $actions_to_reset as $action_id ) {
 $this->store->mark_failure( $action_id );
 do_action( 'action_scheduler_failed_action', $action_id, $timeout );
 }
 }
 public function clean( $time_limit = 300 ) {
 $this->delete_old_actions();
 $this->reset_timeouts( $time_limit );
 $this->mark_failures( $time_limit );
 }
 protected function get_batch_size() {
 return absint( apply_filters( 'action_scheduler_cleanup_batch_size', $this->batch_size ) );
 }
}
