<?php
if ( ! defined( 'myCRED_VERSION' ) ) exit;

/**
 * myCRED_Log_Module class
 * @since 0.1
 * @version 1.1.2
 */
if ( ! class_exists( 'myCRED_Log_Module' ) ) :
	class myCRED_Log_Module extends myCRED_Module {

		public $user;
		public $screen;
		public $log_columns = array();

		/**
		 * Construct
		 */
		function __construct( $type = MYCRED_DEFAULT_TYPE_KEY ) {

			parent::__construct( 'myCRED_Log_Module', array(
				'module_name' => 'log',
				'labels'      => array(
					'menu'        => __( 'Log', 'mycred' ),
					'page_title'  => __( 'Log', 'mycred' )
				),
				'screen_id'   => MYCRED_SLUG,
				'cap'         => 'editor',
				'accordion'   => true,
				'register'    => false,
				'menu_pos'    => 10
			), $type );

		}

		/**
		 * Init
		 * @since 0.1
		 * @version 1.2
		 */
		public function module_init() {

			$this->current_user_id = get_current_user_id();

			add_filter( 'mycred_add_finished', array( $this, 'update_user_references' ), 90, 2 );
			add_action( 'mycred_add_menu',     array( $this, 'my_history_menu' ) );

			// Handle deletions
			add_action( 'before_delete_post',  array( $this, 'post_deletions' ) );
			add_action( 'delete_comment',      array( $this, 'comment_deletions' ) );

			// If we do not want to delete log entries, attempt to hardcode the users
			// details with their last known details.
			if ( isset( $this->core->delete_user ) && ! $this->core->delete_user )
				add_action( 'delete_user', array( $this, 'user_deletions' ) );

			add_action( 'wp_ajax_mycred-delete-log-entry', array( $this, 'action_delete_log_entry' ) );
			add_action( 'wp_ajax_mycred-update-log-entry', array( $this, 'action_update_log_entry' ) );

		}

		/**
		 * Admin Init
		 * @since 1.4
		 * @version 1.1
		 */
		public function module_admin_init() {

			add_action( 'admin_notices',            array( $this, 'admin_notices' ) );
			add_action( 'mycred_delete_point_type', array( $this, 'delete_point_type' ) );

			$screen_id = 'toplevel_page_' . MYCRED_SLUG;
			if ( $this->mycred_type != MYCRED_DEFAULT_TYPE_KEY )
				$screen_id .= '_' . $this->mycred_type;

			$this->set_columns();

			add_filter( "manage_{$screen_id}_columns", array( $this, 'log_columns' ) );

		}

		/**
		 * Set Columns
		 * Sets the table columns that are shown in the log.
		 * @since 1.7
		 * @version 1.0
		 */
		protected function set_columns() {

			$column_headers = array(
				'cb'       => '',
				'username' => __( 'User', 'mycred' ),
				'ref'      => __( 'Reference', 'mycred' ),
				'time'     => __( 'Date', 'mycred' ),
				'creds'    => '%plural%',
				'entry'    => __( 'Entry', 'mycred' )
			);

			$column_headers = apply_filters( 'mycred_log_column_headers', $column_headers, $this, true );
			$column_headers = apply_filters( 'mycred_log_column_' . $this->mycred_type . '_headers', $column_headers, $this );

			$columns = array();
			foreach ( $column_headers as $column_id => $column_name )
				$columns[ $column_id ] = $this->core->template_tags_general( $column_name );

			$this->log_columns = $columns;

		}

		/**
		 * Delete Point Type
		 * Deletes log entries for a particular point type when the point type is deleted.
		 * @since 1.7
		 * @version 1.0
		 */
		public function delete_point_type( $point_type = NULL ) {

			if ( $point_type !== $this->mycred_type || ! $this->core->can_edit_plugin() ) return;

			global $wpdb;

			$wpdb->delete(
				$mycred->log_table,
				array( 'ctype' => $this->mycred_type ),
				array( '%s' )
			);

		}

		/**
		 * Update User References
		 * Removes the saved reference count and sum for re-calculation at earliest convinience.
		 * @since 1.7
		 * @version 1.0
		 */
		public function update_user_references( $result, $request ) {

			if ( $result !== false && strlen( $request['entry'] ) > 0 )
				mycred_delete_user_meta( $request['user_id'], 'mycred-log-count' );

			if ( $result === false || $request['type'] != $this->mycred_type ) return $result;

			mycred_delete_user_meta( $request['user_id'], 'mycred_ref_counts-' . $this->mycred_type );
			mycred_delete_user_meta( $request['user_id'], 'mycred_ref_sums-' . $this->mycred_type );

			return $result;

		}

		/**
		 * Delete Log Entry Action
		 * @since 1.4
		 * @version 1.0
		 */
		public function action_delete_log_entry() {

			// Security
			check_ajax_referer( 'mycred-delete-log-entry', 'token' );

			// Access
			if ( ! $this->core->can_edit_plugin() )
				wp_send_json_error( 'Access denied' );

			$row_id = absint( $_POST['row'] );
			if ( $row_id === 0 )
				wp_send_json_error( 'Unknown Row ID' );

			// Delete Row
			global $wpdb;
			$wpdb->delete( $this->core->log_table, array( 'id' => $row_id ), array( '%d' ) );

			// Respond
			wp_send_json_success( __( 'Row Deleted', 'mycred' ) );

		}

		/**
		 * Update Log Entry Action
		 * @since 1.4
		 * @version 1.0.1
		 */
		public function action_update_log_entry() {

			// Security
			check_ajax_referer( 'mycred-update-log-entry', 'token' );

			// Access
			if ( ! $this->core->can_edit_plugin() )
				wp_send_json_error( array( 'message' => 'Access denied' ) );

			// Make sure we handle our own point type only
			$ctype = sanitize_key( $_POST['ctype'] );
			if ( $ctype !== $this->mycred_type ) return;

			// We need a row id
			$entry_id = absint( $_POST['rowid'] );
			if ( $entry_id === 0 )
				wp_send_json_error( array( 'message' => 'Invalid Log Entry' ) );

			$screen = sanitize_key( $_POST['screen'] );

			// Parse form submission
			parse_str( $_POST['form'], $post );

			// Apply defaults
			$request = shortcode_atts( apply_filters( 'mycred_update_log_entry_request', array(
				'ref'   => NULL,
				'creds' => NULL,
				'entry' => 'current'
			), $post ), $post['mycred_manage_log'] );

			// Check reference
			$all_references = mycred_get_all_references();
			if ( $request['ref'] == '' || ! array_key_exists( $request['ref'], $all_references ) )
				wp_send_json_error( array( 'message' => esc_attr__( 'Invalid or empty reference', 'mycred' ) ) );

			// Check entry
			$request['entry'] = wp_kses_post( $request['entry'] );
			if ( $request['entry'] == '' )
				wp_send_json_error( array( 'message' => esc_attr__( 'Log Entry cannot be empty', 'mycred' ) ) );

			// Check amount
			$amount = $this->core->number( $request['creds'] );
			if ( $amount === $this->core->zero() )
				wp_send_json_error( array( 'message' => esc_attr__( 'Amount can not be zero', 'mycred' ) ) );

			global $wpdb;

			// Get the current version of the entry
			$log_entry = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$this->core->log_table} WHERE id = %d;", $entry_id ) );
			if ( ! isset( $log_entry->ref ) )
				wp_send_json_error( array( 'message' => esc_attr__( 'Log entry not found', 'mycred' ) ) );

			// Prep creds format
			$format = '%d';
			if ( $this->core->format['decimals'] > 0 )
				$format = '%f';

			// Do the actual update
			if ( ! $this->core->update_log_entry( $entry_id, array( 'ref' => $request['ref'], 'creds' => $amount, 'entry' => $request['entry'] ), array( '%s', $format, '%s' ) ) )
				wp_send_json_error( array( 'message' => esc_attr__( 'Could not save the new log entry', 'mycred' ) ) );

			// Reset totals if amount or reference was changed
			if ( $this->core->number( $log_entry->creds ) !== $amount || $log_entry->ref !== $request['ref'] ) {

				mycred_delete_user_meta( $log_entry->user_id, $log_entry->ctype, '_total' );
				mycred_delete_user_meta( $log_entry->user_id, 'mycred_ref_counts-' . $this->mycred_type );
				mycred_delete_user_meta( $log_entry->user_id, 'mycred_ref_sums-' . $this->mycred_type );

				mycred_delete_option( 'mycred-cache-total-' . $log_entry->ctype );

			}

			$wpdb->flush();

			$log                 = new myCRED_Query_Log( array( 'entry_id' => $entry_id ) );
			$log->is_admin       = true;
			$log->headers        = $this->log_columns;
			$log->hidden_headers = get_hidden_columns( $screen );

			wp_send_json_success( array(
				'message' => esc_attr__( 'Log entry successfully updated', 'mycred' ),
				'results' => $log->get_the_entry( $log->results[0] )
			) );

		}

		/**
		 * Add Users History
		 * Adds in a dedicated log page where the current user can view their points
		 * history, if allowed in the wp-admin area.
		 * @since 0.1
		 * @version 1.1
		 */
		public function my_history_menu() {

			// Check if user should be excluded
			if ( $this->core->exclude_user() || apply_filters( 'mycred_admin_show_history_' . $this->mycred_type, true ) === false ) return;

			// Add Points History to Users menu
			$page = add_users_page(
				$this->core->plural() . ' ' . __( 'History', 'mycred' ),
				$this->core->plural() . ' ' . __( 'History', 'mycred' ),
				'read',
				$this->mycred_type . '-history',
				array( $this, 'my_history_page' )
			);

			// Load styles for this page
			add_action( 'admin_print_styles-' . $page, array( $this, 'settings_header' ) );
			add_action( 'load-' . $page,               array( $this, 'screen_options' ) );

		}

		/**
		 * Admin Notices
		 * @since 1.7
		 * @version 1.0
		 */
		public function admin_notices() {

			$screen = get_current_screen();

			if ( substr( $screen->id, 0, 20 ) != 'toplevel_page_' . MYCRED_SLUG ) return;

			if ( isset( $_GET['deleted'] ) && isset( $_GET['ctype'] ) && $_GET['ctype'] == $this->mycred_type )
				echo '<div id="message" class="updated notice is-dismissible"><p>' . sprintf( _n( '1 Entry Deleted', '%d Entries Deleted', absint( $_GET['deleted'] ), 'mycred' ), absint( $_GET['deleted'] ) ) . '</p><button type="button" class="notice-dismiss"></button></div>';

		}

		/**
		 * Log Columns
		 * @since 1.7
		 * @version 1.0
		 */
		public function log_columns() {

			return $this->log_columns;

		}

		/**
		 * Screen Actions
		 * @since 1.7
		 * @version 1.0.1
		 */
		public function screen_actions() {

			$screen = get_current_screen();

			// "My History" screen and not Log archive
			if ( substr( $screen->id, 0, 20 ) != 'toplevel_page_' . MYCRED_SLUG ) {

				do_action( 'mycred_log_my_admin_actions', $this->mycred_type );
				return;

			}

			$settings_key = 'mycred_epp_' . $_GET['page'];
			if ( ! $this->is_main_type )
				$settings_key .= '_' . $this->mycred_type;

			// Update Entries per page option
			if ( isset( $_REQUEST['wp_screen_options']['option'] ) && isset( $_REQUEST['wp_screen_options']['value'] ) ) {
			
				if ( $_REQUEST['wp_screen_options']['option'] == $settings_key ) {
					$value = absint( $_REQUEST['wp_screen_options']['value'] );
					update_user_meta( $this->current_user_id, $settings_key, $value );
				}

				$hidden_columns  = get_hidden_columns( $screen );
				$hidden          = array();
				foreach ( $this->log_columns as $column_id => $column_name ) {

					if ( ! array_key_exists( $column_id . '-hide', $_POST ) )
						$hidden[] = $column_id;

				}

				update_user_option( $this->current_user_id, 'manage' . $screen->id . 'columnshidden', $hidden );

			}

			do_action( 'mycred_log_admin_actions', $this->mycred_type );

			// Make sure we only execute code for the current point type viewed
			if ( ! isset( $_GET['ctype'] ) || $_GET['ctype'] != $this->mycred_type ) return;

			// Bulk action - delete log entries
			if ( isset( $_GET['action'] ) && $_GET['action'] == 'delete' && isset( $_GET['entry'] ) ) {

				// First get a clean list of ids to delete
				$entry_ids = array();
				foreach ( (array) $_GET['entry'] as $id ) {
					$id = absint( $id );
					if ( $id === 0 || in_array( $id, $entry_ids ) ) continue;
					$entry_ids[] = $id;
				}

				// If we have a list, run through them
				$deleted = 0;
				if ( ! empty( $entry_ids ) ) {

					global $wpdb, $mycred;

					foreach ( $entry_ids as $entry_id ) {

						$wpdb->delete(
							$mycred->log_table,
							array( 'id' => $entry_id ),
							array( '%d' )
						);

						$deleted ++;

					}

				}

				// Redirect to the good news
				if ( $deleted > 0 ) {

					if ( $this->is_main_type )
						$url = add_query_arg( array( 'page' => MYCRED_SLUG, 'ctype' => $this->mycred_type ), admin_url( 'admin.php' ) );
					else
						$url = add_query_arg( array( 'page' => MYCRED_SLUG . '_' . $this->mycred_type, 'ctype' => $this->mycred_type ), admin_url( 'admin.php' ) );

					$url = add_query_arg( 'deleted', $deleted, $url );
					wp_safe_redirect( $url );
					exit;

				}

			}

		}

		/**
		 * Screen Options
		 * @since 1.4
		 * @version 1.0
		 */
		public function screen_options() {

			$this->screen_actions();

			$settings_key = 'mycred_epp_' . $_GET['page'];
			if ( ! $this->is_main_type )
				$settings_key .= '_' . $this->mycred_type;

			// Prep Per Page
			$args = array(
				'label'   => __( 'Entries', 'mycred' ),
				'default' => 10,
				'option'  => $settings_key
			);
			add_screen_option( 'per_page', $args );

		}

		/**
		 * Log Header
		 * @since 0.1
		 * @version 1.3
		 */
		public function settings_header() {

			$screen        = get_current_screen();

			if ( substr( $screen->id, 0, 20 ) != 'toplevel_page_' . MYCRED_SLUG ) return;

			$references    = mycred_get_all_references();
			$js_references = array();
			if ( ! empty( $references ) ) {
				foreach ( $references as $ref_id => $ref_label )
					$js_references[ $ref_id ] = esc_js( $ref_label );
			}

			wp_localize_script(
				'mycred-edit-log',
				'myCREDLog',
				array(
					'ajaxurl'    => admin_url( 'admin-ajax.php' ),
					'title'      => esc_attr__( 'Edit Log Entry', 'mycred' ),
					'close'      => esc_attr__( 'Close', 'mycred' ),
					'working'    => esc_attr__( 'Processing...', 'mycred' ),
					'messages'   => array(
						'delete'     => esc_attr__( 'Are you sure you want to delete this log entry? This can not be undone!', 'mycred' ),
						'update'     => esc_attr__( 'The log entry was successfully updated.', 'mycred' ),
						'error'      => esc_attr__( 'The selected log entry could not be deleted.', 'mycred' ),
					),
					'tokens'     => array(
						'delete'     => wp_create_nonce( 'mycred-delete-log-entry' ),
						'update'     => wp_create_nonce( 'mycred-update-log-entry' ),
						'column'     => wp_create_nonce( 'mycred-show-hide-log-columns' )
					),
					'references' => $js_references,
					'ctype'      => $this->mycred_type,
					'screen'     => $screen->id,
					'page'       => $this->screen_id
				)
			);

			wp_enqueue_script( 'mycred-edit-log' );

			wp_enqueue_style( 'mycred-bootstrap-grid' );
			wp_enqueue_style( 'mycred-edit-log' );

		}

		/**
		 * Page Title
		 * @since 0.1
		 * @version 1.0
		 */
		public function page_title( $title = 'Log' ) {

			$title = apply_filters( 'mycred_admin_log_title', $title, $this );

			// Search Results
			if ( isset( $_GET['s'] ) && ! empty( $_GET['s'] ) )
				$search_for = ' <span class="subtitle">' . __( 'Search results for', 'mycred' ) . ' "' . $_GET['s'] . '"</span>';

			elseif ( isset( $_GET['time'] ) && $_GET['time'] != '' ) {
				$time       = urldecode( $_GET['time'] );
				$check      = explode( ',', $time );
				$search_for = ' <span class="subtitle">' . sprintf( _x( 'Log entries from %s', 'e.g. Log entries from April 12th 2016', 'mycred' ), date( 'F jS Y', $check[0] ) ) . '</span>';
			}

			else
				$search_for = '';

			echo $title . ' ' . $search_for;

		}

		/**
		 * Admin Page
		 * @since 0.1
		 * @version 1.4
		 */
		public function admin_page() {

			// Security
			if ( ! $this->core->can_edit_creds() ) wp_die( 'Access Denied' );

			$settings_key = 'mycred_epp_' . $_GET['page'];
			if ( ! $this->is_main_type )
				$settings_key .= '_' . $this->mycred_type;

			$per_page = mycred_get_user_meta( $this->current_user_id, $settings_key, '', true );
			if ( $per_page == '' ) $per_page = 10;

			$name        = mycred_label( true );
			$search_args = mycred_get_search_args();

			// Entries per page
			if ( ! array_key_exists( 'number', $search_args ) )
				$search_args['number'] = absint( $per_page );

			// Only entries for this point type
			if ( ! array_key_exists( 'ctype', $search_args ) )
				$search_args['ctype'] = $this->mycred_type;

			// Query Log
			$log = new myCRED_Query_Log( $search_args );
	
			$log->is_admin        = true;
			$log->hidden_headers  = get_hidden_columns( get_current_screen() );
			$log->headers         = $this->log_columns;

?>
<div class="wrap" id="myCRED-wrap">
	<h1><?php $this->page_title( sprintf( __( '%s Log', 'mycred' ), $this->core->plural() ) ); ?></h1>
<?php

			// This requirement is only checked on activation. If the library is disabled
			// after installation we need to warn the user. Every single feature in myCRED
			// that requires encryption will stop working:
			// Points for clicking on links
			// Exchange Shortcode
			// buyCRED Add-on
			$extensions = get_loaded_extensions();
			if ( ! in_array( 'mcrypt', $extensions ) && ! defined( 'MYCRED_DISABLE_PROTECTION' ) )
				echo '<div id="message" class="error below-h2"><p>' . __( 'Warning. The required Mcrypt PHP Library is not installed on this server! Certain hooks and shortcodes will not work correctly!', 'mycred' ) . '</p></div>';

			// Filter by dates
			$log->filter_dates( admin_url( 'admin.php?page=' . $this->screen_id ) );

?>

	<?php do_action( 'mycred_top_log_page', $this ); ?>

	<form method="get" id="posts-filter" action="">
<?php

			if ( array_key_exists( 'user', $search_args ) )
				echo '<input type="hidden" name="user" value="' . esc_attr( $search_args['user'] ) . '" />';

			if ( array_key_exists( 's', $search_args ) )
				echo '<input type="hidden" name="s" value="' . esc_attr( $search_args['s'] ) . '" />';

			if ( array_key_exists( 'ref', $search_args ) )
				echo '<input type="hidden" name="ref" value="' . esc_attr( $search_args['ref'] ) . '" />';

			if ( isset( $_GET['show'] ) )
				echo '<input type="hidden" name="show" value="' . esc_attr( $_GET['show'] ) . '" />';

			elseif ( array_key_exists( 'time', $search_args ) )
				echo '<input type="hidden" name="time" value="' . esc_attr( $search_args['time'] ) . '" />';

			if ( array_key_exists( 'order', $search_args ) )
				echo '<input type="hidden" name="order" value="' . esc_attr( $search_args['order'] ) . '" />';

			if ( array_key_exists( 'paged', $search_args ) )
				echo '<input type="hidden" name="paged" value="' . esc_attr( $search_args['paged'] ) . '" />';

			$log->search();

?>
		<input type="hidden" name="page" value="<?php echo $this->screen_id; ?>" />
		<input type="hidden" name="ctype" value="<?php if ( array_key_exists( 'ctype', $search_args ) ) echo esc_attr( $search_args['ctype'] ); else echo esc_attr( $this->mycred_type ); ?>" />

		<?php do_action( 'mycred_above_log_table', $this ); ?>

		<div class="tablenav top">

			<?php $log->table_nav( 'top', false ); ?>

		</div>

		<?php $log->display(); ?>

		<div class="tablenav bottom">

			<?php $log->table_nav( 'bottom', false ); ?>

		</div>

		<?php do_action( 'mycred_bellow_log_table', $this ); ?>

	</form>

	<?php do_action( 'mycred_bottom_log_page', $this ); ?>

</div>
<?php

			$this->log_editor();

		}

		/**
		 * My History Page
		 * @since 0.1
		 * @version 1.3.1
		 */
		public function my_history_page() {

			// Security
			if ( ! is_user_logged_in() ) wp_die( 'Access Denied' );

			$settings_key = 'mycred_epp_' . $_GET['page'];
			if ( ! $this->is_main_type )
				$settings_key .= '_' . $this->mycred_type;

			$per_page = mycred_get_user_meta( $this->current_user_id, $settings_key, '', true );
			if ( $per_page == '' ) $per_page = 10;

			$search_args = mycred_get_search_args();

			// Entries per page
			if ( ! array_key_exists( 'number', $search_args ) )
				$search_args['number'] = absint( $per_page );

			// Only entries for this point type
			$search_args['ctype'] = $this->mycred_type;

			// Only entries for the current user
			$search_args['user_id'] = $this->current_user_id;

			$log           = new myCRED_Query_Log( $search_args );

			$log->is_admin = true;
			$log->table_headers();
			unset( $log->headers['username'] );

?>
<div class="wrap" id="myCRED-wrap">
	<h1><?php $this->page_title( sprintf( __( 'My %s History', 'mycred' ),  $this->core->plural() ) ); ?></h1>

	<?php $log->filter_dates( admin_url( 'users.php?page=' . $_GET['page'] ) ); ?>

	<?php do_action( 'mycred_top_my_log_page', $this ); ?>

	<form method="get" action="" name="mycred-mylog-form" novalidate>
		<input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
<?php

			if ( array_key_exists( 's', $search_args ) )
				echo '<input type="hidden" name="s" value="' . esc_attr( $search_args['s'] ) . '" />';

			if ( array_key_exists( 'ref', $search_args ) )
				echo '<input type="hidden" name="ref" value="' . esc_attr( $search_args['ref'] ) . '" />';

			if ( isset( $_GET['show'] ) )
				echo '<input type="hidden" name="show" value="' . esc_attr( $_GET['show'] ) . '" />';

			elseif ( array_key_exists( 'time', $search_args ) )
				echo '<input type="hidden" name="time" value="' . esc_attr( $search_args['time'] ) . '" />';

			if ( array_key_exists( 'order', $search_args ) )
				echo '<input type="hidden" name="order" value="' . esc_attr( $search_args['order'] ) . '" />';

			if ( array_key_exists( 'paged', $search_args ) )
				echo '<input type="hidden" name="paged" value="' . esc_attr( $search_args['paged'] ) . '" />';

			$log->search();

?>

		<?php do_action( 'mycred_above_my_log_table', $this ); ?>

		<div class="tablenav top">

			<?php $log->table_nav( 'top', true ); ?>

		</div>

		<?php $log->display(); ?>

		<div class="tablenav bottom">

			<?php $log->table_nav( 'bottom', true ); ?>

		</div>

		<?php do_action( 'mycred_bellow_my_log_table', $this ); ?>

	</form>

	<?php do_action( 'mycred_bottom_my_log_page', $this ); ?>

</div>
<?php

		}

		/**
		 * Handle Post Deletions
		 * @since 1.0.9.2
		 * @version 1.0
		 */
		public function post_deletions( $post_id ) {

			global $post_type, $wpdb;

			// Check log
			$sql     = "SELECT * FROM {$this->core->log_table} WHERE ref_id = %d;";
			$records = $wpdb->get_results( $wpdb->prepare( $sql, $post_id ) );

			// If we have results
			if ( $wpdb->num_rows > 0 ) {

				// Loop though them
				foreach ( $records as $row ) {

					// Check if the data column has a serialized array
					$check = @unserialize( $row->data );
					if ( $check !== false && $row->data !== 'b:0;' ) {

						// Unserialize
						$data = unserialize( $row->data );

						// If this is a post
						if ( ( isset( $data['ref_type'] ) && $data['ref_type'] == 'post' ) || ( isset( $data['post_type'] ) ) ) {

							// If the entry is blank continue on to the next
							if ( trim( $row->entry ) === '' ) continue;

							// Construct a new data array
							$new_data = array( 'ref_type' => 'post' );

							// Add details that will no longer be available
							$post                   = get_post( $post_id );
							$new_data['ID']         = $post->ID;
							$new_data['post_title'] = $post->post_title;
							$new_data['post_type']  = $post->post_type;

							// Save
							$wpdb->update(
								$this->core->log_table,
								array( 'data' => serialize( $new_data ) ),
								array( 'id'   => $row->id ),
								array( '%s' ),
								array( '%d' )
							);

						}

					}

				}

			}

		}

		/**
		 * Handle User Deletions
		 * @since 1.0.9.2
		 * @version 1.0
		 */
		public function user_deletions( $user_id ) {

			global $wpdb;

			// Check log
			$sql     = "SELECT * FROM {$this->core->log_table} WHERE user_id = %d;";
			$records = $wpdb->get_results( $wpdb->prepare( $sql, $user_id ) );

			// If we have results
			if ( $wpdb->num_rows > 0 ) {

				// Loop though them
				foreach ( $records as $row ) {

					// Construct a new data array
					$new_data = array( 'ref_type' => 'user' );

					// Add details that will no longer be available
					$user                     = get_userdata( $user_id );
					$new_data['ID']           = $user->ID;
					$new_data['user_login']   = $user->user_login;
					$new_data['display_name'] = $user->display_name;

					// Save
					$wpdb->update(
						$this->core->log_table,
						array( 'data' => serialize( $new_data ) ),
						array( 'id'   => $row->id ),
						array( '%s' ),
						array( '%d' )
					);

				}

			}

		}

		/**
		 * Handle Comment Deletions
		 * @since 1.0.9.2
		 * @version 1.0
		 */
		public function comment_deletions( $comment_id ) {

			global $wpdb;

			// Check log
			$sql     = "SELECT * FROM {$this->core->log_table} WHERE ref_id = %d;";
			$records = $wpdb->get_results( $wpdb->prepare( $sql, $comment_id ) );

			// If we have results
			if ( $wpdb->num_rows > 0 ) {

				// Loop though them
				foreach ( $records as $row ) {

					// Check if the data column has a serialized array
					$check = @unserialize( $row->data );
					if ( $check !== false && $row->data !== 'b:0;' ) {

						// Unserialize
						$data = unserialize( $row->data );

						// If this is a post
						if ( isset( $data['ref_type'] ) && $data['ref_type'] == 'comment' ) {

							// If the entry is blank continue on to the next
							if ( trim( $row->entry ) === '' ) continue;

							// Construct a new data array
							$new_data = array( 'ref_type' => 'comment' );

							// Add details that will no longer be available
							$comment                     = get_comment( $comment_id );
							$new_data['comment_ID']      = $comment->comment_ID;
							$new_data['comment_post_ID'] = $comment->comment_post_ID;

							// Save
							$wpdb->update(
								$this->core->log_table,
								array( 'data' => serialize( $new_data ) ),
								array( 'id'   => $row->id ),
								array( '%s' ),
								array( '%d' )
							);

						}

					}

				}

			}

		}

		/**
		 * Log Editor
		 * Renders the log editor modal that is controlled by the log-editor js script.
		 * @since 1.7
		 * @version 1.0
		 */
		protected function log_editor() {

			$name = mycred_label( true );

?>
<div id="edit-mycred-log-entry" style="display: none;">
	<div class="mycred-container">
		<?php if ( $name == 'myCRED' ) : ?><img id="mycred-token-sitting" class="hidden-sm hidden-xs" src="<?php echo plugins_url( 'assets/images/token-sitting.png', myCRED_THIS ); ?>" alt="Token looking on" /><?php endif; ?>
		<form class="form" method="post" action="" id="mycred-editor-form">
			<input type="hidden" name="mycred_manage_log[id]" value="" id="mycred-edit-log-id" />

			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<label><?php _e( 'User', 'mycred' ); ?></label>
					<div id="mycred-user-to-show"></div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<label><?php _e( 'Date', 'mycred' ); ?></label>
					<div id="mycred-date-to-show"></div>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
					<label><?php echo $this->core->plural(); ?></label>
					<input type="text" name="mycred_manage_log[creds]" id="mycred-creds-to-show" class="form-control" placeholder="" value="" />
				</div>
				<div class="col-lg-4 col-md-4 col-sm-8 col-xs-12">
					<label><?php _e( 'Reference', 'mycred' ); ?></label>
					<select name="mycred_manage_log[ref]" id="mycred-referece-to-show"></select>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="mycred-old-entry-to-show-wrapper">
					<label><?php _e( 'Original Entry', 'mycred' ); ?></label>
					<div id="mycred-old-entry-to-show"></div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="mycred-new-entry-to-show-wrapper">
					<label><?php _e( 'Log Entry', 'mycred' ); ?></label>
					<input type="text" name="mycred_manage_log[entry]" id="mycred-new-entry-to-show" class="form-control" placeholder="" value="" />
					<span class="description" id="available-template-tags" style="display:none;"></span>
				</div>
			</div>

			<div class="row last">
				<div class="col-lg-2 col-md-3 col-sm-12 col-xs-12 text-center">
					<a href="javascript:void(0);" class="button button-primary button-large mycred-delete-row" id="mycred-delete-entry-in-editor" data-id=""><?php _e( 'Delete Entry', 'mycred' ); ?></a>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1 col-xs-12"><span id="mycred-editor-indicator" class="spinner"></span></div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="mycred-editor-results"></div>
				<div class="col-lg-3 col-md-2 col-sm-11 col-xs-12 text-right">
					<input type="submit" id="mycred-editor-submit" class="button button-secondary button-large" value="<?php _e( 'Update Entry', 'mycred' ); ?>" />
				</div>
			</div>
		</form>
	</div>
</div>
<?php

		}

	}
endif;

?>