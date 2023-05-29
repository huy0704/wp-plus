/**
 * Enum of general label status.
 *
 * @readonly
 * @enum {string}
 */
export const LABEL_STATUS = Object.freeze( {
	PENDING: 'pending',
	SUCCESS: 'success',
} );

/**
 * Enum of general process status.
 *
 * @readonly
 * @enum {string}
 */
export const PROCESS_STATUS = Object.freeze( {
	...LABEL_STATUS,
	IDLE: 'idle',
	ERROR: 'error',
} );
